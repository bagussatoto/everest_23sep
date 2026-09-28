<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */

switch ($mode) {

    case "index":
        $scriptBottom = "";
        $arrBlacklist = array(
            "no",
        );
        $stepper = isset($_GET['step']) ? $_GET['step'] : 1;
        if (isset($_GET['step'])) {
            //            arrPrint($steps[$_GET['step']]);
        }

        $time_line = "cek";
        if (isset($allSteps)) {
            $time_line = createStateHorizontal('-1', sizeof($allSteps), $jenisTr);
        }
        //-----------------------------------
        $keterangan_notif = notifTransaksi();
        //-----------------------------------
        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }


        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/transaksi_index.html");

        $strOnprePre = "";
        $strOnprog = "";

        //egion onprogressView Doank
        $strOnprogView = "";

        $strHist = "";

        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<table id='arrayHistory' class='03 table table-condensed table-bordered no-padding'>";
            $strHist .= "<thead>";
            $strHist .= "<tr line=" . __LINE__ . ">";
            if (sizeof($arrayHistoryLabels) > 0) {
                foreach ($arrayHistoryLabels as $key => $label) {
                    $strHist .= "<td class='text-muted'>";
                    if (is_array($label)) {
                        $strHist .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strHist .= $label;
                    }
                    $strHist .= "</td>";
                }
            }
            $strHist .= "</tr>";
            $strHist .= "</thead>";
            $strHist .= "<tbody>";

            foreach ($arrayHistory as $key => $val) {
                $strHist .= "<tr line=" . __LINE__ . ">";
                if (sizeof($arrayHistoryLabels) > 0) {
                    foreach ($arrayHistoryLabels as $key => $label) {
                        $strHist .= "<td>";
                        $strHist .= $val[$key];
                        $strHist .= "</td>";
                    }
                }
                $strHist .= "</tr>";
            }
            $strHist .= "</tbody>";
            $strHist .= "</table>";
            $strHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $strHist = "-the item you specified has no entry-";
            $strHistFooter = "";
        }
        //endregion

        $strRecap = "";
        //rregion recap

        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            $scriptBottom .= "

        <script>
    $(document).ready( function () {
        if( $('table#arrayOnProgressHistory') ){

            var startDate = localStorage.getItem('startDate');
            var endDate = localStorage.getItem('endDate');

            var getStartDate = function (){
                return localStorage.getItem('startDate')
            }
            var getEndDate = function (){
                return localStorage.getItem('endDate');
            }
            $.fn.dataTableExt.afnFiltering.push(
                function (oSettings, aData, iDataIndex) {
                if (typeof startdate === 'undefined' || typeof enddate === 'undefined') {
                    return true;
                }
                var coldate = moment(aData[3], 'DD-MM-YYYY');
                console.log('coldate', coldate)
                var valid = true;
                if (coldate.isValid()) {
                    if (enddate.isBefore(coldate)) {
                        console.log('enddate before coldate', enddate)
                        valid = false;
                    }
                    if (coldate.isBefore(startdate)) {
                        console.log('coldate before startdate', startdate)
                        valid = false;
                    }
                }
                else {
                    valid = false;
                }
                return valid;
            });
            arrayOnProgressHistory = $('table#arrayOnProgressHistory').DataTable( {
                $sortColumnDef
                dom: ' l <\"dataTables_filter_custom\"> f r t i p ',
                processing: true,
                serverSide: true,
                ajax: {
                    type: 'GET',
                    url: '" . base_url() . $this->uri->segment(1) . "/viewHistoryApi/$jenisTr/" . $steps[$stepper]['target'] . "?xxx=1&debuger=1',
                    datatype: 'json',
                    data: function (data) {
                        if(undefined!=$('.custom_daterange').val() && ''!=$('.custom_daterange').val()){
                            console.log('range date ada isinya');
                            console.log( $('.custom_daterange').val() );
                            var startDate = $('.custom_daterange').val().split(' to ')[0];
                            var endDate = $('.custom_daterange').val().split(' to ')[1];
                            data.startDate = startDate;
                            data.endDate = endDate;
                            $('#close_btn').removeClass('hidden');
                        }else{
                            console.log('date range kosong, data diabaikan');
                            $('#close_btn').addClass('hidden');
                        }
                    }
                },
                columns: [$labelColumnDt],
                buttons: [
                    {
                        text: 'Reload',
                        action: function ( e, dt, node, config ) {
                            dt.ajax.reload();
                        }
                    }
                ]
            } );

            $('.dataTables_filter_custom').html(\"<div class='input-group'><input type='text' placeholder='Filter Range Tanggal' class='form-control custom_daterange' style='margin-left: 25px;'><span id='close_btn' class='input-group-btn hidden'><button class='btn btn-danger' type='button' style='margin-left: 30px;'><i class='glyphicon glyphicon-remove'></i></button></span></div>\").css('margin-left','25');

            if( $('#arrayOnProgressHistory').length ) { new $.fn.dataTable.FixedHeader( arrayOnProgressHistory ); }

            top.$('.dataTables_scrollBody').floatingScroll();

            $( \".dataTables_scrollBody\" ).scroll(function() {
                setTimeout( function(){
                    $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
                }, 400);
            });

            $('.custom_daterange').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 last days': [moment().subtract(6, 'days'), moment()],
                    '30 last days': [moment().subtract(29, 'days'), moment()],
                    'This month': [moment().startOf('month'), moment().endOf('month')],
                    'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                autoUpdateInput: false,
                opens:'left',
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD-MMM-YYYY'
                }
            });

           $('.custom_daterange', this).on('apply.daterangepicker', function (ev, picker) {
               ev.preventDefault();
               $(this).val(picker.startDate.format('DD-MMM-YYYY') + ' to ' + picker.endDate.format('DD-MMM-YYYY'));
               arrayOnProgressHistory.draw();
           });

           $('.custom_daterange', this).on('cancel.daterangepicker', function (ev, picker) {
               ev.preventDefault();
               $(this).val('');
               arrayOnProgressHistory.draw();
           });

            $('#close_btn').on('click', function () {
                $('#close_btn').addClass('hidden');
                $('.custom_daterange').val('');
                arrayOnProgressHistory.draw();
            });

        }
    });
</script>\n

<style>
    .dataTables_wrapper .dataTables_processing {
        position: absolute;
        top: 30%;
        left: 50%;
        width: 30%;
        height: 60px;
        margin-left: -20%;
        margin-top: -50px;
        padding-top: 10px;
        text-align: center;
        font-size: 2em;
        background: lightgreen;
    }
</style>
        <script>

    $(document).ready( function () {
        var arrayOnprePre;
        var arrayOnProgress;
        var arrayHistory;
        var arrayRecap;
//        var arrayOnProgressToPay;

//         arrayOnProgressToPay = $('#arrayOnProgressToPay').DataTable({
//            pageLength: -1,
//            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
////            sorting: false,
////            searching: false,
////            pageResize: true,
//            scrollY: 'calc(100%-400px)',
//            scrollX: true,
//            scrollCollapse: false,
////            fixedColumns:   {
////                leftColumns: 3,
////                rightColumns: 1
////            },
//            buttons: [
//                {
//                    extend: 'print',
//                    footer: true
//                },
//                {
//                    extend: 'excel',
//                    text: 'Excel',
//                    exportOptions: {
//                        modifier: {
//                            page: 'current'
//                        }
//                    }
//                }
//            ],
//        });

        arrayRecap = $('#arrayRecap').DataTable({
            pageLength: -1,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
//            sorting: false,
//            searching: false,
//            pageResize: true,
            scrollY: 'calc(100%-400)',
            scrollX: true,
            scrollCollapse: false,
//            fixedColumns:   {
//                leftColumns: 3,
//                rightColumns: 1
//            },
            buttons: [
                {
                    extend: 'print',
                    footer: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
        });

        arrayHistory = $('#arrayHistory').DataTable({
            pageLength: -1,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
//            sorting: false,
//            searching: false,
//            pageResize: true,
//            scrollY: '-webkit-calc(100%-400px)',
//            scrollX: true,
//            scrollCollapse: false,
//            fixedColumns:   {
//                leftColumns: 3,
//                rightColumns: 1
//            },
            buttons: [
                {
                    extend: 'print',
                    footer: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
        });

        if( $('#arrayHistory').length ) { new $.fn.dataTable.FixedHeader( arrayHistory ); }

        arrayOnProgress = $('#arrayOnProgress').DataTable({
            pageLength: -1,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
//            sorting: false,
//            searching: false,
//            pageResize: true,
//            scrollY: '-webkit-calc(100%-400)',
//            scrollX: true,
//            scrollCollapse: false,
//            fixedColumns:   {
//                leftColumns: 4,
//                rightColumns: 1
//            },
            buttons: [
                {
                    extend: 'print',
                    footer: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
        });

        if( $('#arrayOnProgress').length ) { new $.fn.dataTable.FixedHeader( arrayOnProgress ); }
        top.$('.dataTables_scrollBody').floatingScroll();

        $( \".dataTables_scrollBody\" ).scroll(function() {
            console.log('sekeroll');
            setTimeout( function(){
                $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
            }, 400);
        });

        arrayOnprePre = $('#arrayOnprePre').DataTable({
            pageLength: -1,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, 'All'] ],
//            sorting: false,
//            searching: false,
//            pageResize: true,
            scrollY: 'calc(100%-400px)',
            scrollX: true,
            scrollCollapse: false,
            fixedColumns:   {
                leftColumns: 3,
                rightColumns: 1
            },
            buttons: [
                {
                    extend: 'print',
                    footer: true
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }
            ],
        });

        $( \".box-body\" ).scroll(function() {
            console.log('sekeroll');
            setTimeout( function(){
                $($.fn.dataTable.tables(true)).DataTable().fixedHeader.adjust();
            }, 400);
        });

        top.$('.box-body').floatingScroll();

        $(\".DTFC_RightBodyLiner\").css(\"overflow\", \"hidden\");
        $(\".DTFC_RightWrapper\").css(\"right\", \"0px\");
        $(\".DTFC_RightWrapper\").css(\"width\", \"fit-content\");
        $(\".DTFC_RightFootWrapper\").hide();

    } );
        </script>";

        }

        $scriptBottom .= isset($scriptBotto) ? $scriptBotto : "";

        $dasboardLists = "
            <div class='container content'>
            <div class='col-md-4'>
                <div class='card-header'>
                    <h2 class='card-title'>Ringkasan Proyek</h2>
                </div>
                <div class='stats-container-2'>
                    <div class='stat-card'>
                        <i class='fa fa-tasks fa-2x' style='color: #3498db;'></i>
                        <div class='stat-value' id='total-projects'>10</div>
                        <div class='stat-label'>Total Proyek</div>
                    </div>
                    <div class='stat-card'>
                        <i class='fa fa-check-circle fa-2x' style='color: #27ae60;'></i>
                        <div class='stat-value' id='completed-projects'>5</div>
                        <div class='stat-label'>Selesai</div>
                    </div>
                    <div class='stat-card'>
                        <i class='fa fa-clock-o fa-2x' style='color: #f39c12;'></i>
                        <div class='stat-value' id='inprogress-projects'>3</div>
                        <div class='stat-label'>Dalam Proses</div>
                    </div>
                    <div class='stat-card'>
                        <i class='fa fa-exclamation-triangle fa-2x' style='color: #e74c3c;'></i>
                        <div class='stat-value' id='delayed-projects'>2</div>
                        <div class='stat-label'>Tertunda</div>
                    </div>
                </div>
                <div class='progress-summary'>
                    <div style='display: flex; justify-content: space-between; margin-bottom: 5px;'>
                        <span>Progress Keseluruhan</span>
                        <span id='overall-progress'>62%</span>
                    </div>
                    <div style='margin: 0px!important;' class='progress'>
                          <div id='progressBar' class='progress-bar text-bold' style='width: 100%;background-color: darkgreen !important;'>100%</div>
                     </div>
                </div>
            </div>
<div class='col-md-1'>&nbsp;</div>
            <div class='col-md-6'>
                <div class='card-header'>
                    <h2 class='card-title'>Ringkasan Tasklist</h2>
                </div>
                <div class='stats-container-3'>
                    <div class='stat-card'>
                        <i class='fa fa-list-ol fa-2x' style='color: #3498db;'></i>
                        <div class='stat-value' id='total-tasks'>0</div>
                        <div class='stat-label'>Total Task</div>
                    </div>
                    <div class='stat-card'>
                        <i class='fa fa-check-circle fa-2x' style='color: #27ae60;'></i>
                        <div class='stat-value' id='completed-tasks'>0</div>
                        <div class='stat-label'>Selesai</div>
                    </div>
                    <div class='stat-card'>
                        <i class='fa fa-spinner fa-2x' style='color: #f39c12;'></i>
                        <div class='stat-value' id='inprogress-tasks'>0</div>
                        <div class='stat-label'>Dalam Proses</div>
                    </div>
                    <div class='stat-card'>
                        <i class='fa fa-clock-o fa-2x' style='color: #e74c3c;'></i>
                        <div class='stat-value' id='delayed-tasks'>0</div>
                        <div class='stat-label'>Terlambat</div>
                    </div>
                    <div class='stat-card'>
                        <i class='fa fa-pause fa-2x' style='color: #95a5a6;'></i>
                        <div class='stat-value' id='pending-tasks'>0</div>
                        <div class='stat-label'>Tertunda</div>
                    </div>
                    <div class='stat-card'>
                        <i class='fa fa-trash fa-2x' style='color: #e74c3c;'></i>
                        <div class='stat-value' id='deleted-tasks'>0</div>
                        <div class='stat-label'>Delete/Batal</div>
                    </div>
                </div>
                <div class='progress-summary'>
                    <div style='display: flex; justify-content: space-between; margin-bottom: 5px;'>
                        <span>Progress Keseluruhan</span>
                        <span id='overall-task-progress'>0%</span>
                    </div>
                    <div style='margin: 0px!important;' class='progress'>
                        <div id='taskProgressBar' class='progress-bar text-bold' style='width: 0%;'>0%</div>
                    </div>
                </div>
            </div>

            </div>

<script>

        // Project data - sesuai dengan JSON yang diberikan
        const projectData = ".json_encode($project_data).";
        const tasklistData = ".json_encode($tasklist_data).";


// Hitung statistik tasklist
function calculateTasklistStats() {
    const today = new Date();
    let completed = 0;
    let inProgress = 0;
    let delayed = 0;
    let pending = 0;
    let deleted = 0;
    let totalProgress = 0;
    let tasksCounted = 0;

    tasklistData.forEach(task => {
        const progress = parseFloat(task.progress_percent) || 0;
        const trash = parseFloat(task.trash) || 0;

        if (trash === 1) {
            deleted++;
        }

        // Hanya hitung progress untuk task yang memiliki progress_percent
        if (!isNaN(progress)) {
            if (trash === 0) {
                totalProgress += progress;
                tasksCounted++;
            }
        }

        if (progress === 100) {
            if (trash === 0) {
                completed++;
            }
        }
        else if (progress > 0) {
            if (trash === 0) {
                inProgress++;
            }
        }
        else {
            if (trash === 0) {
                pending++;
            }
        }

        // Cek task yang terlambat
        if (task.dtime_end) {
            const endDate = new Date(task.dtime_end);
            if (endDate < today && progress < 100) {
                if (trash === 0) {
                    delayed++;
                }
            }
        }
    });

    const overallProgress = tasksCounted > 0 ? Math.round(totalProgress / tasksCounted) : 0;

    return {
        total: tasklistData.length,
        completed,
        inProgress,
        delayed,
        pending,
        deleted,
        overallProgress
    };
}

function updateTasklistSummary() {
    const stats = calculateTasklistStats();

    document.getElementById('total-tasks').textContent = stats.total;
    document.getElementById('completed-tasks').textContent = stats.completed;
    document.getElementById('inprogress-tasks').textContent = stats.inProgress;
    document.getElementById('delayed-tasks').textContent = stats.delayed;
    document.getElementById('pending-tasks').textContent = stats.pending;
    document.getElementById('deleted-tasks').textContent = stats.deleted;
    document.getElementById('overall-task-progress').textContent = stats.overallProgress + '%';

    const progressBar = document.getElementById('taskProgressBar');
    if (progressBar) {
        progressBar.style.width = stats.overallProgress + '%';
        progressBar.textContent = stats.overallProgress + '%';
        if (stats.overallProgress >= 80) {
            progressBar.style.backgroundColor = '#27ae60'; // Hijau
        }
        else if (stats.overallProgress >= 50) {
            progressBar.style.backgroundColor = '#f39c12'; // Kuning
        }
        else {
            progressBar.style.backgroundColor = '#e74c3c'; // Merah
        }
    }
    return stats;
}

function calculateProjectStats() {
    const today = new Date();
    let completed = 0;
    let inProgress = 0;
    let delayed = 0;
    let totalProgress = 0;
    let projectsCounted = 0;

    projectData.forEach(project => {
        const progress = parseFloat(project.persen_progress) || 0;
        if (!isNaN(progress)) {
            totalProgress += progress;
            projectsCounted++;
        }
        if (progress === 100) {
            completed++;
        }
        else if (progress > 0) {
            inProgress++;
        }
        else {
            delayed++;
        }
        if (project.end_dtime) {
            const endDate = new Date(project.end_dtime);
            if (endDate < today && progress < 100) {
                delayed++;
            }
        }
    });

    const overallProgress = projectsCounted > 0 ? Math.round(totalProgress / projectsCounted) : 0;

    return {
        total: projectData.length,
        completed,
        inProgress,
        delayed,
        overallProgress
    };
}

function updateProjectSummary() {
    const stats = calculateProjectStats();

    document.getElementById('total-projects').textContent = stats.total;
    document.getElementById('completed-projects').textContent = stats.completed;
    document.getElementById('inprogress-projects').textContent = stats.inProgress;
    document.getElementById('delayed-projects').textContent = stats.delayed;
    document.getElementById('overall-progress').textContent = stats.overallProgress + '%';

    // Update progress bar
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = stats.overallProgress + '%';
        progressBar.textContent = stats.overallProgress + '%';
    }

    return stats;
}

// Panggil fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    updateProjectSummary();
    updateTasklistSummary();
});
</script>
        ";

        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $p->addTags(
            array(
                "error_msg" => $error,
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "alt_display" => isset($altDisplay) ? $altDisplay : "",
                "prop_display" => isset($propDisplay) ? $propDisplay : "",

                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),

                "prePre_title" => isset($prePreTitle) ? $prePreTitle : "",
                "prePre_content" => isset($strOnprePre) ? $strOnprePre : "",
                "prePre_footer" => isset($strOnprePreFooter) ? $strOnprePreFooter : "",

                "onprogress_title" => isset($onprogressTitle) ? $onprogressTitle : "",
                "onprogress_content" => isset($strOnprog) ? $strOnprog : "",
                "onprogress_footer" => isset($strOnprogFooter) ? $strOnprogFooter : "",

                "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content" => isset($strOnprogView) ? $strOnprogView : "",
                "onprop_display_view" => isset($onpropDisplayView) ? $onpropDisplayView : "",

                "onprop_payment_view" => isset($arrayOnProgressPaymentView) ? $arrayOnProgressPaymentView : "none",

                "add_link" => isset($addLinkStr) ? $addLinkStr : "",
                "history_title" => isset($historyTitle) ? $historyTitle : "",
                "history_content" => isset($strHist) ? $strHist : "",
                "history_footer" => isset($strHistFooter) ? $strHistFooter : "",
                "recap_title" => isset($recapTitle) ? $recapTitle : "",
                "recap_content" => isset($strRecap) ? $strRecap : "",
                "recap_footer" => isset($strRecapFooter) ? $strRecapFooter : "",
                "profile_name" => isset($this->session->login['nama']) ? $this->session->login['nama'] : "",
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
                "index_active" => "class='active'",
                "time_line" => $time_line,
                "keterangan_notif" => isset($keterangan_notif) ? $keterangan_notif : "",
                "dasboardLists" => $dasboardLists,
            )
        );

        $p->render();

        break;

    case "createForm":

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        //region baca atribut, keterangan dari config
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $jenisTr = isset($jenisTr) ? $jenisTr : "";
        $jenisTransaksi = isset($jenisTransaksi) ? $jenisTransaksi : "";
        $pihakCaller = isset($pihakCaller) ? $pihakCaller : "";
        $pihakCaller2 = isset($pihakCaller2) ? $pihakCaller2 : "";
        $selectorCaller = isset($selectorCaller) ? $selectorCaller : "";
        $selectorCaller2 = isset($selectorCaller2) ? $selectorCaller2 : "";
        $selectorCallerForm = ''; // link shopping_cart pilih multi item
        $pihakCallerDelete = isset($pihakCallerDelete) ? $pihakCallerDelete : "";
        $pihakLabel = isset($pihakLabel) ? $pihakLabel : 'pilih';
        $pihakLabel2 = isset($pihakLabel2) ? $pihakLabel2 : 'pilih';
        $selectorLabel = isset($selectorLabel) ? $selectorLabel : 'pilih';
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $template = isset($template) ? $template : "";
        $setSubmitButton = isset($setSubmitButton) ? $setSubmitButton : "LANJUTKAN";
        $submitLabel = "Continue " . $subTitle;
        //endregion baca atribut, keterangan dari config

        $p = New Layout("$title", "$subTitle", "$template");

        $strOnprog = "";
        $strOnprogFooter = "";

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            if (sizeof($arrayOnProgress2) > 0) {
                //region onprogress2
                if (sizeof($arrayOnProgress2) > 0) {
                    $strOnprog .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                    switch ($viewMode) {
                        case "list":
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                            $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                            if (sizeof($arrayProgress2Labels) > 0) {
                                foreach ($arrayProgress2Labels as $key => $label) {
                                    $strOnprog .= "<td class='text-muted'>";
                                    $strOnprog .= $label;
                                    $strOnprog .= "</td>";
                                }
                            }
                            $strOnprog .= "</tr>";
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<td>";
                                        $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                        $strOnprog .= "</td>";
                                    }
                                }
                                $strOnprog .= "</tr>";
                            }
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td class='alert alert-warning' colspan='" . sizeof($arrayProgress2Labels) . "' align='center'>to process <strong>by request</strong> entries, you need to clear the list above from selected items.</td>";
                                $strOnprog .= "</tr>";
                            }
                            else {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td colspan='" . sizeof($arrayProgress2Labels) . "' align='right'><button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\">followup as new $title</button></td>";
                                $strOnprog .= "</tr>";
                            }
                            $strOnprog .= "</table>";
                            break;
                        case "thumbnail":
                            $strOnprog .= "<div class='panel-body' style='background:#e5e5e0;border:2px #cccccc dashed;'>";
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered' cellspacing='4'>";
                            $strOnprog .= "<tr line=" . __LINE__ . ">";
                            $no = 0;
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $no++;
                                $strOnprog .= "<td bgcolor='#f0f0f0' align='center'>";
                                $strOnprog .= "<label for='select_" . $no . "'>";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<div class='text-center'>";
                                        $strVal = isset($val[$key]) ? ($val[$key]) : "";
                                        $strVal = is_numeric($strVal) ? number_format($strVal) : $strVal;
                                        $strOnprog .= $strVal;
                                        $strOnprog .= "</div>";
                                    }
                                }
                                $strOnprog .= "</label>";
                                $strOnprog .= "</td>";
                                if ($no % 5 == 0) {
                                    $strOnprog .= "</tr><tr line=" . __LINE__ . ">";
                                }
                            }
                            $strOnprog .= "</tr>";
                            $strOnprog .= "</table class='table table-condensed table-bordered no-padding'>";

                            $strOnprog .= "<div class='row'>";
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6'>";
                                $strOnprog .= "to process <strong>by request</strong> entries, you need to clear the list above from selected items.";
                                $strOnprog .= "</div>";
                            }
                            else {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6 text-right'>";
                                $strOnprog .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary btn-block' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup as new $title</button>";
                                $strOnprog .= "</div>";
                            }
                            $strOnprog .= "</div>";
                            $strOnprog .= "</div>";

                            break;
                    }

                    $strOnprog .= "</form>";
                }
                //endregion
            }

            if (sizeof($arrayOnProgress) > 0) {
                //region onprogress
                if (sizeof($arrayOnProgress) > 0) {
                    $strOnprog .= "<div class='panel-body'>";
                    $strOnprog .= "<h4>action needed #1</h4>";
                    $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                    if (sizeof($arrayProgressLabels) > 0) {
                        foreach ($arrayProgressLabels as $key => $label) {
                            $strOnprog .= "<td class='text-muted'>";
                            $strOnprog .= $label;
                            $strOnprog .= "</td>";
                        }
                    }
                    $strOnprog .= "</tr>";

                    foreach ($arrayOnProgress as $key => $val) {
                        $strOnprog .= "<tr line=" . __LINE__ . ">";
                        if (sizeof($arrayProgressLabels) > 0) {
                            foreach ($arrayProgressLabels as $key => $label) {
                                $strOnprog .= "<td>";
                                $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                $strOnprog .= "</td>";
                            }
                        }
                        $strOnprog .= "</tr>";
                    }

                    $strOnprog .= "</table>";
                    $strOnprog .= "<div class='text-right'>";
                    $strOnprog .= "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
                    $strOnprog .= "</div class='text-right'>";
                    $strOnprog .= "</div class='panel-body'>";


                }
                else {
                    if (isset($arrayOnProgress2) && sizeof($arrayOnProgress2) > 0) {
                        $strOnprog = "";
                        $strOnprogFooter = "";
                    }
                    else {
                        $strOnprog = "-the item you specified has no entry-";
                        $strOnprogFooter = "";
                    }

                }
                //endregion
            }

        }
        $uploadData = "";
        if (sizeof($uploadConfig) > 0) {
            $jenisTransaksi = $this->uri->segment(3);
            $labelUpload = $uploadConfig['label'];
            $uploadAction = base_url() . $uploadConfig['action'];
            $uploadData .= "<form id='uplodXls' method='post' enctype='multipart/form-data' action='$uploadAction' target='result'>";
            $uploadData .= "<input type='file' name='fileExcel' class='form-control'>";
            $uploadData .= "<input type='submit' value='upload' class='btn btn-primary'>";
            $uploadData .= "</form>";
            $uploadData .= "<script>
                    function insertItem(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        var totalProduk = parseFloat(Object.keys(dTemp).length);
                        top.$('#result').load('" . base_url() . "/Selectors/_processSelectProduct/selectNoQty/" . $jenisTransaksi . "?noview=1&id='+data.id+'&minValue=0', null, function(){
                            setTimeout( function(){ changeUnit(ls_urut, ls_concated) }, 1200);
                            var ls_urut_tt = parseFloat(totalProduk) - parseFloat(ls_urut);
                            if(parseFloat(ls_urut_tt) != parseFloat(totalProduk)){
                                top.$('#totalProduk').html(parseFloat(totalProduk));
                                top.$('#progressProduk').html(parseFloat(ls_urut_tt));
                                console.log('totalProduk: ' + totalProduk);
                                console.log('ls_urut_tt: ' + ls_urut_tt);
                            }
                            else if(parseFloat(ls_urut_tt) === parseFloat(totalProduk)){
                                top.$('#totalProduk').html(parseFloat(totalProduk));
                                top.$('#progressProduk').html(parseFloat(ls_urut_tt));
                                HoldOn.close();
                                swal('selesai upload '+parseFloat(totalProduk)+' PRODUK, silahkan diperiksa kembali sebelum disimpan')
                                window.location.reload();
                                console.log('selesai');
                                console.error('totalProduk: ' + totalProduk);
                                console.error('ls_urut_tt: ' + ls_urut_tt);
                            }
                            else{
                                console.log('selesai **');
                            }
                        })
                    }
                    function changeUnit(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        top.$('#result').load('" . base_url() . "/Selectors/_processSelectProduct/selectNoQty/" . $jenisTransaksi . "?noview=1&id='+data.id+'&newQty=&qty_opname='+data.qty, null, function(){
                            rolling(ls_urut, ls_concated);
                        })
                    }
                    function rolling(ls_urut, ls_concated){
                        var dTemp = JSON.parse(ls_concated);
                        var data = dTemp[ls_urut];
                        var rl_ls_urut = (ls_urut-1);
                        if(rl_ls_urut>=0){
                            setTimeout( function(){ insertItem(rl_ls_urut, ls_concated) }, 500);
                        }
                        else{
                        }
                    }

$('#uplodXls').on('submit',function() {
    localStorage.clear();
    var setInt= setInterval(function() {
    var arrProduk = JSON.parse(localStorage.getItem('items'));
        if(null!=arrProduk){
            var options = {
                theme:\"custom\",
                // If theme == \"custom\" , the content option will be available to customize the logo
                content:'<img style=\"width:80px;\" src=\"https://www.google.de/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png\" class=\"center-block\">',
                message:' <h4>SEDANG PROSES UPLOAD PRODUK<br>MOHON UNTUK TIDAK MEREFRESH BROWSER ANDA.</h4><br><br><h1>PROGRESS... <span class=\"text-bold text-red\" id=\"progressProduk\"></span> Produk, DARI TOTAL <span id=\"totalProduk\" class=\"text-bold text-orange\"></span> PRODUK </h1><br> <input type=\"button\" value=\"Close this Cover\" onclick=\"HoldOn.close();\">',
                backgroundColor:\"#1847B1\",
                textColor:\"white\"
            };
            top.HoldOn.open(options);
            clearInterval(setInt);
            var arrProduk = JSON.parse(localStorage.getItem('items'));
            var totalProduk = Object.keys(arrProduk).length;
            var urut = 1;
            var concated = [];
            var arrays = [];
            arrProduk = Object.keys(arrProduk).map(function(k){
                arrProduk[k] = arrProduk[k]
                arrProduk[k]['key'] = k*1
                if(arrProduk[k]['id']*1>0){
                    return arrProduk[k]
                }
            });
            arrProduk.sort(function (a, b) {
                return a.key*1 - b.key*1;
            });
            jQuery.each(arrProduk, function(id,data){
                arrays = data;
                arrays['id'] = data.id;
                concated[data.key] = arrays;
                urut++;
            });
            concated = concated.reverse()
            localStorage.setItem('urut', '');
            localStorage.setItem('concat', '');
            localStorage.setItem('urut', (urut-2));
            localStorage.setItem('concat', JSON.stringify(concated).replace('null,', '') );
            var ls_urut = localStorage.getItem('urut');
            var ls_concated = localStorage.getItem('concat');
            insertItem(ls_urut, ls_concated);
        }
    },500);
})";

            $uploadData .= "</script>";

        }

        //region onprogressView Doank
        $strOnprogView = "";
        if (is_array($arrayOnProgressView) && sizeof($arrayOnProgressView) > 0) {
            $strOnprogView .= "<table class='table table-condensed table-bordered no-padding'>";
            $strOnprogView .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($stepHistoryFields) > 0) {
                foreach ($stepHistoryFields as $key => $label) {
                    $strOnprogView .= "<td class='text-muted'>";
                    if (is_array($label)) {
                        $strOnprogView .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strOnprogView .= $label;
                    }
                    $strOnprogView .= "</td>";
                }
            }
            $strOnprogView .= "</tr>";
            foreach ($arrayOnProgressView as $key => $val) {
                $strOnprogView .= "<tr line=" . __LINE__ . ">";
                if (sizeof($stepHistoryFields) > 0) {
                    foreach ($stepHistoryFields as $key => $label) {
                        $strOnprogView .= "<td>";
                        $strOnprogView .= isset($val[$key]) ? $val[$key] : "";
                        $strOnprogView .= "</td>";
                    }
                }
                $strOnprogView .= "</tr>";
            }
            $strOnprogView .= "</table>";
            //            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";

            $onpropDisplayView = "block";
        }
        else {
            $strOnprogView .= "-the item you specified has no entry-";
            $strOnprogFooter = "";
            $onpropDisplayView = "none";
        }
        //endregion


        $strHist = "";
        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<table class='table table-condensed table-bordered no-padding'>";

            $strHist .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($arrayHistoryLabels) > 0) {
                foreach ($arrayHistoryLabels as $key => $label) {
                    $strHist .= "<td class='text-muted'>";
                    if (is_array($label)) {
                        $strHist .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $strHist .= $label;
                    }
                    $strHist .= "</td>";
                }
            }
            $strHist .= "</tr>";

            foreach ($arrayHistory as $key => $val) {
                // print_r($val);
                $strHist .= "<tr line=" . __LINE__ . ">";
                if (sizeof($arrayHistoryLabels) > 0) {
                    foreach ($arrayHistoryLabels as $key => $label) {
                        $strHist .= "<td>";
                        $strHist .= $val[$key];
                        $strHist .= "</td>";
                    }
                }
                $strHist .= "</tr>";
            }


            $strHist .= "</table>";

            $strHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $strHist = "-the item you specified has no entry-";
            $strHistFooter = "";
        }
        //endregion

        //        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
        //
        //            $propDisplay = "block";
        //            $altDisplay = "none";
        //        } else {
        //
        //            $propDisplay = "none";
        //            $altDisplay = "block";
        //        }

        $propDisplay = "block";
        $altDisplay = "none";


        //        cekkuning($strOnprog);die();

        //        die("allowTmpSave:".$allowTmpSave);

        if (isset($barcodeSettings['srcModel'])) {
            $barcodeProcessor = "document.getElementById('result').src='" . base_url() . "Addons/BarcodeReader/readCode?jenisTr=$jenisTr&srcModel=" . $barcodeSettings['srcModel'] . "&srcColumn=" . $barcodeSettings['srcColumn'] . "&proc=" . blobEncode($selectorProcessor) . "&code='+this.value;";
        }
        else {
            $barcodeProcessor = "return false;";
        }
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";
        $tags = array();
        if (sizeof($editableItems) > 0) {
            foreach ($editableItems as $tag => $value) {
                $tags[$tag] = "$value";
            }
            $p->addTags($tags);
        }
        $p->addTags(
            array(
                "error_msg" => $error,
                "alt_display" => $altDisplay,
                "modeedit" => $modeedit,
                "modeeditopt" => "$modeeditopt",
                "prop_display" => $propDisplay,
                "tmpsave_display" => $allowTmpSave == true ? "block" : "none",
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "pihak_caller" => $pihakCaller,
                "pihak_caller2" => $pihakCaller2,
                "pihak_caller_rules" => $pihakMainCallerRules,
                "pihak_caller3" => $pihakCaller3,
                "pihak_callerExtern" => $pihakExternCaller,
                "selector_caller" => $selectorCaller,
                "selector_callerExtern" => $pihakExternCaller,
                "selector_caller2" => isset($selectorCaller2) ? $selectorCaller2 : "",
                "selector_caller_rules" => isset($selectorCalleRules) ? $selectorCallerRules : "",
                "selector_caller3" => isset($selectorCaller3) ? $selectorCaller3 : "",
                "pihak_caller_delete" => $pihakCallerDelete,
                "pihak_main_caller_delete" => $pihakMainCallerDelete,
                "pihak_main_caller_rules_delete" => $pihakMainCallerRulesDelete,
                "selector_caller_form" => $selectorCallerForm,
                "pihak_label" => $pihakLabel,
                "pihak_label2" => isset($pihakLabel2) ? $pihakLabel2 : "",
                "pihak_label3" => isset($pihakLabel3) ? $pihakLabel3 : "",
                "selector_label" => $selectorLabel,
                "selector_label2" => isset($selectorLabel2) ? $selectorLabel2 : "",
                "selector_rules_label" => isset($selectorLabelRules) ? $selectorLabelRules : "",
                "selector_label3" => isset($selectorLabel3) ? $selectorLabel3 : "",
                "submit_button" => $submitLabel,
                "pihak_main_label" => $pihakMainLabel,
                "pihak_rules_label" => $pihakMainLabelRules,
                "pihak_main_caller" => $pihakMainCaller,
                "pihakExternLabel" => $pihakExternLabel,
                //                "clear_shopping_cart" => $setClearShoppingCart,
                //                "action_shopping_cart" => $setActionShoppingCart,
                "onprogress_content" => $strOnprog,
                "onprogress_footer" => $strOnprogFooter,
                "history_content" => $strHist,
                "history_footer" => $strHistFooter,
                //                "payment_str"          => $strPaymentMethod,
                "ext_tool" => $extTool,
                "column_recorder" => $columnRecorderTarget,
                "default_description" => $defaultDescription,
                "profile_name" => $this->session->login['nama'],
                "add_pihak" => $addPihakStr,
                "add_pihak_rules" => (isset($addPihakRulesStr) ? $addPihakRulesStr : ""),
                "add_item" => $addItemStr,
                "this_page" => $thisPage,
                "view_mode_switch" => $viewModeSwitch,
                "barcode_action" => $barcodeProcessor,
                "mobile_scan" => $isMobile ? $mobScanStr : "",
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",

                "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content" => $strOnprogView,
                "onprop_display_view" => $onpropDisplayView,
                "globalTemplate" => $globalTemplate,
                "upload_item" => "$uploadData",
            )
        );

        $p->render();
        break;

    case "editForm":

        //        die($allowJoin);

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        //region baca atribut, keterangan dari config
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $jenisTr = isset($jenisTr) ? $jenisTr : "";
        $jenisTransaksi = isset($jenisTransaksi) ? $jenisTransaksi : "";
        $pihakCaller = isset($pihakCaller) ? $pihakCaller : "";
        $selectorCaller = isset($selectorCaller) ? $selectorCaller : "";
        $selectorCaller2 = isset($selectorCaller2) ? $selectorCaller2 : "";
        $selectorCallerForm = ''; // link shopping_cart pilih multi item
        $pihakCallerDelete = isset($pihakCallerDelete) ? $pihakCallerDelete : "";
        $pihakLabel = isset($pihakLabel) ? $pihakLabel : 'pilih';
        $selectorLabel = isset($selectorLabel) ? $selectorLabel : 'pilih';
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $template = isset($template) ? $template : "";
        $setSubmitButton = isset($setSubmitButton) ? $setSubmitButton : "LANJUTKAN";
        $submitLabel = "Continue " . $subTitle;
        //endregion baca atribut, keterangan dari config

        $p = New Layout("$title", "$subTitle", "$template");

        //cekHitam($template);
        $strOnprog = "";
        $strOnprogFooter = "";

        //        arrprint($arrayProgressLabels);
        //        arrprint($arrayOnProgress2);

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            if (sizeof($arrayOnProgress2) > 0) {
                //region onprogress2
                if (sizeof($arrayOnProgress2) > 0) {
                    $strOnprog .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                    switch ($viewMode) {
                        case "list":
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                            $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                            if (sizeof($arrayProgress2Labels) > 0) {
                                foreach ($arrayProgress2Labels as $key => $label) {
                                    $strOnprog .= "<td class='text-muted'>";
                                    $strOnprog .= $label;
                                    $strOnprog .= "</td>";
                                }
                            }
                            $strOnprog .= "</tr>";
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<td>";
                                        $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                        $strOnprog .= "</td>";
                                    }
                                }
                                $strOnprog .= "</tr>";
                            }
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td class='alert alert-warning' colspan='" . sizeof($arrayProgress2Labels) . "' align='center'>to process <strong>by request</strong> entries, you need to clear the list above from selected items.</td>";
                                $strOnprog .= "</tr>";
                            }
                            else {
                                $strOnprog .= "<tr line=" . __LINE__ . ">";
                                $strOnprog .= "<td colspan='" . sizeof($arrayProgress2Labels) . "' align='right'><button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\">followup as new $title</button></td>";
                                $strOnprog .= "</tr>";
                            }
                            $strOnprog .= "</table>";
                            break;
                        case "thumbnail":
                            $strOnprog .= "<div class='panel-body' style='background:#e5e5e0;border:2px #cccccc dashed;'>";
                            $strOnprog .= "<h4>by requests</h4>";
                            $strOnprog .= "<table class='table table-condensed table-bordered' cellspacing='4'>";
                            $strOnprog .= "<tr line=" . __LINE__ . ">";
                            $no = 0;
                            foreach ($arrayOnProgress2 as $key => $val) {
                                $no++;
                                $strOnprog .= "<td bgcolor='#f0f0f0' align='center'>";
                                $strOnprog .= "<label for='select_" . $no . "'>";
                                if (sizeof($arrayProgress2Labels) > 0) {
                                    foreach ($arrayProgress2Labels as $key => $label) {
                                        $strOnprog .= "<div class='text-center'>";
                                        $strVal = isset($val[$key]) ? ($val[$key]) : "";
                                        $strVal = is_numeric($strVal) ? number_format($strVal) : $strVal;
                                        $strOnprog .= $strVal;
                                        $strOnprog .= "</div>";
                                    }
                                }
                                $strOnprog .= "</label>";
                                $strOnprog .= "</td>";
                                if ($no % 5 == 0) {
                                    $strOnprog .= "</tr><tr line=" . __LINE__ . ">";
                                }
                            }
                            $strOnprog .= "</tr>";
                            $strOnprog .= "</table class='table table-condensed table-bordered no-padding'>";

                            $strOnprog .= "<div class='row'>";
                            if (isset($needToClear) && $needToClear == true) {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6'>";
                                $strOnprog .= "to process <strong>by request</strong> entries, you need to clear the list above from selected items.";
                                $strOnprog .= "</div>";
                            }
                            else {
                                $strOnprog .= "<div class='col-sm-6'></div>";
                                $strOnprog .= "<div class='col-sm-6 text-right'>";
                                $strOnprog .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary btn-block' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup as new $title</button>";
                                $strOnprog .= "</div>";
                            }
                            $strOnprog .= "</div>";
                            $strOnprog .= "</div>";

                            break;
                    }

                    $strOnprog .= "</form>";
                }
                //endregion
            }
            if (sizeof($arrayOnProgress) > 0) {
                //region onprogress
                if (sizeof($arrayOnProgress) > 0) {
                    $strOnprog .= "<div class='panel-body'>";
                    $strOnprog .= "<h4>action needed #2</h4>";
                    $strOnprog .= "<table class='table table-condensed table-bordered no-padding'>";
                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                    if (sizeof($arrayProgressLabels) > 0) {
                        foreach ($arrayProgressLabels as $key => $label) {
                            $strOnprog .= "<td class='text-muted'>";
                            $strOnprog .= $label;
                            $strOnprog .= "</td>";
                        }
                    }
                    $strOnprog .= "</tr>";

                    foreach ($arrayOnProgress as $key => $val) {
                        $strOnprog .= "<tr line=" . __LINE__ . ">";
                        if (sizeof($arrayProgressLabels) > 0) {
                            foreach ($arrayProgressLabels as $key => $label) {
                                $strOnprog .= "<td>";
                                $strOnprog .= isset($val[$key]) ? $val[$key] : "";
                                $strOnprog .= "</td>";
                            }
                        }
                        $strOnprog .= "</tr>";
                    }

                    $strOnprog .= "</table>";
                    $strOnprog .= "<div class='text-right'>";
                    $strOnprog .= "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
                    $strOnprog .= "</div class='text-right'>";
                    $strOnprog .= "</div class='panel-body'>";


                }
                else {
                    if (isset($arrayOnProgress2) && sizeof($arrayOnProgress2) > 0) {
                        $strOnprog = "";
                        $strOnprogFooter = "";
                    }
                    else {
                        $strOnprog = "-the item you specified has no entry-";
                        $strOnprogFooter = "";
                    }

                }
                //endregion
            }

        }


        $strHist = "";
        //region histories
        if (sizeof($arrayHistory) > 0) {
            $strHist .= "<table class='table table-condensed table-bordered no-padding'>";

            $strHist .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($arrayHistoryLabels) > 0) {
                foreach ($arrayHistoryLabels as $key => $label) {
                    $strHist .= "<td class='text-muted'>";
                    $strHist .= $label;
                    $strHist .= "</td>";
                }
            }
            $strHist .= "</tr>";

            foreach ($arrayHistory as $key => $val) {
                // print_r($val);
                $strHist .= "<tr line=" . __LINE__ . ">";
                if (sizeof($arrayHistoryLabels) > 0) {
                    foreach ($arrayHistoryLabels as $key => $label) {
                        $strHist .= "<td>";
                        $strHist .= $val[$key];
                        $strHist .= "</td>";
                    }
                }
                $strHist .= "</tr>";
            }


            $strHist .= "</table>";

            $strHistFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewHistory/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete histories ...</a>";
        }
        else {
            $strHist = "-the item you specified has no entry-";
            $strHistFooter = "";
        }
        //endregion

        //        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
        //
        //            $propDisplay = "block";
        //            $altDisplay = "none";
        //        } else {
        //
        //            $propDisplay = "none";
        //            $altDisplay = "block";
        //        }

        $propDisplay = "block";
        $altDisplay = "none";


        //        cekkuning($strOnprog);die();

        //        die("allowTmpSave:".$allowTmpSave);

        if (isset($barcodeSettings['srcModel'])) {
            $barcodeProcessor = "document.getElementById('result').src='" . base_url() . "Addons/BarcodeReader/readCode?jenisTr=$jenisTr&srcModel=" . $barcodeSettings['srcModel'] . "&srcColumn=" . $barcodeSettings['srcColumn'] . "&proc=" . blobEncode($selectorProcessor) . "&code='+this.value;";
        }
        else {
            $barcodeProcessor = "return false;";
        }
        $str_group = isset($_GET['gr']) ? "?gr=" . $_GET['gr'] : "";

        $p->addTags(
            array(
                "error_msg" => $error,
                "alt_display" => $altDisplay,
                "prop_display" => $propDisplay,
                "tmpsave_display" => $allowTmpSave == true ? "block" : "none",
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "pihak_caller" => $pihakCaller,
                "selector_caller" => $selectorCaller,
                "selector_caller2" => $selectorCaller2,
                "pihak_caller_delete" => $pihakCallerDelete,
                "selector_caller_form" => $selectorCallerForm,
                "pihak_label" => $pihakLabel,
                "selector_label" => $selectorLabel,
                "selector_label2" => isset($selectorLabel2) ? $selectorLabel2 : "",
                "submit_button" => $submitLabel,
                //                "clear_shopping_cart" => $setClearShoppingCart,
                //                "action_shopping_cart" => $setActionShoppingCart,
                "onprogress_content" => $strOnprog,
                "onprogress_footer" => $strOnprogFooter,
                "history_content" => $strHist,
                "history_footer" => $strHistFooter,
                //                "payment_str"          => $strPaymentMethod,
                "selectedID" => $selectedID,
                "ext_tool" => $extTool,
                "column_recorder" => $columnRecorderTarget,
                "default_description" => $defaultDescription,
                "profile_name" => $this->session->login['nama'],
                "add_pihak" => $addPihakStr,
                "add_item" => $addItemStr,
                "this_page" => $thisPage,
                "view_mode_switch" => $viewModeSwitch,
                "barcode_action" => $barcodeProcessor,
                "mobile_scan" => $isMobile ? $mobScanStr : "",
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
            )
        );

        $p->render();
        break;

    case "viewUndoneItems":

        $str = "";

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            $str .= "<div class='box box-danger'>";
            $str .= "<div class='box-header with-border text-green'>";
            $str .= "<h3><i class=\"blink fa fa-flash\"></i> on-going transactions <div onclick=\"top.viewundoneListApprove();\" class='btn btn-md btn-info pull-right'><i class='fa fa-refresh'></i></div></h3>";
            $str .= "</div>";
            $str .= "<div class='box-body table-responsive'>";
            if (sizeof($arrayOnProgress2) > 0) {
                $str .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                $str .= "<div class='panel panel-default'>";
                //            $str .= "<div class='panel-body' style='background:#e5e5e5;border:2px #cccccc dashed;'>";
                $str .= "<div class='panel-body'>";
                $str .= "<h4 class='text-blue'>from requests</h4>";
                $str .= "<div class='row'>";
                //            $str.="<ul class='pager'>";
                $rCtr = 0;
                foreach ($arrayOnProgress2 as $key => $pSpec) {
                    $rCtr++;
                    //                    $str .= "<div class='col-xs-2 panel'>";
                    if (isset($pSpec['select'])) {
                        $str .= "<label style='margin-bottom:-2px'>";
                    }
                    //                    $str .= "<div>{lihat produk}</div>";
                    $str .= "<div class='text-center alaIcon'>";
                    foreach ($arrayProgress2Labels as $k => $label) {
                        $iVal = isset($pSpec[$k]) ? $pSpec[$k] : "";
                        $str .= "<div>";
                        $str .= formatGlanceField($k, $iVal);
                        $str .= "</div>";
                    }
                    if (isset($pSpec['select'])) {
                        $str .= $pSpec['select'];
                    }
                    $str .= "</div class='col-md-3'>";
                    if (isset($pSpec['select'])) {
                        $str .= "</label>";
                    }
                }
                //            $str.="</ul class='pager'>";
                $str .= "</div>";
                if ($allowMultiSelect == true) {
                    $str .= "<div class='row'>";
                    if (isset($needToClear) && $needToClear == true) {
                        $str .= "<div class='col-sm-12 text-center'>";
                        $str .= "<div class='text-warning'>";
                        $str .= "to process one of entries above, you need to clear selected items<br>";
                        $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items</a>";
                        $str .= "</div class='alert alert-warning'>";
                        $str .= "</div>";
                    }
                    else {
                        $str .= "<div class='col-sm-6'></div>";
                        $str .= "<div class='col-sm-6 text-right'>";
                        $str .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup selected entry</button>";
                        $str .= "</div>";
                    }
                    $str .= "</div>";
                }
                $str .= "</div>";
                $str .= "</div class='panel panel-default'>";
                $str .= "</form>";
            }
            if (sizeof($arrayOnProgress) > 0) {
                /* -------------
                 * bloking kolom state
                 * untuk kembali versi lama $showState dibikin true
                 * di contoler line 761-763 hidupkan scrip yg lama
                 * ----------------------------------------*/
                $showState = false;
                if ($showState === true) {
                    $arrayProgressLabels_2 = $arrayProgressLabels;
                }
                else {
                    $arrBlock = array("state");
                    $arrayProgressLabels_2 = array_diff_key($arrayProgressLabels, array_flip($arrBlock));
                    $jml_tampil = sizeof($arrayProgressLabels_2);
                }

                $str .= "<table id='undoneitems_table' class='table dataTable table-bordered table-condensed compact table-hover-color-red table-striped'>";

                $str .= "<thead class='bg-gray'>";
                $str .= "<tr class='text-uppercase'>";
                foreach ($arrayProgressLabels_2 as $k => $label) {
                    $str .= "<th>";
                    if (is_array($label)) {
                        $str .= isset($label['label']) ? $label['label'] : "-";
                    }
                    else {
                        $str .= $label;
                    }
                    $str .= "</th>";
                }
                $str .= "</tr>";
                $str .= "</thead>";

                $str .= "<tbody>";
                $noi = 0;
                foreach ($arrayOnProgress as $key => $pSpec) {
                    $noi++;
                    $bg_color = $noi % 2 == 0 ? "#dcdcdc" : "#ffffff";
                    $str .= "<tr>";
                    foreach ($arrayProgressLabels_2 as $k => $label) {
                        $iVal = isset($pSpec[$k]) ? $pSpec[$k] : "";
//                        $iVal = str_replace("- ", "<br>- ", $iVal);
//                        $iVal = str_replace("=> ", "<br>=> ", $iVal);
//                        $value = "<a href='javascript:void()'>" . formatField($key, $trmp_0->$key) . "</a>";
                        $str .= "<td data-key='$k'>";
                        $str .= formatField($k, $iVal);
//                        $str .= formatGlanceField($k, $iVal);
                        $str .= "</td>";
                    }
                    $str .= "</tr>";

                    // -----------------------------------state horisontal---------------------------
                    if ($showState == true) {
                        $strState = $pSpec['state'];
                        $str .= "<tr style='background-color:$bg_color;'>";
                        $str .= "<td colspan='$jml_tampil'>$strState</td>";
                        $str .= "</tr>";
                    }
                }
                $str .= "</tbody>";

                $str .= "<tfoot>";
                if (isset($sumFooter) && sizeof($sumFooter) > 0) {
                    $str .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($arrayProgressLabels) > 0) {
                        //                        $str .= "<td>-</td>";
                        foreach ($arrayProgressLabels as $key => $label) {
                            $str .= "<td>";
                            if (isset($sumFooter) && isset($sumFooter[$key])) {
                                $str .= $sumFooter[$key];
                            }
                            else {
                                $str .= "-";
                            }
                            $str .= "</td>";
                        }
                    }
                    $str .= "</tr>";
                }
                $str .= "</tfoot>";
                $str .= "</table class='table table-condensed'>";

            }
            $str .= "</div class='box-body'>";
            $str .= "</div class='box box-danger'>";
        }

        $str .= "
                <script>
                    var filterButtons = [
                        { text: 'SO BELUM DIAPPROVE', keyword: 'preview quotation', color: 'badge-strong-danger' },
                        { text: 'BELUM BUAT RAB', keyword: 'create bom', color: 'badge-strong-danger' },
                        { text: 'RAB BELUM DIAPPROVE', keyword: 'approve', color: 'badge-strong-danger' },
                        { text: 'SEDANG RUNNING', keyword: 'on progress', color: 'badge-strong-danger' },
                        { text: 'SPK COMPLETE', keyword: 'spk progress', color: 'badge-strong-danger' },
                        { text: '(TAHAP I) CLOSING', keyword: 'akhir pekerja', color: 'badge-strong-danger' },
                        { text: 'CLEAR FILTER', keyword: '', color: 'badge-strong-info' }
                    ];
                    var dtButtons = filterButtons.map(f => {
                        return {
                          text: f.text,
                          action: function (e, dt, node, config) {
                            dt.columns(10).search(f.keyword).draw();
                          }
                        };
                    });
                    var undoneitems_table = $('#undoneitems_table').DataTable({
                        dom: 'lBfrtip',
                        order: [[8, 'desc']],
                        buttons: dtButtons,
                        columnDefs: [{
                            targets: 0,
                            render: function ( data, type, row ) {
                                if(data.indexOf('<')==0){
                                    data = $(data).text()
                                }
                                var isi_data = data.length>15?data.substr(0,15)+'…':data;
                                return \"<span title='\"+data+\"'>\"+isi_data+\"</span>\"
                            }
                        },
                        {
                            targets: 2,
                            render: function ( data, type, row ) {
                                if(data.indexOf('<')==0){
                                    data = $(data).text();
                                }
                                var isi_data = data.length>15?data.substr(0,15)+'…':data;
                                return \"<span title='\"+data+\"'>\"+isi_data+\"</span>\"
                            }
                        },
                        {
                            targets: 3,
                            render: function ( data, type, row ) {
                                var isi_data = data.length>15?data.substr(0,15)+'…':data;
                                return \"<span title='\"+data+\"'>\"+isi_data+\"</span>\"
                            }
                        }]
                    });
                    function updateButtons() {
                      $('#undoneitems_table_wrapper .dt-buttons .dt-button').each(function (i) {
                        var btnConfig = filterButtons[i];
                        if (!btnConfig) return;
                        var text = btnConfig.text;
                        var className = text.toLowerCase().replace(/\s+/g, '_');
                        $(this).addClass(className);
                        var count = 0;
                        if (btnConfig.keyword) {
                          count = undoneitems_table
                            .column(10)
                            .data()
                            .filter(function (d) {
                              return d.toLowerCase().indexOf(btnConfig.keyword) !== -1;
                            }).length;
                        } else {
                          count = undoneitems_table.rows().count(); // total semua baris
                        }
                        if(count>0){
                            $(this).html(
                              text + \" <span class='badge \" + btnConfig.color + \"'>\" + count + \"</span>\"
                            );
                        }
                      });
                    }
                    setTimeout(updateButtons, 300);
                    undoneitems_table.on('draw', function () {
                        updateButtons();
                    });
                </script>
            ";

        echo $str;
        break;

    case "viewUndoneItemsIndex":

        $str = "";
        $arrBlacklist = array(
            "no",
        );
        $stepper = isset($_GET['step']) ? $_GET['step'] : 1;
        if (isset($_GET['step'])) {
            //            arrPrint($steps[$_GET['step']]);
        }

        //        $time_line = "cek";
        //        if (isset($allSteps)) {
        //            $time_line = createStateHorizontal('-1', sizeof($allSteps), $jenisTr);
        //        }
        //        //-----------------------------------
        //        $keterangan_notif = notifTransaksi();
        //        //-----------------------------------
        //        if (strlen($errMsg) > 0) {
        //            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        //        }
        //        else {
        //            $error = "";
        //        }


        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();


        //region onprogress

        $strOnprog = "";

        $strOnprog .= "<div class=\"box box-danger\">";
        $strOnprog .= "<div class=\"box-header with-border text-red\">";
        $strOnprog .= "<h4 class=\"box-title text-uppercase blink\">$onprogressTitle</h4>";
        $strOnprog .= "</div>";

        $strOnprog .= "<div class=\"box-body\">";


        $switchToHistory = count($steps) == $stepper ? "History" : "";
        if (sizeof($steps) > 1) {
            $strOnprog .= "<ul class='nav nav-tabs'>";
            foreach ($steps as $tStep => $stepData) {
                $isiBadge = isset($arrayOnprogressGroup[$tStep]) ? "<span class='badge bg-red'>" . sizeof($arrayOnprogressGroup[$tStep]) . "</span>" : "";
                $actives = $tStep == $stepper ? "active" : "";
                $trSelesai = count($steps) == $tStep ? "SELESAI<br>" : "";


                $cssSelesai = count($steps) == $tStep ? "style='padding-top: 0;padding-bottom: 0;'" : "";
                $strOnprog .= "<li class='$actives'>";
                $undoneLinkIndex = MODUL_PATH . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "?step=$tStep";

                $varUrl = $tStep == $stepper ? "javascript:void(0);" : "if($('#undoneList>div>div.box-body')){ $('#undoneList>div>div.box-body').append(`<div id='overlay'><div id='text'>Loading Content...</div></div>`); document.getElementById('overlay').style.display = 'block'; $('#undoneList').load('$undoneLinkIndex'); }else{ console.log('tidak ada container undoneList'); }";

                $strOnprog .= "<a $cssSelesai class='nav-link btn' onclick=\"$varUrl\">";
                $strOnprog .= "<span class='text-uppercase text-bold'>$trSelesai" . $stepData['label'] . "</span>  $isiBadge </a>";
                $strOnprog .= "</li>";
            }
            $strOnprog .= "</ul>";

            $strOnprog .= "<div class='clearfix'>&nbsp;</div>";
        }

        if (isset($arrayOnprogressGroup[$stepper]) && sizeof($arrayOnprogressGroup[$stepper]) > 0) {
            $arrayOnProgress = $arrayOnprogressGroup[$stepper];
            $arrayOnprogressMarking = (isset($arrayOnprogressGroupPartialMark[$stepper]) && (sizeof($arrayOnprogressGroupPartialMark[$stepper]) > 0)) ? $arrayOnprogressGroupPartialMark[$stepper] : "";
        }
        else {
            $arrayOnProgress = array();
            $arrayOnprogressMarking = array();
        }
        if (sizeof($arrayOnProgress) > 0) {

            $strOnprog .= "<div class='table-responsive step_$stepper'>";
            $strOnprog .= "<table id='arrayOnProgress_step_$stepper' class='table datatables stripe compact nowarp order-column table-condensed table-bordered no-padding' style='border:solid red 0px;'>";
            $strOnprog .= "<thead>";

            if (count($steps) == $stepper) {

            }
            else {
                $strOnprog .= "<tr class='text-uppercase' line=" . __LINE__ . ">";
                if (sizeof($arrayProgressLabels) > 0) {
                    $strOnprog .= "<th class=''>No.</th>";
                    foreach ($arrayProgressLabels as $key => $label) {
                        $strOnprog .= "<th class=''>";
                        if (is_array($label)) {
                            $strOnprog .= isset($label['label']) ? $label['label'] : "-";
                        }
                        else {
                            $strOnprog .= $label;
                        }
                        $strOnprog .= "</th>";
                    }
                }
                $strOnprog .= "</tr>";
            }

            $strOnprog .= "</thead>";
            $strOnprog .= "<tbody>";

            if (count($steps) == $stepper) {

            }
            else {
                $no = 0;
                foreach ($arrayOnProgress as $key => $val) {
                    //----------------------
                    $background_color = isset($arrayOnprogressMarking[$key]['style']) ? $arrayOnprogressMarking[$key]['style'] : "";


                    $no++;
                    $strOnprog .= "<tr line=" . __LINE__ . " style='$background_color'>";
                    $strOnprog .= "<td>$no</td>";
                    if (sizeof($arrayProgressLabels) > 0) {
                        foreach ($arrayProgressLabels as $key => $label) {
                            $strOnprog .= "<td>";
                            $strOnprog .= $val[$key];
                            $strOnprog .= "</td>";
                        }
                    }
                    $strOnprog .= "</tr>";
                }
            }

            $strOnprog .= "</tbody>";

            if (isset($sumFooter) && sizeof($sumFooter) > 0) {
                $strOnprog .= "<tfoot>";
                $strOnprog .= "<tr line=" . __LINE__ . ">";

                if (count($steps) == $stepper) {

                }
                else {
                    if (sizeof($arrayProgressLabels) > 0) {

                        foreach ($arrayProgressLabels as $key => $label) {
                            $strOnprog .= "<th>";
//                            if (isset($sumFooter) && isset($sumFooter[$key])) {
//                                $strOnprog .= $sumFooter[$key];
//                            }
//                            else {
                            $strOnprog .= "-";
//                            }
                            $strOnprog .= "</th>";
                        }
                        $strOnprog .= "<th>-</th>";
                    }
                }

                $strOnprog .= "</tr>";
                $strOnprog .= "</tfoot>";
            }

            $strOnprog .= "</table>";
            $strOnprog .= "</div>";

            $strOnprog .= "<script>
                    $(document).ready( function(){
                        var table = $('#arrayOnProgress_step_$stepper').DataTable({
                            dom: 'lBfrtip',
                            fixedHeader: true,
                            lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                            pageLength: -1,
                            buttons: [],
//                            buttons: [
//                                        'copy', 'csv', 'excel', 'pdf', 'print'
//                                    ],

                            footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;
                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };
                                        var arrayFooter = $('#arrayOnProgress_step_$stepper>tfoot>tr>th');
                                        var dpageTotal = [];
                                        jQuery.each(arrayFooter, function(i,d){
                                            var id_n_index = parseFloat(i);
                                            dpageTotal[id_n_index] = 0;
                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii, obj){
                                                var pos = obj.indexOf('<');
                                                var hr = obj.indexOf('<hr>');
                                                var br = obj.indexOf('<br>');
                                                if(pos*1 > -1){ console.log('nilai pos: '+pos)}
                                                if(pos==0&&hr==-1&&br==-1&&id_n_index>0){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                }
                                            });
                                            if( !isNaN(dpageTotal[id_n_index]) && dpageTotal[id_n_index] > 0 ){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>\"+addCommas(dpageTotal[id_n_index])+\"</div>\"
                                                );
                                            }
                                        });
                                    }
                                });
                            });

                            $('.table-responsive.step_$stepper').floatingScroll();
                            $('.table-responsive.step_$stepper').scroll(function () {
                                setTimeout(function () {
                                    $('#arrayOnProgress_step_$stepper').DataTable().fixedHeader.adjust();
                                }, 400);
                            });
                    </script>";
        }
        else {
            $stepName = isset($steps[$stepper]['label']) ? $steps[$stepper]['label'] : "";
            $strOnprog .= "<div class='alert alert-warning'>";
            $strOnprog .= "- $stepName item you specified has no entry -";
            $strOnprog .= "</div>";
            //                $strOnprogFooter = "";
        }


        $strOnprog .= "</div>";
        $strOnprog .= "</div class=\"box box-danger\">";

        //endregion


        $str .= $strOnprog;
        echo $str;
        break;

    case "viewRequestItems":

        //        arrPrint($tabFieldsItems);

        $str = "";
        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            if (sizeof($tabHistoryFields) > 0) {
                //                cekKuning("::");
                $str .= "<div class=\"clearfix\">&nbsp;</div>";
                $str .= "<div class=\"nav-tabs-custom\">";
                $str .= "<ul class=\"nav nav-tabs\">";
                $str .= "<li class=\"pull-left header\"><i class=\"fa fa-th\"></i> REQUEST LIST<br><span style='margin-top: -18px;' class='pull-right text-green blink'>----------></span></li>";
                $no1 = 1;
                foreach ($tabHistoryFields as $ky => $arrLab) {
                    $active = $no1 == 1 ? "active text-bold text-green" : "text-bold";
                    $str .= "<li class=\"$active\"><a href=\"#tab_$ky\" data-toggle=\"tab\" aria-expanded=\"false\">" . $arrLab['label'] . "</a></li>";
                    $no1++;
                }
                $str .= "</ul>";
                $str .= "<div class=\"tab-content\">";

                $no2 = 1;
                foreach ($tabHistoryFields as $ky => $arrLab) {

                    $active = $no2 == 1 ? " active" : "";
                    $str .= "<div class=\"tab-pane$active\" id=\"tab_$ky\">";

                    $str .= "<form method='post' id='$ky' name='$ky' target='result' action='$reqFormTarget'>";
                    $str .= "<table class='table $ky table-hover' >";
                    $str .= "<thead style='background: lightgrey;'>";
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($tabFieldsItems[$ky] as $kyLabel => $label) {

                        if ($kyLabel == 'select') {
                            if (isset($allowMultiSelect) && $allowMultiSelect == true) {
                                $selectAll = "<input type='checkbox' id='selectAll_$ky'>";
                                $str .= "<th>$selectAll $label</th>";
                            }
                            else {
                                $str .= "<th>$label</th>";
                            }

                        }
                        else {
                            $str .= "<th>$label</th>";
                        }

                    }
                    $str .= "</tr>";
                    $str .= "</thead>";
                    $str .= "<tbody>";
                    //                    arrPrint($arrayOnProgress2);
                    foreach ($arrayOnProgress2[$ky] as $row) {
                        $str .= "<tr line=" . __LINE__ . ">";
                        foreach ($tabFieldsItems[$ky] as $kyLabel => $rows) {
                            if (isset($row[$kyLabel])) {
                                $str .= "<td>";
                                $str .= $row[$kyLabel];
                                $str .= "</td>";
                            }
                        }
                        $str .= "</tr>";
                    }
                    $str .= "</tbody>";
                    $str .= "<tfoot style='background: lightgrey;'>";
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($tabFieldsItems[$ky] as $kyLabel => $rows) {
                        $angka = array();
                        foreach ($arrayOnProgress2[$ky] as $row) {
                            if (!isset($angka[$ky])) {
                                $angka[$ky] = 0;
                            }
                            $angka[$ky] += is_numeric($row[$kyLabel]) ? $row[$kyLabel] : "";
                        }
                        $str .= "<th>";
                        $str .= $angka[$ky] > 0 ? $angka[$ky] : "";
                        $str .= "</th>";
                    }

                    $str .= "</tr>";
                    $str .= "</tfoot>";
                    $str .= "</table>";


                    if ($allowMultiSelect == true) {
                        $str .= "<div class='row'>";
                        if (isset($needToClear) && $needToClear == true) {
                            $str .= "<div class='col-sm-12 text-center'>";
                            $str .= "<div class='text-warning'>";
                            $str .= "to process one of entries above, you need to clear selected items<br>";
                            $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items </a>";
                            $str .= "</div class='alert alert-warning'>";
                            $str .= "</div>";
                        }
                        else {
                            if (isset($arrLab['allowFollowup']) && $arrLab['allowFollowup'] == true) {
                                cekHijau($ky);
                                $str .= "<div class='col-sm-6 text-left'>";
                                $str .= "<button id='btnConnect$ky' name='btnConnect$ky' class='btn btn-primary' href=# onclick=\"this.disabledxx=true;this.innerHTML='clear the list to connect another one';document.getElementById('$ky').submit()\"><span class='fa fa-external-link'></span> Followup " . $arrLab['label'] . "</button>";
                                $str .= "</div>";
                                $str .= "<div class='col-sm-6'></div>";
                            }
                            else {
                                $str .= "<div class='clearfix'>&nbsp;</div>";
                                $str .= "<div class='col-sm-12 text-left'>";
                                $str .= "<div class='alert alert-danger' role='error'>";
                                $str .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>";
                                $str .= "<span class='sr-only'>Error:</span>";
                                $str .= " NOTE:";
                                $str .= "<div> - Tidak Bisa Followup " . $arrLab['label'] . ", silahkan Followup melalui metode lainnya.</div>";
                                $str .= "<div> - Bagian ini hanya untuk kebutuhan Pengecekan.</div>";
                                $str .= "<div> - Letakan Cursor pada isi kolom produk, untuk melihat Rincian.</div>";
                                $str .= "</div>";
                                $str .= "</div>";
                            }

                        }
                        $str .= "</div class=row>";
                    }


                    $str .= "</div>";
                    $str .= "</form>";
                    $no2++;
                }
                $str .= "</div>";
                $str .= "</div>";

            }
            else {
                cekMerah("####");
                $str .= "<div class='box box-danger'>";
                $str .= "<div class='box-header text-green blink'>";
                $str .= "<h4><span class=\"glyphicon glyphicon-flash\"></span> on-going transactions</h4>";
                $str .= "</div class='box box-header'>";
                $str .= "<div class='box-body'>";
                if (sizeof($arrayOnProgress2) > 0) {
                    $str .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                    $str .= "<div class='panel panel-default'>";
                    $str .= "<div class='panel-body'>";
                    $str .= "<h4 class='text-blue'>from requests</h4>";
                    $str .= "<div class='row'>";
                    $rCtr = 0;
                    foreach ($arrayOnProgress2 as $key => $pSpec) {
                        $rCtr++;
                        if (isset($pSpec['select'])) {
                            $str .= "<label>";
                        }
                        $str .= "<div class='text-center alaIcon'>";
                        foreach ($arrayProgress2Labels as $k => $label) {
                            $iVal = isset($pSpec[$k]) ? $pSpec[$k] : "";
                            $str .= "<div>";
                            $str .= formatGlanceField($k, $iVal);
                            $str .= "</div>";
                        }
                        if (isset($pSpec['select'])) {
                            $str .= $pSpec['select'];
                        }
                        $str .= "</div class='col-md-3'>";
                        if (isset($pSpec['select'])) {
                            $str .= "</label>";
                        }
                    }
                    $str .= "</div>";
                    if ($allowMultiSelect == true) {
                        $str .= "<div class='row'>";
                        if (isset($needToClear) && $needToClear == true) {
                            $str .= "<div class='col-sm-12 text-center'>";
                            $str .= "<div class='text-warning'>";
                            $str .= "to process one of entries above, you need to clear selected items<br>";
                            $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items</a>";
                            $str .= "</div class='alert alert-warning'>";
                            $str .= "</div>";
                        }
                        else {
                            $str .= "<div class='col-sm-6'></div>";
                            $str .= "<div class='col-sm-6 text-right'>";
                            $str .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup selected entry</button>";
                            $str .= "</div>";
                        }
                        $str .= "</div>";
                    }
                    $str .= "</div>";
                    $str .= "</div class='panel panel-default'>";
                    $str .= "</form>";


                }
                if (sizeof($arrayOnProgress) > 0) {
                    $str .= "<table class='table table-condensed'>";
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($arrayProgressLabels as $k => $label) {
                        $str .= "<td>$label</td>";
                    }
                    $str .= "</tr>";
                    foreach ($arrayOnProgress as $key => $pSpec) {
                        $str .= "<tr line=" . __LINE__ . ">";
                        foreach ($arrayProgressLabels as $k => $label) {
                            $str .= "<td>";
                            $str .= $pSpec[$k];
                            $str .= "</td>";
                        }
                        $str .= "</tr>";
                    }
                    $str .= "</table class='table table-condensed'>";
                }
                $str .= "</div class='box-body'>";
                $str .= "</div class='box box-danger'>";
            }
        }

        echo $str;
        break;

    case "viewCompactUndoneItems":


        //        arrprint($arrayOnProgress);

        $str = "";

        if (sizeof($arrayOnProgress) > 0 || sizeof($arrayOnProgress2) > 0) {
            $str .= "<div class='box box-danger'>";

            $str .= "<div class='box-header text-red blink'>";
            $str .= "<h4><span class=\"glyphicon glyphicon-flash\"></span> on-going transactions</h4>";
            $str .= "</div class='box box-header'>";


            $str .= "<div class='box-body'>";
            //


            if (sizeof($arrayOnProgress2) > 0) {
                $str .= "<form method='post' id='fAsNew' name='fAsNew' target='result' action='$reqFormTarget'>";
                $str .= "<div class='panel panel-default'>";
                //            $str .= "<div class='panel-body' style='background:#e5e5e5;border:2px #cccccc dashed;'>";
                $str .= "<div class='panel-body'>";
                $str .= "<h4 class='text-blue'>from requests</h4>";
                $str .= "<div class='row'>";
                //            $str.="<ul class='pager'>";
                $rCtr = 0;
                foreach ($arrayOnProgress2 as $key => $pSpec) {
                    $rCtr++;
                    //                $str.="<li style='border:1px #777777 dotted;'>";
                    if (isset($pSpec['select'])) {
                        $str .= "<label>";

                    }

                    $str .= "<div class='text-center alaIcon'>";


                    foreach ($arrayProgress2Labels as $k => $label) {
                        //                    $iVal=is_numeric($pSpec[$k])?number_format($pSpec[$k]):$pSpec[$k];
                        //                    $str.=$iVal."<br>";
                        $iVal = isset($pSpec[$k]) ? $pSpec[$k] : "";
                        $str .= "<div>";
                        $str .= formatGlanceField($k, $iVal);
                        $str .= "</div>";

                    }

                    if (isset($pSpec['select'])) {
                        $str .= $pSpec['select'];

                    }
                    $str .= "</div class='col-md-3'>";
                    if (isset($pSpec['select'])) {

                        $str .= "</label>";
                    }
                    //                $str.="</li>";
                }
                //            $str.="</ul class='pager'>";

                $str .= "</div>";
                if ($allowMultiSelect == true) {

                    $str .= "<div class='row'>";
                    if (isset($needToClear) && $needToClear == true) {

                        $str .= "<div class='col-sm-12 text-center'>";
                        $str .= "<div class='text-warning'>";
                        $str .= "to process one of entries above, you need to clear selected items<br>";
                        $str .= "<a class='btn btn-warning' href='javascript:void(0)' onclick=\"document.getElementById('result').src='$clearCartTarget';\">clear selected items</a>";
                        $str .= "</div class='alert alert-warning'>";
                        $str .= "</div>";
                    }
                    else {
                        $str .= "<div class='col-sm-6'></div>";
                        $str .= "<div class='col-sm-6 text-right'>";
                        $str .= "<button id='btnConnect' name='btnConnect' class='btn btn-primary' href=# onclick=\"this.disabled=true;this.innerHTML='clear the list to connect another one';document.getElementById('fAsNew').submit()\"><span class='fa fa-external-link'></span> followup selected entry</button>";
                        $str .= "</div>";
                    }
                    $str .= "</div>";
                }
                $str .= "</div>";
                $str .= "</div class='panel panel-default'>";
                $str .= "</form>";


            }

            if (sizeof($arrayOnProgress) > 0) {
                $str .= "<table class='table table-condensed'>";

                $str .= "<tr line=" . __LINE__ . ">";
                foreach ($arrayProgressLabels as $k => $label) {
                    $str .= "<td>$label</td>";
                }
                $str .= "</tr>";


                foreach ($arrayOnProgress as $key => $pSpec) {
                    $str .= "<tr line=" . __LINE__ . ">";
                    foreach ($arrayProgressLabels as $k => $label) {
                        $str .= "<td>";
                        //                    $str.=formatField($k,$pSpec[$k]);
                        $str .= $pSpec[$k];
                        $str .= "</td>";
                    }
                    $str .= "</tr>";
                }


                $str .= "</table class='table table-condensed'>";

            }

            //
            $str .= "</div class='box-body'>";
            $str .= "</div class='box box-danger'>";

        }


        echo $str;
        break;

    case "preview":
        //        cekHere(":: HAHAHA ::");

        echo "<div class='alert alert-warning-dot text-center'>";
        echo "this is preview of what you are going to save";
        echo "</div class='alert alert-warning'>";

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey overflow-h' style=''>";
            // echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo createStateHorizontalMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";

        foreach ($headerRows as $key => $label) {
            echo "<li class='list-group-item' style='background:#f0f0f0;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            $val = isset($main[$key]) ? $main[$key] : "-";
            echo $val;
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if (isset($items) && sizeof($items) > 0) {
            //region itemssrc
            //            arrPrint($itemSrcLabels);
            $srcItems = "";
            if (sizeof($itemsSrc) > 0) {
                $srcItems .= "<div class='table-responsive'>";
                $srcItems .= "<table  class='table table-bordered table-condensed' style='background:#ffffff;'>";
                $srcItems .= "<tr bgcolor='#f5f5f5'>";
                $srcItems .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemSrcLabels as $cKol => $cAlias) {
                    $srcItems .= "<th class='text-muted' style='font-weight:bold;'>";
                    $srcItems .= $cAlias;
                    $srcItems .= "</th>";
                }
                $n = 0;
                foreach ($itemsSrc as $itemsSrc_0) {
                    $n++;
                    $srcItems .= "<tr line=" . __LINE__ . ">";
                    $srcItems .= "<td>$n</td>";
                    foreach ($itemSrcLabels as $cKol => $cAlias) {
                        $srcItems .= "<td>" . formatField($cKol, $itemsSrc_0[$cKol]);
                        $srcItems .= "</td>";
                    }
                    $srcItems .= "</tr>";
                }

                $srcItems .= "</tr>";
                $srcItems .= "</table>";
                $srcItems .= "</div>";
            }
            echo $srcItems;
            //endregion
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            echo "<tr bgcolor='#f5f5f5'>";
            echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($itemLabels as $key => $label) {
                echo "<th class='text-muted' style='font-weight:bold;'>";
                echo $label;
                echo "</th>";
            }
            echo "</tr>";

            $no = 0;
            foreach ($items as $iSpec) {
                $no++;
                $fieldVal = "";


                echo "<tr line=" . __LINE__ . ">";
                echo "<td align='right'>";
                echo $no;
                echo ".</td>";
                foreach ($itemLabels as $key => $label) {
                    echo "<td>";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                    }

                    echo $fieldVal;
                    echo "</td>";
                }
                echo "</tr>";
                // cekHijau($imageEnabled);
                // arrPrint($iSpec);
                if (($noteEnabled == true) || ((isset($imageEnabled)) && ($imageEnabled == true))) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                    if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        echo $iVal;
                        // echo "</td>";


                    }
                    if (isset($imageEnabled) && ($imageEnabled == true)) {
                        $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                        echo $iVal;
                    }
                    echo "</td>";
                    echo "</tr>";

                }
            }

            if (isset($items2) && sizeof($items2) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr view transaksi line=" . __LINE__ . " bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
//                cekMerah("items2");
//                arrPrint($items2);
//                cekMerah("items2_ses");
//                arrPrint($items2_ses);
                $no = 0;
                foreach ($items2 as $iSpec) {
                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td>";
                        if (isset($iSpec[$key])) {
                            $iVal = $iSpec[$key];
                        }
                        else {
                            $iVal = 0;
                        }
                        echo formatField($key, $iVal);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

            }

            if (isset($items3) && sizeof($items3) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items3 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        echo "<td>";
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }
                if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                    foreach ($sumRows3 as $key => $label) {
                        $colspanX = sizeof($itemLabels3) > 1 ? sizeof($itemLabels3) : sizeof($itemLabels);
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";

                        $val = 0;
                        if (isset($main[$key]) && $main[$key] > 0) {
                            $val = $main[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }
            }

            if (isset($sumRows) && sizeof($sumRows) > 0) {
                foreach ($sumRows as $key => $label) {
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
                    //                    echo $main[$key];
                    $val = 0;
                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                            $val = $mainAddValues[$key];
                        }
                    }

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
                //                arrPrint($mainAddValues);

            }

            if (isset($sumAddRows) && sizeof($sumAddRows) > 0) {
                $valAdd = 0;
                foreach ($sumAddRows as $keyAdd => $label) {
                    //                        cekLime($keyAdd);
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
                    $val = 0;
                    if (isset($main[$keyAdd]) && $main[$keyAdd] > 0) {
                        $valAdd = isset($main[$keyAdd]) ? $main[$keyAdd] : 0;
                    }
                    else {
                        if (isset($mainAddValues[$keyAdd]) && $mainAddValues[$keyAdd] > 0) {
                            $valAdd = isset($mainAddValues[$keyAdd]) ? $mainAddValues[$keyAdd] : 0;
                        }
                        else {
                            $valAdd = 0;
                        }
                    }

                    echo formatField($keyAdd, $valAdd);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                echo "<tr bgcolor='#e5e5e5'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                echo "</tr>";
                //                arrPrint($mainAddFields);
                foreach ($extValueLabels as $key => $lSpec) {
                    //                    arrPrint($lSpec);
                    if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {


                        $mdlName9 = $lSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        $relPairs = array();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $relPairs[$row9->id] = $row9->nama;
                            }
                        }
                        //                        arrPrint($relPairs);die();
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_src";
                        $val = isset($mainAddFields[$key2]) ? $mainAddFields[$key2] : 0;
                        $realVal = isset($relPairs[$val]) ? $relPairs[$val] : $val;
                        echo $realVal;
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                    echo "<td class='text-right'>";

                    $val = isset($mainAddValues[$key]) ? $mainAddValues[$key] : 0;
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                    if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_tax";
                        $val = isset($mainAddValues[$key . "_tax"]) ? $mainAddValues[$key . "_tax"] : 0;
                        echo formatField($key2, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                //                if (isset($grandTotal) && $grandTotal > 0) {
                //                    echo "<tr bgcolor='#e5e5e5'>";
                //                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total</td>";
                //                    echo "<td class='text-right'>";
                //
                //
                //                    echo formatField("total", $grandTotal);
                //                    echo "</td>";
                //                    echo "</tr>";
                //                }
            }

            if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                foreach ($mainInputs as $key => $val) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                    echo "<td class='text-right'>";

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";

            if (sizeof($mainElements) > 0) {

                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' bgcolor=#f0f0f0>";
                echo "$title details";
                echo "</td>";
                echo "</tr>";
                //                arrprint($elementConfig);die();
                foreach ($mainElements as $elName => $aSpec) {
                    if (isset($elementConfig[$elName]['elementType'])) {
                        //                    cekkuning("element: $elName");

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>";
                        echo "<span class='text-muted'>" . $aSpec['label'] . "</span>";
                        echo "</td>";
                        echo "<td colspan='" . (sizeof($itemLabels)) . "'>";

                        switch ($elementConfig[$elName]['elementType']) {
                            case "dataModel":
                                //                            cekkuning("$elName dataModel");
                                $elContents = unserialize(base64_decode($aSpec['contents']));
                                //                            arrprint($elContents);
                                if (sizeof($elContents) > 0) {
                                    echo "<table class='tables table-condensed'>";
                                    foreach ($elContents as $label => $val) {

                                        if ($val != "") {
                                            echo "<tr line=" . __LINE__ . ">";
                                            $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                            if (strlen($strLabel) > 0) {
                                                echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                            }
                                            echo "<td align='left'>$val</td>";
                                            echo "</tr>";
                                        }


                                    }
                                    echo "</table>";
                                }
                                break;
                            case "dataField":
                                echo $aSpec['value'];
                                //                            cekkuning("$elName dataField");
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }


                }
                echo "</table>";
            }

            if (strlen($description) > 0) {
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                echo "<span class='text-muted'>description note</span><br>";
                echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>" . nl2br($description) . "</span><br>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }

            echo "</div class='table-responsive'>";

            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-default' data-dismiss='modal'><span class='glyphicon glyphicon-chevron-left'></span> cancel</a>";
            echo "</div class='col-md-6'>";

            $notif_text = "your selected items will be processed. Continue saving?";
            $notif_html = "";

            $non_material = $items2_ses[0]['produk'];
            $non_biaya = $items2_ses[0]['biaya'];

//            arrPrintWebs($items2_ses);

//            echo json_encode($non_biaya) . "<br>";
//            echo json_encode($non_material);
//            matiHere(__LINE__);
            if( count($items2_ses[0]['biaya']) === 0 ){
                $notif_text = "PROJECT TANPA BIAYA";
                $notif_html = "<i class=\"fa fa-warning text-orange\"></i> <span class=\"text-bold text-red\">PILIHAN INI MEMBAWA RESIKO.</span> <i class=\"fa fa-warning text-orange\"></i><br>PAHAMI DAMPAKNYA ATAU KONSULTASIKAN DULU DENGAN ATASAN.<br><br>APAKAH ANDA INGIN MELANJUTKAN?";
            }

            if( count($items2_ses[0]['produk']) === 0 ){
                $notif_text = "PROJECT TANPA MATERIAL";
                $notif_html = "<i class=\"fa fa-warning text-orange\"></i> <span class=\"text-bold text-red\">PILIHAN INI MEMBAWA RESIKO.</span> <i class=\"fa fa-warning text-orange\"></i> <br>PAHAMI DAMPAKNYA ATAU KONSULTASIKAN DULU DENGAN ATASAN.<br><br>APAKAH ANDA INGIN MELANJUTKAN?";
            }

            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-success' onclick=\"clickSave(this)\"><span class='glyphicon glyphicon-ok'></span> $buttonLabel</a>";
            echo "</div class='col-md-6'>";

            echo "</div class='row'>";

            echo "<div class='row'>";
            echo "<div class='panel-body'>";
            echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
            echo "<small>";
            echo $saveWarning;
            echo "</small>";
            echo "</div class='col-md-12 text-center'>";
            echo "</div class='panel-body'>";
            echo "</div class='row'>";

            echo "<script>
            
                function clickSave(e){
                    e.style.visibility='hidden';
                    swal({
                        title: '$notif_text',
                        html: '$notif_html',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, lanjutkan',
                        cancelButtonText: 'Tidak, batalkan',
                        buttonsStyling: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    })
                    .then(function() {
                        swal('PROCESSING...', 'SEDANG MENYIMPAN TRANSAKSI,, MOHON TUNGGU SEBENTAR.', 'info');
                        swal.enableLoading();
                        document.getElementById('result').src='".$actionTarget."';
                    }, 
                    function(dismiss) {
                        if (dismiss === 'cancel') {
                            console.log('anda membatalkan pilihan lanjutan');
                            e.style.visibility='visible';
                        }
                    });
                }
            
            </script>";

        }


        break;

    case "editPreview":
        //        cekHere(":: HAHAHA ::");

        echo "<div class='alert alert-warning-dot text-center'>";
        echo "this is preview of what you are going to save";
        echo "</div class='alert alert-warning'>";

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";

        foreach ($headerRows as $key => $label) {
            echo "<li class='list-group-item' style='background:#f0f0f0;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            $val = isset($main[$key]) ? $main[$key] : "-";
            echo $val;
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if (isset($items) && sizeof($items) > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            echo "<tr bgcolor='#f5f5f5'>";
            echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($itemLabels as $key => $label) {
                echo "<th class='text-muted' style='font-weight:bold;'>";
                echo $label;
                echo "</th>";
            }
            echo "</tr>";

            $no = 0;
            foreach ($items as $iSpec) {
                $no++;
                $fieldVal = "";


                echo "<tr line=" . __LINE__ . ">";
                echo "<td align='right'>";
                echo $no;
                echo ".</td>";
                foreach ($itemLabels as $key => $label) {
                    echo "<td>";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                    }

                    echo $fieldVal;
                    echo "</td>";
                }
                echo "</tr>";
                // cekHijau($imageEnabled);
                // arrPrint($iSpec);
                if (($noteEnabled == true) || ($imageEnabled == true)) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                    if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        echo $iVal;
                        // echo "</td>";


                    }
                    if ($imageEnabled == true) {
                        $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                        echo $iVal;
                    }
                    echo "</td>";
                    echo "</tr>";

                }
            }

            arrPrint($items2);
            if (isset($items2) && sizeof($items2) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items2 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td>";
                        //                    echo $iSpec[$key];
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

            }


            //            arrprint($main);
            //            arrprint($mainAddValues);
            if (isset($sumRows) && sizeof($sumRows) > 0) {
                foreach ($sumRows as $key => $label) {
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
                    //                    echo $main[$key];
                    $val = 0;
                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                            $val = $mainAddValues[$key];
                        }
                    }

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                echo "<tr bgcolor='#e5e5e5'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                echo "</tr>";
                //                arrPrint($mainAddFields);
                foreach ($extValueLabels as $key => $lSpec) {
                    //                    arrPrint($lSpec);
                    if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {


                        $mdlName9 = $lSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        $relPairs = array();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $relPairs[$row9->id] = $row9->nama;
                            }
                        }
                        //                        arrPrint($relPairs);die();
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_src";
                        $val = isset($mainAddFields[$key2]) ? $mainAddFields[$key2] : 0;
                        $realVal = isset($relPairs[$val]) ? $relPairs[$val] : $val;
                        echo $realVal;
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                    echo "<td class='text-right'>";

                    $val = isset($mainAddValues[$key]) ? $mainAddValues[$key] : 0;
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                    if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_tax";
                        $val = isset($mainAddValues[$key . "_tax"]) ? $mainAddValues[$key . "_tax"] : 0;
                        echo formatField($key2, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                //                if (isset($grandTotal) && $grandTotal > 0) {
                //                    echo "<tr bgcolor='#e5e5e5'>";
                //                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total</td>";
                //                    echo "<td class='text-right'>";
                //
                //
                //                    echo formatField("total", $grandTotal);
                //                    echo "</td>";
                //                    echo "</tr>";
                //                }
            }

            if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                foreach ($mainInputs as $key => $val) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                    echo "<td class='text-right'>";

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            //            if (isset($main['tagihan'])) {
            //                echo "<tr line=".__LINE__.">";
            //                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
            //                echo "<td class='text-right'>";
            //
            //                echo formatField("tagihan", $main['tagihan']);
            //                echo "</td>";
            //                echo "</tr>";
            //            }

            echo "</table>";


            if (sizeof($mainElements) > 0) {

                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' bgcolor=#f0f0f0>";
                echo "$title details";
                echo "</td>";
                echo "</tr>";
                //                arrprint($elementConfig);die();
                foreach ($mainElements as $elName => $aSpec) {
                    if (isset($elementConfig[$elName]['elementType'])) {
                        //                    cekkuning("element: $elName");

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>";
                        echo "<span class='text-muted'>" . $aSpec['label'] . "</span>";
                        echo "</td>";
                        echo "<td colspan='" . (sizeof($itemLabels)) . "'>";

                        switch ($elementConfig[$elName]['elementType']) {
                            case "dataModel":
                                //                            cekkuning("$elName dataModel");
                                $elContents = unserialize(base64_decode($aSpec['contents']));
                                //                            arrprint($elContents);
                                if (sizeof($elContents) > 0) {
                                    echo "<table class='tables table-condensed'>";
                                    foreach ($elContents as $label => $val) {
                                        echo "<tr line=" . __LINE__ . ">";
                                        $strLabel = $elementConfig[$elName]['usedFields'][$label];
                                        if (strlen($strLabel) > 0) {

                                            echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                            //                                    echo "<td align='left'>$label</td>";
                                        }
                                        echo "<td align='left'>$val</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                }
                                break;
                            case "dataField":
                                echo $aSpec['value'];
                                //                            cekkuning("$elName dataField");
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }


                }
                echo "</table>";
            }


            if (strlen($description) > 0) {
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                echo "<span class='text-muted'>description note</span><br>";
                echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>" . nl2br($description) . "</span><br>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }


            echo "</div class='table-responsive'>";


            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-default' data-dismiss='modal'><span class='glyphicon glyphicon-chevron-left'></span> cancel</a>";
            echo "</div class='col-md-6'>";

            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-success' onclick=\"if(confirm('your selected items will be processed. Continue saving?')==1){document.getElementById('result').src='" . $actionTarget . "';this.style.visibility='hidden';}\"><span class='glyphicon glyphicon-ok'></span> $buttonLabel</a>";
            echo "</div class='col-md-6'>";

            echo "</div class='row'>";

            echo "<div class='row'>";
            echo "<div class='panel-body'>";
            echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
            echo "<small>";
            echo $saveWarning;
            echo "</small>";
            echo "</div class='col-md-12 text-center'>";
            echo "</div class='panel-body'>";
            echo "</div class='row'>";

        }


        break;

    case "cancelPackingPreview":
        cekHere(":: cancelPackingPreview HAHAHA ::");

        echo "<div class='alert alert-warning-dot text-center'>";
        echo "this is preview of what you are going to save";
        echo "</div class='alert alert-warning'>";

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";

        foreach ($headerRows as $key => $label) {
            echo "<li class='list-group-item' style='background:#f0f0f0;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            $val = isset($main[$key]) ? $main[$key] : "-";
            echo $val;
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if (isset($items) && sizeof($items) > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            echo "<tr bgcolor='#f5f5f5'>";
            echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($itemLabels as $key => $label) {
                echo "<th class='text-muted' style='font-weight:bold;'>";
                echo $label;
                echo "</th>";
            }
            echo "</tr>";

            $no = 0;
            foreach ($items as $iSpec) {
                $no++;
                $fieldVal = "";

                echo "<tr line=" . __LINE__ . ">";
                echo "<td align='right'>";
                echo $no;
                echo ".</td>";
                foreach ($itemLabels as $key => $label) {
                    echo "<td>";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                    }

                    echo $fieldVal;
                    echo "</td>";
                }
                echo "</tr>";
                // cekHijau($imageEnabled);
                // arrPrint($iSpec);
                if (($noteEnabled == true) || ($imageEnabled == true)) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                    if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        echo $iVal;
                    }
                    if ($imageEnabled == true) {
                        $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                        echo $iVal;
                    }
                    echo "</td>";
                    echo "</tr>";

                }
            }


            if (isset($items2) && sizeof($items2) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items2 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td>";
                        //                    echo $iSpec[$key];
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

            }


            //            arrprint($main);
            //            arrprint($mainAddValues);
            if (isset($sumRows) && sizeof($sumRows) > 0) {
                foreach ($sumRows as $key => $label) {
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
                    //                    echo $main[$key];
                    $val = 0;
                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                            $val = $mainAddValues[$key];
                        }
                    }

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                echo "<tr bgcolor='#e5e5e5'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                echo "</tr>";
                //                arrPrint($mainAddFields);
                foreach ($extValueLabels as $key => $lSpec) {
                    //                    arrPrint($lSpec);
                    if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {


                        $mdlName9 = $lSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        $relPairs = array();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $relPairs[$row9->id] = $row9->nama;
                            }
                        }
                        //                        arrPrint($relPairs);die();
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_src";
                        $val = isset($mainAddFields[$key2]) ? $mainAddFields[$key2] : 0;
                        $realVal = isset($relPairs[$val]) ? $relPairs[$val] : $val;
                        echo $realVal;
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                    echo "<td class='text-right'>";

                    $val = isset($mainAddValues[$key]) ? $mainAddValues[$key] : 0;
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                    if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_tax";
                        $val = isset($mainAddValues[$key . "_tax"]) ? $mainAddValues[$key . "_tax"] : 0;
                        echo formatField($key2, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                //                if (isset($grandTotal) && $grandTotal > 0) {
                //                    echo "<tr bgcolor='#e5e5e5'>";
                //                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total</td>";
                //                    echo "<td class='text-right'>";
                //
                //
                //                    echo formatField("total", $grandTotal);
                //                    echo "</td>";
                //                    echo "</tr>";
                //                }
            }

            if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                foreach ($mainInputs as $key => $val) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                    echo "<td class='text-right'>";

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            //            if (isset($main['tagihan'])) {
            //                echo "<tr line=".__LINE__.">";
            //                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
            //                echo "<td class='text-right'>";
            //
            //                echo formatField("tagihan", $main['tagihan']);
            //                echo "</td>";
            //                echo "</tr>";
            //            }

            echo "</table>";


            if (sizeof($mainElements) > 0) {

                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' bgcolor=#f0f0f0>";
                echo "$title details";
                echo "</td>";
                echo "</tr>";
                //                arrprint($elementConfig);die();
                foreach ($mainElements as $elName => $aSpec) {
                    if (isset($elementConfig[$elName]['elementType'])) {
                        //                    cekkuning("element: $elName");

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>";
                        echo "<span class='text-muted'>" . $aSpec['label'] . "</span>";
                        echo "</td>";
                        echo "<td colspan='" . (sizeof($itemLabels)) . "'>";

                        switch ($elementConfig[$elName]['elementType']) {
                            case "dataModel":
                                //                            cekkuning("$elName dataModel");
                                $elContents = unserialize(base64_decode($aSpec['contents']));
                                //                            arrprint($elContents);
                                if (sizeof($elContents) > 0) {
                                    echo "<table class='tables table-condensed'>";
                                    foreach ($elContents as $label => $val) {
                                        echo "<tr line=" . __LINE__ . ">";
                                        $strLabel = $elementConfig[$elName]['usedFields'][$label];
                                        if (strlen($strLabel) > 0) {

                                            echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                            //                                    echo "<td align='left'>$label</td>";
                                        }
                                        echo "<td align='left'>$val</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                }
                                break;
                            case "dataField":
                                echo $aSpec['value'];
                                //                            cekkuning("$elName dataField");
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }


                }
                echo "</table>";
            }


            if (strlen($description) > 0) {
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                echo "<span class='text-muted'>description note</span><br>";
                echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>" . nl2br($description) . "</span><br>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }


            echo "</div class='table-responsive'>";


            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-default' data-dismiss='modal'><span class='glyphicon glyphicon-chevron-left'></span> cancel</a>";
            echo "</div class='col-md-6'>";

            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-success' onclick=\"if(confirm('your selected items will be processed. Continue saving?')==1){document.getElementById('result').src='" . $actionTarget . "';this.style.visibility='hidden';}\"><span class='glyphicon glyphicon-ok'></span> $buttonLabel</a>";
            echo "</div class='col-md-6'>";

            echo "</div class='row'>";

            echo "<div class='row'>";
            echo "<div class='panel-body'>";
            echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
            echo "<small>";
            echo $saveWarning;
            echo "</small>";
            echo "</div class='col-md-12 text-center'>";
            echo "</div class='panel-body'>";
            echo "</div class='row'>";

        }


        break;

    case "preCancelPackingPreview":
        cekHere(":: preCancelPackingPreview HAHAHA ::");

        echo "<div class='alert alert-warning-dot text-center'>";
        echo "this is preview of what you are going to save";
        echo "</div class='alert alert-warning'>";

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";

        foreach ($headerRows as $key => $label) {
            echo "<li class='list-group-item' style='background:#f0f0f0;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            $val = isset($main[$key]) ? $main[$key] : "-";
            echo $val;
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if (isset($items) && sizeof($items) > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            echo "<tr bgcolor='#f5f5f5'>";
            echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
            foreach ($itemLabels as $key => $label) {
                echo "<th class='text-muted' style='font-weight:bold;'>";
                echo $label;
                echo "</th>";
            }
            echo "</tr>";

            $no = 0;
            foreach ($items as $iSpec) {
                $no++;
                $fieldVal = "";

                echo "<tr line=" . __LINE__ . ">";
                echo "<td align='right'>";
                echo $no;
                echo ".</td>";
                foreach ($itemLabels as $key => $label) {
                    echo "<td>";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        $fieldVal = isset($iSpec[$key]) ? formatField($key, $iSpec[$key]) : "";
                    }

                    echo $fieldVal;
                    echo "</td>";
                }
                echo "</tr>";
                // cekHijau($imageEnabled);
                // arrPrint($iSpec);
                if (($noteEnabled == true) || ($imageEnabled == true)) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                    if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        echo $iVal;
                    }
                    if ($imageEnabled == true) {
                        $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                        echo $iVal;
                    }
                    echo "</td>";
                    echo "</tr>";

                }
            }


            if (isset($items2) && sizeof($items2) > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";

                $no = 0;
                foreach ($items2 as $iSpec) {
                    $no++;

                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<td>";
                        //                    echo $iSpec[$key];
                        echo formatField($key, $iSpec[$key]);
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ($noteEnabled == true) {
                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            echo $iVal;
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

            }


            //            arrprint($main);
            //            arrprint($mainAddValues);
            if (isset($sumRows) && sizeof($sumRows) > 0) {
                foreach ($sumRows as $key => $label) {
                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    echo "<td class='text-right'>";
                    //                    echo $main[$key];
                    $val = 0;
                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                            $val = $mainAddValues[$key];
                        }
                    }

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                echo "<tr bgcolor='#e5e5e5'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                echo "</tr>";
                //                arrPrint($mainAddFields);
                foreach ($extValueLabels as $key => $lSpec) {
                    //                    arrPrint($lSpec);
                    if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {


                        $mdlName9 = $lSpec['mdlName'];
                        $this->load->model("Mdls/" . $mdlName9);
                        $o9 = new $mdlName9();
                        $tmp9 = $o9->lookupAll()->result();
                        $relPairs = array();
                        if (sizeof($tmp9) > 0) {
                            foreach ($tmp9 as $row9) {
                                $relPairs[$row9->id] = $row9->nama;
                            }
                        }
                        //                        arrPrint($relPairs);die();
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_src";
                        $val = isset($mainAddFields[$key2]) ? $mainAddFields[$key2] : 0;
                        $realVal = isset($relPairs[$val]) ? $relPairs[$val] : $val;
                        echo $realVal;
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                    echo "<td class='text-right'>";

                    $val = isset($mainAddValues[$key]) ? $mainAddValues[$key] : 0;
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                    if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainAddValues[$key . "_tax"];
                        $key2 = $key . "_tax";
                        $val = isset($mainAddValues[$key . "_tax"]) ? $mainAddValues[$key . "_tax"] : 0;
                        echo formatField($key2, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                //                if (isset($grandTotal) && $grandTotal > 0) {
                //                    echo "<tr bgcolor='#e5e5e5'>";
                //                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total</td>";
                //                    echo "<td class='text-right'>";
                //
                //
                //                    echo formatField("total", $grandTotal);
                //                    echo "</td>";
                //                    echo "</tr>";
                //                }
            }

            if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                foreach ($mainInputs as $key => $val) {
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                    echo "<td class='text-right'>";

                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            //            if (isset($main['tagihan'])) {
            //                echo "<tr line=".__LINE__.">";
            //                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
            //                echo "<td class='text-right'>";
            //
            //                echo formatField("tagihan", $main['tagihan']);
            //                echo "</td>";
            //                echo "</tr>";
            //            }

            echo "</table>";


            if (sizeof($mainElements) > 0) {

                echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' bgcolor=#f0f0f0>";
                echo "$title details";
                echo "</td>";
                echo "</tr>";
                //                arrprint($elementConfig);die();
                foreach ($mainElements as $elName => $aSpec) {
                    if (isset($elementConfig[$elName]['elementType'])) {
                        //                    cekkuning("element: $elName");

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td>";
                        echo "<span class='text-muted'>" . $aSpec['label'] . "</span>";
                        echo "</td>";
                        echo "<td colspan='" . (sizeof($itemLabels)) . "'>";

                        switch ($elementConfig[$elName]['elementType']) {
                            case "dataModel":
                                //                            cekkuning("$elName dataModel");
                                $elContents = unserialize(base64_decode($aSpec['contents']));
                                //                            arrprint($elContents);
                                if (sizeof($elContents) > 0) {
                                    echo "<table class='tables table-condensed'>";
                                    foreach ($elContents as $label => $val) {
                                        echo "<tr line=" . __LINE__ . ">";
                                        $strLabel = $elementConfig[$elName]['usedFields'][$label];
                                        if (strlen($strLabel) > 0) {

                                            echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                            //                                    echo "<td align='left'>$label</td>";
                                        }
                                        echo "<td align='left'>$val</td>";
                                        echo "</tr>";
                                    }
                                    echo "</table>";
                                }
                                break;
                            case "dataField":
                                echo $aSpec['value'];
                                //                            cekkuning("$elName dataField");
                                break;
                        }

                        echo "</td>";
                        echo "</tr>";
                    }


                }
                echo "</table>";
            }


            if (strlen($description) > 0) {
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                echo "<span class='text-muted'>description note</span><br>";
                echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>" . nl2br($description) . "</span><br>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }


            echo "</div class='table-responsive'>";


            echo "<div class='row'>";
            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-default' data-dismiss='modal'><span class='glyphicon glyphicon-chevron-left'></span> cancel</a>";
            echo "</div class='col-md-6'>";

            echo "<div class='col-md-6'>";
            echo "<a class='btn btn-block btn-success' onclick=\"if(confirm('your selected items will be processed. Continue saving?')==1){document.getElementById('result').src='" . $actionTarget . "';this.style.visibility='hidden';}\"><span class='glyphicon glyphicon-ok'></span> $buttonLabel</a>";
            echo "</div class='col-md-6'>";

            echo "</div class='row'>";

            echo "<div class='row'>";
            echo "<div class='panel-body'>";
            echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
            echo "<small>";
            echo $saveWarning;
            echo "</small>";
            echo "</div class='col-md-12 text-center'>";
            echo "</div class='panel-body'>";
            echo "</div class='row'>";

        }


        break;

    case "followupPreview":
//        matiHere(__LINE__);
        echo "<div id='followupPreview' line='".__LINE__."'>";
        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";

            $arrSwals = array(
                "type" => "warning",
                "title" => "<span style='color: red;'>Perhatian..</span>",
                "html" => $newWarningLabel,
                "allowOutsideClick" => false,
                // "imageUrl"            => img_bitzer(),
                "background" => "#34abeb",
                "confirmButtonText" => "Close",
                "confirmButtonColor" => "#ff0055",
            );

            echo swalAlert($arrSwals);
        }
        else {
            $msgWarnings = array();
        }

        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";

            $newWarningLabel = "<span style='color: yellow;'>";
            $newWarningLabel .= $msgSpec['label'];
            $newWarningLabel .= "<div class='font-size-0-7 margin-top-20'>silahkan tutup notifikasi ini untuk melanjutkan transaksi</div>";
            $newWarningLabel .= "</span>";
            $arrSwals = array(
                "type" => "warning",
                "title" => "<span style='color: red;'>Perhatian</span>",
                "html" => $newWarningLabel,
                "allowOutsideClick" => false,
                // "imageUrl"            => img_bitzer(),
                "background" => "#34abeb",
                "confirmButtonText" => "Close",
                "confirmButtonColor" => "#ff0055",
            );

            echo swalAlert($arrSwals);
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='overflow: hidden;'>";
            // echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            // echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo createStateHorizontalMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

//        echo "<ul class='list-group'>";
        echo "<div class='box-header box-solid bg-warning'>";
        echo "<div class='row'>";
        foreach ($mainLabels as $key => $label) {
//            echo "<li class='list-group-item'>";
            echo "<div class='col-md-6'>";
            echo "<div class='row'>";

            echo "<div class='col-md-4 text-muted text-bold text-uppercase'>";
            echo $label;
            echo "</div>";

            echo "<div class='col-md-8 text-capitalize'>";
            if (isset($main->$key)) {
                if (is_array($main->$key)) {
                    $rslt_isi = "";
                    foreach ($main->$key as $isi) {
                        if ($rslt_isi == "") {
                            $rslt_isi = $isi;
                        }
                        else {
                            $rslt_isi = $rslt_isi . ", $isi";
                        }
                    }
                    echo formatField($key, $rslt_isi);
                }
                else {
                    echo formatField($key, $main->$key);
                }
            }
            else {
                if (isset($mainValues[$key])) {
                    echo formatField($key, $mainValues[$key]);
                }
                else {
                    echo "";
                }
            }
            echo "</div>";

            echo "</div>";
            echo "</div>";
//            echo "</li>";
        }
        echo "</div>";
        echo "</div>";
//        echo "</ul>";

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            //rincian project
            if (isset($rincianProject) && $rincianProject != "") {
                echo $rincianProject;
            }

//            cekMerah($rincianProject);
//            cekMerah(__LINE__);

            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table line='" . __LINE__ . "' class='table table-bordered table-condensed' style='background:#ffffff;'>";
            $no = 0;
            if (isset($items) && sizeof($items) > 0) {
                echo "<thead line='" . __LINE__ . "'>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($items as $id => $iSpec) {
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels as $key => $label) {


                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );

                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }

                        switch ($detailSizeKey) {
                            default:
                            case "ckd":

                                foreach ($items as $pid => $item) {

                                    $replacers = array(
                                        "volume_new" => "volume_gross",
                                        "sub_volume_new" => "sub_volume_gross",
                                        "berat_new" => "berat_gross",
                                        "sub_berat_new" => "sub_berat_gross",
                                    );

                                    foreach ($replacers as $orig => $new) {
                                        if ($key == $orig) {
                                            $key = $new;
                                        }
                                    }
                                }

                                break;
                            case "cbu":
                                break;
                        }

                        $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;

//                        cekHere($key . " " . $iSpec[$key] . " | subVal: $subVal");
//arrPrintWebs($detailValues);
                        if ($key == "stok") {
                            $val = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                        elseif ($key == "stok_center") {
                            $val = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                        else {
                            $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;
                        }

                        //                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;

                        if ($allowEdit == true && in_array($key, $editableFields)) {
                            //                            cekKuning(":: $key editable ::");
                            if (is_numeric($val)) {
                                $val += 0;
                                $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                                $inputType = "text";
                                $addEvent = "";
                                if (!$allowIncrement) {
                                    $addEvent = " oninput=\"if(parseInt(removeCommas(this.value))<1 || parseInt(removeCommas(this.value))>$maxVal){this.value='" . number_format($maxVal) . "';}\" 
                                    onkeyup=\"this.value=addCommas(this.value);\" 
                                    onblur=\"top.$('#result').load('$updateItemFieldTarget?id=$id&key=$key&val='+removeCommas(this.value))\" ";
                                }
                                else {
                                    $addEvent = " onkeyup=\"this.value=addCommas(this.value);\"  
                                    onblur=\"top.$('#result').load('$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+removeCommas(this.value))\" ";
                                }

                            }
                            else {
                                $inputType = "text";
                                $addEvent = "";
                            }
                            $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='" . number_format($val) . "' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
                            //                            cekMerah(":: $key NOT editable ::");
                            $strVal = formatField($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }

                        echo "<td $tdOpt >$strVal";
                        echo "</td>";
                    }
                    if ($allowEdit == true) {//==delete item
                        if ($allowRemove == false) {

                        }
                        else {
                            //sengaja dimatiin biar items tidka diremove
                            // echo "<td>";
                            // echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                            // echo "</td>";
                        }
                    }
                    echo "</tr>";

                    if ((($noteEnabled === true)) || (($imageEnabled === true))) {

                        if ((isset($iSpec['note']) && strlen($iSpec['note']) > 1) || (isset($iSpec['images']) && strlen($iSpec['images']) > 1)) {

                            echo "<tr line=" . __LINE__ . ">";

                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($noteEditabled) && ($noteEditabled === true)) {
                                $key_note = "note";
                                $note_val = isset($iSpec['note']) ? $iSpec['note'] : "";
                                $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key_note&val='+encodeURIComponent(this.value)\" ";
                                if (isset($noteType)) {
                                    switch ($noteType) {
                                        case "textarea":
                                            $iVal = "<textarea line='" . __LINE__ . "' id='$key_note" . "_" . "$id' class='form-control text-left' onclick='this.select()' $addEvent>" . $note_val . "</textarea>";
                                            break;
                                        case "text":
                                        default:
                                            $iVal = "<input line='" . __LINE__ . "' type='text' name='$key_note" . "_" . "$id' class='form-control text-left' value='$note_val' onclick='this.select()' $addEvent>";
                                            break;
                                    }
                                }

                            }
                            else {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            }

//                            $iVal = str_replace("\n", "<br>", $iVal);
//                            $iVal = str_replace("\r", "<br>", $iVal);

                            echo "<div class='row no-padding no-margin'>";
                            echo "<div class='col-md-11'>";
                            echo $iVal;
                            echo "</div>";


                            if (($imageEnabled === true)) {
                                $image_val = isset($iSpec['images']) ? $iSpec['images'] : "";
                                if (strlen($image_val) > 1) {
                                    echo "<div class='col-md-1 text-left'>";
                                    echo "<img src='$image_val' height='50px;' stylee='float: right;'>";
                                    echo "</div>";
                                }
                            }
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }

                    }

                }
//                if ($_SERVER['REMOTE_ADDR'] == "202.65.117.72") {
//                    mati_disini("LINE: " . __LINE__ . " TRANSAKSI BERHASIL (mode maintenance), tunggu beberapa saat lagi yaa.., TRID: $insertID");
//                }


                if ((isset($itemLabels2)) && (sizeof($itemLabels2) > 1)) {
                    if (isset($items2) && sizeof($items2) > 0 && $currentStep != 3) {
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr bgcolor='#f5f5f5'>";
                        echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                        foreach ($itemLabels2 as $key => $label) {
                            echo "<th class='text-muted' style='font-weight:bold;'>";
                            echo $label;
                            echo "</th>";
                        }
                        echo "</tr>";

                        $no = 0;
                        foreach ($items2 as $id => $iSpec2) {

                            $no++;
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo ".</td>";
                            foreach ($itemLabels2 as $key2 => $label2) {

                                $subVal2 = isset($iSpec2[$key2]) ? $iSpec2[$key2] : 0;
                                if ($key2 == "stok") {
                                    $val2 = isset($iSpec2[$key2]) ? $iSpec2[$key2] : 0;
                                }
                                elseif ($key2 == "stok_center") {
                                    $val2 = isset($iSpec2[$key2]) ? $iSpec2[$key2] : 0;
                                }
                                else {
                                    $val2 = isset($detailSubValues[$id][$key2]) ? $detailSubValues[$id][$key2] : $subVal2;
                                }

                                if ($allowEdit == true && in_array($key2, $editableFields2)) {
                                    if (is_numeric($val2)) {
                                        $val2 += 0;
                                        $maxVal2 = isset($iSpec2["max_" . $key2]) ? $iSpec2["max_" . $key2] : $iSpec2[$key2];
                                        // cekMErah($maxVal2);
                                        $inputType = "text";
                                        $addEvent = "";
                                        if (!$allowIncrement) {
                                            $addEvent = " oninput=\"if(parseInt(removeCommas(this.value))<1 || parseInt(removeCommas(this.value))>$maxVal2){this.value='" . number_format($maxVal2) . "';}\" 
                                            onkeyup=\"this.value=addCommas(this.value);\" 
                                            onblur=\"top.$('#result').load('$updateSubItemFieldTarget?id=$id&key=$key2&val='+removeCommas(this.value))\" ";
                                        }
                                        else {
                                            // cekHitam("allowincrement");
                                            $addEvent = " onkeyup=\"this.value=addCommas(this.value);\"  
                                            onblur=\"top.$('#result').load('$updateSubItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key2&val='+removeCommas(this.value))\" ";
                                        }

                                    }
                                    else {
                                        $inputType = "text";
                                        $addEvent = "";
                                    }
                                    $strVal2 = "<input type=$inputType name='$key2" . "_" . "$id' class='form-control text-right' value='" . number_format($val2) . "' onclick='this.select()' $addEvent>";
                                    $tdOpt2 = "style='margin:0px;padding:0px;$addStyle' ";
                                }
                                else {
                                    //                            cekMerah(":: $key NOT editable ::");
                                    if (isset($iSpec2[$key2])) {
                                        $iVal2 = $iSpec2[$key2];
                                    }
                                    else {
                                        $iVal2 = 0;
                                    }
                                    $strVal2 = formatField($key2, $iVal2);
                                    $tdOpt2 = "style='$addStyle'";
                                }
                                echo "<td $tdOpt2>$strVal2";
                                echo "</td>";

                            }
                            if ($allowEdit == true) {//==delete item
                                if ($allowRemove == false) {

                                }
                                else {
                                    echo "<td>";
                                    echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeSubItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                                    echo "</td>";
                                }
                            }
                            else {
                                echo "";
                            }
                            echo "</tr>";
                        }
                    }
                }

                if ((isset($itemLabels2)) && (sizeof($itemLabels2) > 1)) {
                    if (isset($items3) && sizeof($items3) > 0 && $currentStep != 3) {
                        echo "<div class='table-responsive'>";
                        echo "<table line=" . __LINE__ . " class='table table-bordered table-condensed'>";
                        echo "<tr bgcolor='#f5f5f5'>";
                        echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                        foreach ($itemLabels3 as $key => $label) {
                            echo "<th class='text-muted' style='font-weight:bold;'>";
                            echo $label;
                            echo "</th>";
                        }
                        echo "</tr>";

                        $no = 0;
                        foreach ($items3 as $id => $iSpec2) {

                            $no++;
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo ".</td>";
                            foreach ($itemLabels3 as $key2 => $label2) {
                                $subVal2 = isset($iSpec2[$key2]) ? $iSpec2[$key2] : 0;
                                if ($key2 == "stok") {
                                    $val2 = isset($iSpec2[$key2]) ? $iSpec2[$key2] : 0;
                                }
                                elseif ($key2 == "stok_center") {
                                    $val2 = isset($iSpec2[$key2]) ? $iSpec2[$key2] : 0;
                                }
                                else {
                                    $val2 = isset($detailSubValues[$id][$key2]) ? $detailSubValues[$id][$key2] : $subVal2;
                                }

                                if ($allowEdit == true && in_array($key2, $editableFields2)) {
                                    if (is_numeric($val2)) {
                                        $val2 += 0;
                                        $maxVal2 = isset($iSpec2["max_" . $key2]) ? $iSpec2["max_" . $key2] : $iSpec2[$key2];
                                        // cekMErah($maxVal2);
                                        $inputType = "text";
                                        $addEvent = "";
                                        if (!$allowIncrement) {
                                            $addEvent = " oninput=\"if(parseInt(removeCommas(this.value))<1 || parseInt(removeCommas(this.value))>$maxVal2){this.value='" . number_format($maxVal2) . "';}\"
                                            onkeyup=\"this.value=addCommas(this.value);\"
                                            onblur=\"top.$('#result').load('$updateSubItemFieldTarget?id=$id&key=$key2&val='+removeCommas(this.value))\" ";
                                        }
                                        else {
                                            // cekHitam("allowincrement");
                                            $addEvent = " onkeyup=\"this.value=addCommas(this.value);\"
                                            onblur=\"top.$('#result').load('$updateSubItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key2&val='+removeCommas(this.value))\" ";
                                        }

                                    }
                                    else {
                                        $inputType = "text";
                                        $addEvent = "";
                                    }
                                    $strVal2 = "<input type=$inputType name='$key2" . "_" . "$id' class='form-control text-right' value='" . number_format($val2) . "' onclick='this.select()' $addEvent>";
                                    $tdOpt2 = "style='margin:0px;padding:0px;$addStyle' ";
                                }
                                else {
                                    //                            cekMerah(":: $key NOT editable ::");
                                    if (isset($iSpec2[$key2])) {
                                        $iVal2 = $iSpec2[$key2];
                                    }
                                    else {
                                        $iVal2 = 0;
                                    }
                                    $strVal2 = formatField($key2, $iVal2);
                                    $tdOpt2 = "style='$addStyle'";
                                }
                                echo "<td $tdOpt2>$strVal2";
                                echo "</td>";

                            }
                            if ($allowEdit == true) {//==delete item
                                if ($allowRemove == false) {

                                }
                                else {
                                    echo "<td>";
                                    echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeSubItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                                    echo "</td>";
                                }
                            }
                            else {
                                echo "";
                            }
                            echo "</tr>";
                        }
                    }
                }


                if (isset($extractedSubItems2) && sizeof($extractedSubItems2) > 0) {
                    // cekLime("ada");
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels3 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";
                    $no = 0;
                    foreach ($extractedSubItems2 as $ixID => $iiSpec) {
                        foreach ($iiSpec as $iSpec) {
                            $no++;
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo "</td>";
                            foreach ($itemLabels3 as $key => $label) {
                                echo "<td>";
                                echo formatField_he_format($key, $iSpec[$key]);
                                echo "</td>";
                            }
                            echo "</tr>";

                            if (isset($items3[$ixID]) && sizeof($items3[$ixID]) > 0) {
                                foreach ($items3[$ixID] as $xids => $rSpeck) {
                                    echo "<tr>";
                                    echo "<td ></td>";
                                    foreach ($itemLabels3_sub as $key_id => $alias) {
                                        echo "<td>";
                                        echo formatField_he_format($key_id, $rSpeck[$key_id]);
                                        echo "</td>";


                                    }
                                    echo "</tr>";


                                }

                            }
                            echo "</tr>";

                        }


                    }
                    echo "</table class='table table-bordered table-condensed'>";
                    echo "</div class='table-responsive'>";

                }

                if (isset($sumRows) && sizeof($sumRows) > 0 && $currentStep != 3) {
                    foreach ($sumRows as $key => $label) {

                        if (isset($items2) && sizeof($items2) > 0 ) {
                            // Jika items2 ada dan tidak kosong
                            $colspanSum = sizeof($itemLabels2) > 1 ? $itemLabels2 : $itemLabels;
                        } elseif (isset($items3) && sizeof($items3) > 0) {
                            // Jika items3 ada dan tidak kosong (dan items2 tidak ada atau kosong)
                            $colspanSum = sizeof($itemLabels3) > 1 ? $itemLabels3 : $itemLabels;
                        } else {
                            // Jika keduanya tidak ada atau kosong
                            $colspanSum = $itemLabels;
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($colspanSum) . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";

                        if (isset($mainValues[$key])) {
                            echo formatField($key, $mainValues[$key]);
                        }
                        else {
                            echo "";
                        }

                        echo "</td>";
                        echo "</tr>";

                    }

                    if (isset($sumAddRows) && sizeof($sumAddRows) > 0) {
                        foreach ($sumAddRows as $key => $label) {
                            echo "<tr line='" . __LINE__ . " key: $key'>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                            echo "<td class='text-right'>";
                            if (isset($mainValues[$key])) {
                                echo formatField($key, $mainValues[$key]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }


                //region child data
                if (isset($items_child) && sizeof($items_child) > 0) {
                    echo "<div class='table-responsive'>";
                    //                    echo "<div class=''>Detail</div>";
                    echo "<table line='" . __LINE__ . "' class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemsChildLabel as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    if ($itemsChildGate == "main") {
                        //                        arrPrint($main);
                        //                        foreach ($items as $id => $itemSpec) {
                        foreach ($items_child as $id => $itemSpec) {
                            $no++;
                            foreach ($itemSpec as $x => $iSpec) {
                                echo "<tr line=" . __LINE__ . ">";
                                echo "<td align='right'>";
                                echo $no;
                                echo ".</td>";
                                foreach ($itemsChildLabel as $key => $label) {
                                    //                                cekHere()test
                                    if (isset($itemsChildLabelEditable[$key])) {
                                        $inputType = "text";
                                        $val = $iSpec[$key];
                                        $addEvent = " onblur=\"document.getElementById('result').src='$updateItemChildTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&x=$x&val='+this.value\" ";
                                        $strVal = "<input type=$inputType name='$id" . "_" . "$x' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                                        $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                                    }
                                    else {
                                        $strVal = $iSpec[$key];
                                    }
                                    echo "<td $tdOpt>";
                                    echo $strVal;
                                    echo "</td>";
                                }
                                echo "</tr>";
                            }
                            //                                arrPrintWebs($iSpec);


                        }
                        //                        }
                    }
                    else {
                        foreach ($items as $id => $itemSpec) {
                            foreach ($items_child[$id] as $x => $iSpec) {
                                $no++;
                                echo "<tr line=" . __LINE__ . ">";
                                echo "<td align='right'>";
                                echo $no;
                                echo ".</td>";
                                foreach ($itemsChildLabel as $key => $label) {
                                    //                                cekHere()test
                                    if (isset($itemsChildLabelEditable[$key])) {
                                        $inputType = "text";
                                        $val = $iSpec[$key];
                                        $addEvent = " onblur=\"document.getElementById('result').src='$updateItemChildTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&x=$x&val='+this.value\" ";
                                        $strVal = "<input type=$inputType name='$id" . "_" . "$x' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                                        $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                                    }
                                    else {
                                        $strVal = $iSpec[$key];
                                    }
                                    echo "<td $tdOpt>";
                                    echo $strVal;
                                    echo "</td>";
                                }
                                echo "</tr>";

                            }
                        }
                    }


                }

                //endregion


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    echo "<tr bgcolor='#e5e5e5'>";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    echo "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            echo "<td class='text-right'>";
                            //                            echo $mainValues[$key . "_tax"];

                            if (in_array($key, $extEditableFields)) {
                                $defValue = isset($mainAddFields[$key . "_src"]) ? $mainAddFields[$key . "_src"] : 0;
                                $selKey = $key . "_src";
                                echo "<select name='$selKey' class='form-control'>";
                                if (sizeof($relPairs) > 0) {
                                    foreach ($relPairs as $id => $name) {
                                        $selected = $id == $defValue ? "selected" : "";
                                        echo "<option value='$id' $selected>$name</option>";
                                    }
                                }
                                echo "</select>";
                            }
                            else {

                                if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                    $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                                }
                                else {
                                    $val = "n/a";
                                }

                                echo $val;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainValues[$key];

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }
                        if (in_array($key, $extEditableFields)) {
                            $defValue = (0 + $val);
                            echo "<input type=number class='form-control text-right' name='$key' step='1000' value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if(parseInt(this.value)>$defValue || parseInt(this.value)<0){this.value='$defValue';}\">";
                        }
                        else {
                            echo formatField($key, $val);
                        }
                        echo "</td>";
                        echo "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            echo "<td class='text-right'>";
                            //                            echo $mainValues[$key . "_tax"];

                            if (in_array($key, $extEditableFields)) {
                                $defValue = (0 + $val);
                                echo "<input type=number class='form-control text-right' name='$key" . "_tax" . "' step=1000 value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if (parseInt(this.value) > $defValue || parseInt(this.value)<0) {this.value= '$defValue';}\">";
                            }
                            else {
                                echo formatField($key . "_tax", $val);
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }

                if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                    foreach ($mainInputs as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($sumRowsAdditional) && (sizeof($sumRowsAdditional) > 0)) {
                    foreach ($sumRowsAdditional as $key => $val) {
                        $value = isset($mainValues[$key]) ? $mainValues[$key] : "";
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$val</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $value);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($addRows) && sizeof($addRows) > 0) {
                    foreach ($addRows as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$addRowLabels[$key]</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                //region extended add main source
                $no = 0;
                if (isset($addMainSourceField) && sizeof($addMainSourceField) > 0) {
                    echo "<div class='table-responsive'>";
                    //                    echo "<div class=''>Detail</div>";
                    echo "<table line='" . __LINE__ . "' class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($addMainSourceField as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td $tdOpt>";
                    echo "1";
                    echo "</td>";
                    foreach ($addMainSourceField as $kol => $alias) {
                        if (isset($addMainSourceEdit[$kol])) {
                            $inputType = $addMainSourceEdit[$kol];
                            $val = isset($mainValues[$kol]) ? $mainValues[$kol] : "";
                            $addEvent = " onblur=\"document.getElementById('result').src='$updateMainSourceTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$kol&val='+this.value\" ";
                            $strVal = "<input type=$inputType name='$kol' class='form-control text-left' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
                            $strVal = formatField($kol, $mainValues[$kol]);
                        }
                        echo "<td $tdOpt>";
                        echo $strVal;
                        echo "</td>";

                    }
                    echo "</tr>";


                }
                //endregion

                //	            if(isset($main['tagihan'])){
                //		            echo "<tr line=".__LINE__.">";
                //		            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
                //		            echo "<td class='text-right'>";
                //
                //		            echo formatField("tagihan", $main['tagihan']);
                //		            echo "</td>";
                //		            echo "</tr>";
                //	            }
            }

            echo "</tbody>";
            echo "</table>";

            if (isset($items) && sizeof($items) > 0) {
                if (isset($dpData) && sizeof($dpData) > 0) {

                    echo "<table line='" . __LINE__ . "' class='table table-bordered table-condensed' style='background:#ffffff;'>";
                    foreach ($dpData['field'] as $dp_key => $dp_label) {
                        echo "<tr bbgcolor='#f0f0f0'>";
                        echo "<td align='left'>$dp_label</td>";
                        echo "<td align='right'> " . formatField($dp_key, $dpData['value'][$dp_key]) . " </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            }

            //cbu-ckd
            if (isset($items) && sizeof($items) > 0) {
                $volume_gross = "";
                $berat_gross = "";
                if (isset($detilSizeBar) && sizeof($detilSizeBar) > 0) {

                    if (isset($mainElements['detilSize'])) {
                        if (in_array('detilSize', $editableElements)) {
                            $editLink = "BootstrapDialog.show(
                                       {
                                           title:'detilSize',
                                            message: $('<div></div>').load('" . $elementEditTarget . "detilSize" . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                            size:BootstrapDialog.SIZE_WIDE,
                                            draggable:false,
                                            closable:true,
                                            }
                                            );
                                           ";

                            echo "<div style='font-size: 14px;' class='text-center col-md-12'>";
                            echo "Anda Sedang Menggunakan Data Ukuran: <span class='text-uppercase text-bold'>$detailSizeKey</span> ";
                            echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                            echo "<span class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i> ganti</span>";
                            echo "</a>";
                            echo "</div>";
                        }
                    }

                    $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                    $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                                </div>
                             </div>";
                    echo "&nbsp;";
                }
            }

            if (isset($items) && sizeof($items) > 0) {

                if (isset($saldoLocker) && (sizeof($saldoLocker) > 0)) {
                    $lockerWarning = array();
                    $str = "<div line='" . __LINE__ . "' class='panel panel-default' style='background:#f0f0f0;'>";
                    $str .= "<table class='table table-bordered table-condensed'>";
                    $str .= "<tr>";
                    $str .= "<th>item</th>";
                    $str .= "<th>saldo</th>";
                    $str .= "</tr>";
                    foreach ($saldoLocker as $md => $mdSpec) {
                        $mdName = formatField("name", $mdSpec['name']);
                        $mdNilai = formatField("nilai", $mdSpec['nilai']);
                        if (isset($mdSpec['warning'])) {
                            $lockerWarning[] = $mdSpec['warning'];
                        }

                        $str .= "<tr>";
                        $str .= "<td>$mdName</td>";
                        $str .= "<td>$mdNilai</td>";
                        $str .= "</tr>";
                    }
                    $str .= "</table class='table table-bordered table-condensed'>";
                    $str .= "</div class='panel-default'>";

                    if (sizeof($lockerWarning) > 0) {
                        $str .= "<div class='alert alert-danger' style='font-size:15px;'>";
                        foreach ($lockerWarning as $labelWarning) {
                            $str .= $labelWarning;
                        }
                        $str .= "</div class='alert alert-danger'>";
                    }


                    echo $str;
                }

                if (sizeof($mainElements) > 0) {
                    echo "<h4 line='" . __LINE__ . "'>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    foreach ($mainElements as $elName => $aSpec) {
                        //                        cekBiru("$elName");
                        if (array_key_exists($elName, $elementConfig)) {
                            //                            cekKuning("$elName");
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " &nbsp;&nbsp;&nbsp;</span>";
                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";
                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";
                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));

                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            if ($val != "") {
                                                echo "<tr line=" . __LINE__ . ">";
                                                $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                                if (strlen($strLabel) > 0) {
                                                    echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                                }
                                                echo "<td align='left' class='text-black'>$val</td>";
                                                echo "</tr>";
                                            }


                                        }
                                        echo "</table>";
                                    }
                                    else {
                                        //                                        echo "<table class='tables table-condensed'>";
                                        //                                        echo "<tr line=".__LINE__.">";
                                        //                                        $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                        //                                        echo "<td align='left' class='text-black'>$strLabel harus dipilih</td>";
                                        //                                        echo "</tr>";
                                        //                                        echo "</table>";

                                        $msg = "<span class='glyphicon glyphicon-arrow-left'></span> &nbsp;&nbsp;silahkan " . $aSpec['label'] . " dipilih ulang dengan klik icon pensil sebelah kiri.";
                                        echo "<table class='tables table-condensed'>";
                                        echo "<tr line=" . __LINE__ . ">";
                                        echo "<td align='left' class='text-red' style='font-size: 15px;'>$msg</td>";
                                        echo "</tr>";
                                        echo "</table>";
                                    }
                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                    }
                    echo "</table>";
                    echo "</div class='panel-default'>";
                }

                // if (strlen($description) > 0) { // mendeteksi jumlah karakter catatan, kalau lebih dari 0 maka ditampilkan. berlaku semua transaksi.
                if (isset($description)) { // mendeteksi gerbang catatan (main), bila ada maka ditampilkan. berlaku semua transaksi.
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";

                    // bila bisa mengedit catatan dan mau edit maka editlah.
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    // bila tidak bisa mengedit catatan, maka lihat saja
                    else {
                        if (strlen($description) > 0) {

                            echo nl2br($description);
                        }
                        else {
                            echo "-";
                        }
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }

                if (isset($descriptionAdditionalRule) && ($descriptionAdditionalRule['enabled'] == true)) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note (from current step) </span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($descriptionAdditionalRule['editabled']) && ($descriptionAdditionalRule['editabled'] == true)) {
                        $key_note = "description_additional";
                        $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($descriptionAdditional);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($descriptionAdditional);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                else {
                    //                    arrPrint($descriptionAdditionalPreviews);
                    //                    cekHere(sizeof($descriptionAdditionalPreviews));
                    if (sizeof($descriptionAdditionalPreviews) > 0) {
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                        echo "<span class='text-muted'>description note (dari step sebelumnya) </span><br>";
                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";

                        $val_result = "";
                        foreach ($descriptionAdditionalPreviews as $ii => $iiVal) {
                            if ($val_result == "") {
                                $val_result = $iiVal;
                            }
                            else {
                                $val_result .= "<br>" . $iiVal;
                            }
                        }
                        echo nl2br($val_result);


                        echo "</span><br>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                }

                if (sizeof($descriptionMainFollowupRule) > 0) {

                    if (isset($descriptionMainFollowupRule['enabled']) && ($descriptionMainFollowupRule['enabled'] == true)) {
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                        echo "<span class='text-muted'>" . $descriptionMainFollowupRule['label'] . "</span><br>";
                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                        if (isset($descriptionMainFollowupRule['editabled']) && ($descriptionMainFollowupRule['editabled'] == true)) {
                            $key_note = "description_main_followup";
                            $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                            echo "<textarea class='form-control text-left' $addEvent_description>";
                            echo nl2br($descriptionMainFollowup);
                            echo "</textarea>";
                        }
                        else {
                            echo nl2br($descriptionMainFollowup);
                        }

                        echo "</span><br>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                }

                //                else {
                //                    if (sizeof($descriptionAdditionalPreviews) > 0) {
                //                        echo "<table class='table table-bordered table-condensed'>";
                //                        echo "<tr line=".__LINE__.">";
                //                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                //                        echo "<span class='text-muted'>description note (dari step sebelumnya) </span><br>";
                //                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                //
                //                        $val_result = "";
                //                        foreach ($descriptionAdditionalPreviews as $ii => $iiVal) {
                //                            if ($val_result == "") {
                //                                $val_result = $iiVal;
                //                            }
                //                            else {
                //                                $val_result .= "<br>" . $iiVal;
                //                            }
                //                        }
                //                        echo nl2br($val_result);
                //
                //
                //                        echo "</span><br>";
                //                        echo "</td>";
                //                        echo "</tr>";
                //                        echo "</table>";
                //                    }
                //                }


                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }

            //=================================================================================
            echo "<h3 id='showTasklist'>Status Keseluruhan Project *<r>(".$mainValues['projectName'].")</r> <span id='reloadTasklistModal' onclick=\"top.open_holdon();top.$('#result').load('$btnReloadTaskList#showTasklist');\" class='pull-right btn btn-xs btn-danger'><i class='fa fa-refresh'></i> REFRESH</span></h3>";
            echo "<h4>NILAI PROJECT: " . number_format($mainValues['grand_total_ui']) . " (Excl.PPN)</h4>";
            echo "<h4>PPN: " . number_format($mainValues['grand_total_ui']*0.11). "</h4>";
            echo "<h4>NILAI PROJECT: " . number_format($mainValues['grand_total_ui']*1.11). " (Incl.PPN)</h4>";

            /*
             * UANG MUKA
             */
            echo "<h4 class='text-bold text-blue'>DP / UANG MUKA $uangmukaDisplay</h4>";
            echo "<table class='table dataTable compact table-bordered table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th key='no'>No.</th>";
            foreach($uangmukaprojectHeader as $ky => $rhead){
                echo "<th key='$ky'>$rhead</th>";
            }
            echo "</tr>";
            echo "</thead>";

            $colSpan = count($uangmukaprojectHeader)+1;
            $nilai_dp = $uangmukaData[0]["harga"]*1;
            $nilai_project = ($mainValues['grand_total_ui']*1.11);

            //arrPrint($uangmukaData);
            if($uangmukaCheckSetting>0){
                echo "<tbody>";
                if($uangmukaproject){
                    $totalSubProgress = 0;
                    $totalSubBobot = 0;
                    $tsNo = 0;
                    $total_um = array();
                    $total_ostd = array();
                    $um_terbayar = 0;
                    $arr_um_terbayar = [];
                    foreach($uangmukaproject as $num => $row){

                        $tsk_id = $row->id;
                        $produk_id = $row->produk_id;
                        $gudang_id = $row->gudang_id;
                        $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                        $totalSubBobot += $row->persen_sub*1;
                        $tsNo++;

                        if(!isset($total_ostd['sisa'])){
                            $total_ostd['sisa'] = 0;
                        }
                        $total_ostd['sisa'] += ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                        $um_terbayar += ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                        $arr_um_terbayar[] = ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                        if(!isset($total_ostd['tagihan'])){
                            $total_ostd['tagihan'] = 0;
                        }
                        $total_ostd['tagihan'] += $row->tagihan;

                        echo "<tr class='gdi_$gudang_id'>";
                        echo "<td>$tsNo</td>";
                        foreach($uangmukaprojectHeader as $kk => $label){
                            $val_ = $row->$kk;
                            switch($kk){
                                case "terbayar_persen":
                                    $dpp_ppn_nppn = $row->dpp_ppn + $row->ppn_sisa;
                                    $persen_terbayar = $dpp_ppn_nppn>0?(($dpp_ppn_nppn)/$nilai_project)*100:0;
                                    $val_ = number_format($persen_terbayar,0) . "";
                                    break;
                                case "sisa_persen":
                                    $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                    $val_ = number_format($sisa_persen,0);
                                    break;
                                case "cek":
                                    $nilai_tagihan = $row->tagihan;
                                    $val_ = "<span nilai_dp='$nilai_dp' nilai_tagihan_um='$nilai_tagihan' class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> UM sudah diterima</span>";
                                    $val_ .= "<span class='hidden'><input checked name='uangmuka' class='financeCheck' type='checkbox'></span>";
                                    break;
                                case "dpp_ppn":
                                    $val_ = number_format($nilai_dp,0);
                                    break;
                                case "terbayar":
                                case "tagihan":
                                    $terima_pd = $row->tagihan + $row->ppn;
                                    $val_ = number_format($terima_pd,0);
                                    break;
                                case "sisa":
                                    $sisa_nppn = $nilai_project - ($row->$kk + $row->ppn_sisa);
//                                        $val_ = number_format($sisa_nppn,0); //dimatikan dulu
                                    $val_ = 0;
                                    break;
                            }
                            echo "<td line='".__LINE__."' in_$kk >$val_</td>";
                        }
                        echo "</tr>";
                    }
                }
                else{
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold text-red' style='font-size:18px;' colspan='$colSpan'><i class='fa fa-warning blink'></i> BELUM MENERIMA UANG MUKA <i class='fa fa-warning blink'></i><div>".number_format($nilai_dp)." (Incl.PPN)</div><div class='hidden'>silahkan buat uang muka project <a href='javascript:void(0)' onclick=\"top.window.open('https://google.com', '_blank', rel='noopener noreferrer');\">disini</a></div></td>";
                    echo "<span class='hidden'><input name='uangmuka' class='financeCheck' type='checkbox'></span>";
                    echo "<span class='hidden'><input name='' class='financeCheck' type='checkbox'>".json_encode($uangmukaCheckSetting)."</span>";
                    echo "</tr>";
                }
                echo "</tbody>";
            }
            else{
                echo "<tbody>";
                echo "<tr class=''>";
                echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN UANG MUKA <i class='glyphicon glyphicon-check text-success'></i></td>";
                echo "<span class='hidden'><input checked name='uangmuka' class='financeCheck' type='checkbox'></span>";
                echo "</tr>";
                echo "</tbody>";
            }

            echo "<tfoot>";
            echo "<th>-</th>"; //nomer
            $valTh = "";

            foreach($uangmukaprojectHeader as $kk => $label){
                switch($kk){
                    default:
                        $valTh .= "<th line='".__LINE__."' in_$kk>-</th>";
                        break;
                    case "tagihan":
                        $gTotalUmTxt= "";
                        $gTotalUm = number_format($total_ostd['sisa']*1);
                        if( ($total_ostd['sisa']*1) < ($nilai_dp*1) ){
                            $gTotalUmTxt .= "
                                    <span gTotalUm='$gTotalUm' nilai_dp='$nilai_dp' class='hidden'>
                                        <input name='uangmuka' class='financeCheck' type='checkbox'>
                                    </span>";
                        }
                        $valTh .= "<th line='".__LINE__."' in_$kk>$gTotalUmTxt</th>";
//                            $um_terbayar = $total_ostd['sisa']*1;
                        break;
                    case "sisa":
//                            $gTotalUm = number_format($nilai_dp-$total_ostd[$kk]*1); //di nolkan dulu
                        $gTotalUm = 0;
                        $valTh .= "<th line='".__LINE__."' in_$kk>$gTotalUm</th>";
                        break;
                    case "persen_sub":
                        $totalSubBobot_f = number_format($totalSubBobot, 2);
                        $valTh .= "<th line='".__LINE__."' in_$kk>$totalSubBobot_f%</th>";
                        break;
                    case "progress_percent":
                        $totalSubProgress_f = number_format($totalSubProgress, 2);
                        $valTh .= "<th line='".__LINE__."' in_$kk>$totalSubProgress_f%</th>";
                        break;
                }
            }
            echo $valTh;
            echo "</tfoot>";
            echo "</table>";

            $termin_terbayar = 0;

            /*
             * TERMIN
             */
            echo "<h4 class='text-bold text-blue'>TERMIN $terminDisplay</h4>";
            echo "<div class=''>saldo termin = nilai yang belum ditagihkan ke-konsumen.</div>";
            echo "<table class='table dataTable compact table-bordered table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th key='no'>No.</th>";
            foreach($terminprojectHeader as $ky => $rhead){
                echo "<th key='$ky'>$rhead</th>";
            }
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $totalSubProgress = 0;
            $totalSubBobot = 0;
            $tsNo = 0;
            foreach($terminproject as $num => $row){
                $tsk_id = $row->id;
                $produk_id = $row->produk_id;
                $gudang_id = $row->gudang_id;
                $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
//                    $totalSubProgress += $row->
                $totalSubBobot += $row->persen_sub*1;
                $tsNo++;
                echo "<tr payment_source_id='$tsk_id' class='gdi_$gudang_id'>";
                echo "<td>$tsNo</td>";
                foreach($terminprojectHeader as $kk => $label){
//                        $val_ = $row->$kk;
                    switch($kk){
                        case "terbayar_persen":
                            $persen_terbayar = $row->terbayar > 0 ? (round($row->terbayar)/$row->tagihan)*100 : 0;
                            $val_ = number_format(round($persen_terbayar),0);
                            break;
                        case "sisa_persen":
                            $sisa_persen = floor($row->sisa) > 0 ? (floor($row->sisa)/$row->tagihan)*100 : 0;
                            $val_ = number_format(floor($sisa_persen),0);
                            break;
                        case "cek":
                            if( floor($row->sisa) < 1000){
                                $val_ = "<span class='btn btn-xs btn-success'>tagihan sudah diterbitkan <i class='glyphicon glyphicon-check'></i></span>";
                                $val_ .= "<span class='hidden'><input checked name='termin' class='financeCheck' type='checkbox'></span>";
                                $totalSubProgress += $row->persen_sub*1;
                            }
                            else if( floor($row->sisa) > 1000 && floor($row->sisa) < floor($row->tagihan) ){
                                $val_ = "<span class='btn btn-xs bg-orange belum_termin' disabled><i class='fa fa-warning'></i> Dibayar sebagian <i class='fa fa-warning'></i> </span>";
                                $val_ .= "<span class='hidden'><input name='termin' class='financeCheck' type='checkbox'></span>";
                            }
                            else{
                                $val_ = "<span class='btn btn-xs btn-danger belum_termin'><i class='fa fa-warning blink'></i>  belum ada tagihan yang dibuat <i class='fa fa-warning blink'></i> </span>";
                                $val_ .= "<span class='hidden'><input name='termin' class='financeCheck' type='checkbox'></span>";
                            }
                            break;
                        case "tagihan":
                            $val_ = number_format(floor($row->$kk*1.11),0);
                            break;
                        case "terbayar":
                            $val_ = number_format(floor($row->$kk*1.11),0);
                            $termin_terbayar += $row->$kk;
                            break;
                        case "sisa":
                            $val_ = number_format(floor($row->$kk*1.11),0);
                            break;
                        default:
                            if($kk=="dtime"){
                                $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                            }
                            else{
                                $val_ = $row->$kk;
                            }
                            break;
                    }
                    echo "<td>$val_</td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";

            echo "<tfoot>";
            echo "<th>-</th>"; //nomer
            $valTh = "";
            foreach($terminprojectHeader as $kk => $label){
                switch($kk){
                    default:
                        $valTh .= "<th>-</th>";
                        break;
                    case "persen_sub":
                        $totalSubBobot_f = number_format($totalSubBobot, 2);
                        $valTh .= "<th>$totalSubBobot_f%</th>";
                        break;
                    case "progress_percent":
                        $totalSubProgress_f = number_format($totalSubProgress, 2);
                        $valTh .= "<th>$totalSubProgress_f%</th>";
                        break;
                }
            }
            echo $valTh;
            echo "</tfoot>";
            echo "</table>";

            /*
             * TASKLIST
             */
            echo "<h4 class='text-bold text-blue'>WORK-ORDER / TASKLIST</h4>";
            echo "<table class='table dataTable compact table-bordered table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th key='no'>No.</th>";
            foreach($tasklistHeader as $ky => $rhead){
                echo "<th key='$ky'>$rhead</th>";
            }
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $totalSubProgress = 0;
            $totalSubBobot = 0;
            $tsNo = 0;
            foreach($tasklist as $num => $row){
                $tsk_id = $row->id;
                $produk_id = $row->produk_id;
                $gudang_id = $row->gudang_id;
                $post_biaya_id = $row->post_biaya_id;
                $post_return_id = $row->post_return_id;
                $checkBiaya = isset($row->biaya) ? count($row->biaya) : 0;
                $checkLogReturn = isset($row->log_return) ? $row->log_return : 0;
                $ada_log_return_supplies = isset($checkLogReturn[0]['supplies']) ? 1 : 0;
                $ada_log_return_produk = isset($checkLogReturn[0]['produk']) ? 1 : 0;
                $getTransaksiHis = isset($row->his_trx) ? $row->his_trx : array();
                $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();

                $totalSubBobot += $row->persen_sub*1;
                $total_pembayaran = array();
                $tsNo++;
                echo "<tr class='gdi_$gudang_id'>";
                echo "<td>$tsNo</td>";
                foreach($tasklistHeader as $kk => $label){
                    $val_ = $row->$kk;
                    $bb_box = "";
                    if( count($arrBB) > 0 ){
                        $noBB = 0;
                        $bb_box .= "<span data-id='$gudang_id' style='margin-left: 3px;' class='btn-tooltip btn btn-xs bg-violet unused_stok'>ada stok produk</span>";
                        $bb_box .= "<span style='margin-left: 3px;' data-id='create-$tsk_id-$produk_id' onclick='fnTasklist.create(this)' id='' class='btn btn-xs btn-info'><i class='fa fa-send'></i> View Progress</span>";
                    }
                    else{
                        $bb_box .= "<span style='margin-left: 3px;' class='btn btn-xs bg-olive' disabled>belum distribusi</span>";
                    }
                    switch($kk){
                        case "progress_nama":
                            if($row->progress_id == 3){
                                $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> sudah QC</span>";
                                $val_ .= "<span class='hidden'><input checked name='tasklist' class='validationCheck' type='checkbox'></span>";
                                $totalSubProgress += $row->persen_sub*1;
                            }
                            else if($row->progress_id == 2 && $row->progress_percent == 100){
                                $val_ = "<span class='btn btn-xs bg-orange belum_qc' disabled><i class='fa fa-warning blink'></i> belum QC</span>";
                                $val_ .= "<span style='margin-left: 3px;' data-id='create-$tsk_id-$produk_id' onclickx='fnTasklist.create(this)' id='' class='btn btn-xs btn-info'><i class='fa fa-send'></i> Silahkan Lakukan QC</span>";
                                $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                            }
                            else{
                                if($row->progress_id == 2 && $row->progress_percent > 0 && $row->progress_percent < 100){
                                    $val_ = "<span class='btn btn-xs btn-danger'>dikerjakan parsial</span>";
                                    $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                                }
                                else{
                                    $val_ = "<span class='btn btn-xs btn-danger'>belum dikerjakan</span>";
                                    $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                                }
                                if($bb_box!=""){
                                    $val_ .= $bb_box;
                                }
                            }
                            break;
                        case "progress_percent":
                            $val_ = $val_ . "%";
                            break;
                        case "persen_sub":
                            $val_ = number_format($val_, 2) . "%";
                            break;
                        case "nilai_sub_fase":
                            $val_ = number_format($row->$kk,0);
                            if(!isset($total_pembayaran[$kk])){
                                $total_pembayaran[$kk] = 0;
                            }
                            $total_pembayaran[$kk] += $row->$kk > 1000 ? $row->$kk*1 : 0;
                            break;
                        default:
                            if($kk=="dtime"){
                                $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                            }
                            else{
                                $val_ = $row->$kk;
                            }
                            break;
                    }
                    echo "<td>$val_</td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";

            echo "<tfoot>";
            echo "<th>-</th>"; //nomer
            $valTh = "";
            foreach($tasklistHeader as $kk => $label){
                switch($kk){
                    default:
                        $valTh .= "<th>-</th>";
                        break;
                    case "persen_sub":
                        $totalSubBobot_f = number_format($totalSubBobot, 2);
                        $taskStatusALl = "";
                        if( $totalSubBobot < 99 ){
                            $taskStatusALl .= "<span class='hidden'><input class='project_persen'></span>";
                            $taskStatusALl .= "<span class='hidden'><input name='project_persen' class='financeCheck' type='checkbox'></span>";
                        }
                        $valTh .= "<th>";
                        $valTh .= "$totalSubBobot_f%";
                        $valTh .= $taskStatusALl;
                        $valTh .= "</th>";
                        break;
                    case "nilai_sub_fase":
                        $totalPembayaran = number_format($total_pembayaran[$kk]);
                        $valTh .= "<th>$totalPembayaran</th>";
                        break;
                }
            }
            echo $valTh;
            echo "</tfoot>";
            echo "</table>";

            /*
             * PENERIMAAN PEMBAYARAN DARI TERMIN
             */
            $ar_terbayar = 0;
            echo "<h4 class='text-bold text-blue'>PENERIMAAN PEMBAYARAN</h4>";
//                echo "<div class='text-bold text-red'><i>konsumen belum menyelesaikan pembayaran</i></div>";
            echo "<table class='table dataTable compact table-bordered table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th key='no'>No.</th>";
            foreach($terimabayarprojectHeader as $ky => $rhead){
                echo "<th key='$ky'>$rhead</th>";
            }
            echo "</tr>";
            echo "</thead>";
            $colSpan = count($terimabayarprojectHeader)+1;
            if(!empty($terimabayarproject)){
                echo "<tbody>";
                $totalSubProgress = 0;
                $totalSubBobot = 0;
                $tsNo = 0;
                $total = array();
                foreach($terimabayarproject as $num => $row){
                    $tsk_id = $row->id;
                    $produk_id = $row->produk_id;
                    $gudang_id = $row->gudang_id;
                    $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                    $totalSubBobot += $row->persen_sub*1;
                    $tsNo++;
                    echo "<tr payment_source_id='$tsk_id' class='gdi_$gudang_id'>";
                    echo "<td>$tsNo</td>";
                    foreach($terimabayarprojectHeader as $kk => $label){
                        $val_ = $row->$kk;
                        switch($kk){
                            case "terbayar_persen":
                                $persen_terbayar = $row->terbayar > 0 ? ($row->terbayar/$row->tagihan)*100 : 0;
                                $val_ = number_format($persen_terbayar,0);
                                break;
                            case "sisa_persen":
                                $sisa_persen = $row->sisa > 100 ? ($row->sisa/$row->tagihan)*100 : 0;
                                $val_ = number_format($sisa_persen,0);
                                break;
                            case "cek":
                                if($row->sisa < 100 && $row->returned > 100 ){
                                    $val_ = "<span class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-trash'></i> penerimaan dibatalkan*</span>";
                                    $val_ .= "<span class='hidden'><input checked name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                }
                                else if($row->sisa < 100 ){
                                    $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> sudah lunas</span>";
                                    $val_ .= "<span class='hidden'><input checked name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                    $totalSubProgress += $row->persen_sub*1;
                                }
                                else if($row->sisa < 100 && $row->sisa < $row->tagihan ){
                                    $val_ = "<span class='btn btn-xs bg-orange penerimaan_belum_lunas' disabled><i class='fa fa-warning blink'></i> Dibayar sebagian</span>";
                                    $val_ .= "<span class='hidden'><input name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                }
                                else{
                                    $val_ = "<span class='btn btn-xs btn-danger penerimaan_belum_lunas'>belum ada pembayaran</span>";
                                    $val_ .= "<span class='hidden'><input name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                }
                                break;
                            case "tagihan":
                                $val_ = number_format($row->$kk,0);
                                if(!isset($total[$kk])){
                                    $total[$kk] = 0;
                                }
                                $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                break;
                            case "returned":
                                $val_ = number_format($row->$kk,0);
                                if(!isset($total[$kk])){
                                    $total[$kk] = 0;
                                }
                                $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                break;
                            case "terbayar":
                                $val_ = number_format($row->$kk,0);
                                if(!isset($total[$kk])){
                                    $total[$kk] = 0;
                                }
                                $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                $ar_terbayar += $row->$kk;
                                break;
                            case "sisa":
                                $val_ = number_format($row->$kk,0);
                                if(!isset($total[$kk])){
                                    $total[$kk] = 0;
                                }
                                $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                break;
                            default:
                                if($kk=="dtime"){
                                    $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                }
                                else{
                                    $val_ = $row->$kk;
                                }
                                break;
                        }
                        echo "<td colom='$kk'>$val_</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";

            }
            else{
                echo "<tbody>";
                echo "<tr class=''>";
                echo "<td class='text-center text-bold text-red blink' style='font-size:18px;' colspan='$colSpan'><i class='fa fa-warning text-danger'></i> BELUM ADA PEMBAYARAN MASUK UNTUK PROJECT INI <i class='fa fa-warning text-danger'></i></td>";
                echo "<span class='hidden'><input name='uangmuka' class='financeCheck' type='checkbox'></span>";
                echo "</tr>";
                echo "</tbody>";
            }

            echo "<tfoot>";
            echo "<th>-</th>"; //nomer
            $valTh = "";
            foreach($terimabayarprojectHeader as $kk => $label){
                switch($kk){
                    default:
                        $valTh .= "<th>-</th>";
                        break;
                    case "persen_sub":
                        $totalSubBobot_f = number_format($totalSubBobot, 2);
                        $valTh .= "<th>$totalSubBobot_f%</th>";
                        break;
                    case "tagihan":
                        $total_tagihan = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                        $valTh .= "<th jenis='$kk' >$total_tagihan</th>";
                        break;
                    case "terbayar":
                        $total_terbayar = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                        $valTh .= "<th jenis='$kk' >$total_terbayar</th>";
                        break;
                    case "returned":
                        $total_retur = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                        $valTh .= "<th jenis='$kk' >$total_retur</th>";
                        break;
                    case "sisa":
                        $total_tagihan = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                        $valTh .= "<th>$total_tagihan</th>";
                        break;
                    case "progress_percent":
                        $totalSubProgress_f = number_format($totalSubProgress, 2);
                        $valTh .= "<th>$totalSubProgress_f%</th>";
                        break;
                }
            }
            echo $valTh;
            echo "</tfoot>";
            echo "</table>";

//                echo "DP TERBAYAR: " . json_encode($arr_um_terbayar) . "<br>";
//                echo "TERMIN TERBAYAR: " . number_format($termin_terbayar) . "<br>";
//                echo "DP TERBAYAR: " . number_format($um_terbayar) . "<br>";
//                echo "A/R TERBAYAR: " . number_format($ar_terbayar) . "<br>";
//                echo "TOTAL: " . number_format($ar_terbayar+$um_terbayar) . " (Incl.PPN)<br>";
//                echo "PROJECT: " . number_format($mainValues['grand_total_ui']) . " (Belum PPN) || " . number_format($mainValues['grand_total_ui']*1.11) . " (Incl.PPN)<br>";
            $check_kekurangan = ($mainValues['grand_total_ui']*1.11) - ($ar_terbayar+$um_terbayar);
//                echo "KEKURANGAN: " . number_format($check_kekurangan) . " (Belum PPN) || " . number_format($check_kekurangan*1.11) . " (Incl.PPN)<br>";

            $grand_total_ui = (string)$mainValues['grand_total_ui'];
            $um_terbayar = (string)$um_terbayar;

            $check_kekurangan = bcsub(bcmul($grand_total_ui, '1.11', 0), $um_terbayar, 0);
            $check_kekurangan = bcsub(bcmul($check_kekurangan, '1', 0), $ar_terbayar, 0);

//                echo "check: " . $check_kekurangan . "<br>";
//                echo "grand_total_ui: " . ($mainValues['grand_total_ui']*1.11) . "<br>";
//                echo "ar_terbayar: " . $ar_terbayar . "<br>";
//                echo "um_terbayar: " . $um_terbayar . "<br>";
//                echo "check: (".($mainValues['grand_total_ui']*1.11) . ") - ( ". ($ar_terbayar+$um_terbayar) . ") = " . number_format($check_kekurangan) . "<br>";
//                echo "type_check: " . var_dump($check_kekurangan) . "<br>";

            //===========================================================
            //===========================================================
            /*
             * RETENSI/GARANSI  (TIDAK MENGIKAT TRANSAKSI)
             */
            echo "<div class='alert' style='background: #ffd01a;'>";
            echo "<h4 class='text-bold text-blue'>RETENSI/GARANSI $retensiDisplay</h4>";
            echo "<table class='table dataTable compact table-bordered table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th key='no'>No.</th>";
            foreach($retensiprojectHeader as $ky => $rhead){
                echo "<th key='$ky'>$rhead</th>";
            }
            echo "</tr>";
            echo "</thead>";
            $colSpan = count($retensiprojectHeader)+1;
            if($retensiCheckSetting>0){
                echo "<tbody>";
                if($retensiproject){
                    $totalSubProgress = 0;
                    $totalSubBobot = 0;
                    $tsNo = 0;
                    foreach($retensiproject as $num => $row){
                        $tsk_id = $row->id;
                        $produk_id = $row->produk_id;
                        $gudang_id = $row->gudang_id;
                        $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                        $totalSubBobot += $row->persen_sub*1;
                        $tsNo++;
                        echo "<tr class='gdi_$gudang_id'>";
                        echo "<td>$tsNo</td>";
                        foreach($retensiprojectHeader as $kk => $label){
                            $val_ = $row->$kk;
                            switch($kk){
                                case "terbayar_persen":
                                    $persen_terbayar = $row->terbayar > 0 ? ($row->terbayar/$row->tagihan)*100 : 0;
                                    $val_ = number_format($persen_terbayar,0);
                                    break;
                                case "sisa_persen":
                                    $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                    $val_ = number_format($sisa_persen,0);
                                    break;
                                case "cek":
                                    $nilai_retensi = $retensiData[0]["harga"];
                                    $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d");
                                    $tanggal_hari_ini = date("Y-m-d");

                                    if($row->sisa == 0){
                                        if ($akhir_retensi > $tanggal_hari_ini) {
                                            // Sudah bayar, tapi belum habis masa garansi
                                            $val_ = "<span class='btn btn-xs btn-primary '><i class='fa fa-check'></i> Sudah dibayar - Garansi belum habis</span>";
                                        }
                                        else {
                                            // Sudah bayar dan garansi sudah habis
                                            $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> Sudah lunas & garansi selesai</span>";
                                        }
                                    }
                                    else if($row->sisa > 0 && $row->sisa < $row->tagihan ){
                                        // Dibayar sebagian
                                        $val_ = "<span class='btn btn-xs bg-orange' disabled><i class='fa fa-warning blink'></i> Dibayar sebagian</span>";
                                    }
                                    else {
                                        // Belum ada pembayaran
                                        $val_ = "<span class='btn btn-xs btn-danger'>Belum ada pembayaran</span>";
                                    }
                                    break;
                                case "tgl_berakhir_retensi":
                                    $dateToDebug = date("Y-m-d", strtotime("+2 month 5 day"));
                                    $nilai_retensi = $retensiData[0]["harga"];
                                    $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("+2 day"));
                                    $tanggal_hari_ini = date("Y-m-d");
                                    $umur_akhir_retensi = createTimeDescSoon($akhir_retensi);

                                    if ($akhir_retensi > $tanggal_hari_ini) {
                                        // masa retensi belum habis
                                        $val_ = "<span class='btn btn-xs btn-warning'><i class='fa fa-clock-o'></i> $akhir_retensi </span><br>masa garansi belum habis<br>$umur_akhir_retensi";
                                    }
                                    else{
                                        // masa retensi sudah habis
                                        $val_ = "<span akhir_retensi='$akhir_retensi' class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> $akhir_retensi </span><br>$umur_akhir_retensi";
                                    }
                                    break;
                                case "tagihan":
                                    $val_ = number_format($row->$kk,0);
                                    break;
                                case "terbayar":
                                    $val_ = number_format($row->$kk,0);
                                    break;
                                case "sisa":
                                    $val_ = number_format($row->$kk,0);
                                    break;
                                default:
                                    if($kk=="dtime"){
                                        $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                    }
                                    else{
                                        $val_ = $row->$kk;
                                    }
                                    break;
                            }
                            echo "<td>$val_</td>";
                        }
                        echo "</tr>";
                    }
                }
                else{
                    $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("2 week"));
                    $umur_akhir_retensi = createTimeDescSoon($akhir_retensi);
                    $nilai_retensi = number_format($retensiData[0]["harga"]*1);
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'> <i class='fa fa-warning text-red blink'></i> <r>BELUM ADA PEMBAYARAN RETENSI</r> <i class='fa fa-warning text-red blink'></i> <br>TGL AKHIR RETENSI: <br>$akhir_retensi <br><i>($umur_akhir_retensi)</i><br><r>$nilai_retensi (Incl.PPN)</r></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
            }
            else{
                echo "<tbody>";
                echo "<tr class=''>";
                echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN RETENSI / GARANSI <i class='glyphicon glyphicon-check text-success'></i></td>";
                echo "</tr>";
                echo "</tbody>";
            }

            echo "<tfoot>";
            echo "<th>-</th>"; //nomer
            $valTh = "";
            foreach($retensiprojectHeader as $kk => $label){
                switch($kk){
                    default:
                        $valTh .= "<th>-</th>";
                        break;
                    case "persen_sub":
                        $totalSubBobot_f = number_format($totalSubBobot, 2);
                        $valTh .= "<th>$totalSubBobot_f%</th>";
                        break;
                    case "progress_percent":
                        $totalSubProgress_f = number_format($totalSubProgress, 2);
                        $valTh .= "<th>$totalSubProgress_f%</th>";
                        break;
                }
            }
            echo $valTh;
            echo "</tfoot>";
            echo "</table>";
            echo "</div>";
            //===========================================================


            echo "</div class='table-responsive'>";

//            if (isset($additionalPackinglist) && (sizeof($additionalPackinglist) > 0)) {
//                if (isset($additionalPackinglist['enabled']) && ($additionalPackinglist['enabled'] == true)) {
//                    if (isset($extractedSumSubItems) && (sizeof($extractedSumSubItems) > 0)) {
//                        echo "<div class='table-responsive'>";
////                        echo "<div class='box box-solid box-danger no-margin'>";
//                        echo "<h4 style='margin-bottom:-20px;'>Daftar item (finish goods) yang belum packinglist</h4>";
////cekHere("cek subitem");
//                        echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
//                        echo "<thead>";
//                        echo "<tr bgcolor='#f0f0f0'>";
//                        echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
//                        foreach ($additionalPackinglist['header'] as $key => $val) {
//                            echo "<th class='text-muted' style='font-weight:bold;'>";
//                            echo $val;
//                            echo "</th>";
//                        }
//                        echo "</tr bgcolor='#f0f0f0'>";
//                        echo "</thead>";
//
//                        echo "<tbody>";
//                        $nom = 0;
//                        $sumSubItems = array();
//                        $total_items = sizeof($extractedSumSubItems);
//                        foreach ($extractedSumSubItems as $pID => $spec) {
//                            $nom++;
//                            echo "<tr line=" . __LINE__ . ">";
//                            echo "<td align='right' style='$addStyle'>";
//                            echo $nom;
//                            echo ".</td>";
//                            foreach ($additionalPackinglist['header'] as $key => $val) {
//                                echo "<td>";
//                                echo isset($spec[$key]) ? formatField_he_format($key, $spec[$key], "", "") : "-";
//                                echo "</td>";
//                                if (isset($spec[$key]) && is_numeric($spec[$key])) {
//                                    if (!isset($sumSubItems[$key])) {
//                                        $sumSubItems[$key] = 0;
//                                    }
//                                    $sumSubItems[$key] += $spec[$key];
//                                }
//                            }
//                            echo "</tr>";
//                        }
//                        echo "<tr>";
//                        echo "<td align='right' colspan='3'>Total</td>";
//                        echo "<td align='right'>" . formatField("qty", $sumSubItems['qty'], "", "") . "</td>";
//                        echo "<td align='left'>-</td>";
//                        echo "</tr>";
//                        echo "</tbody>";
//
//                        echo "<table>";
//                        echo "</table>";
//                        echo "</div class='panel panel-danger'>";
//                        echo "<div class='alert alert-danger'>";
//
//                        $msgNote = "Lanjutkan Close Project dengan menutup $total_items item, " . $sumSubItems['qty'] . " unit";
//                        $checklistNoteEncode = blobEncode($msgNote);
//                        echo "<input type='checkbox' value=''
//                            onclick=\"document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode';\">";
//                        echo "&nbsp; <span>$msgNote.</span>";
//                        echo "</div>";
//                    }
//                    else {
//                        echo "<div class='alert alert-danger'>";
//
//                        $msgNote = "Lanjutkan Close Project.";
//                        $checklistNoteEncode = blobEncode($msgNote);
//                        $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";
//                        echo "<input type='checkbox' value='' $checked
//                            onclick=\"document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode';\">";
//                        echo "&nbsp; <span>$msgNote.</span>";
//                        echo "</div>";
//                    }
//                }
//                else {
//                    echo "<div class='alert alert-danger'>";
//
//                    $msgNote = "Lanjutkan Close Project.";
//                    $checklistNoteEncode = blobEncode($msgNote);
//                    $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";
//                    echo "<input type='checkbox' value='' $checked
//                            onclick=\"document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode';\">";
//                    echo "&nbsp; <span>$msgNote.</span>";
//                    echo "</div>";
//                }
//            }
//            else {
//                echo "<div class='alert alert-danger'>";
//
//                $msgNote = "Lanjutkan Close Project.";
//                $checklistNoteEncode = blobEncode($msgNote);
//                $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";
//                echo "<input type='checkbox' value='' $checked
//                            onclick=\"document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode';\">";
//                echo "&nbsp; <span>$msgNote.</span>";
//                echo "</div>";
//            }

            if (isset($items) && sizeof($items) > 0) {

                $new_beforeStepLabels = isset($beforeStepLabels) ? $beforeStepLabels : "";
                $new_beforeAllStepLabels = isset($beforeAllStepLabels) ? $beforeAllStepLabels : "";

                echo "<div>";

                // echo "<div class='col-md-2'>";
                echo "<button type='button' class='btn btn-default margin' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </button>";
                // echo "</div class='col-md-2'>";

                echo "&nbsp;<div class='btn-group'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-danger margin' style='border:1px #ff7700 solid;ccolor:#ff7700;' 
                    onclick=\"if(confirm('" . $deleteSpec['warning'] . " " . $new_beforeStepLabels . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-danger margin' style='border:1px #ff7700 solid;ccolor:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default margin' style='border:1px #ff7700 solid;color:#ff7700;' 
                    onclick=\"if(confirm('" . $undoSpec['warning'] . " " . $new_beforeStepLabels . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default margin' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default margin' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default margin' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $editSpec['label'] . "</button>";
                }
                echo "</div>";

                // echo "<div class='col-md-2'>&nbsp;";
                // echo "</div class='col-md-2'>";
                echo "<div line='".__LINE__."' class='bbtn-group pull-right #1'>";

                //dimatikan dulu antisipasi mereka batal2kan project belum stabil

                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    // echo "<div class='panel-body'>";
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-danger margin' style='border:1px #dd3300 solid;ccolor:#dd3300;'
                        onclick=\"if(confirm('" . $rejectionSpec['warning'] . " " . $new_beforeStepLabels . "')==1){
                        document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';
                        document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    else {
                        echo "<button type='button' disabled class='btn btn-danger margin' style='border:1px #dd3300 solid;color:#dcdcdc;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    // -------------------------------------------------------------
                    if (isset($rejectionSpecAll['targetUrl']) != "" && $rejectionSpecAll['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-danger margin' style='border:1px #000000 solid;color:#ffffff;background-color:#000000;'
                        onclick=\"if(confirm('" . $rejectionSpecAll['warning'] . "')==1){
                        document.getElementById('f1').action='" . $rejectionSpecAll['targetUrl'] . "';
                        document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp;" . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    else {
                        echo "<button line='".__LINE__."' type='button' disabled class='btn btn-danger margin' style='border:1px #000000 solid;color:#dcdcdc;background-color:#000000;'><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp;" . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    // -------------------------------------------------------------
                    echo "<button type='button' disabled class='btn btn-success margin' style='border:1px #008800 solid;color:#ffffff;'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";
                    // echo "</div>";
                }
                else {
                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
//                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
//                        echo "<button type='button' class='btn btn-danger margin' style='border:1px #dd3300 solid;ccolor:#dd3300;'
//                        onclick=\"if(confirm('" . $rejectionSpec['warning'] . " " . $new_beforeStepLabels . "')==1){
//                        document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';this.disabled=true;
//                        document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                        //                        echo "&nbsp;&nbsp;";
//                    }
//                    else {
//                        echo "<button button type='button' disabled class='btn btn-danger' style='border:1px #dd3300 solid;color:#dcdcdc;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                    }
                    // -------------------------------------------------------------
//                    if (isset($rejectionSpecAll['targetUrl']) != "" && $rejectionSpecAll['targetUrl'] != "") {
//                        echo "<button type='button' class='btn btn-danger' style='border:1px #000000 solid;color:#ffffff;background-color:#000000;'
//                        onclick=\"if(confirm('" . $rejectionSpecAll['warning'] . "')==1){
//                        document.getElementById('f1').action='" . $rejectionSpecAll['targetUrl'] . "';this.disabled=true;
//                        document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp; " . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                    }
//                    else {
//                        echo "<button line='".__LINE__."' type='button' disabled class='btn btn-danger margin' style='border:1px #000000 solid;color:#dcdcdc;background-color:#000000;'><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp; " . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                    }
                    // -------------------------------------------------------------
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "<button button type='button' class='btn btn-success margin' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</button>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }
                //dimatikan sampe sini

                if (isset($xShipmentBtn['targetUrl']) && $xShipmentBtn['targetUrl'] != "") {
                    echo "&nbsp;&nbsp;<button type='button' class='btn btn-danger margin' style='bborder:1px #fff solid;color:#ffffff;' 
                    onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';
                    document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</button>";
                }

                echo "</div>";

                echo "</div>"; // 2669

                //                if(isset($beforeStepWarning) && ($beforeStepWarning != NULL)){
                //                    echo "<br><br>";
                //                    echo "<div class='col-md-12 text-center alert alert-danger' sstyle='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                //                    echo $beforeStepWarning;
                //                    echo "</div>";
                //                }

                if (isset($definitionButton) && sizeof($definitionButton) > 0) {

                    echo "<div class='row' style='margin-top: 100px;margin-bottom:-30px;font-size: larger;'>";
                    echo "<div class='panel-body'>";
                    echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                    if (isset($beforeStepWarning) && ($beforeStepWarning != NULL)) {
                        echo "<strong>$beforeStepWarning</strong>";
                        echo "<hr>";
                        echo "<br>";
                    }
                    foreach ($definitionButton as $lButton => $kButton) {
                        echo "<strong>$lButton</strong> : $kButton";
                        echo "<br>";
                    }

                    echo "</div class='col-md-12 text-center'>";
                    echo "</div class='panel-body'>";
                    echo "</div class='row'>";
                }

                echo "<div class='row' style='margin-top: 20px;'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";
                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";
                echo "</div>";
                echo "</div class='row'>";
            }

            echo "</form>";
            echo "<script>$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl');</script>";

        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();
        }
        echo "</div id='followupPreview'>";
        break;

    case "followupPreview_mod":
        echo "<div id='followupPreview_mod'>";
        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";

            $arrSwals = array(
                "type" => "warning",
                "title" => "<span style='color: red;'>Perhatian..</span>",
                "html" => $newWarningLabel,
                "allowOutsideClick" => false,
                // "imageUrl"            => img_bitzer(),
                "background" => "#34abeb",
                "confirmButtonText" => "Close",
                "confirmButtonColor" => "#ff0055",
            );

            echo swalAlert($arrSwals);
        }
        else {
            $msgWarnings = array();
        }

        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";

            $newWarningLabel = "<span style='color: yellow;'>";
            $newWarningLabel .= $msgSpec['label'];

            if(isset($msgSpec['extra_button'])){
                $newWarningLabel .= $msgSpec['extra_button'];
            }

            $newWarningLabel .= "<div class='font-size-0-7 margin-top-20'>silahkan tutup notifikasi ini untuk melanjutkan transaksi</div>";
            $newWarningLabel .= "</span>";
            $arrSwals = array(
                "type" => "warning",
                "title" => "<span style='color: red;'>Perhatian</span>",
                "html" => $newWarningLabel,
                "allowOutsideClick" => false,
                // "imageUrl"            => img_bitzer(),
                "background" => "#34abeb",
                "confirmButtonText" => "Close",
                "confirmButtonColor" => "#ff0055",
            );

            if(isset($msgSpec['onOpen'])){
                $arrSwals['onOpen'] = $msgSpec['onOpen'];
            }

            echo swalAlert($arrSwals);
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='overflow: hidden;'>";
            // echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            // echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo createStateHorizontalMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<div class='box-header box-solid bg-warning'>";
        echo "<div class='row'>";
        foreach ($mainLabels as $key => $label) {
//            echo "<li class='list-group-item'>";
            echo "<div class='col-md-6'>";
            echo "<div class='row'>";

            echo "<div class='col-md-4 text-muted text-bold text-uppercase'>";
            echo $label;
            echo "</div>";

            echo "<div class='col-md-8 text-capitalize'>";
            if (isset($main->$key)) {
                if (is_array($main->$key)) {
                    $rslt_isi = "";
                    foreach ($main->$key as $isi) {
                        if ($rslt_isi == "") {
                            $rslt_isi = $isi;
                        }
                        else {
                            $rslt_isi = $rslt_isi . ", $isi";
                        }
                    }
                    echo formatField($key, $rslt_isi);
                }
                else {
                    echo formatField($key, $main->$key);
                }
            }
            else {
                if (isset($mainValues[$key])) {
                    echo formatField($key, $mainValues[$key]);
                }
                else {
                    echo "";
                }
            }
            echo "</div>";

            echo "</div>";
            echo "</div>";
//            echo "</li>";
        }
        echo "</div>";
        echo "</div>";

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            //rincian project
            if (isset($rincianProject) && $rincianProject != "") {
                echo $rincianProject;
            }

            echo "<form id='f1' name='f1' method='post' target='result'>";

            if (isset($items) && sizeof($items) > 0) {
                if (isset($saldoLocker) && (sizeof($saldoLocker) > 0)) {
                    $lockerWarning = array();
                    $str = "<div class='panel panel-default' style='background:#f0f0f0;'>";
                    $str .= "<table class='table table-bordered table-condensed'>";
                    $str .= "<tr>";
                    $str .= "<th>item</th>";
                    $str .= "<th>saldo</th>";
                    $str .= "</tr>";
                    foreach ($saldoLocker as $md => $mdSpec) {
                        $mdName = formatField("name", $mdSpec['name']);
                        $mdNilai = formatField("nilai", $mdSpec['nilai']);
                        if (isset($mdSpec['warning'])) {
                            $lockerWarning[] = $mdSpec['warning'];
                        }

                        $str .= "<tr>";
                        $str .= "<td>$mdName</td>";
                        $str .= "<td>$mdNilai</td>";
                        $str .= "</tr>";
                    }
                    $str .= "</table class='table table-bordered table-condensed'>";
                    $str .= "</div class='panel-default'>";

                    if (sizeof($lockerWarning) > 0) {
                        $str .= "<div class='alert alert-danger' style='font-size:15px;'>";
                        foreach ($lockerWarning as $labelWarning) {
                            $str .= $labelWarning;
                        }
                        $str .= "</div class='alert alert-danger'>";
                    }
                    echo $str;
                }
                if (sizeof($mainElements) > 0) {
                    echo "<h4 line='" . __LINE__ . "'>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    foreach ($mainElements as $elName => $aSpec) {
                        if (array_key_exists($elName, $elementConfig)) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " &nbsp;&nbsp;&nbsp;</span>";
                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show({
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        });";
                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";
                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));
                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            if ($val != "") {
                                                echo "<tr line=" . __LINE__ . ">";
                                                $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                                if (strlen($strLabel) > 0) {
                                                    echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                                }
                                                echo "<td align='left' class='text-black'>$val</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        echo "</table>";
                                    }
                                    else {
                                        $msg = "<span class='glyphicon glyphicon-arrow-left'></span> &nbsp;&nbsp;silahkan " . $aSpec['label'] . " dipilih ulang dengan klik icon pensil sebelah kiri.";
                                        echo "<table class='tables table-condensed'>";
                                        echo "<tr line=" . __LINE__ . ">";
                                        echo "<td align='left' class='text-red' style='font-size: 15px;'>$msg</td>";
                                        echo "</tr>";
                                        echo "</table>";
                                    }
                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                    }
                    echo "</table>";
                    echo "</div class='panel-default'>";
                }
                if (isset($description)) { // mendeteksi gerbang catatan (main), bila ada maka ditampilkan. berlaku semua transaksi.
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";

                    // bila bisa mengedit catatan dan mau edit maka editlah.
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    // bila tidak bisa mengedit catatan, maka lihat saja
                    else {
                        if (strlen($description) > 0) {

                            echo nl2br($description);
                        }
                        else {
                            echo "-";
                        }
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }

                if (isset($descriptionAdditionalRule) && ($descriptionAdditionalRule['enabled'] == true)) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note (from current step) </span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($descriptionAdditionalRule['editabled']) && ($descriptionAdditionalRule['editabled'] == true)) {
                        $key_note = "description_additional";
                        $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($descriptionAdditional);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($descriptionAdditional);
                    }
                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                else {
                    if (sizeof($descriptionAdditionalPreviews) > 0) {
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                        echo "<span class='text-muted'>description note (dari step sebelumnya) </span><br>";
                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                        $val_result = "";
                        foreach ($descriptionAdditionalPreviews as $ii => $iiVal) {
                            if ($val_result == "") {
                                $val_result = $iiVal;
                            }
                            else {
                                $val_result .= "<br>" . $iiVal;
                            }
                        }
                        echo nl2br($val_result);
                        echo "</span><br>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                }
                if (sizeof($descriptionMainFollowupRule) > 0) {
                    if (isset($descriptionMainFollowupRule['enabled']) && ($descriptionMainFollowupRule['enabled'] == true)) {
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                        echo "<span class='text-muted'>" . $descriptionMainFollowupRule['label'] . "</span><br>";
                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                        if (isset($descriptionMainFollowupRule['editabled']) && ($descriptionMainFollowupRule['editabled'] == true)) {
                            $key_note = "description_main_followup";
                            $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                            echo "<textarea class='form-control text-left' $addEvent_description>";
                            echo nl2br($descriptionMainFollowup);
                            echo "</textarea>";
                        }
                        else {
                            echo nl2br($descriptionMainFollowup);
                        }

                        echo "</span><br>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                }
                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }

            $btnReloadTaskList = blobDecode($rawBuilderURL);

            if( isset($tasklist) && !empty($tasklist) && $modePreview == "close_project" ){

                echo "<h3 id='showTasklist'>Status Keseluruhan Project *<r>(".$mainValues['projectName'].")</r> <span id='reloadTasklistModal' onclick=\"top.open_holdon();top.$('#result').load('$btnReloadTaskList#showTasklist');\" class='pull-right btn btn-xs btn-danger'><i class='fa fa-refresh'></i> REFRESH</span></h3>";
                echo "<h4>NILAI PROJECT: " . number_format($mainValues['grand_total_ui']) . " (Excl.PPN)</h4>";
                echo "<h4>PPN: " . number_format($mainValues['grand_total_ui']*0.11). "</h4>";
                echo "<h4>NILAI PROJECT: " . number_format($mainValues['grand_total_ui']*1.11). " (Incl.PPN)</h4>";

                /*
                 * UANG MUKA
                 */
                echo "<h4 class='text-bold text-blue'>DP / UANG MUKA $uangmukaDisplay</h4>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($uangmukaprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";

                $colSpan = count($uangmukaprojectHeader)+1;
                $nilai_dp = $uangmukaData[0]["harga"]*1;
                $nilai_project = ($mainValues['grand_total_ui']*1.11);

                //arrPrint($uangmukaData);
                if($uangmukaCheckSetting>0){
                    echo "<tbody>";
                    if($uangmukaproject){
                        $totalSubProgress = 0;
                        $totalSubBobot = 0;
                        $tsNo = 0;
                        $total_um = array();
                        $total_ostd = array();
                        $um_terbayar = 0;
                        $arr_um_terbayar = [];
                        foreach($uangmukaproject as $num => $row){

                            $tsk_id = $row->id;
                            $produk_id = $row->produk_id;
                            $gudang_id = $row->gudang_id;
                            $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                            $totalSubBobot += $row->persen_sub*1;
                            $tsNo++;

                            if(!isset($total_ostd['sisa'])){
                                $total_ostd['sisa'] = 0;
                            }
                            $total_ostd['sisa'] += ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                            $um_terbayar += ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                            $arr_um_terbayar[] = ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                            if(!isset($total_ostd['tagihan'])){
                                $total_ostd['tagihan'] = 0;
                            }
                            $total_ostd['tagihan'] += $row->tagihan;

                            echo "<tr class='gdi_$gudang_id'>";
                            echo "<td>$tsNo</td>";
                            foreach($uangmukaprojectHeader as $kk => $label){
                                $val_ = $row->$kk;
                                switch($kk){
                                    case "terbayar_persen":
                                        $dpp_ppn_nppn = $row->dpp_ppn + $row->ppn_sisa;
                                        $persen_terbayar = $dpp_ppn_nppn>0?(($dpp_ppn_nppn)/$nilai_project)*100:0;
                                        $val_ = number_format($persen_terbayar,0) . "";
                                        break;
                                    case "sisa_persen":
                                        $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                        $val_ = number_format($sisa_persen,0);
                                        break;
                                    case "cek":
                                        $nilai_tagihan = $row->tagihan;
                                        $val_ = "<span nilai_dp='$nilai_dp' nilai_tagihan_um='$nilai_tagihan' class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> UM sudah diterima</span>";
                                        $val_ .= "<span class='hidden'><input checked name='uangmuka' class='financeCheck' type='checkbox'></span>";
                                        break;
                                    case "dpp_ppn":
                                        $val_ = number_format($nilai_dp,0);
                                        break;
                                    case "terbayar":
                                    case "tagihan":
                                        $terima_pd = $row->tagihan + $row->ppn;
                                        $val_ = number_format($terima_pd,0);
                                        break;
                                    case "sisa":
                                        $sisa_nppn = $nilai_project - ($row->$kk + $row->ppn_sisa);
//                                        $val_ = number_format($sisa_nppn,0); //dimatikan dulu
                                        $val_ = 0;
                                        break;
                                }
                                echo "<td line='".__LINE__."' in_$kk >$val_</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    else{
                        echo "<tr class=''>";
                        echo "<td class='text-center text-bold text-red' style='font-size:18px;' colspan='$colSpan'><i class='fa fa-warning blink'></i> BELUM MENERIMA UANG MUKA <i class='fa fa-warning blink'></i><div>".number_format($nilai_dp)." (Incl.PPN)</div><div class='hidden'>silahkan buat uang muka project <a href='javascript:void(0)' onclick=\"top.window.open('https://google.com', '_blank', rel='noopener noreferrer');\">disini</a></div></td>";
                        echo "<span class='hidden'><input name='uangmuka' class='financeCheck' type='checkbox'></span>";
                        echo "<span class='hidden'><input name='' class='financeCheck' type='checkbox'>".json_encode($uangmukaCheckSetting)."</span>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                }
                else{
                    echo "<tbody>";
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN UANG MUKA <i class='glyphicon glyphicon-check text-success'></i></td>";
                    echo "<span class='hidden'><input checked name='uangmuka' class='financeCheck' type='checkbox'></span>";
                    echo "</tr>";
                    echo "</tbody>";
                }

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";

                foreach($uangmukaprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th line='".__LINE__."' in_$kk>-</th>";
                            break;
                        case "tagihan":
                            $gTotalUmTxt= "";
                            $gTotalUm = number_format($total_ostd['sisa']*1);
                            if( ($total_ostd['sisa']*1) < ($nilai_dp*1) ){
                                $gTotalUmTxt .= "
                                    <span gTotalUm='$gTotalUm' nilai_dp='$nilai_dp' class='hidden'>
                                        <input name='uangmuka' class='financeCheck' type='checkbox'>
                                    </span>";
                            }
                            $valTh .= "<th line='".__LINE__."' in_$kk>$gTotalUmTxt</th>";
//                            $um_terbayar = $total_ostd['sisa']*1;
                            break;
                        case "sisa":
//                            $gTotalUm = number_format($nilai_dp-$total_ostd[$kk]*1); //di nolkan dulu
                            $gTotalUm = 0;
                            $valTh .= "<th line='".__LINE__."' in_$kk>$gTotalUm</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th line='".__LINE__."' in_$kk>$totalSubBobot_f%</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th line='".__LINE__."' in_$kk>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                $termin_terbayar = 0;

                /*
                 * TERMIN
                 */
                echo "<h4 class='text-bold text-blue'>TERMIN $terminDisplay</h4>";
                echo "<div class=''>saldo termin = nilai yang belum ditagihkan ke-konsumen.</div>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($terminprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $totalSubProgress = 0;
                $totalSubBobot = 0;
                $tsNo = 0;
                foreach($terminproject as $num => $row){
                    $tsk_id = $row->id;
                    $produk_id = $row->produk_id;
                    $gudang_id = $row->gudang_id;
                    $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
//                    $totalSubProgress += $row->
                    $totalSubBobot += $row->persen_sub*1;
                    $tsNo++;
                    echo "<tr payment_source_id='$tsk_id' class='gdi_$gudang_id'>";
                    echo "<td>$tsNo</td>";
                    foreach($terminprojectHeader as $kk => $label){
//                        $val_ = $row->$kk;
                        switch($kk){
                            case "terbayar_persen":
                                $persen_terbayar = $row->terbayar > 0 ? (round($row->terbayar)/$row->tagihan)*100 : 0;
                                $val_ = number_format(round($persen_terbayar),0);
                                break;
                            case "sisa_persen":
                                $sisa_persen = floor($row->sisa) > 0 ? (floor($row->sisa)/$row->tagihan)*100 : 0;
                                $val_ = number_format(floor($sisa_persen),0);
                                break;
                            case "cek":
                                if( floor($row->sisa) < 1000){
                                    $val_ = "<span class='btn btn-xs btn-success'>tagihan sudah diterbitkan <i class='glyphicon glyphicon-check'></i></span>";
                                    $val_ .= "<span class='hidden'><input checked name='termin' class='financeCheck' type='checkbox'></span>";
                                    $totalSubProgress += $row->persen_sub*1;
                                }
                                else if( floor($row->sisa) > 1000 && floor($row->sisa) < floor($row->tagihan) ){
                                    $val_ = "<span class='btn btn-xs bg-orange belum_termin' disabled><i class='fa fa-warning'></i> Dibayar sebagian <i class='fa fa-warning'></i> </span>";
                                    $val_ .= "<span class='hidden'><input name='termin' class='financeCheck' type='checkbox'></span>";
                                }
                                else{
                                    $val_ = "<span class='btn btn-xs btn-danger belum_termin'><i class='fa fa-warning blink'></i>  belum ada tagihan yang dibuat <i class='fa fa-warning blink'></i> </span>";
                                    $val_ .= "<span class='hidden'><input name='termin' class='financeCheck' type='checkbox'></span>";
                                }
                                break;
                            case "tagihan":
                                $val_ = number_format(floor($row->$kk*1.11),0);
                                break;
                            case "terbayar":
                                $val_ = number_format(floor($row->$kk*1.11),0);
                                $termin_terbayar += $row->$kk;
                                break;
                            case "sisa":
                                $val_ = number_format(floor($row->$kk*1.11),0);
                                break;
                            default:
                                if($kk=="dtime"){
                                    $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                }
                                else{
                                    $val_ = $row->$kk;
                                }
                                break;
                        }
                        echo "<td>$val_</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($terminprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th>$totalSubBobot_f%</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                /*
                 * TASKLIST
                 */
                echo "<h4 class='text-bold text-blue'>WORK-ORDER / TASKLIST</h4>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($tasklistHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $totalSubProgress = 0;
                $totalSubBobot = 0;
                $tsNo = 0;
                foreach($tasklist as $num => $row){
                    $tsk_id = $row->id;
                    $produk_id = $row->produk_id;
                    $gudang_id = $row->gudang_id;
                    $post_biaya_id = $row->post_biaya_id;
                    $post_return_id = $row->post_return_id;
                    $checkBiaya = isset($row->biaya) ? count($row->biaya) : 0;
                    $checkLogReturn = isset($row->log_return) ? $row->log_return : 0;
                    $ada_log_return_supplies = isset($checkLogReturn[0]['supplies']) ? 1 : 0;
                    $ada_log_return_produk = isset($checkLogReturn[0]['produk']) ? 1 : 0;
                    $getTransaksiHis = isset($row->his_trx) ? $row->his_trx : array();
                    $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();

                    $totalSubBobot += $row->persen_sub*1;
                    $total_pembayaran = array();
                    $tsNo++;
                    echo "<tr class='gdi_$gudang_id'>";
                    echo "<td>$tsNo</td>";
                    foreach($tasklistHeader as $kk => $label){
                        $val_ = $row->$kk;
                        $bb_box = "";
                        if( count($arrBB) > 0 ){
                            $noBB = 0;
                            $bb_box .= "<span data-id='$gudang_id' style='margin-left: 3px;' class='btn-tooltip btn btn-xs bg-violet unused_stok'>ada stok produk</span>";
                            $bb_box .= "<span style='margin-left: 3px;' data-id='create-$tsk_id-$produk_id' onclick='fnTasklist.create(this)' id='' class='btn btn-xs btn-info'><i class='fa fa-send'></i> View Progress</span>";
                        }
                        else{
                            $bb_box .= "<span style='margin-left: 3px;' class='btn btn-xs bg-olive' disabled>belum distribusi</span>";
                        }
                        switch($kk){
                            case "progress_nama":
                                if($row->progress_id == 3){
                                    $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> sudah QC</span>";
                                    $val_ .= "<span class='hidden'><input checked name='tasklist' class='validationCheck' type='checkbox'></span>";
                                    $totalSubProgress += $row->persen_sub*1;
                                }
                                else if($row->progress_id == 2 && $row->progress_percent == 100){
                                    $val_ = "<span class='btn btn-xs bg-orange belum_qc' disabled><i class='fa fa-warning blink'></i> belum QC</span>";
                                    $val_ .= "<span style='margin-left: 3px;' data-id='create-$tsk_id-$produk_id' onclickx='fnTasklist.create(this)' id='' class='btn btn-xs btn-info'><i class='fa fa-send'></i> Silahkan Lakukan QC</span>";
                                    $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                                }
                                else{
                                    if($row->progress_id == 2 && $row->progress_percent > 0 && $row->progress_percent < 100){
                                        $val_ = "<span class='btn btn-xs btn-danger'>dikerjakan parsial</span>";
                                        $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                                    }
                                    else{
                                        $val_ = "<span class='btn btn-xs btn-danger'>belum dikerjakan</span>";
                                        $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                                    }
                                    if($bb_box!=""){
                                        $val_ .= $bb_box;
                                    }
                                }
                                break;
                            case "progress_percent":
                                $val_ = $val_ . "%";
                                break;
                            case "persen_sub":
                                $val_ = number_format($val_, 2) . "%";
                                break;
                            case "nilai_sub_fase":
                                $val_ = number_format($row->$kk,0);
                                if(!isset($total_pembayaran[$kk])){
                                    $total_pembayaran[$kk] = 0;
                                }
                                $total_pembayaran[$kk] += $row->$kk > 1000 ? $row->$kk*1 : 0;
                                break;
                            default:
                                if($kk=="dtime"){
                                    $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                }
                                else{
                                    $val_ = $row->$kk;
                                }
                                break;
                        }
                        echo "<td>$val_</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($tasklistHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $taskStatusALl = "";
                            if( $totalSubBobot < 99 ){
                                $taskStatusALl .= "<span class='hidden'><input class='project_persen'></span>";
                                $taskStatusALl .= "<span class='hidden'><input name='project_persen' class='financeCheck' type='checkbox'></span>";
                            }
                            $valTh .= "<th>";
                            $valTh .= "$totalSubBobot_f%";
                            $valTh .= $taskStatusALl;
                            $valTh .= "</th>";
                            break;
                        case "nilai_sub_fase":
                            $totalPembayaran = number_format($total_pembayaran[$kk]);
                            $valTh .= "<th>$totalPembayaran</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                /*
                 * PENERIMAAN PEMBAYARAN DARI TERMIN
                 */
                $ar_terbayar = 0;
                echo "<h4 class='text-bold text-blue'>PENERIMAAN PEMBAYARAN</h4>";
//                echo "<div class='text-bold text-red'><i>konsumen belum menyelesaikan pembayaran</i></div>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($terimabayarprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                $colSpan = count($terimabayarprojectHeader)+1;
                if(!empty($terimabayarproject)){
                    echo "<tbody>";
                    $totalSubProgress = 0;
                    $totalSubBobot = 0;
                    $tsNo = 0;
                    $total = array();
                    foreach($terimabayarproject as $num => $row){
                        $tsk_id = $row->id;
                        $produk_id = $row->produk_id;
                        $gudang_id = $row->gudang_id;
                        $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                        $totalSubBobot += $row->persen_sub*1;
                        $tsNo++;
                        echo "<tr payment_source_id='$tsk_id' class='gdi_$gudang_id'>";
                        echo "<td>$tsNo</td>";
                        foreach($terimabayarprojectHeader as $kk => $label){
                            $val_ = $row->$kk;
                            switch($kk){
                                case "terbayar_persen":
                                $persen_terbayar = $row->terbayar > 0 ? ($row->terbayar/$row->tagihan)*100 : 0;
                                $val_ = number_format($persen_terbayar,0);
                                break;
                                case "sisa_persen":
                                        $sisa_persen = $row->sisa > 100 ? ($row->sisa/$row->tagihan)*100 : 0;
                                    $val_ = number_format($sisa_persen,0);
                                    break;
                                case "cek":
                                        if($row->sisa < 100 && $row->returned > 100 ){
                                            $val_ = "<span class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-trash'></i> penerimaan dibatalkan*</span>";
                                            $val_ .= "<span class='hidden'><input checked name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                        }
                                        else if($row->sisa < 100 ){
                                        $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> sudah lunas</span>";
                                            $val_ .= "<span class='hidden'><input checked name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                        $totalSubProgress += $row->persen_sub*1;
                                    }
                                        else if($row->sisa < 100 && $row->sisa < $row->tagihan ){
                                            $val_ = "<span class='btn btn-xs bg-orange penerimaan_belum_lunas' disabled><i class='fa fa-warning blink'></i> Dibayar sebagian</span>";
                                            $val_ .= "<span class='hidden'><input name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                    }
                                    else{
                                            $val_ = "<span class='btn btn-xs btn-danger penerimaan_belum_lunas'>belum ada pembayaran</span>";
                                            $val_ .= "<span class='hidden'><input name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                    }
                                    break;
                                case "tagihan":
                                        $val_ = number_format($row->$kk,0);
                                        if(!isset($total[$kk])){
                                            $total[$kk] = 0;
                                        }
                                        $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                        break;
                                case "returned":
                                        $val_ = number_format($row->$kk,0);
                                        if(!isset($total[$kk])){
                                            $total[$kk] = 0;
                                        }
                                        $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                        break;
                                case "terbayar":
                                        $val_ = number_format($row->$kk,0);
                                        if(!isset($total[$kk])){
                                            $total[$kk] = 0;
                                        }
                                        $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                        $ar_terbayar += $row->$kk;
                                    break;
                                case "sisa":
                                    $val_ = number_format($row->$kk,0);
                                        if(!isset($total[$kk])){
                                            $total[$kk] = 0;
                                        }
                                        $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                    break;
                                default:
                                    if($kk=="dtime"){
                                        $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                    }
                                    else{
                                        $val_ = $row->$kk;
                                    }
                                    break;
                            }
                            echo "<td colom='$kk'>$val_</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";

                }
                else{
                    echo "<tbody>";
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold text-red blink' style='font-size:18px;' colspan='$colSpan'><i class='fa fa-warning text-danger'></i> BELUM ADA PEMBAYARAN MASUK UNTUK PROJECT INI <i class='fa fa-warning text-danger'></i></td>";
                    echo "<span class='hidden'><input name='uangmuka' class='financeCheck' type='checkbox'></span>";
                    echo "</tr>";
                    echo "</tbody>";
                }

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($terimabayarprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th>$totalSubBobot_f%</th>";
                            break;
                        case "tagihan":
                            $total_tagihan = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                            $valTh .= "<th jenis='$kk' >$total_tagihan</th>";
                            break;
                        case "terbayar":
                            $total_terbayar = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                            $valTh .= "<th jenis='$kk' >$total_terbayar</th>";
                            break;
                        case "returned":
                            $total_retur = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                            $valTh .= "<th jenis='$kk' >$total_retur</th>";
                            break;
                        case "sisa":
                            $total_tagihan = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                            $valTh .= "<th>$total_tagihan</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

//                echo "DP TERBAYAR: " . json_encode($arr_um_terbayar) . "<br>";
//                echo "TERMIN TERBAYAR: " . number_format($termin_terbayar) . "<br>";
//                echo "DP TERBAYAR: " . number_format($um_terbayar) . "<br>";
//                echo "A/R TERBAYAR: " . number_format($ar_terbayar) . "<br>";
//                echo "TOTAL: " . number_format($ar_terbayar+$um_terbayar) . " (Incl.PPN)<br>";
//                echo "PROJECT: " . number_format($mainValues['grand_total_ui']) . " (Belum PPN) || " . number_format($mainValues['grand_total_ui']*1.11) . " (Incl.PPN)<br>";
                $check_kekurangan = ($mainValues['grand_total_ui']*1.11) - ($ar_terbayar+$um_terbayar);
//                echo "KEKURANGAN: " . number_format($check_kekurangan) . " (Belum PPN) || " . number_format($check_kekurangan*1.11) . " (Incl.PPN)<br>";

                $grand_total_ui = (string)$mainValues['grand_total_ui'];
                $um_terbayar = (string)$um_terbayar;

                $check_kekurangan = bcsub(bcmul($grand_total_ui, '1.11', 0), $um_terbayar, 0);
                $check_kekurangan = bcsub(bcmul($check_kekurangan, '1', 0), $ar_terbayar, 0);

//                echo "check: " . $check_kekurangan . "<br>";
//                echo "grand_total_ui: " . ($mainValues['grand_total_ui']*1.11) . "<br>";
//                echo "ar_terbayar: " . $ar_terbayar . "<br>";
//                echo "um_terbayar: " . $um_terbayar . "<br>";
//                echo "check: (".($mainValues['grand_total_ui']*1.11) . ") - ( ". ($ar_terbayar+$um_terbayar) . ") = " . number_format($check_kekurangan) . "<br>";
//                echo "type_check: " . var_dump($check_kekurangan) . "<br>";

                //===========================================================
                //===========================================================
                /*
                 * RETENSI/GARANSI  (TIDAK MENGIKAT TRANSAKSI)
                 */
                echo "<div class='alert' style='background: #ffd01a;'>";
                echo "<h4 class='text-bold text-blue'>RETENSI/GARANSI $retensiDisplay</h4>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($retensiprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                $colSpan = count($retensiprojectHeader)+1;
                if($retensiCheckSetting>0){
                    echo "<tbody>";
                    if($retensiproject){
                    $totalSubProgress = 0;
                    $totalSubBobot = 0;
                    $tsNo = 0;
                        foreach($retensiproject as $num => $row){
                        $tsk_id = $row->id;
                        $produk_id = $row->produk_id;
                        $gudang_id = $row->gudang_id;
                        $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                        $totalSubBobot += $row->persen_sub*1;
                        $tsNo++;
                        echo "<tr class='gdi_$gudang_id'>";
                        echo "<td>$tsNo</td>";
                            foreach($retensiprojectHeader as $kk => $label){
                            $val_ = $row->$kk;
                            switch($kk){
                                case "terbayar_persen":
                                    $persen_terbayar = $row->terbayar > 0 ? ($row->terbayar/$row->tagihan)*100 : 0;
                                    $val_ = number_format($persen_terbayar,0);
                                    break;
                                case "sisa_persen":
                                    $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                    $val_ = number_format($sisa_persen,0);
                                    break;
                                case "cek":
                                        $nilai_retensi = $retensiData[0]["harga"];
                                        $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d");
                                        $tanggal_hari_ini = date("Y-m-d");

                                    if($row->sisa == 0){
                                            if ($akhir_retensi > $tanggal_hari_ini) {
                                                // Sudah bayar, tapi belum habis masa garansi
                                                $val_ = "<span class='btn btn-xs btn-primary '><i class='fa fa-check'></i> Sudah dibayar - Garansi belum habis</span>";
                                            }
                                            else {
                                                // Sudah bayar dan garansi sudah habis
                                                $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> Sudah lunas & garansi selesai</span>";
                                            }
                                    }
                                    else if($row->sisa > 0 && $row->sisa < $row->tagihan ){
                                            // Dibayar sebagian
                                            $val_ = "<span class='btn btn-xs bg-orange' disabled><i class='fa fa-warning blink'></i> Dibayar sebagian</span>";
                                        }
                                        else {
                                            // Belum ada pembayaran
                                            $val_ = "<span class='btn btn-xs btn-danger'>Belum ada pembayaran</span>";
                                        }
                                        break;
                                    case "tgl_berakhir_retensi":
                                        $dateToDebug = date("Y-m-d", strtotime("+2 month 5 day"));
                                        $nilai_retensi = $retensiData[0]["harga"];
                                        $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("+2 day"));
                                        $tanggal_hari_ini = date("Y-m-d");
                                        $umur_akhir_retensi = createTimeDescSoon($akhir_retensi);

                                        if ($akhir_retensi > $tanggal_hari_ini) {
                                            // masa retensi belum habis
                                            $val_ = "<span class='btn btn-xs btn-warning'><i class='fa fa-clock-o'></i> $akhir_retensi </span><br>masa garansi belum habis<br>$umur_akhir_retensi";
                                    }
                                    else{
                                            // masa retensi sudah habis
                                            $val_ = "<span akhir_retensi='$akhir_retensi' class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> $akhir_retensi </span><br>$umur_akhir_retensi";
                                    }
                                    break;
                                case "tagihan":
                                        $val_ = number_format($row->$kk,0);
                                        break;
                                case "terbayar":
                                        $val_ = number_format($row->$kk,0);
                                        break;
                                case "sisa":
                                    $val_ = number_format($row->$kk,0);
                                    break;
                                default:
                                    if($kk=="dtime"){
                                        $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                    }
                                    else{
                                        $val_ = $row->$kk;
                                    }
                                    break;
                            }
                            echo "<td>$val_</td>";
                        }
                        echo "</tr>";
                    }
                    }
                    else{
                        $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("2 week"));
                        $umur_akhir_retensi = createTimeDescSoon($akhir_retensi);
                        $nilai_retensi = number_format($retensiData[0]["harga"]*1);
                        echo "<tr class=''>";
                        echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'> <i class='fa fa-warning text-red blink'></i> <r>BELUM ADA PEMBAYARAN RETENSI</r> <i class='fa fa-warning text-red blink'></i> <br>TGL AKHIR RETENSI: <br>$akhir_retensi <br><i>($umur_akhir_retensi)</i><br><r>$nilai_retensi (Incl.PPN)</r></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                }
                else{
                    echo "<tbody>";
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN RETENSI / GARANSI <i class='glyphicon glyphicon-check text-success'></i></td>";
                    echo "</tr>";
                    echo "</tbody>";
                }

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($retensiprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th>$totalSubBobot_f%</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";
                echo "</div>";
                //===========================================================

                echo "<script>
                            function reCheckValidation(data=null){
                                var err = 0;
                                var done = 0;
                                var check_kekurangan = $check_kekurangan*1;
                                var valCheckArr = $('.validationCheck');
                                var financeCheckArr = $('.financeCheck');
                                console.log('check_kekurangan', check_kekurangan);
                                
                                jQuery.each(valCheckArr, function(a, b){
                                    if( !$(b).is(':checked') ){
                                        err += 1;
                                    }
                                    else{
                                        done += 1;
                                    }
                                });

                                jQuery.each(financeCheckArr, function(a, b){
                                    if( !$(b).is(':checked') ){
                                        if(check_kekurangan*1>0){
                                            err += 1;
                                        }
                                        else{
                                            done += 1;
                                        }
                                    }
                                    else{
                                        done += 1;
                                    }
                                });
                                
                                $('#approvalButton').prop('disabled', true);

                                if( !$('#checklist_trx').is(':checked') ){
                                    err += 1;
                                }
                                
                                if( !err > 0 ){
                                    $('#approvalButton').prop('disabled', false);
                                }
                                else{
                                    if( $('#checklist_trx').is(':checked') ){
                                        var note_tambahan = data != null ? \"<br><br><r>NOTES:</r><br>$modePreview\" + data : ''
                                        setTimeout(function(){
                                            swal('SILAHKAN PERIKSA KEMBALI', 'PROJECT BELUM BISA DI TUTUP KARENA MASIH ADA YANG BELUM ANDA SELESAIKAN'+note_tambahan, 'info');
                                            $('#checklist_trx').prop('checked', false).trigger('change');
                                        },500);
                                    }
                                }
                            }
                            
                            $('.validationCheck').on('change', function(){
                                reCheckValidation();
                            })
                            
                            setTimeout(function(){
                                top.reCheckValidation();
                            },500);
                            
                            function check_status_project(){
                                var stok_gantung = $(\".unused_stok\").length;
                                var belum_qc = $(\".belum_qc\").length;
                                var penerimaan_belum_lunas = $(\".penerimaan_belum_lunas\").length;
                                var termin_belum_selesai = $(\".belum_termin\").length;
                                var project_persen = $(\".project_persen\").length;
                                var announcement = '';
                                if(stok_gantung==0 && belum_qc==0 && penerimaan_belum_lunas==0 && termin_belum_selesai==0 && project_persen==0){
                                    //top.$('#approvalButton').prop('disabled', false);
                                }
                                else{
                                
                                    if(belum_qc){
                                        announcement += \"<div class='text-left'>- ada (\"+belum_qc+\") WO yang belum dilakukan QC.</div>\";
                                    }
                                    
                                    if(penerimaan_belum_lunas){
                                        announcement += \"<div class='text-left'>- ada (\"+penerimaan_belum_lunas+\") Pembayaran yang belum lunas/dibayar sebagian.</div>\";
                                    }
                                    
                                    if(termin_belum_selesai){
                                        announcement += \"<div class='text-left'>- ada (\"+termin_belum_selesai+\") Termin belum lunas/dibayar sebagian.</div>\";
                                    }
                                    
                                    
                                    if(stok_gantung || belum_qc || penerimaan_belum_lunas || termin_belum_selesai || project_persen){
                                        announcement += ''
                                    }
                                    
                                    if(stok_gantung){
                                        announcement += \"<div class='text-left'>- ada (\"+stok_gantung+\") WO dengan stok gantung.</div>\";
                                    }
                                    
                                    
                                    if(project_persen){
                                        announcement += \"<div class='text-left'>- pengerjaan project belum 100%.</div>\";
                                    }
                                    
                                    if(stok_gantung || belum_qc || penerimaan_belum_lunas || termin_belum_selesai || project_persen){
                                        announcement += ''
                                    }
                                    
                                    //swal('ada yang perlu di cek..!!', announcement, 'info');
                                }
                                reCheckValidation(announcement);
                            }
                            $(document).ready(function(){
                                $('#check_lanjut_closing').removeClass('hidden');
                                $('#approvalButton').prop('disabled', true);
                            })
                        </script>
                ";

                echo "<div style='padding: 10px;' id='check_lanjut_closing' class='panel bg-red hiddsen'>";

                $msgNote = "Checklist (Tahap 1) Serah Terima Sementara";
                $checklistNoteEncode = blobEncode($msgNote);
                $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";

                echo "<div class='text-rightx'>";
                echo "<label for='checklist_trx'>";
                echo "<input id='checklist_trx' type='checkbox' $checked onchange=\"check_status_project();document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode&state='+$(this).is(':checked');\">";
                echo "&nbsp; <span style='font-size: 20px;'>$msgNote</span>";
                echo "</label>";
                echo "</div>";

                echo "<div style='margin-top: 4px;'>";
                echo "<div class='text-bold fa-2x'>NOTES:</div>";
                echo "<div style='font-size: 16px;'>1. Pastikan seluruh pengembalian bahan baku telah diproses. Jika ada yang belum, lakukan otorisasi pengembalian..</div>";
                echo "<div style='font-size: 16px;'>2. Pastikan seluruh WO telah menjalani QC. Jika ada yang belum, lakukan QC.</div>";
                echo "<div style='font-size: 16px;'>3. Pastikan uang muka proyek telah diterima apabila disyaratkan dalam kontrak..</div>";
                echo "<div style='font-size: 16px;'>4. Pastikan seluruh termin pembayaran telah diterbitkan apabila proyek menggunakan skema termin.</div>";
                echo "<div style='font-size: 16px;'>5. Proses ini akan mencatat piutang retensi jika project memiliki garansi.</div>";
                echo "<div style='font-size: 16px;'>6. Pengecualian pada segi Finance, jika nilai penerimaan project dari termin/UM/AR sudah dinyatakan selesai.</div>";
                echo "</div>";
                echo "</div>";
//            }
            }
            elseif( isset($tasklist) && !empty($tasklist) && $modePreview == "final_close_project_ori" ){
                echo "<h2 id='showTasklist'>--Status Keseluruhan Project <r>(".$mainValues['projectName'].")</r> <span id='reloadTasklistModal' onclick=\"top.open_holdon();top.$('#result').load('$btnReloadTaskList#showTasklist');\" class='pull-right btn btn-xs btn-danger'><i class='fa fa-refresh'></i> REFRESH</span></h2>";

                echo "<h3>NILAI PROJECT (Excl.PPN): " . number_format($mainValues['grand_total_ui']) . "</h3>";
                echo "<h3>PPN: " . number_format($mainValues['grand_total_ui']*0.11). "</h3>";
                echo "<h3>NILAI PROJECT (Incl.PPN): " . number_format($mainValues['grand_total_ui']*1.11). "</h3>";

//                arrPrint($uangmukaproject);
//                arrPrint($retensiproject);
//                arrPrint($terminproject);
//                arrPrint($terimabayarproject);

                $nilai_dp_final = !empty($uangmukaData) ? $uangmukaData[0]['harga'] : 0;
                $persen_dp = !empty($uangmukaData) ? $uangmukaData[0]['persen'] : 0;

                $nilai_retensi_final = !empty($retensiData) ? $retensiData[0]['harga'] : 0;
                $persen_retensi = !empty($retensiData) ? $retensiData[0]['persen'] : 0;

                $nilai_termin_final = 0;
                $persen_termin = 0;
                if (!empty($terminData)) {
                    foreach ($terminData as $t) {
                        $nilai_termin_final += $t['harga'];
                        $persen_termin += $t['persen'];
                    }
                }

                $total_harga  = $nilai_dp_final + $nilai_retensi_final + $nilai_termin_final;
                $total_persen = $persen_dp + $persen_retensi + $persen_termin;

                echo "Uang Muka: (" . number_format($nilai_dp_final) . " || {$persen_dp}%)<br>";
                echo "Termin: (" . number_format($nilai_termin_final) . " || {$persen_termin}%)<br>";
                echo "Retensi: (" . number_format($nilai_retensi_final) . " || {$persen_retensi}%)<br>";
                echo "Total Gabungan: (" . number_format($total_harga) . " || {$total_persen}%)<br><br><br>";

                function sumField($rows, $field)
                {
                    $sum = 0.0;
                    foreach ($rows as $r) {
                        if (is_object($r)) {
                            $v = isset($r->$field) ? $r->$field : 0;
                        } else {
                            $v = isset($r[$field]) ? $r[$field] : 0;
                        }
                        $sum += (float)$v;
                    }
                    return $sum;
                }

                // ----- INPUT: pastikan variabel tidak null -----
                if (!isset($uangmukaproject)) $uangmukaproject = array();
                if (!isset($retensiproject)) $retensiproject = array();
                if (!isset($terminproject)) $terminproject = array();
                if (!isset($terimabayarproject)) $terimabayarproject = array();

                // ----- PER KATEGORI -----
                // Uang Muka
                $um_tagihan  = sumField($uangmukaproject, 'tagihan');
                $um_terbayar = sumField($uangmukaproject, 'terbayar');
                $um_sisa     = sumField($uangmukaproject, 'sisa');
                $um_ppn      = sumField($uangmukaproject, 'ppn');
                $um_dpp      = sumField($uangmukaproject, 'dpp_ppn');

                // Termin -> gunakan terimabayarproject (penerimaan termin)
                $tr_tagihan  = sumField($terimabayarproject, 'tagihan');
                $tr_terbayar = sumField($terimabayarproject, 'terbayar');
                $tr_sisa     = sumField($terimabayarproject, 'sisa');
                $tr_ppn      = sumField($terimabayarproject, 'ppn');
                $tr_dpp      = sumField($terimabayarproject, 'dpp_ppn');

                // Retensi
                $rt_tagihan  = sumField($retensiproject, 'tagihan');
                $rt_terbayar = sumField($retensiproject, 'terbayar');
                $rt_sisa     = sumField($retensiproject, 'sisa');
                $rt_ppn      = sumField($retensiproject, 'ppn');
                $rt_dpp      = sumField($retensiproject, 'dpp_ppn');

                // ----- TOTAL GABUNGAN -----
                $tot_tagihan  = $um_tagihan + $tr_tagihan + $rt_tagihan;
                $tot_terbayar = $um_terbayar + $tr_terbayar + $rt_terbayar;
                $tot_sisa     = $um_sisa + $tr_sisa + $rt_sisa;
                $tot_ppn      = $um_ppn + $tr_ppn + $rt_ppn;
                $tot_dpp      = $um_dpp + $tr_dpp + $rt_dpp;

//                echo '<table border="1" cellspacing="0" cellpadding="4">';
//                echo '<tr>
//                        <th>Kategori</th>
//                        <th>Tagihan</th>
//                        <th>Terbayar</th>
//                        <th>Sisa</th>
//                        <th>PPN</th>
//                        <th>DPP</th>
//                      </tr>';
//
//                echo '<tr>
//                        <td>Uang Muka</td>
//                        <td>' . number_format($um_tagihan) . '</td>
//                        <td>' . number_format($um_terbayar) . '</td>
//                        <td>' . number_format($um_sisa) . '</td>
//                        <td>' . number_format($um_ppn) . '</td>
//                        <td>' . number_format($um_dpp) . '</td>
//                      </tr>';
//
//                echo '<tr>
//                        <td>Termin</td>
//                        <td>' . number_format($tr_tagihan) . '</td>
//                        <td>' . number_format($tr_terbayar) . '</td>
//                        <td>' . number_format($tr_sisa) . '</td>
//                        <td>' . number_format($tr_ppn) . '</td>
//                        <td>' . number_format($tr_dpp) . '</td>
//                      </tr>';
//
//                echo '<tr>
//                        <td>Retensi</td>
//                        <td>' . number_format($rt_tagihan) . '</td>
//                        <td>' . number_format($rt_terbayar) . '</td>
//                        <td>' . number_format($rt_sisa) . '</td>
//                        <td>' . number_format($rt_ppn) . '</td>
//                        <td>' . number_format($rt_dpp) . '</td>
//                      </tr>';
//
//                echo '<tr style="font-weight:bold;">
//                        <td>TOTAL</td>
//                        <td>' . number_format($tot_tagihan) . '</td>
//                        <td>' . number_format($tot_terbayar) . '</td>
//                        <td>' . number_format($tot_sisa) . '</td>
//                        <td>' . number_format($tot_ppn) . '</td>
//                        <td>' . number_format($tot_dpp) . '</td>
//                      </tr>';
//
//                echo '</table><br><br>';

                //==================================================
                //==================================================
                /* ===== Input aman ===== */
                if (!isset($uangmukaproject))    $uangmukaproject = array();
                if (!isset($terimabayarproject)) $terimabayarproject = array(); // termin (realisasi)
                if (!isset($retensiproject))     $retensiproject = array();

                $terminSetting   = isset($_SESSION[$cCode]['items3']) ? $_SESSION[$cCode]['items3'] : array();
                $uangmukaSetting = isset($_SESSION[$cCode]['items4']) ? $_SESSION[$cCode]['items4'] : array();
                $retensiSetting  = isset($_SESSION[$cCode]['items5']) ? $_SESSION[$cCode]['items5'] : array();

                /* ===== Rencana (target) ===== */
                $plan_dp   = !empty($uangmukaSetting) ? (float)$uangmukaSetting[0]['harga'] : 0.0;
                $plan_ret  = !empty($retensiSetting)  ? (float)$retensiSetting[0]['harga']  : 0.0;
                $plan_term = 0.0;
                for ($i=0; $i<count($terminSetting); $i++) { $plan_term += (float)$terminSetting[$i]['harga']; }
                $plan_total = $plan_dp + $plan_term + $plan_ret;

                /* ===== Realisasi (tagihan) ===== */
                $real_dp_tagihan   = sumField($uangmukaproject, 'tagihan');
                $real_term_tagihan = sumField($terimabayarproject, 'tagihan');
                $real_ret_tagihan  = sumField($retensiproject, 'tagihan');
                $real_ret_terbayar = sumField($retensiproject, 'terbayar');
                $real_ret_sisa     = sumField($retensiproject, 'sisa');

                /* ===== Status DP (sesuai ketentuan Anda) ===== */
                $dp_sudah_diterima = ($real_dp_tagihan > 0);

                /* ===== Garansi (dari setting retensi) ===== */
                $tgl_garansi = '';
                if (!empty($retensiSetting) && isset($retensiSetting[0]['tgl_akhir_garansi'])) {
                    $tgl = trim($retensiSetting[0]['tgl_akhir_garansi']);
                    if ($tgl !== '' && $tgl !== '0000-00-00') $tgl_garansi = $tgl;
                }
                $today = date('Y-m-d'); // server time
                $garansi_ada     = ($tgl_garansi !== '');
                $garansi_selesai = (!$garansi_ada) ? true : (strtotime($today) >= strtotime($tgl_garansi));

                /* ===== Threshold selesai ===== */
                $eps = 0.5; // toleransi pembulatan rupiah
                $threshold_tanpa_retensi = $plan_total - $plan_ret;

                $tagihan_tanpa_retensi  = $real_dp_tagihan + $real_term_tagihan;
                $tagihan_dengan_retensi = $tagihan_tanpa_retensi + $real_ret_tagihan;

                /* ===== Kondisi retensi ===== */
                $retensi_masih_terutang = ($real_ret_tagihan - $real_ret_terbayar) > $eps || $real_ret_sisa > $eps;

                /* ===== Keputusan ===== */
// Selesai TANPA retensi: capai target tanpa komponen retensi
                $selesai_tanpa_retensi  = ($tagihan_tanpa_retensi + $eps) >= $threshold_tanpa_retensi;

// Selesai DENGAN retensi: harus capai target total + retensi tidak terutang + garansi selesai
                $selesai_dengan_retensi = ($tagihan_dengan_retensi + $eps) >= $plan_total
                    && !$retensi_masih_terutang
                    && $garansi_selesai;

                /* ===== Perlu tambah retensi? =====
                   Jika tanpa retensi BELUM selesai, dan saat ini belum ada retensi sama sekali.
                */
                $ada_retensi_setting = ($plan_ret > 0);
                $ada_retensi_realisasi = ($real_ret_tagihan > 0 || $real_ret_terbayar > 0);
                $perlu_tambah_retensi = (!$selesai_tanpa_retensi) && (!$ada_retensi_realisasi);

                /* ===== Output ringkas ===== */
//                echo "DP_sudah_diterima: " . ($dp_sudah_diterima ? 'YA' : 'TIDAK') . "<br>";
//                echo "Retensi_terutang : " . ($retensi_masih_terutang ? 'YA' : 'TIDAK') . "<br>";
//                echo "Garansi_ada      : " . ($garansi_ada ? 'YA' : 'TIDAK') . "<br>";
//                if ($garansi_ada) {
//                    echo "Garansi_selesai  : " . ($garansi_selesai ? 'YA' : 'TIDAK') . " (tgl akhir: {$tgl_garansi})<br>";
//                }
//                echo "Selesai TANPA retensi : " . ($selesai_tanpa_retensi ? 'YA' : 'TIDAK') . "<br>";
//                echo "Selesai DENGAN retensi: " . ($selesai_dengan_retensi ? 'YA' : 'TIDAK') . "<br>";
//
//                if ($selesai_dengan_retensi) {
//                    $status_project = 'SELESAI (dengan retensi)';
//                } elseif ($selesai_tanpa_retensi) {
//                    $status_project = 'TAHAP FISIK SELESAI, MENUNGGU RETENSI/GARANSI';
//                } else {
//                    $status_project = 'BELUM SELESAI';
//                }
//                echo "Status_project: {$status_project}<br>";
//
//                echo "Perlu_tambah_retensi (jika belum ada): " . ($perlu_tambah_retensi ? 'YA' : 'TIDAK') . "<br>";

                //=======================================================
                //=======================================================

                /*
                 * RETENSI/GARANSI
                 */
                echo "<h4 class='text-bold text-blue'>RETENSI/GARANSI $retensiDisplay</h4>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($retensiprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                $colSpan = count($retensiprojectHeader)+1;
                if($retensiCheckSetting>0){
                    echo "<tbody>";
                    if($retensiproject){
                    $totalSubProgress = 0;
                    $totalSubBobot = 0;
                    $tsNo = 0;
                    foreach($retensiproject as $num => $row){
                        $tsk_id = $row->id;
                        $produk_id = $row->produk_id;
                        $gudang_id = $row->gudang_id;
                        $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                        $totalSubBobot += $row->persen_sub*1;

                        $tsNo++;
                        echo "<tr class='gdi_$gudang_id'>";
                        echo "<td>$tsNo</td>";
                        foreach($retensiprojectHeader as $kk => $label){
                            $val_ = $row->$kk;
                            switch($kk){
                                case "terbayar_persen":
                                    $persen_terbayar = $row->terbayar > 0 ? ($row->terbayar/$row->tagihan)*100 : 0;
                                    $val_ = number_format($persen_terbayar,0);
                                    break;
                                case "sisa_persen":
                                    $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                    $val_ = number_format($sisa_persen,0);
                                    break;
                                case "cek":
                                        $nilai_retensi = $retensiData[0]["harga"];
                                            $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("+2 day"));
                                        $tanggal_hari_ini = date("Y-m-d");

                                        if ($row->sisa < 1000) {
                                            if ($akhir_retensi > $tanggal_hari_ini) {
                                                // Sudah bayar, tapi belum habis masa garansi
                                                $val_ = "<span class='btn btn-xs btn-primary belum_retensi'><i class='fa fa-check'></i> Sudah dibayar - Garansi belum habis</span>";
                                        $val_ .= "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                                    }
                                    else{
                                                // Sudah bayar dan garansi sudah habis
                                                $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> Sudah lunas & garansi selesai</span>";
                                                $val_ .= "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                                            }
                                        }
                                        elseif ($row->sisa > 1000 && $row->sisa < $row->tagihan) {
                                            // Dibayar sebagian
                                            $val_ = "<span sisa='".$row->sisa."' class='btn btn-xs bg-orange belum_retensi' disabled><i class='fa fa-warning blink'></i> Dibayar sebagian</span>";
                                            $val_ .= "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                                        }
                                        else {
                                            // Belum ada pembayaran
                                            $val_ = "<span class='btn btn-xs btn-danger belum_retensi'>Belum ada pembayaran</span>";
                                        $val_ .= "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                                    }
                                        break;
                                    case "tgl_berakhir_retensi":
                                        $dateToDebug = date("Y-m-d", strtotime("+2 month 5 day"));
                                        $nilai_retensi = $retensiData[0]["harga"];
                                            $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("+2 day"));
                                        $tanggal_hari_ini = date("Y-m-d");
                                        $umur_akhir_retensi = createTimeDescSoon($akhir_retensi);

                                        if ($akhir_retensi > $tanggal_hari_ini) {
                                            // masa retensi belum habis
                                            $val_ = "<span class='btn btn-xs btn-warning tgl_belum_retensi'><i class='fa fa-clock-o'></i> $akhir_retensi </span><br>masa garansi belum habis<br>$umur_akhir_retensi";
                                            $val_ .= "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                                        }
                                        else {
                                            // masa retensi sudah habis
                                            $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> $akhir_retensi </span><br>$umur_akhir_retensi";
                                            $val_ .= "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                                        }
                                    break;
                                case "tagihan":
                                        $val_ = number_format($row->$kk,0);
                                        break;
                                case "terbayar":
                                        $val_ = number_format($row->$kk,0);
                                        break;
                                case "sisa":
                                    $val_ = number_format($row->$kk,0);
                                    break;
                            }
                            echo "<td>$val_</td>";
                        }
                        echo "</tr>";
                    }
                    }
                    else{
                        $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("2 week"));
                        $umur_akhir_retensi = createTimeDescSoon($akhir_retensi);
                        $nilai_retensi = number_format($retensiData[0]["harga"]*1);
                        echo "<tr class=''>";
                        echo "<td class='text-center text-bold belum_retensi' style='font-size:18px;' colspan='$colSpan'> <i class='fa fa-warning text-red blink'></i> <r>BELUM ADA PEMBAYARAN RETENSI</r> <i class='fa fa-warning text-red blink'></i> <br>TGL AKHIR RETENSI: <br>$akhir_retensi <br><i>($umur_akhir_retensi)</i><br><r>$nilai_retensi (Incl.PPN)</r></td>";
                        echo "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                }
                else{
                    echo "<tbody>";
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN RETENSI / GARANSI <i class='glyphicon glyphicon-check text-success'></i></td>";
                    echo "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                    echo "</tr>";
                    echo "</tbody>";
                }
                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($retensiprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th>$totalSubBobot_f%</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                echo "<div style='padding: 10px;' id='check_lanjut_closing' class='panel bg-red hiddsen'>";

                $msgNote = "Checklist Serah Terima Akhir (FINAL)";
                $checklistNoteEncode = blobEncode($msgNote);
                $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";

                echo "<div class='text-rightx'>";
                echo "<label for='checklist_trx'>";
                echo "<input id='checklist_trx' type='checkbox' $checked onchange=\"check_status_project();document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode&state='+$(this).is(':checked');\">";
                echo "&nbsp; <span style='font-size: 20px;'>$msgNote</span>";
                echo "</label>";
                echo "</div>";

                echo "<div style='margin-top: 4px;'>";
                echo "<div class='text-bold fa-2x'>NOTES:</div>";
                echo "<div style='font-size: 16px;'>1. Pastikan retensi telah habis masa perawatannya, dan telah diterima sisa pembayaran (jika ada).</div>";
                echo "</div>";

                echo "</div>";

                echo "
                        <script>
                            function reCheckValidation(data=null){
                                var err = 0;
                                var done = 0;
                                var valCheckArr = $('.validationCheck');
                                jQuery.each(valCheckArr, function(a, b){
                                    if( !$(b).is(':checked') ){
                                        err += 1;
                                    }
                                    else{
                                        done += 1;
                                    }
                                });
                                $('#approvalButton').prop('disabled', true);
                                if( !$('#checklist_trx').is(':checked') ){
                                    err += 1;
                                }
                                if( !err > 0 ){
                                    $('#approvalButton').prop('disabled', false);
                                    console.log('!err>0');
                                    console.log('tombol Approve dibuka ');
                                }
                                else{
                                    if( $('#checklist_trx').is(':checked') ){
                                        var note_tambahan = data != null ? \"<br><br><r>NOTES:</r><br>$modePreview\" + data : ''
                                        setTimeout(function(){
                                            swal('SILAHKAN PERIKSA KEMBALI', 'PROJECT BELUM BISA DI TUTUP KARENA MASIH ADA YANG BELUM ANDA SELESAIKAN'+note_tambahan, 'info');
                                            $('#checklist_trx').prop('checked', false);
                                        },500);
                                    }
                                }
                            }
                            $('.validationCheck').on('change', function(){
                                reCheckValidation();
                            })
                            setTimeout(function(){
                                top.reCheckValidation();
                            },500);
                            function check_status_project(){

                                var retensi_belum_selesai = $(\".belum_retensi\").length;
                                var tgl_belum_retensi = $(\".tgl_belum_retensi\").length;
                                var pym_belum_retensi = $(\".pym_belum_retensi\").length;

                                var announcement = '';
                                if(pym_belum_retensi==0 && retensi_belum_selesai ==0 && tgl_belum_retensi==0){
                                    //top.$('#approvalButton').prop('disabled', false);
                                }
                                else{
                                    if(pym_belum_retensi || retensi_belum_selesai || tgl_belum_retensi){
                                        announcement += ''
                                    }
                                    
                                    if(retensi_belum_selesai){
                                        announcement += \"<div class='text-left'>- ada (\"+retensi_belum_selesai+\") Retensi belum lunas/dibayar sebagian.</div>\";
                                    }
                                    
                                    if(tgl_belum_retensi){
                                        announcement += \"<div class='text-left'>- retensi belum habis masa garansinya..</div>\";
                                    }
                                    if(pym_belum_retensi){
                                        announcement += \"<div class='text-left'>- belum ada penerimaan retensi atau dibayar sebagian.</div>\";
                                    }
                                    if(pym_belum_retensi || retensi_belum_selesai || tgl_belum_retensi){
                                        announcement += ''
                                    }
                                    //swal('ada yang perlu di cek..!!', announcement, 'info');
                                }
                                reCheckValidation(announcement);
                            }

                            $(document).ready(function(){
                                $('#check_lanjut_closing').removeClass('hidden');
                                $('#approvalButton').prop('disabled', true);
                            })

                        </script>

                ";
            }
            elseif( isset($tasklist) && !empty($tasklist) && $modePreview == "final_close_project" ){

                echo "<h3 id='showTasklist'>*Status Keseluruhan Project <r>(".$mainValues['projectName'].")</r> <span id='reloadTasklistModal' onclick=\"top.open_holdon();top.$('#result').load('$btnReloadTaskList#showTasklist');\" class='pull-right btn btn-xs btn-danger'><i class='fa fa-refresh'></i> REFRESH</span></h3>";
                echo "<h4>NILAI PROJECT: " . number_format($mainValues['grand_total_ui']) . " (Excl.PPN)</h4>";
                echo "<h4>PPN: " . number_format($mainValues['grand_total_ui']*0.11). "</h4>";
                echo "<h4>NILAI PROJECT: " . number_format($mainValues['grand_total_ui']*1.11). " (Incl.PPN)</h4>";

                /*
                 * UANG MUKA
                 */
                echo "<h4 class='text-bold text-blue'>DP / UANG MUKA $uangmukaDisplay</h4>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($uangmukaprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";

                $colSpan = count($uangmukaprojectHeader)+1;
                $nilai_dp = $uangmukaData[0]["harga"]*1;
                $nilai_project = ($mainValues['grand_total_ui']*1.11);

                //arrPrint($uangmukaData);
                if($uangmukaCheckSetting>0){
                    echo "<tbody>";
                    if($uangmukaproject){
                        $totalSubProgress = 0;
                        $totalSubBobot = 0;
                        $tsNo = 0;
                        $total_um = array();
                        $total_ostd = array();
                        $um_terbayar = 0;
                        $arr_um_terbayar = [];
                        foreach($uangmukaproject as $num => $row){

                            $tsk_id = $row->id;
                            $produk_id = $row->produk_id;
                            $gudang_id = $row->gudang_id;
                            $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                            $totalSubBobot += $row->persen_sub*1;
                            $tsNo++;

                            if(!isset($total_ostd['sisa'])){
                                $total_ostd['sisa'] = 0;
                            }
                            $total_ostd['sisa'] += ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                            $um_terbayar += ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                            $arr_um_terbayar[] = ($row->dpp_ppn*1) + ($row->ppn_sisa*1);
                            if(!isset($total_ostd['tagihan'])){
                                $total_ostd['tagihan'] = 0;
                            }
                            $total_ostd['tagihan'] += $row->tagihan;

                            echo "<tr class='gdi_$gudang_id'>";
                            echo "<td>$tsNo</td>";
                            foreach($uangmukaprojectHeader as $kk => $label){
                                $val_ = $row->$kk;
                                switch($kk){
                                    case "terbayar_persen":
                                        $dpp_ppn_nppn = $row->dpp_ppn + $row->ppn_sisa;
                                        $persen_terbayar = $dpp_ppn_nppn>0?(($dpp_ppn_nppn)/$nilai_project)*100:0;
                                        $val_ = number_format($persen_terbayar,0) . "";
                                        break;
                                    case "sisa_persen":
                                        $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                        $val_ = number_format($sisa_persen,0);
                                        break;
                                    case "cek":
                                        $nilai_tagihan = $row->tagihan;
                                        $val_ = "<span nilai_dp='$nilai_dp' nilai_tagihan_um='$nilai_tagihan' class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> UM sudah diterima</span>";
                                        $val_ .= "<span class='hidden'><input checked name='uangmuka' class='financeCheck' type='checkbox'></span>";
                                        break;
                                    case "dpp_ppn":
                                        $val_ = number_format($nilai_dp,0);
                                        break;
                                    case "terbayar":
                                    case "tagihan":
                                        $terima_pd = $row->tagihan + $row->ppn;
                                        $val_ = number_format($terima_pd,0);
                                        break;
                                    case "sisa":
                                        $sisa_nppn = $nilai_project - ($row->$kk + $row->ppn_sisa);
//                                        $val_ = number_format($sisa_nppn,0); //dimatikan dulu
                                        $val_ = 0;
                                        break;
                                }
                                echo "<td line='".__LINE__."' in_$kk >$val_</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    else{
                        echo "<tr class=''>";
                        echo "<td class='text-center text-bold text-red' style='font-size:18px;' colspan='$colSpan'><i class='fa fa-warning blink'></i> BELUM MENERIMA UANG MUKA <i class='fa fa-warning blink'></i><div>".number_format($nilai_dp)." (Incl.PPN)</div><div class='hidden'>silahkan buat uang muka project <a href='javascript:void(0)' onclick=\"top.window.open('https://google.com', '_blank', rel='noopener noreferrer');\">disini</a></div></td>";
                        echo "<span class='hidden'><input name='uangmuka' class='financeCheck' type='checkbox'></span>";
                        echo "<span class='hidden'><input name='' class='financeCheck' type='checkbox'>".json_encode($uangmukaCheckSetting)."</span>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                }
                else{
                    echo "<tbody>";
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN UANG MUKA <i class='glyphicon glyphicon-check text-success'></i></td>";
                    echo "<span class='hidden'><input checked name='uangmuka' class='financeCheck' type='checkbox'></span>";
                    echo "</tr>";
                    echo "</tbody>";
                }

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";

                foreach($uangmukaprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th line='".__LINE__."' in_$kk>-</th>";
                            break;
                        case "tagihan":
                            $gTotalUmTxt= "";
                            $gTotalUm = number_format($total_ostd['sisa']*1);
                            if( ($total_ostd['sisa']*1) < ($nilai_dp*1) ){
                                $gTotalUmTxt .= "
                                    <span gTotalUm='$gTotalUm' nilai_dp='$nilai_dp' class='hidden'>
                                        <input name='uangmuka' class='financeCheck' type='checkbox'>
                                    </span>";
                            }
                            $valTh .= "<th line='".__LINE__."' in_$kk>$gTotalUmTxt</th>";
//                            $um_terbayar = $total_ostd['sisa']*1;
                            break;
                        case "sisa":
//                            $gTotalUm = number_format($nilai_dp-$total_ostd[$kk]*1); //di nolkan dulu
                            $gTotalUm = 0;
                            $valTh .= "<th line='".__LINE__."' in_$kk>$gTotalUm</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th line='".__LINE__."' in_$kk>$totalSubBobot_f%</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th line='".__LINE__."' in_$kk>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                $termin_terbayar = 0;

                /*
                 * TERMIN
                 */
                echo "<h4 class='text-bold text-blue'>TERMIN $terminDisplay</h4>";
                echo "<div class=''>saldo termin = nilai yang belum ditagihkan ke-konsumen.</div>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($terminprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $totalSubProgress = 0;
                $totalSubBobot = 0;
                $tsNo = 0;
                foreach($terminproject as $num => $row){
                    $tsk_id = $row->id;
                    $produk_id = $row->produk_id;
                    $gudang_id = $row->gudang_id;
                    $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
//                    $totalSubProgress += $row->
                    $totalSubBobot += $row->persen_sub*1;
                    $tsNo++;
                    echo "<tr payment_source_id='$tsk_id' class='gdi_$gudang_id'>";
                    echo "<td>$tsNo</td>";
                    foreach($terminprojectHeader as $kk => $label){
//                        $val_ = $row->$kk;
                        switch($kk){
                            case "terbayar_persen":
                                $persen_terbayar = $row->terbayar > 0 ? (round($row->terbayar)/$row->tagihan)*100 : 0;
                                $val_ = number_format(round($persen_terbayar),0);
                                break;
                            case "sisa_persen":
                                $sisa_persen = floor($row->sisa) > 0 ? (floor($row->sisa)/$row->tagihan)*100 : 0;
                                $val_ = number_format(floor($sisa_persen),0);
                                break;
                            case "cek":
                                if( floor($row->sisa) < 1000){
                                    $val_ = "<span class='btn btn-xs btn-success'>tagihan sudah diterbitkan <i class='glyphicon glyphicon-check'></i></span>";
                                    $val_ .= "<span class='hidden'><input checked name='termin' class='financeCheck' type='checkbox'></span>";
                                    $totalSubProgress += $row->persen_sub*1;
                                }
                                else if( floor($row->sisa) > 1000 && floor($row->sisa) < floor($row->tagihan) ){
                                    $val_ = "<span class='btn btn-xs bg-orange belum_termin' disabled><i class='fa fa-warning'></i> Dibayar sebagian <i class='fa fa-warning'></i> </span>";
                                    $val_ .= "<span class='hidden'><input name='termin' class='financeCheck' type='checkbox'></span>";
                                }
                                else{
                                    $val_ = "<span class='btn btn-xs btn-danger belum_termin'><i class='fa fa-warning blink'></i>  belum ada tagihan yang dibuat <i class='fa fa-warning blink'></i> </span>";
                                    $val_ .= "<span class='hidden'><input name='termin' class='financeCheck' type='checkbox'></span>";
                                }
                                break;
                            case "tagihan":
                                $val_ = number_format(floor($row->$kk*1.11),0);
                                break;
                            case "terbayar":
                                $val_ = number_format(floor($row->$kk*1.11),0);
                                $termin_terbayar += $row->$kk;
                                break;
                            case "sisa":
                                $val_ = number_format(floor($row->$kk*1.11),0);
                                break;
                            default:
                                if($kk=="dtime"){
                                    $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                }
                                else{
                                    $val_ = $row->$kk;
                                }
                                break;
                        }
                        echo "<td>$val_</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($terminprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th>$totalSubBobot_f%</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                /*
                 * TASKLIST
                 */
                echo "<h4 class='text-bold text-blue'>WORK-ORDER / TASKLIST</h4>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($tasklistHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $totalSubProgress = 0;
                $totalSubBobot = 0;
                $tsNo = 0;
                foreach($tasklist as $num => $row){
                    $tsk_id = $row->id;
                    $produk_id = $row->produk_id;
                    $gudang_id = $row->gudang_id;
                    $post_biaya_id = $row->post_biaya_id;
                    $post_return_id = $row->post_return_id;
                    $checkBiaya = isset($row->biaya) ? count($row->biaya) : 0;
                    $checkLogReturn = isset($row->log_return) ? $row->log_return : 0;
                    $ada_log_return_supplies = isset($checkLogReturn[0]['supplies']) ? 1 : 0;
                    $ada_log_return_produk = isset($checkLogReturn[0]['produk']) ? 1 : 0;
                    $getTransaksiHis = isset($row->his_trx) ? $row->his_trx : array();
                    $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();

                    $totalSubBobot += $row->persen_sub*1;
                    $total_pembayaran = array();
                    $tsNo++;
                    echo "<tr class='gdi_$gudang_id'>";
                    echo "<td>$tsNo</td>";
                    foreach($tasklistHeader as $kk => $label){
                        $val_ = $row->$kk;
                        $bb_box = "";
                        if( count($arrBB) > 0 ){
                            $noBB = 0;
                            $bb_box .= "<span data-id='$gudang_id' style='margin-left: 3px;' class='btn-tooltip btn btn-xs bg-violet unused_stok'>ada stok produk</span>";
                            $bb_box .= "<span style='margin-left: 3px;' data-id='create-$tsk_id-$produk_id' onclick='fnTasklist.create(this)' id='' class='btn btn-xs btn-info'><i class='fa fa-send'></i> View Progress</span>";
                        }
                        else{
                            $bb_box .= "<span style='margin-left: 3px;' class='btn btn-xs bg-olive' disabled>belum distribusi</span>";
                        }
                        switch($kk){
                            case "progress_nama":
                                if($row->progress_id == 3){
                                    $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> sudah QC</span>";
                                    $val_ .= "<span class='hidden'><input checked name='tasklist' class='validationCheck' type='checkbox'></span>";
                                    $totalSubProgress += $row->persen_sub*1;
                                }
                                else if($row->progress_id == 2 && $row->progress_percent == 100){
                                    $val_ = "<span class='btn btn-xs bg-orange belum_qc' disabled><i class='fa fa-warning blink'></i> belum QC</span>";
                                    $val_ .= "<span style='margin-left: 3px;' data-id='create-$tsk_id-$produk_id' onclickx='fnTasklist.create(this)' id='' class='btn btn-xs btn-info'><i class='fa fa-send'></i> Silahkan Lakukan QC</span>";
                                    $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                                }
                                else{
                                    if($row->progress_id == 2 && $row->progress_percent > 0 && $row->progress_percent < 100){
                                        $val_ = "<span class='btn btn-xs btn-danger'>dikerjakan parsial</span>";
                                        $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                                    }
                                    else{
                                        $val_ = "<span class='btn btn-xs btn-danger'>belum dikerjakan</span>";
                                        $val_ .= "<span class='hidden'><input name='tasklist' class='validationCheck' type='checkbox'></span>";
                                    }
                                    if($bb_box!=""){
                                        $val_ .= $bb_box;
                                    }
                                }
                                break;
                            case "progress_percent":
                                $val_ = $val_ . "%";
                                break;
                            case "persen_sub":
                                $val_ = number_format($val_, 2) . "%";
                                break;
                            case "nilai_sub_fase":
                                $val_ = number_format($row->$kk,0);
                                if(!isset($total_pembayaran[$kk])){
                                    $total_pembayaran[$kk] = 0;
                                }
                                $total_pembayaran[$kk] += $row->$kk > 1000 ? $row->$kk*1 : 0;
                                break;
                            default:
                                if($kk=="dtime"){
                                    $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                }
                                else{
                                    $val_ = $row->$kk;
                                }
                                break;
                        }
                        echo "<td>$val_</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($tasklistHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $taskStatusALl = "";
                            if( $totalSubBobot < 99 ){
                                $taskStatusALl .= "<span class='hidden'><input class='project_persen'></span>";
                                $taskStatusALl .= "<span class='hidden'><input name='project_persen' class='financeCheck' type='checkbox'></span>";
                            }
                            $valTh .= "<th>";
                            $valTh .= "$totalSubBobot_f%";
                            $valTh .= $taskStatusALl;
                            $valTh .= "</th>";
                            break;
                        case "nilai_sub_fase":
                            $totalPembayaran = number_format($total_pembayaran[$kk]);
                            $valTh .= "<th>$totalPembayaran</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                /*
                 * PENERIMAAN PEMBAYARAN DARI TERMIN
                 */
                $ar_terbayar = 0;
                echo "<h4 class='text-bold text-blue'>PENERIMAAN PEMBAYARAN</h4>";
//                echo "<div class='text-bold text-red'><i>konsumen belum menyelesaikan pembayaran</i></div>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($terimabayarprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                $colSpan = count($terimabayarprojectHeader)+1;
                if(!empty($terimabayarproject)){
                    echo "<tbody>";
                    $totalSubProgress = 0;
                    $totalSubBobot = 0;
                    $tsNo = 0;
                    $total = array();
                    foreach($terimabayarproject as $num => $row){
                        $tsk_id = $row->id;
                        $produk_id = $row->produk_id;
                        $gudang_id = $row->gudang_id;
                        $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                        $totalSubBobot += $row->persen_sub*1;
                        $tsNo++;
                        echo "<tr payment_source_id='$tsk_id' class='gdi_$gudang_id'>";
                        echo "<td>$tsNo</td>";
                        foreach($terimabayarprojectHeader as $kk => $label){
                            $val_ = $row->$kk;
                            switch($kk){
                                case "terbayar_persen":
                                    $persen_terbayar = $row->terbayar > 0 ? ($row->terbayar/$row->tagihan)*100 : 0;
                                    $val_ = number_format($persen_terbayar,0);
                                    break;
                                case "sisa_persen":
                                    $sisa_persen = $row->sisa > 100 ? ($row->sisa/$row->tagihan)*100 : 0;
                                    $val_ = number_format($sisa_persen,0);
                                    break;
                                case "cek":
                                    if($row->sisa < 100 && $row->returned > 100 ){
                                        $val_ = "<span class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-trash'></i> penerimaan dibatalkan*</span>";
                                        $val_ .= "<span class='hidden'><input checked name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                    }
                                    else if($row->sisa < 100 ){
                                        $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> sudah lunas</span>";
                                        $val_ .= "<span class='hidden'><input checked name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                        $totalSubProgress += $row->persen_sub*1;
                                    }
                                    else if($row->sisa < 100 && $row->sisa < $row->tagihan ){
                                        $val_ = "<span class='btn btn-xs bg-orange penerimaan_belum_lunas' disabled><i class='fa fa-warning blink'></i> Dibayar sebagian</span>";
                                        $val_ .= "<span class='hidden'><input name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                    }
                                    else{
                                        $val_ = "<span class='btn btn-xs btn-danger penerimaan_belum_lunas'>belum ada pembayaran</span>";
                                        $val_ .= "<span class='hidden'><input name='pembayaran' class='financeCheck' type='checkbox'></span>";
                                    }
                                    break;
                                case "tagihan":
                                    $val_ = number_format($row->$kk,0);
                                    if(!isset($total[$kk])){
                                        $total[$kk] = 0;
                                    }
                                    $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                    break;
                                case "returned":
                                    $val_ = number_format($row->$kk,0);
                                    if(!isset($total[$kk])){
                                        $total[$kk] = 0;
                                    }
                                    $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                    break;
                                case "terbayar":
                                    $val_ = number_format($row->$kk,0);
                                    if(!isset($total[$kk])){
                                        $total[$kk] = 0;
                                    }
                                    $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                    $ar_terbayar += $row->$kk;
                                    break;
                                case "sisa":
                                    $val_ = number_format($row->$kk,0);
                                    if(!isset($total[$kk])){
                                        $total[$kk] = 0;
                                    }
                                    $total[$kk] += $row->$kk > 100 ? $row->$kk*1 : 0;
                                    break;
                                default:
                                    if($kk=="dtime"){
                                        $val_ = date("Y-m-d H:i", strtotime($row->$kk));
                                    }
                                    else{
                                        $val_ = $row->$kk;
                                    }
                                    break;
                            }
                            echo "<td colom='$kk'>$val_</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";

                }
                else{
                    echo "<tbody>";
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold text-red blink' style='font-size:18px;' colspan='$colSpan'><i class='fa fa-warning text-danger'></i> BELUM ADA PEMBAYARAN MASUK UNTUK PROJECT INI <i class='fa fa-warning text-danger'></i></td>";
                    echo "<span class='hidden'><input name='uangmuka' class='financeCheck' type='checkbox'></span>";
                    echo "</tr>";
                    echo "</tbody>";
                }

                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($terimabayarprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th>$totalSubBobot_f%</th>";
                            break;
                        case "tagihan":
                            $total_tagihan = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                            $valTh .= "<th jenis='$kk' >$total_tagihan</th>";
                            break;
                        case "terbayar":
                            $total_terbayar = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                            $valTh .= "<th jenis='$kk' >$total_terbayar</th>";
                            break;
                        case "returned":
                            $total_retur = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                            $valTh .= "<th jenis='$kk' >$total_retur</th>";
                            break;
                        case "sisa":
                            $total_tagihan = $total[$kk]*1>0 ? number_format($total[$kk]*1) : 0;
                            $valTh .= "<th>$total_tagihan</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

//                echo "DP TERBAYAR: " . json_encode($arr_um_terbayar) . "<br>";
//                echo "TERMIN TERBAYAR: " . number_format($termin_terbayar) . "<br>";
//                echo "DP TERBAYAR: " . number_format($um_terbayar) . "<br>";
//                echo "A/R TERBAYAR: " . number_format($ar_terbayar) . "<br>";
//                echo "TOTAL: " . number_format($ar_terbayar+$um_terbayar) . " (Incl.PPN)<br>";
//                echo "PROJECT: " . number_format($mainValues['grand_total_ui']) . " (Belum PPN) || " . number_format($mainValues['grand_total_ui']*1.11) . " (Incl.PPN)<br>";
                $check_kekurangan = ($mainValues['grand_total_ui']*1.11) - ($ar_terbayar+$um_terbayar);
//                echo "KEKURANGAN: " . number_format($check_kekurangan) . " (Belum PPN) || " . number_format($check_kekurangan*1.11) . " (Incl.PPN)<br>";

                $grand_total_ui = (string)$mainValues['grand_total_ui'];
                $um_terbayar = (string)$um_terbayar;

                $check_kekurangan = bcsub(bcmul($grand_total_ui, '1.11', 0), $um_terbayar, 0);
                $check_kekurangan = bcsub(bcmul($check_kekurangan, '1', 0), $ar_terbayar, 0);

                echo "check: " . $check_kekurangan . "<br>";
                echo "grand_total_ui: " . ($mainValues['grand_total_ui']*1.11) . "<br>";
                echo "ar_terbayar: " . $ar_terbayar . "<br>";
                echo "um_terbayar: " . $um_terbayar . "<br>";


                //=======================================================
                //=======================================================

                /*
                 * RETENSI/GARANSI
                 */
                echo "<h4 class='text-bold text-blue'>RETENSI/GARANSI $retensiDisplay</h4>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                foreach($retensiprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";
                $colSpan = count($retensiprojectHeader)+1;
                //$retensiCheckSetting=array();
                if($retensiCheckSetting>0){
                    echo "<tbody>";
                    if($retensiproject){
                        $totalSubProgress = 0;
                        $totalSubBobot = 0;
                        $tsNo = 0;
                        foreach($retensiproject as $num => $row){
                            $tsk_id = $row->id;
                            $produk_id = $row->produk_id;
                            $gudang_id = $row->gudang_id;
                            $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                            $totalSubBobot += $row->persen_sub*1;

                            $tsNo++;
                            echo "<tr class='gdi_$gudang_id'>";
                            echo "<td>$tsNo</td>";
                            foreach($retensiprojectHeader as $kk => $label){
                                $val_ = $row->$kk;
                                switch($kk){
                                    case "terbayar_persen":
                                        $persen_terbayar = $row->terbayar > 0 ? ($row->terbayar/$row->tagihan)*100 : 0;
                                        $val_ = number_format($persen_terbayar,0);
                                        break;
                                    case "sisa_persen":
                                        $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                        $val_ = number_format($sisa_persen,0);
                                        break;
                                    case "cek":
                                        $nilai_retensi = $retensiData[0]["harga"];
                                        $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("+2 day"));
                                        $tanggal_hari_ini = date("Y-m-d");

                                        if ($row->sisa < 1000) {
                                            if ($akhir_retensi > $tanggal_hari_ini) {
                                                // Sudah bayar, tapi belum habis masa garansi
                                                $val_ = "<span class='btn btn-xs btn-primary belum_retensi'><i class='fa fa-check'></i> Sudah dibayar - Garansi belum habis</span>";
                                                $val_ .= "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                                            }
                                            else{
                                                // Sudah bayar dan garansi sudah habis
                                                $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> Sudah lunas & garansi selesai</span>";
                                                $val_ .= "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                                            }
                                        }
                                        elseif ($row->sisa > 1000 && $row->sisa < $row->tagihan) {
                                            // Dibayar sebagian
                                            $val_ = "<span sisa='".$row->sisa."' class='btn btn-xs bg-orange belum_retensi' disabled><i class='fa fa-warning blink'></i> Dibayar sebagian</span>";
                                            $val_ .= "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                                        }
                                        else {
                                            // Belum ada pembayaran
                                            $val_ = "<span class='btn btn-xs btn-danger belum_retensi'>Belum ada pembayaran</span>";
                                            $val_ .= "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                                        }
                                        break;
                                    case "tgl_berakhir_retensi":
                                        $dateToDebug = date("Y-m-d", strtotime("+2 month 5 day"));
                                        $nilai_retensi = $retensiData[0]["harga"];

                                        $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : 999999999;

                                        $text_belum_setting = "";

                                        if($akhir_retensi!=999999999){

                                        }
                                        else{
                                            $akhir_retensi = date("Y-m-d", strtotime("+2 day"));
                                            $text_belum_setting = "tanggal berakhir retensi belum disetting";
                                        }

                                        $tanggal_hari_ini = date("Y-m-d");
                                        $umur_akhir_retensi = createTimeDescSoon($akhir_retensi);

                                        $actionTarget = "
                                            top.BootstrapDialog.show({
                                                title: 'Retensi Editor',
                                                message: " . 'top.$' . "('<div></div>').load('" . MODUL_PATH . "FollowUp/showRetensiDetails/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "/" . $this->uri->segment(7) . "/" . $trIdSO . "?rawBuilderURL=$rawBuilderURL'),
                                                size: top.BootstrapDialog.SIZE_WIDE,
                                                type: top.BootstrapDialog.TYPE_DEFAULT,
                                                draggable: true,
                                                closable: true
                                            });";

                                        //nanti dibuatkan hak akses yaa
                                        $editRetensi = "<span onclick=\"$actionTarget\" class='btn btn-xs btn-info'><i class='fa fa-pencil'></i> edit</span>";

                                        if ($akhir_retensi > $tanggal_hari_ini) {
                                            // masa retensi belum habis
                                            $val_ = "<span class='btn btn-xs btn-warning tgl_belum_retensi'><i class='fa fa-clock-o'></i> $akhir_retensi </span>$editRetensi<br>masa garansi belum habis<br>$umur_akhir_retensi";
                                            $val_ .= "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                                        }
                                        else {
                                            // masa retensi sudah habis
                                            $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> $akhir_retensi </span>$editRetensi<br>$umur_akhir_retensi";
                                            $val_ .= "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                                        }
                                        break;
                                    case "tagihan":
                                        $val_ = number_format($row->$kk,0);
                                        break;
                                    case "terbayar":
                                        $val_ = number_format($row->$kk,0);
                                        break;
                                    case "sisa":
                                        $val_ = number_format($row->$kk,0);
                                        break;
                                }
                                echo "<td>$val_</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    else{
                        $akhir_retensi = isset($retensiData[0]["tgl_akhir_garansi"]) && $retensiData[0]["tgl_akhir_garansi"] != "" ? $retensiData[0]["tgl_akhir_garansi"] : date("Y-m-d", strtotime("2 week"));
                        $umur_akhir_retensi = createTimeDescSoon($akhir_retensi);
                        $nilai_retensi = number_format($retensiData[0]["harga"]*1);
                        echo "<tr class=''>";
                        echo "<td class='text-center text-bold belum_retensi' style='font-size:18px;' colspan='$colSpan'> <i class='fa fa-warning text-red blink'></i> <r>BELUM ADA PEMBAYARAN RETENSI</r> <i class='fa fa-warning text-red blink'></i> <br>TGL AKHIR RETENSI: <br>$akhir_retensi <br><i>($umur_akhir_retensi)</i><br><r>$nilai_retensi (Incl.PPN)</r></td>";
                        echo "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                }
            else{
                    echo "<tbody>";
                    echo "<tr class=''>";
                    echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN RETENSI / GARANSI <i class='glyphicon glyphicon-check text-success'></i></td>";
                    echo "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                    echo "</tr>";
                    echo "</tbody>";
                }
                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                foreach($retensiprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th>$totalSubBobot_f%</th>";
                            break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                echo "<div style='padding: 10px;' id='check_lanjut_closing' class='panel bg-red hiddsen'>";

                $msgNote = "Checklist Serah Terima Akhir (FINAL)";
                $checklistNoteEncode = blobEncode($msgNote);
                $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";

                echo "<div class='text-rightx'>";
                echo "<label for='checklist_trx'>";
                echo "<input id='checklist_trx' type='checkbox' $checked onchange=\"check_status_project();document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode&state='+$(this).is(':checked');\">";
                echo "&nbsp; <span style='font-size: 20px;'>$msgNote</span>";
                echo "</label>";
                echo "</div>";

                echo "<div style='margin-top: 4px;'>";
                echo "<div class='text-bold fa-2x'>NOTES:</div>";
                echo "<div style='font-size: 16px;'>1. Pastikan retensi telah habis masa perawatannya, dan telah diterima sisa pembayaran (jika ada).</div>";
                echo "</div>";

                echo "</div>";

                echo "
                        <script>
                            function reCheckValidation(data=null){
                                var err = 0;
                                var done = 0;
                                var valCheckArr = $('.validationCheck');
                                jQuery.each(valCheckArr, function(a, b){
                                    if( !$(b).is(':checked') ){
                                        err += 1;
                                    }
                                    else{
                                        done += 1;
                                    }
                                });
                                $('#approvalButton').prop('disabled', true);
                                if( !$('#checklist_trx').is(':checked') ){
                                    err += 1;
                                }
                                if( !err > 0 ){
                                    $('#approvalButton').prop('disabled', false);
                                    console.log('!err>0');
                                    console.log('tombol Approve dibuka ');
                                }
                                else{
                                    if( $('#checklist_trx').is(':checked') ){
                                        var note_tambahan = data != null ? \"<br><br><r>NOTES:</r><br>$modePreview\" + data : ''
                                        setTimeout(function(){
                                            swal('SILAHKAN PERIKSA KEMBALI', 'PROJECT BELUM BISA DI TUTUP KARENA MASIH ADA YANG BELUM ANDA SELESAIKAN'+note_tambahan, 'info');
                                            $('#checklist_trx').prop('checked', false);
                                        },500);
                                    }
                                }
                            }
                            $('.validationCheck').on('change', function(){
                                reCheckValidation();
                            })
                            setTimeout(function(){
                                top.reCheckValidation();
                            },500);
                            function check_status_project(){

                                var retensi_belum_selesai = $(\".belum_retensi\").length;
                                var tgl_belum_retensi = $(\".tgl_belum_retensi\").length;
                                var pym_belum_retensi = $(\".pym_belum_retensi\").length;

                                var announcement = '';
                                if(pym_belum_retensi==0 && retensi_belum_selesai ==0 && tgl_belum_retensi==0){
                                    //top.$('#approvalButton').prop('disabled', false);
                                }
                                else{
                                    if(pym_belum_retensi || retensi_belum_selesai || tgl_belum_retensi){
                                        announcement += ''
                                    }
                                    
                                    if(retensi_belum_selesai){
                                        announcement += \"<div class='text-left'>- ada (\"+retensi_belum_selesai+\") Retensi belum lunas/dibayar sebagian.</div>\";
                                    }
                                    
                                    if(tgl_belum_retensi){
                                        announcement += \"<div class='text-left'>- retensi belum habis masa garansinya..</div>\";
                                    }
                                    if(pym_belum_retensi){
                                        announcement += \"<div class='text-left'>- belum ada penerimaan retensi atau dibayar sebagian.</div>\";
                                    }
                                    if(pym_belum_retensi || retensi_belum_selesai || tgl_belum_retensi){
                                        announcement += ''
                                    }
                                    //swal('ada yang perlu di cek..!!', announcement, 'info');
                                }
                                reCheckValidation(announcement);
                            }

                            $(document).ready(function(){
                                $('#check_lanjut_closing').removeClass('hidden');
                                $('#approvalButton').prop('disabled', true);
                            })

                        </script>

                ";
            }
            else{

                echo "<h2 id='showTasklist'>Status Keseluruhan Project <r>(".$mainValues['projectName'].")</r> <span id='reloadTasklistModal' onclick=\"top.open_holdon();top.$('#result').load('$btnReloadTaskList#showTasklist');\" class='pull-right btn btn-xs btn-danger'><i class='fa fa-refresh'></i> REFRESH</span></h2>";

                echo "<h3>NILAI PROJECT (Excl.PPN): " . number_format($mainValues['grand_total_ui']) . "</h3>";
                echo "<h3>PPN: " . number_format($mainValues['grand_total_ui']*0.11). "</h3>";
                echo "<h3>NILAI PROJECT (Incl.PPN): " . number_format($mainValues['grand_total_ui']*1.11). "</h3>";

//                echo "modePreview: " . $modePreview . "<br>";
//                echo "start_project: " . $start_project . "  |||  close_project: $close_project";

                if($modePreview == "start_project"){
                    $nilai_dp = $uangmukaData[0]["harga"]*1;
                    $um_text = $nilai_dp > 0 ? "&nbsp;<r>(".number_format($nilai_dp).")</r>" : "";
                /*
                     * UANG MUKA
                 */
                    echo "<h4 class='text-bold text-blue'>*DP / UANG MUKA $uangmukaDisplay</h4>";
                echo "<table class='table dataTable compact table-bordered table-hover'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th key='no'>No.</th>";
                    foreach($uangmukaprojectHeader as $ky => $rhead){
                    echo "<th key='$ky'>$rhead</th>";
                }
                echo "</tr>";
                echo "</thead>";

                    $colSpan = count($uangmukaprojectHeader)+1;

                    arrPrint($uangmukaproject);
                    if($uangmukaCheckSetting>0){
                        echo "<tbody>";
                        if($uangmukaproject){
                            $totalSubProgress = 0;
                            $totalSubBobot = 0;
                            $tsNo = 0;
                            $total_um = array();
                            $total_ostd = array();
                            foreach($uangmukaproject as $num => $row){
                                $tsk_id = $row->id;
                                $produk_id = $row->produk_id;
                                $gudang_id = $row->gudang_id;
                                $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                                $totalSubBobot += $row->persen_sub*1;
                                $nilai_return = $row->returned*1;

                                if(!isset($total_ostd['sisa'])){
                                    $total_ostd['sisa'] = 0;
                                }
                                $total_ostd['sisa'] += $row->sisa + $row->ppn;

                                $bg_color_return = $nilai_return>0 ? "bg-red" : "";

                                $tsNo++;
                                echo "<tr class='gdi_$gudang_id $bg_color_return'>";
                                echo "<td>$tsNo</td>";
                                foreach($uangmukaprojectHeader as $kk => $label){
                                    $val_ = $row->$kk;
                                    switch($kk){
                                        case "terbayar_persen":
                                            $persen_terbayar = $row->returned > 0 ? 0 :( ($row->tagihan*1.11) /$nilai_dp)*100;
                                            $val_ = number_format($persen_terbayar,0);
                                            break;
                                        case "sisa_persen":
                                            $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                            $val_ = number_format($sisa_persen,0);
                                            break;
                                        case "cek":
                                            $nilai_sisa = $row->tagihan - $row->sisa;
                                            $nilai_tagihan = $row->tagihan;
                                            $nilai_terbayar = $row->terbayar;
                                            if($nilai_sisa==0){
                                                $val_ = "<span nilai_dp='$nilai_dp' nilai_tagihan_um='$nilai_tagihan' class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> UM sudah diterima</span>";
                                                $val_ .= "<span class='hidden'><input checked name='uangmuka' class='validationCheck' type='checkbox'></span>";
                                            }
                                            elseif ($nilai_terbayar > 0){
                                                $val_ = "<span nilai_dp='$nilai_dp' nilai_tagihan_um='$nilai_tagihan' class='btn btn-xs btn-warning'><i class='glyphicon glyphicon-check'></i> UM sudah diterima sebagian</span>";
                                                $val_ .= "<span class='hidden'><input name='uangmuka' class='validationCheck' type='checkbox'></span>";
                                            }
                                            else {
                                                if( $nilai_return > 0 ){
                                                    $val_ = "<span returned='$nilai_return' nilai_dp='$nilai_dp' nilai_tagihan_um='$nilai_tagihan' class='btn btn-xs btn-warning'><i class='glyphicon glyphicon-check'></i> UM dibatalkan</span>";
                                                }
                                                else{
                                                    $val_ = "<span nilai_dp='$nilai_dp' nilai_tagihan_um='$nilai_tagihan' class='btn btn-xs btn-danger'><i class='glyphicon glyphicon-check'></i> UM belum diterima</span>";
                                                    $val_ .= "<span class='hidden'><input name='uangmuka' class='validationCheck' type='checkbox'></span>";
                                                }
                                            }
                                            break;
                                        case "dpp_ppn":
                                            $val_ = number_format($nilai_dp,0);
                                            break;
                                        case "tagihan":
                                            if(!isset($total_ostd[$kk])){
                                                $total_ostd[$kk] = 0;
                                            }
                                            $total_ostd[$kk] += $row->tagihan + $row->ppn;
                                            $sisa_nppn = $row->$kk + $row->ppn;
                                            $val_ = number_format($sisa_nppn,0);
                                            break;
                                        case "terbayar":
                                            if(!isset($total_ostd[$kk])){
                                                $total_ostd[$kk] = 0;
                                            }
                                            $total_ostd[$kk] += $row->terbayar*1.11;
//                                            $terbayar_nppn = $row->$kk*1.11;
                                            $terbayar_nppn = $row->returned > 0 ? 0:$row->tagihan*1.11;
                                            $val_ = number_format($terbayar_nppn,0);
                                            
                                            break;
                                        case "sisa":
                                            $sisa_nppn = $row->returned > 0 ? $row->sisa : ($row->sisa + $row->ppn_sisa);
                                            $val_ = number_format($sisa_nppn,0);
                                            break;
                                        case "returned":
                                            $returned = $row->returned > 0 ? ($row->returned + $row->ppn):0;
                                            $val_ = number_format($returned,0);
                                            break;
                                    }
                                    echo "<td kk='$kk' ".$row->returned.">$val_</td>";
                                }
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr class=''>";
                            echo "<td class='text-center text-bold text-red' style='font-size:18px;' colspan='$colSpan'><i class='fa fa-warning blink'></i> BELUM MENERIMA UANG MUKA <i class='fa fa-warning blink'></i><div>".number_format($nilai_dp)."  (Incl.PPN)</div><div class='hidden'>silahkan buat uang muka project <a href='javascript:void(0)' onclick=\"top.window.open('https://google.com', '_blank', rel='noopener noreferrer');\">disini</a></div></td>";
                            echo "<span class='hidden'><input name='uangmuka' class='validationCheck' type='checkbox'></span>";
                            echo "<span class='hidden'><input name='' class='validationCheck' type='checkbox'>".json_encode($uangmukaCheckSetting)."</span>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    }
                    else{
                        echo "<tbody>";
                        echo "<tr class=''>";
                        echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN UANG MUKA <i class='glyphicon glyphicon-check text-success'></i></td>";
                        echo "<span class='hidden'><input checked name='uangmuka' class='validationCheck' type='checkbox'></span>";
                        echo "</tr>";
                        echo "</tbody>";
                    }

                    echo "<tfoot>";
                    echo "<th>-</th>"; //nomer
                    $valTh = "";
                    foreach($uangmukaprojectHeader as $kk => $label){
                        switch($kk){
                            default:
                                $valTh .= "<th kk=$kk >-</th>";
                                break;
                            case "tagihan":
                                // Konversi ke float untuk hitungan
                                $nTagihan = (float) $total_ostd[$kk];
                                $nDpNilai = (float) $nilai_dp;
                                // Hitung selisih
                                $nilai_kekurangan = $nDpNilai - $nTagihan;
                                // Toleransi error floating-point
                                $epsilon = 0.0001;
                                // Format tampilan utama
                                $gTotalUm = number_format($nTagihan);
                                $txtKekurangan = "";
                                // Hanya tampilkan jika selisih benar-benar positif dan di atas batas toleransi
                                if ($nilai_kekurangan > $epsilon) {
                                    $gTotalUm .= "<span class='hidden'><input name='uangmuka' class='validationCheck' type='checkbox'></span>";
                                    $txtKekurangan .= "<br>Rumus: (nTagihan: {$nTagihan} < nDpNilai: {$nDpNilai} )"
                                        . "<r>kurang (" . number_format($nilai_kekurangan) . ")</r>";
                                }
                                // Gabungkan hasil ke tabel
                                $valTh .= "<th kk='{$kk}'>" . $gTotalUm . $txtKekurangan . "</th>";
                                break;
                            case "sisa":
                                $gTotalUm = number_format($total_ostd[$kk]*1);
                                $valTh .= "<th kk=$kk >$gTotalUm</th>";
                                break;
                            case "persen_sub":
                                $totalSubBobot_f = number_format($totalSubBobot, 2);
                                $valTh .= "<th kk=$kk >$totalSubBobot_f%</th>";
                                break;
                            case "progress_percent":
                                $totalSubProgress_f = number_format($totalSubProgress, 2);
                                $valTh .= "<th kk=$kk >$totalSubProgress_f%</th>";
                                break;
                        }
                    }
                    echo $valTh;
                    echo "</tfoot>";
                    echo "</table>";


                    /*
                     * TERMIN
                     */
                    echo "<h4 class='text-bold text-blue'>TERMIN $terminDisplay</h4>";
                    echo "<div class=''>saldo termin = nilai yang belum ditagihkan ke-konsumen.</div>";
                    echo "<table class='table dataTable compact table-bordered table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th key='no'>No.</th>";
                    foreach($terminprojectHeaderView as $ky => $rhead){
                        echo "<th key='$ky'>$rhead</th>";
                    }
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    $totalSubProgress = 0;
                    $totalSubBobot = 0;
                    $tsNo = 0;
                    $total_termin = array();
                    foreach($terminData as $num => $row){
                        $tsNo++;
                        echo "<tr class='gdi_$gudang_id'>";
                        echo "<td>$tsNo</td>";
                        foreach($terminprojectHeaderView as $kk => $label){
                            $val_ = $row[$kk];
                            switch($kk){
                                case "harga":
                                    $val_ = number_format($val_,0);
                                    if(!isset($total_termin[$kk])){
                                        $total_termin[$kk] = 0;
                                    }
                                    $total_termin[$kk] += $row[$kk]*1;
                                    break;
                                case "progress":
                                    $val_ = number_format($val_,0);
                                    if(!isset($total_termin[$kk])){
                                        $total_termin[$kk] = 0;
                                    }
                                    $total_termin[$kk] += $row[$kk]*1;
                                    break;
                                case "persen":
                                    $val_ = number_format($val_,0);
                                    if(!isset($total_termin[$kk])){
                                        $total_termin[$kk] = 0;
                                    }
                                    $total_termin[$kk] += $row[$kk]*1;
                                    break;
                                default:
                                    break;
                            }
                            echo "<td>$val_</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "<tfoot>";
                    echo "<th>-</th>"; //nomer
                    $valTh = "";
                    foreach($terminprojectHeaderView as $kk => $label){
                        switch($kk){
                            default:
                                $valTh .= "<th>-</th>";
                                break;
                            case "harga":
                            case "progress":
                            case "persen":
                                $valTh .= "<th>".number_format($total_termin[$kk])."</th>";
                                break;
                        }
                    }
                    echo $valTh;
                    echo "</tfoot>";
                    echo "</table>";

                    /*
                     * RETENSI/GARANSI
                     */
                    $nilai_retensi = $retensiData[0]["harga"];
                    $akhir_retensi = $retensiData[0]["tgl_akhir_garansi"];
                    $txt_retensi = $nilai_retensi > 0 ? "&nbsp;<r>(".number_format($nilai_retensi).")</r>" : "";
                    echo "<h4 class='text-bold text-blue'>RETENSI/GARANSI $retensiDisplay</h4>";
                    echo "<table class='table dataTable compact table-bordered table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th key='no'>No.</th>";
                    foreach($retensiprojectHeader as $ky => $rhead){
                        echo "<th key='$ky'>$rhead</th>";
                    }
                    echo "</tr>";
                    echo "</thead>";

                    $colSpan = count($retensiprojectHeader)+1;
                    if($retensiCheckSetting>0){
                        echo "<tbody>";
                        if($retensiproject){
                            $totalSubProgress = 0;
                            $totalSubBobot = 0;
                            $tsNo = 0;
                            foreach($retensiproject as $num => $row){
                                $tsk_id = $row->id;
                                $produk_id = $row->produk_id;
                                $gudang_id = $row->gudang_id;
                                $arrBB = isset($arrMaterialDist[$gudang_id]) && count($arrMaterialDist[$gudang_id]) > 0 ? $arrMaterialDist[$gudang_id] : array();
                                $totalSubBobot += $row->persen_sub*1;

                                $tsNo++;
                                echo "<tr class='gdi_$gudang_id'>";
                                echo "<td>$tsNo</td>";
                                foreach($retensiprojectHeader as $kk => $label){
                                    $val_ = $row->$kk;
                        switch($kk){
                                        case "terbayar_persen":
                                            $persen_terbayar = $row->terbayar > 0 ? ($row->terbayar/$row->tagihan)*100 : 0;
                                            $val_ = number_format($persen_terbayar,0);
                                            break;
                                        case "sisa_persen":
                                            $sisa_persen = $row->sisa > 0 ? ($row->sisa/$row->tagihan)*100 : 0;
                                            $val_ = number_format($sisa_persen,0);
                                            break;
                                        case "cek":
                                            $nilai_retensi = $retensiData[0]["harga"];
                                            $akhir_retensi = $retensiData[0]["tgl_akhir_garansi"];
                                            $tanggal_hari_ini = date("Y-m-d");
                                            if ($row->sisa == 0) {
                                                if ($akhir_retensi > $tanggal_hari_ini) {
                                                    // Sudah bayar, tapi belum habis masa garansi
                                                    $val_ = "<span class='btn btn-xs btn-primary'><i class='fa fa-check'></i> Sudah dibayar - Garansi belum habis</span>";
                                                    $val_ .= "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                                }
                                else{
                                                    // Sudah bayar dan garansi sudah habis
                                                    $val_ = "<span class='btn btn-xs btn-success'><i class='glyphicon glyphicon-check'></i> Sudah lunas & garansi selesai</span>";
                                                    $val_ .= "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                                                }
                                            }
                                            elseif ($row->sisa > 0 && $row->sisa < $row->tagihan) {
                                                // Dibayar sebagian
                                                $val_ = "<span class='btn btn-xs bg-orange belum_retensi' disabled><i class='fa fa-warning blink'></i> Dibayar sebagian</span>";
                                                $val_ .= "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                                    }
                                    else{
                                                // Belum ada pembayaran
                                                $val_ = "<span class='btn btn-xs btn-danger'>Belum ada pembayaran</span>";
                                                $val_ .= "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                                }
                                break;
                                        case "tagihan":
                                            $val_ = number_format($row->$kk,0);
                                break;
                                        case "terbayar":
                                        $val_ = number_format($row->$kk,0);
                                        break;
                                        case "sisa":
                                            $val_ = number_format($row->$kk,0);
                                            break;
                                        case "tgl_berakhir_retensi":
                                            $akhir_retensi = $retensiData[0]["tgl_akhir_garansi"];
                                            $tanggal_hari_ini = date("Y-m-d");

                                            // Hitung selisih hari
                                            $date1 = new DateTime($tanggal_hari_ini);
                                            $date2 = new DateTime($akhir_retensi);
                                            $interval = $date1->diff($date2);
                                            $selisih_hari = (int)$interval->format('%r%a'); // bisa negatif

                                            // Tampilkan berdasarkan status waktu
                                            if ($selisih_hari > 0) {
                                                // Masih dalam masa garansi
                                                $val_ = "<span class='btn btn-xs btn-warning'>Garansi sampai: <b>" . date("d-m-Y", strtotime($akhir_retensi)) . "</b><br>Sisa: <b>{$selisih_hari} hari</b></span>";
                                            }
                                            elseif ($selisih_hari == 0) {
                                                // Hari terakhir garansi
                                                $val_ = "<span class='btn btn-xs bg-orange'><b>Hari terakhir garansi</b><br>(" . date("d-m-Y", strtotime($akhir_retensi)) . ")</span>";
                                            }
                                            else {
                                                // Garansi sudah habis
                                                $val_ = "<span class='btn btn-xs btn-success'>Garansi selesai: <b>" . date("d-m-Y", strtotime($akhir_retensi)) . "</b><br>Lewat: <b>" . abs($selisih_hari) . " hari lalu</b></span>";
                                            }
                                break;
                        }
                        echo "<td>$val_</td>";
                    }
                    echo "</tr>";
                }
                        }
                        else{
                            echo "<tr class=''>";
                            echo "<td class='text-center text-bold text-red belum_retensi' style='font-size:18px;' colspan='$colSpan'>BELUM ADA PEMBAYARAN RETENSI <i class='glyphicon glyphicon-times'></i></td>";
//                            echo "<span class='hidden'><input name='retensi' class='validationCheck' type='checkbox'></span>";
                            echo "</tr>";
                        }
                echo "</tbody>";
                    }
                    else{
                        echo "<tbody>";
                        echo "<tr class=''>";
                        echo "<td class='text-center text-bold' style='font-size:18px;' colspan='$colSpan'>PROYEK INI TIDAK ADA SETINGAN RETENSI / GARANSI <i class='glyphicon glyphicon-check text-success'></i></td>";
                        echo "<span class='hidden'><input checked name='retensi' class='validationCheck' type='checkbox'></span>";
                        echo "</tr>";
                        echo "</tbody>";
                    }
                echo "<tfoot>";
                echo "<th>-</th>"; //nomer
                $valTh = "";
                    foreach($retensiprojectHeader as $kk => $label){
                    switch($kk){
                        default:
                            $valTh .= "<th>-</th>";
                            break;
                        case "persen_sub":
                            $totalSubBobot_f = number_format($totalSubBobot, 2);
                            $valTh .= "<th>$totalSubBobot_f%</th>";
                        break;
                        case "progress_percent":
                            $totalSubProgress_f = number_format($totalSubProgress, 2);
                            $valTh .= "<th>$totalSubProgress_f%</th>";
                            break;
                    }
                }
                echo $valTh;
                echo "</tfoot>";
                echo "</table>";

                echo "

                <script>
                            function reCheckValidation(data=null){
                                var err = 0;
                                var done = 0;
                                var valCheckArr = $('.validationCheck');
                                var objErr = []
                                jQuery.each(valCheckArr, function(a, b){
                                    if( !$(b).is(':checked') ){
                                        err += 1;
                                        objErr.push( $(b).attr('name') )
                                    }
                                    else{
                                        done += 1;
                                    }
                                });
                                $('#approvalButton').prop('disabled', true);
                                if( !$('#checklist_trx').is(':checked') ){
                                    err += 1;
                            }
                                if( !err > 0 ){
                                    $('#approvalButton').prop('disabled', false);
                                }
                                else{
                                    if( $('#checklist_trx').is(':checked') ){
                                        setTimeout(function(){
                                            swal('SILAHKAN PERIKSA KEMBALI', 'PROJECT BELUM BISA <r><b>DI-START</b></r> KARENA UANG MUKA BELUM DITERIMA<br><br>SILAHKAN BUAT PENERIMAAN UANG MUKA DULU...<br><br>{$modePreview}', 'info');
                                            $('#checklist_trx').prop('checked', false);
                                        },500);
                                    }
                                }
                                console.log(objErr)
                            }
                            $('.validationCheck').on('change', function(){
                                reCheckValidation();
                            })
                            function check_status_project(){
                            reCheckValidation();
                            }
                                                        setTimeout(function(){
                                top.reCheckValidation();
                            },500);
                        </script>";

                    echo "<div class='container-fluid'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-8'>";
                    echo "</div>";
                    echo "<div class='col-md-4'>";
                echo "<div style='padding: 10px;' id='check_lanjut_closing' class='panel bg-red hiddsen'>";

                    $msgNote = "Checklist Lanjutkan Start Project";
                $checklistNoteEncode = blobEncode($msgNote);
                $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";

                echo "<div class='text-rightx'>";
                echo "<label for='checklist_trx'>";
                echo "<input id='checklist_trx' type='checkbox' $checked onchange=\"check_status_project();document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode&state='+$(this).is(':checked');\">";
                echo "&nbsp; <span style='font-size: 20px;'>$msgNote</span>";
                echo "</label>";
                echo "</div>";

                echo "<div style='margin-top: 4px;'>";
                echo "<div class='text-bold fa-2x'>NOTES:</div>";
                    echo "<div style='font-size: 16px;'>1. Pastikan telah melakukan setting Termin.</div>";
                    echo "<div style='font-size: 16px;'>2. Pastikan telah menerima uang muka project (jika ada).</div>";
                    echo "</div>";

                echo "</div>";

                echo "</div>";
                    echo "</div>";
                    echo "</div>";

                }
                else if($modePreview == "close_project"){
                    //jika close project tapi belum ada tasklist atau project belum dijalankan secara system akan masuk sini

                    echo "<script>
                        top.$('#approvalButton').prop('disabled', true);
                    </script>";

                }
                else{

                }

            }

            echo "<div class='text-center text-red'>$modePreview</div>";

            if (isset($items) && sizeof($items) > 0) {

                $new_beforeStepLabels = isset($beforeStepLabels) ? $beforeStepLabels : "";
                $new_beforeAllStepLabels = isset($beforeAllStepLabels) ? $beforeAllStepLabels : "";

                echo "<div line='".__LINE__."'>";
                echo "<button type='button' class='btn btn-default margin' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </button>";

                echo "&nbsp;<div class='btn-group'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-danger margin' style='border:1px #ff7700 solid;ccolor:#ff7700;'
                    onclick=\"if(confirm('" . $deleteSpec['warning'] . " " . $new_beforeStepLabels . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-danger margin' style='border:1px #ff7700 solid;ccolor:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }

                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default margin' style='border:1px #ff7700 solid;color:#ff7700;'
                    onclick=\"if(confirm('" . $undoSpec['warning'] . " " . $new_beforeStepLabels . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default margin' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }

                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default margin' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default margin' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $editSpec['label'] . "</button>";
                }
                echo "</div>";

                echo "<div line='".__LINE__."' class='bbtn-group pull-right #2'>";

//matikan sementara
                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-danger margin' style='border:1px #dd3300 solid;ccolor:#dd3300;'
                        onclick=\"if(confirm('" . $rejectionSpec['warning'] . " " . $new_beforeStepLabels . "')==1){
                        document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';
                        document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    else {
                        echo "<button type='button' disabled class='btn btn-danger margin' style='border:1px #dd3300 solid;color:#dcdcdc;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    // -------------------------------------------------------------
                    if (isset($rejectionSpecAll['targetUrl']) != "" && $rejectionSpecAll['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-danger margin' style='border:1px #000000 solid;color:#ffffff;background-color:#000000;'
                        onclick=\"if(confirm('" . $rejectionSpecAll['warning'] . "')==1){
                        document.getElementById('f1').action='" . $rejectionSpecAll['targetUrl'] . "';
                        document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp;" . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    else {
                        echo "<button type='button' disabled class='btn btn-danger margin' style='border:1px #000000 solid;color:#dcdcdc;background-color:#000000;'><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp;" . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
                    }
                    // -------------------------------------------------------------
                    echo "<button type='button' disabled class='btn btn-success margin' style='border:1px #008800 solid;color:#ffffff;'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";
                }
                else {
                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }

                    //$rejectionSpec dimatikan dulu
//                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
//                        echo "<button type='button' class='btn btn-danger margin' style='border:1px #dd3300 solid;ccolor:#dd3300;'
//                        onclick=\"if(confirm('" . $rejectionSpec['warning'] . " " . $new_beforeStepLabels . "')==1){
//                        document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';this.disabled=true;
//                        document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                    }
//                    else {
//                        echo "<button button type='button' disabled class='btn btn-danger' style='border:1px #dd3300 solid;color:#dcdcdc;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                    }
                    //$rejectionSpec dimatikan dulu

                    // -------------------------------------------------------------
                    //$rejectionSpecAll dimatikan dulu
//                    if (isset($rejectionSpecAll['targetUrl']) != "" && $rejectionSpecAll['targetUrl'] != "") {
//                        echo "<button type='button' class='btn btn-danger' style='border:1px #000000 solid;color:#ffffff;background-color:#000000;'
//                        onclick=\"if(confirm('" . $rejectionSpecAll['warning'] . "')==1){
//                        document.getElementById('f1').action='" . $rejectionSpecAll['targetUrl'] . "';this.disabled=true;
//                        document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp; " . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                    }
//                    else {
//                        echo "<button button type='button' disabled class='btn btn-danger margin' style='border:1px #000000 solid;color:#dcdcdc;background-color:#000000;'><span class='glyphicon glyphicon-alert'></span>&nbsp;&nbsp; " . $rejectionSpecAll['label'] . "</button>&nbsp;&nbsp;&nbsp;";
//                    }
                    //$rejectionSpecAll dimatikan dulu

                    // -------------------------------------------------------------
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "<button id='approvalButton' type='button' class='btn btn-success margin' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();top.open_holdon();}\">
                        <span class='glyphicon glyphicon-check'></span>&nbsp;".$approvalSpec['label']."</button>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }
//matikan sementara

                if (isset($xShipmentBtn['targetUrl']) && $xShipmentBtn['targetUrl'] != "") {
                    echo "&nbsp;&nbsp;<button type='button' class='btn btn-danger margin' style='bborder:1px #fff solid;color:#ffffff;'
                    onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';
                    document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</button>";
                }

                echo "</div>";
                echo "</div>"; // 2669

                if (isset($definitionButton) && sizeof($definitionButton) > 0) {

                    echo "<div class='row' style='margin-top: 100px;margin-bottom:-30px;font-size: larger;'>";
                    echo "<div class='panel-body'>";
                    echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                    if (isset($beforeStepWarning) && ($beforeStepWarning != NULL)) {
                        echo "<strong>$beforeStepWarning</strong>";
                        echo "<hr>";
                        echo "<br>";
                    }
                    foreach ($definitionButton as $lButton => $kButton) {
                        echo "<strong>$lButton</strong> : $kButton";
                        echo "<br>";
                    }

                    echo "</div class='col-md-12 text-center'>";
                    echo "</div class='panel-body'>";
                    echo "</div class='row'>";
                }

                echo "<div class='row' style='margin-top: 20px;'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";
                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";
                echo "</div>";
                echo "</div class='row'>";
            }

            echo "</form>";

            echo "<script>
                      $('.modal-dialog')
                      .removeClass('modal-lg')
                      .addClass('modal-xl');
                  </script>";

            echo "<script> </script>";

        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();
        }
        echo "</div id='followupPreview_mod'>";
        break;

    case "followupCancelPackingPrePreview":

        cekHere(":: followupCancelPackingPrePreview HAHAHA ::");

        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings = array();
        }
        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";
        foreach ($mainLabels as $key => $label) {
            echo "<li class='list-group-item'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            if (isset($main->$key)) {
                echo formatField($key, $main->$key);
            }
            else {
                echo "";
            }
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            $no = 0;

            //table produk
            if (isset($items) && sizeof($items) > 0) {
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                foreach ($items as $id => $iSpec) {
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;

                        if ($allowEdit == true && in_array($key, $editableFields)) {
                            //                            cekKuning(":: $key editable ::");
                            if (is_numeric($val)) {
                                $val += 0;
                                $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                                $inputType = "number";
                                $addEvent = "";
                                if (!$allowIncrement) {
                                    $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
                                }
                                else {
                                    $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
                                }

                            }
                            else {
                                $inputType = "text";
                                $addEvent = "";
                            }
                            $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
                            //                            cekMerah(":: $key NOT editable ::");
                            $strVal = formatField($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }

                        echo "<td $tdOpt >$strVal";
                        echo "</td>";
                    }
                    if ($allowEdit == true) {//==delete item
                        echo "<td>";
                        echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ((($noteEnabled === true)) || (($imageEnabled === true))) {

                        if ((isset($iSpec['note']) && strlen($iSpec['note']) > 1) || (isset($iSpec['images']) && strlen($iSpec['images']) > 1)) {

                            echo "<tr line=" . __LINE__ . ">";

                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($noteEditabled) && ($noteEditabled === true)) {
                                $key_note = "note";
                                $note_val = isset($iSpec['note']) ? $iSpec['note'] : "";
                                $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key_note&val='+this.value\" ";
                                if (isset($noteType)) {
                                    switch ($noteType) {
                                        case "textarea":
                                            $iVal = "<textarea class='form-control text-left' onclick='this.select()' $addEvent>$note_val</textarea>";
                                            break;
                                        case "text":
                                        default:
                                            $iVal = "<input type='text' name='$key_note" . "_" . "$id' class='form-control text-left' value='$note_val' onclick='this.select()' $addEvent>";
                                            break;
                                    }
                                }

                            }
                            else {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            }
                            $iVal = str_replace("\n", "<br>", $iVal);
                            $iVal = str_replace("\r", "<br>", $iVal);
                            echo "<div class='row no-padding no-margin'>";
                            echo "<div class='col-md-11'>";
                            echo $iVal;
                            echo "</div>";

                            if (($imageEnabled === true)) {
                                $image_val = isset($iSpec['images']) ? $iSpec['images'] : "";
                                if (strlen($image_val) > 1) {
                                    echo "<div class='col-md-1 text-left'>";
                                    echo "<img src='$image_val' height='50px;' stylee='float: right;'>";
                                    echo "</div>";
                                }
                            }
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }
                    }
                }


                //                if (isset($items2) && sizeof($items2) > 0) {
                //
                //                    foreach ($items2 as $id => $iSpec) {
                //                        if (array_key_exists($id, $msgWarnings)) {
                //                            $addStyle = "background-color:yellow;color:#000000;";
                //                        } else {
                //                            $addStyle = "";
                //                        }
                //
                //                        $no++;
                //                        echo "<tr line=".__LINE__.">";
                //                        echo "<td align='right' style='$addStyle'>";
                //                        echo $no;
                //                        echo ".</td>";
                //                        foreach ($itemLabels2 as $key => $label) {
                //
                //                            $replacers = array(
                //                                "produk_nama"    => "nama",
                //                                "produk_ord_jml" => "jml",
                //                            );
                //
                //                            foreach ($replacers as $orig => $new) {
                //                                if ($key == $orig) {
                //                                    $key = $new;
                //                                }
                //                            }
                //
                //
                //                            $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $iSpec[$key];
                //
                //                            if ($allowEdit == true && in_array($key, $editableFields)) {
                //                                if (is_numeric($val)) {
                //                                    $val += 0;
                //                                    $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                //                                    $inputType = "number";
                //                                    $addEvent = "";
                //                                    if (!$allowIncrement) {
                //                                        $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
                //                                    } else {
                //                                        $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
                //                                    }
                //
                //                                } else {
                //                                    $inputType = "text";
                //                                    $addEvent = "";
                //                                }
                //                                $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                //                                $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                //                            } else {
                //                                $strVal = formatField($key, $val);
                //                                $tdOpt = "style='$addStyle'";
                //                            }
                //
                //                            echo "<td $tdOpt >$strVal";
                //                            echo "</td>";
                //                        }
                //                        if ($allowEdit == true) {//==delete item
                //                            echo "<td>";
                //                            echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                //                            echo "</td>";
                //                        }
                //                        echo "</tr>";
                //                    }
                //                }

                //arrPrint($items2);
                if (isset($items2) && sizeof($items2) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items2 as $iSpec2) {
                        $no++;
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels2 as $key2 => $label2) {
                            $replacers = array(
                                "produk_nama" => "nama",
                                "produk_ord_jml" => "jml",
                            );
                            foreach ($replacers as $orig => $new) {
                                if ($key2 == $orig) {
                                    $key2 = $new;
                                    //                                    cekHere(":: $key2 :: $new ::");
                                }
                            }

                            echo "<td>";
                            if (isset($iSpec2[$key2])) {
                                echo formatField($key2, $iSpec2[$key2]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
                        //                    if ($noteEnabled == true) {
                        //                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        //                            echo "<tr line=".__LINE__.">";
                        //                            echo "<td>&nbsp;</td>";
                        //                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                        //                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        //                            echo $iVal;
                        //                            echo "</td>";
                        //
                        //                            echo "</tr>";
                        //                        }
                        //
                        //                    }
                    }
                }

                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";
                        if (isset($mainValues[$key])) {
                            echo formatField($key, $mainValues[$key]);

                        }
                        else {
                            echo "";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    echo "<tr bgcolor='#e5e5e5'>";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    echo "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            echo "<td class='text-right'>";

                            if (in_array($key, $extEditableFields)) {
                                $defValue = isset($mainAddFields[$key . "_src"]) ? $mainAddFields[$key . "_src"] : 0;
                                $selKey = $key . "_src";
                                echo "<select name='$selKey' class='form-control'>";
                                if (sizeof($relPairs) > 0) {
                                    foreach ($relPairs as $id => $name) {
                                        $selected = $id == $defValue ? "selected" : "";
                                        echo "<option value='$id' $selected>$name</option>";
                                    }
                                }
                                echo "</select>";
                            }
                            else {

                                if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                    $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                                }
                                else {
                                    $val = "n/a";
                                }

                                echo $val;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }
                        if (in_array($key, $extEditableFields)) {
                            $defValue = (0 + $val);
                            echo "<input type=number class='form-control text-right' name='$key' step='1000' value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if(parseInt(this.value)>$defValue || parseInt(this.value)<0){this.value='$defValue';}\">";
                        }
                        else {
                            echo formatField($key, $val);
                        }
                        echo "</td>";
                        echo "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            echo "<td class='text-right'>";

                            if (in_array($key, $extEditableFields)) {
                                $defValue = (0 + $val);
                                echo "<input type=number class='form-control text-right' name='$key" . "_tax" . "' step=1000 value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if (parseInt(this.value) > $defValue || parseInt(this.value)<0) {this.value= '$defValue';}\">";
                            }
                            else {
                                echo formatField($key . "_tax", $val);
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }


                if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                    foreach ($mainInputs as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                        echo "<td class='text-right'>";
                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($addRows) && sizeof($addRows) > 0) {
                    foreach ($addRows as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$addRowLabels[$key]</td>";
                        echo "<td class='text-right'>";
                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

            }
            echo "</table>";

            //cbu-ckd
            if (isset($items) && sizeof($items) > 0) {
                $volume_gross = "";
                $berat_gross = "";

                //                arrPrint($detilSizeBar);

                if (isset($detilSizeBar) && sizeof($detilSizeBar) > 0) {
                    $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                    $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                                </div>
                             </div>";
                    echo "&nbsp;";
                }
            }

            //details
            if (isset($items) && sizeof($items) > 0) {

                if (sizeof($mainElements) > 0) {

                    echo "<h4 line='" . __LINE__ . "'>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";

                    echo "<table class='table table-bordered table-condensed'>";

                    foreach ($mainElements as $elName => $aSpec) {

                        if (array_key_exists($elName, $elementConfig)) {

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " </span>";

                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";

                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";

                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));
                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                            if (sizeof($strLabel) > 0 && $val != '') {
                                                echo "<tr line=" . __LINE__ . ">";
                                                if (strlen($strLabel) > 0) {
                                                    echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                                }
                                                echo "<td align='left' class='text-black'>$val</td>";
                                                echo "</tr>";
                                            }


                                        }
                                        echo "</table>";
                                    }

                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";

                    echo "</div class='panel-default'>";
                }
                if (strlen($description) > 0) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').
src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+this.value;\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($description);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }
            echo "</div class='table-responsive'>";

            //button action
            if (isset($items) && sizeof($items) > 0) {
                echo "<div class='row'>";

                echo "<div class='col-md-2'>";
                echo "<a class='btn btn-block btn-default' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </a>";
                echo "</div class='col-md-2'>";

                echo "<div class='col-md-2'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<a class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $deleteSpec['warning'] . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</a>";
                }
                else {
                    echo "<button disabled class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                echo "</div class='col-md-2'>";

                echo "<div class='col-md-2'>";
                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<a class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $undoSpec['warning'] . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</a>";
                }
                else {
                    //                    echo "&nbsp;";
                    echo "<button disabled class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                echo "</div class='col-md-2'>";

                echo "<div class='col-md-2'>";
                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<a class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</a>";
                }
                else {
                    //                    echo "&nbsp;";
                    echo "<button disabled class='btn btn-block btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                echo "</div class='col-md-2'>";

                //                echo "<div class='col-md-2'>&nbsp;";
                //                echo "</div class='col-md-2'>";

                echo "<div class='col-md-4 text-right'>";

                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    echo "<div class='panel-body'>";
                    //                    echo "<span class='text-danger'>these values need to be verified first</span><br>";
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }

                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }


                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<a class='btn btn-block btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</a>";
                    }
                    else {
                        echo "<button disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    echo "<button disabled class='btn btn-success'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";

                    echo "</div>";
                }
                else {

                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }

                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<a class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</a>";
                    }
                    else {
                        echo "<button disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "<a class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</a>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }

                if (isset($xShipmentBtn['targetUrl']) != "" && $xShipmentBtn['targetUrl'] != "") {
                    echo "<span class='btn btn-default ' style='border:1px #fff solid;color:#ff7700;' onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</span>";
                }

                echo "</div class='col-md-4'>";
                echo "</div class='row'>";

                echo "<div class='row'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";

                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";

                echo "</div>";
                echo "</div class='row'>";
            }


            echo "</form>";

        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();

        }

        break;

    case "followupCancelPackingPreview":

        cekHere(":: followupCancelPackingPreview HAHAHA ::");

        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings = array();
        }
        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";
        foreach ($mainLabels as $key => $label) {
            echo "<li class='list-group-item'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            if (isset($main->$key)) {
                echo formatField($key, $main->$key);
            }
            else {
                echo "";
            }
            echo "</div class='col-md-6'>";
            echo "</div class='row'>";
            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            $no = 0;

            //table produk
            if (isset($items) && sizeof($items) > 0) {
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                foreach ($items as $id => $iSpec) {
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($itemLabels as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;

                        if ($allowEdit == true && in_array($key, $editableFields)) {
                            //                            cekKuning(":: $key editable ::");
                            if (is_numeric($val)) {
                                $val += 0;
                                $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                                $inputType = "number";
                                $addEvent = "";
                                if (!$allowIncrement) {
                                    $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
                                }
                                else {
                                    $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
                                }

                            }
                            else {
                                $inputType = "text";
                                $addEvent = "";
                            }
                            $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
                            //                            cekMerah(":: $key NOT editable ::");
                            $strVal = formatField($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }

                        echo "<td $tdOpt >$strVal";
                        echo "</td>";
                    }
                    if ($allowEdit == true) {//==delete item
                        echo "<td>";
                        echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                        echo "</td>";
                    }
                    echo "</tr>";
                    if ((($noteEnabled === true)) || (($imageEnabled === true))) {

                        if ((isset($iSpec['note']) && strlen($iSpec['note']) > 1) || (isset($iSpec['images']) && strlen($iSpec['images']) > 1)) {

                            echo "<tr line=" . __LINE__ . ">";

                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($noteEditabled) && ($noteEditabled === true)) {
                                $key_note = "note";
                                $note_val = isset($iSpec['note']) ? $iSpec['note'] : "";
                                $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key_note&val='+this.value\" ";
                                if (isset($noteType)) {
                                    switch ($noteType) {
                                        case "textarea":
                                            $iVal = "<textarea class='form-control text-left' onclick='this.select()' $addEvent>$note_val</textarea>";
                                            break;
                                        case "text":
                                        default:
                                            $iVal = "<input type='text' name='$key_note" . "_" . "$id' class='form-control text-left' value='$note_val' onclick='this.select()' $addEvent>";
                                            break;
                                    }
                                }

                            }
                            else {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            }
                            $iVal = str_replace("\n", "<br>", $iVal);
                            $iVal = str_replace("\r", "<br>", $iVal);
                            echo "<div class='row no-padding no-margin'>";
                            echo "<div class='col-md-11'>";
                            echo $iVal;
                            echo "</div>";


                            if (($imageEnabled === true)) {
                                $image_val = isset($iSpec['images']) ? $iSpec['images'] : "";
                                if (strlen($image_val) > 1) {
                                    echo "<div class='col-md-1 text-left'>";
                                    echo "<img src='$image_val' height='50px;' stylee='float: right;'>";
                                    echo "</div>";
                                }
                            }
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }


                //                if (isset($items2) && sizeof($items2) > 0) {
                //
                //                    foreach ($items2 as $id => $iSpec) {
                //                        if (array_key_exists($id, $msgWarnings)) {
                //                            $addStyle = "background-color:yellow;color:#000000;";
                //                        } else {
                //                            $addStyle = "";
                //                        }
                //
                //                        $no++;
                //                        echo "<tr line=".__LINE__.">";
                //                        echo "<td align='right' style='$addStyle'>";
                //                        echo $no;
                //                        echo ".</td>";
                //                        foreach ($itemLabels2 as $key => $label) {
                //
                //                            $replacers = array(
                //                                "produk_nama"    => "nama",
                //                                "produk_ord_jml" => "jml",
                //                            );
                //
                //                            foreach ($replacers as $orig => $new) {
                //                                if ($key == $orig) {
                //                                    $key = $new;
                //                                }
                //                            }
                //
                //
                //                            $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $iSpec[$key];
                //
                //                            if ($allowEdit == true && in_array($key, $editableFields)) {
                //                                if (is_numeric($val)) {
                //                                    $val += 0;
                //                                    $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                //                                    $inputType = "number";
                //                                    $addEvent = "";
                //                                    if (!$allowIncrement) {
                //                                        $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
                //                                    } else {
                //                                        $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
                //                                    }
                //
                //                                } else {
                //                                    $inputType = "text";
                //                                    $addEvent = "";
                //                                }
                //                                $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                //                                $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                //                            } else {
                //                                $strVal = formatField($key, $val);
                //                                $tdOpt = "style='$addStyle'";
                //                            }
                //
                //                            echo "<td $tdOpt >$strVal";
                //                            echo "</td>";
                //                        }
                //                        if ($allowEdit == true) {//==delete item
                //                            echo "<td>";
                //                            echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                //                            echo "</td>";
                //                        }
                //                        echo "</tr>";
                //                    }
                //                }

                //arrPrint($items2);
                if (isset($items2) && sizeof($items2) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items2 as $iSpec2) {
                        $no++;
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels2 as $key2 => $label2) {
                            $replacers = array(
                                "produk_nama" => "nama",
                                "produk_ord_jml" => "jml",
                            );
                            foreach ($replacers as $orig => $new) {
                                if ($key2 == $orig) {
                                    $key2 = $new;
                                    //                                    cekHere(":: $key2 :: $new ::");
                                }
                            }

                            echo "<td>";
                            if (isset($iSpec2[$key2])) {
                                echo formatField($key2, $iSpec2[$key2]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
                        //                    if ($noteEnabled == true) {
                        //                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        //                            echo "<tr line=".__LINE__.">";
                        //                            echo "<td>&nbsp;</td>";
                        //                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                        //                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        //                            echo $iVal;
                        //                            echo "</td>";
                        //
                        //                            echo "</tr>";
                        //                        }
                        //
                        //                    }
                    }

                }


                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";
                        if (isset($mainValues[$key])) {
                            echo formatField($key, $mainValues[$key]);

                        }
                        else {
                            echo "";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    echo "<tr bgcolor='#e5e5e5'>";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    echo "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            echo "<td class='text-right'>";

                            if (in_array($key, $extEditableFields)) {
                                $defValue = isset($mainAddFields[$key . "_src"]) ? $mainAddFields[$key . "_src"] : 0;
                                $selKey = $key . "_src";
                                echo "<select name='$selKey' class='form-control'>";
                                if (sizeof($relPairs) > 0) {
                                    foreach ($relPairs as $id => $name) {
                                        $selected = $id == $defValue ? "selected" : "";
                                        echo "<option value='$id' $selected>$name</option>";
                                    }
                                }
                                echo "</select>";
                            }
                            else {

                                if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                    $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                                }
                                else {
                                    $val = "n/a";
                                }

                                echo $val;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }
                        if (in_array($key, $extEditableFields)) {
                            $defValue = (0 + $val);
                            echo "<input type=number class='form-control text-right' name='$key' step='1000' value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if(parseInt(this.value)>$defValue || parseInt(this.value)<0){this.value='$defValue';}\">";
                        }
                        else {
                            echo formatField($key, $val);
                        }
                        echo "</td>";
                        echo "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            echo "<td class='text-right'>";

                            if (in_array($key, $extEditableFields)) {
                                $defValue = (0 + $val);
                                echo "<input type=number class='form-control text-right' name='$key" . "_tax" . "' step=1000 value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if (parseInt(this.value) > $defValue || parseInt(this.value)<0) {this.value= '$defValue';}\">";
                            }
                            else {
                                echo formatField($key . "_tax", $val);
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }


                if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                    foreach ($mainInputs as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                        echo "<td class='text-right'>";
                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($addRows) && sizeof($addRows) > 0) {
                    foreach ($addRows as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$addRowLabels[$key]</td>";
                        echo "<td class='text-right'>";
                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

            }
            echo "</table>";

            //cbu-ckd
            if (isset($items) && sizeof($items) > 0) {
                $volume_gross = "";
                $berat_gross = "";

                //                arrPrint($detilSizeBar);

                if (isset($detilSizeBar) && sizeof($detilSizeBar) > 0) {
                    $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                    $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                                </div>
                             </div>";
                    echo "&nbsp;";
                }
            }

            //details
            if (isset($items) && sizeof($items) > 0) {

                if (sizeof($mainElements) > 0) {

                    echo "<h4>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";

                    echo "<table class='table table-bordered table-condensed'>";

                    foreach ($mainElements as $elName => $aSpec) {

                        if (array_key_exists($elName, $elementConfig)) {

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " </span>";

                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";

                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";

                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));
                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                            if (sizeof($strLabel) > 0 && $val != '') {
                                                echo "<tr line=" . __LINE__ . ">";
                                                if (strlen($strLabel) > 0) {
                                                    echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                                }
                                                echo "<td align='left' class='text-black'>$val</td>";
                                                echo "</tr>";
                                            }


                                        }
                                        echo "</table>";
                                    }

                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";

                    echo "</div class='panel-default'>";
                }
                if (strlen($description) > 0) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').
src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+this.value;\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($description);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }
            echo "</div class='table-responsive'>";

            //button action
            if (isset($items) && sizeof($items) > 0) {
                echo "<div>";

                // echo "<div class='col-md-2'>";
                echo "<button type='button' class='btn btn-default' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </button>";
                // echo "</div class='col-md-2'>";

                echo "&nbsp;<div class='btn-group'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $deleteSpec['warning'] . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $undoSpec['warning'] . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $editSpec['label'] . "</button>";
                }
                echo "</div>";

                // echo "<div class='col-md-2'>&nbsp;";
                // echo "</div class='col-md-2'>";
                echo "<div class='btn-group pull-right'>";
                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    // echo "<div class='panel-body'>";
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "&nbsp;<button type='button' class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    else {
                        echo "&nbsp;<button type='button' disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    echo "&nbsp;<button type='button' disabled class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";
                    // echo "</div>";
                }
                else {
                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    else {
                        echo "<button button type='button' disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "&nbsp;<button button type='button' class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</button>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }
                echo "</div>";

                if (isset($xShipmentBtn['targetUrl']) != "" && $xShipmentBtn['targetUrl'] != "") {
                    echo "<span class='btn btn-default ' style='border:1px #fff solid;color:#ff7700;' onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</span>";
                }

                echo "</div>"; // 2669

                echo "<div class='row' style='margin-top: 60px;'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";

                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";

                echo "</div>";
                echo "</div class='row'>";
            }

            echo "</form>";

        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();

        }

        break;

    case "showDetails":
        function bs_modal($label, $field)
        {
            $label_width = "col-sm-3";
            $forms_width = "col-sm-9";

            $var = "<div class='form-group overflow-h'>";
            $var .= "<label class='$label_width control-label'>$label</label>
                  <div class='$forms_width'>
                  <div class='input-group' style='width:100%;'>
                    $field
                    
                  </div>
                  </div>";
            $var .= "</div>";
            return $var;
        }

        $p = New Pages("$title", "sub judul", "application/template/pages.html");
        $arrAtribut = array(
            "target" => "result",
            "name" => "myForm",
            "id" => "myForm",
        );
        // $action_link = "";
        //        $form = "";
        //        arrPrint($arrayHeaderLabels);
        //        arrPrint($arrayTablesHeader);
        //        arrPrint($arrayNotaData);
        //        arrPrint($dataProduk);

        //region header Nota
        $template = array(
            'table_open' => '<table border="2" cellpadding="1" cellspacing="1" class="table  tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
        );
        $this->table->set_template($template);
        $headerNota = "<table>";
        if (sizeof($arrayHeaderLabels) > 0) {
            $header_f = array();
            foreach ($arrayNotaData as $key => $dataHeaderNota) {

                $header_result_f = array();
                foreach ($arrayHeaderLabels as $kolom => $label) {
                    $value = $dataHeaderNota[$kolom];
                    $headerNota .= "<tr line=" . __LINE__ . ">";
                    $headerNota .= "<td>$label</td>";
                    $headerNota .= "<td>:</td>";
                    $headerNota .= "<td>$value</td>";
                    $headerNota .= "</tr>";
                }


            }

            //            $this->table->add_row($header_f);

        }
        $headerNota .= "</table>";
        //endregion header nota

        //region data transaksi
        if (sizeof($dataProduk) > 0) {
            $header_f = array();
            foreach ($arrayTablesHeader as $kolom => $alias) {
                $header_result_f = array('data' => $alias, 'class' => 'text-center');
                $header_f[] = $header_result_f;
            }
            $this->table->set_heading($header_f);
            //region data transaksi
            foreach ($dataProduk as $dataTrasnsaksi) {
                $isi = array();
                foreach ($arrayTablesHeader as $kolom => $alias) {
                    $value = $dataTrasnsaksi[$alias];
                    $isi[] = array('data' => $value);
                }
                $this->table->add_row($isi);
            }
            //endregion
        }

        $contens = $headerNota;
        $contens .= $this->table->generate();
        //endregion

        //  region button modal-footer
        $pihak_data = "<div class='row'>";
        foreach ($arrayNotaPihak as $key => $arrPihak) {
            foreach ($arrPihak as $pihak => $by) {
                $pihak_data .= "<div class='col-md-6'>";
                $pihak_data .= "<div>$pihak</div>";
                $pihak_data .= "<div class='row'></div>";
                $pihak_data .= "<div>$by</div>";
                $pihak_data .= "</div>";
            }

        }
        $pihak_data .= "</div>";
        $contens .= "$pihak_data";

        $button = form_button("tes", "<i class='fa fa-close'> Close</i>", "class='btn btn-default pull-left' data-dismiss='modal'");
        //  endregion button form


        $p->setLayoutModalHeader($title, true);
        $p->setLayoutModalBody($contens);
        $p->setLayoutModalFooter($button);

        //        $modal = form_open($action_link, $arrAtribut);
        $modal = $p->layout_modal();
        //        $modal .= form_close();

        echo $modal;
        break;

    case "viewReceipt":
        //        arrPrint($items);
        if (isset($mainElements)) {
            //            arrPrint($mainElements);
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            if (isset($elementUsedFieldsConfigs) && sizeof($elementUsedFieldsConfigs) > 0) {
                                                if (isset($elementUsedFieldsConfigs[$e]) && $elementUsedFieldsConfigs[$e] != "") {
                                                    $colLabel = $elementUsedFieldsConfigs[$e];
                                                }
                                                else {
                                                    $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                                }
                                            }
                                            else {
                                                $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            }
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }
        }
        $item_src = "";
        if (sizeof($itemSrc) > 0) {
            //            arrPrint($itemSrc);

            $item_src .= "<div class='table-responsive' style='border:0px solid red;'>";
            $item_src .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
            $item_src .= "<tr bgcolor='#f5f5f5'>";
            $item_src .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";

            foreach ($itemSrcLabel as $ky => $srcLabel) {
                $item_src .= "<th class='text-muted' style='font-weight:bold;'>";
                $item_src .= $srcLabel . "";
                $item_src .= "</th>";
            }
            $item_src .= "</tr>";
            $mno = 0;
            foreach ($itemSrc as $itemSrc0) {
                $mno++;
                $item_src .= "<tr line=" . __LINE__ . ">";
                $item_src .= "<td align='right'>";
                $item_src .= $mno;
                $item_src .= "</td>";
                foreach ($itemSrcLabel as $ky => $srclabel) {
                    $val = isset($itemSrc0[$ky]) ? $itemSrc0[$ky] : "";
                    $item_src .= "<td>";
                    $item_src .= formatField($ky, $val);
                    $item_src .= "</td>";
                }
                $item_src .= "</tr>";

            }


            $item_src .= "</table>";
            $item_src .= "</div>";
        }
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }
                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    // arrPrint($iSpec);
                    //                     arrPrint($itemLabels);

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();

                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));

                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";
                    //                    arrPrint($items[$id]);
                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {

                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";

                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";

                            cekMerah($iVal);

                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);

                            cekHijau($string);

                            $string = str_replace("&lt;br&gt;", "<br>", $string);


                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";

                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;

                }

                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                }

                //                 arrPrint($mainValues);
                //                 arrPrint($sumRows);
                //                arrPrint($inWord);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    if (strlen($inWord) > 5) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td style='vertical-align: bottom;' colspan='$colspan' rowspan='$rowspan' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                        $contentStr .= "</tr>";
                    }
                    //                                       arrPrint($mainValues);

                    foreach ($sumRows as $key => $label) {

                        //                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
                        //                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key])) {

                            if (sizeof($mainValues[$key]) > 0) {
                                //                                cekHere("$key " . $mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif ($mainValues[$key] < 0) {
                                //                                cekHitam($mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, $mainValues[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                        }
                        //                        cekHere($label." - ".$key." - ".$val);
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr line=" . __LINE__ . ">";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr line=" . __LINE__ . ">";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                arrPrint($sumRows2);
                if (isset($sumRows2) && sizeof($sumRows2) > 0) {
                    foreach ($sumRows2 as $key2 => $label2) {

                        //                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
                        //                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key2])) {

                            if (sizeof($mainValues[$key2]) > 0) {
                                //                                cekHere("$key " . $mainValues[$key]);
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key2, $zeroAllowed))) {
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                            elseif ($main[$key2] < 0) {
                                //                                cekHitam($mainValues[$key]);
                                $contentStr2 .= "<tr line=" . __LINE__ . ">";
                                $contentStr2 .= "<td colspan='$colspan2' class='text-right'>$label2</td>";
                                $contentStr2 .= "<td class='text-right'>";
                                if (isset($mainValues[$key2])) {
                                    $contentStr2 .= formatField($key2, $mainValues[$key2]);
                                }
                                else {
                                    $contentStr2 .= "0";
                                }
                                $contentStr2 .= "</td>";
                                $contentStr2 .= "</tr>";
                            }
                        }
                        //                        cekHere($label." - ".$key." - ".$val);
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }
            $contentStr4 = "";
            if (isset($items3) && sizeof($items3) > 0) {
                $no = 0;
                $contentStr4 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr4 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr4 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr4 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    $contentStr4 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr4 .= $label;
                    $contentStr4 .= "</th>";
                }
                $contentStr4 .= "</tr>";
                foreach ($items3 as $id => $iSpec) {
                    $no++;
                    $arrItems3Registries[$id] = isset($items3Registries[$id]) ? $items3Registries[$id] : array();
                    $contentStr4 .= "<tr line=" . __LINE__ . ">";
                    $contentStr4 .= "<td align='right'>";
                    $contentStr4 .= $no;
                    $contentStr4 .= ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr4 .= "<td>";
                        $contentStr4 .= formatField($key, $val);
                        $contentStr4 .= "</td>";
                    }
                    $contentStr4 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items3[$id]['note']) && strlen($items3[$id]['note']) > 1) {
                            $contentStr4 .= "<tr line=" . __LINE__ . ">";
                            $contentStr4 .= "<td>&nbsp;</td>";
                            $contentStr4 .= "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items3[$id]['note']) ? $items3[$id]['note'] : "";
                            $contentStr4 .= $iVal;
                            $contentStr4 .= "</td>";
                            $contentStr4 .= "</tr>";
                        }
                    }
                }
                $contentStr4 .= "</table>";
                $contentStr4 .= "</div>";
            }

            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr line=" . __LINE__ . ">";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";
            }


            $contentStr6 = "";
            if (isset($dpValueDetilsINV) && sizeof($dpValueDetilsINV) > 0) {
                $contentStr6 .= "<div class='panel-body'>";
                $contentStr6 .= "<table class='table table-responsive'>";
                foreach ($dpFieldNameINV as $dp_fields => $dpFields_alias) {
                    $contentStr6 .= "<tr line=" . __LINE__ . ">";
                    $contentStr6 .= "<td>$dpFields_alias</td>";
                    $contentStr6 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetilsINV[$dp_fields]) . "</td>";
                    $contentStr6 .= "</tr>";
                }
                $contentStr6 .= "</table>";
                $contentStr6 .= "</div>";

                $elementLabels["content_6_display"] = "block";
            }
            else {
                $elementLabels["content_6_display"] = "none";
            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }

            $contenStr5 = "";
            if (isset($mainData2) && sizeof($mainData2) > 0) {

                //                $contenStr5 .= "<div class='panel-body'>";
                $contenStr5 .= "<table class='table table-bordered'>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                $contenStr5 .= "<td class='text-centter'>No</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
                    $contenStr5 .= "<td class='text-centter'>$add_fields</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                $contenStr5 .= "<td class='text-center'>1</td>";
                foreach ($mainData2Fields as $fieldsKey => $add_fields) {
                    //                    cekHitam($fieldsKey);
                    $contenStr5 .= "<td>" . formatField($fieldsKey, $mainData2[$fieldsKey]) . "</td>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "<tr line=" . __LINE__ . ">";
                if (strlen($inWord2) > 5) {
                    $contenStr5 .= "<tr line=" . __LINE__ . ">";
                    $contenStr5 .= "<td style='vertical-align: bottom;' colspan='" . sizeof($mainData2Fields) . "' rowspan='' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord2</span></td>";
                    $contenStr5 .= "</tr>";
                }
                $contenStr5 .= "</tr>";
                $contenStr5 .= "</table>";
                //                $contenStr5 .= "</div>";

            }
            $elementLabels["content_src"] = $item_src;
            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
            $elementLabels["content_4"] = $contentStr4;
            $elementLabels["content_5"] = $contenStr5;
            $elementLabels["content_6"] = $contentStr6;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr line=" . __LINE__ . ">
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr line=" . __LINE__ . "></tr>
                </thead>
                <tbody>
                    <tr line=" . __LINE__ . ">
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }

        $p = New Layout("$title", "", $template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        //arrPrintWebs($arrTags);

        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewReceiptCashIn":

        //cekHere("iki");
        //        arrPrint($mainElements);
        //        arrPrint($elementConfigs);
        //"nomer"

        //        arrPrint($mainElements);
        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
                        //                        $elementStr .= $eSpec['label']."**";//of dulu
                        if ($eSpec['label'] == "cash in") {
                            $elementStr .= "";
                        }
                        else {
                            if ($eSpec['label'] == "customer details") {
                                $elementStr .= "billing details";
                            }
                            else {
                                $elementStr .= $eSpec['label'];
                            }
                        }
                        $elementStr .= "";
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
                                    //                                    $elementStr .= $colLabel!="" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>$val</td>" : "<td colspan='3'>$val</td>";
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }
                                        //                                        cekHere($eKey);

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }


        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }

        }
        //        arrPrint($elementLabels);
        // arrPrint($items);

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }

                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));
                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";

                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";

                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";

                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";
                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";
                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);
                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;

                    //                    arrPrint($iSpec);
                }
                //cekHere(sizeof($itemLabels));
                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                    $rowspan2 = sizeof($dpValueDetils) + 1;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                    $rowspan2 = "";
                }
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {

                        if (isset($mainValues2[$key])) {
                            if ($mainValues2[$key] > 0) {

                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$mainColspan' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues2[$key])) {
                                    $contentStr .= formatField($key, $mainValues2[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues2[$key])) {
                                    $contentStr .= formatField($key, $mainValues2[$key]);
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";

                            }


                        }

                    }
                    if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "'>&nbsp</td>";
                        $contentStr .= "</tr>";
                        if (strlen($inWord) > 5) {
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td style='vertical-align: top;' colspan='$colspan' rowspan='$rowspan2' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                            $contentStr .= "</tr>";

                        }
                        foreach ($dpFieldName as $dp_fields => $dpFields_alias) {

                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='$colspan2' class='text-right'>$dpFields_alias</td>";
                            $contentStr .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                    else {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='$mainColspan' class='text-right'>Vat 10%</td>";
                        $contentStr .= "<td class='text-right'>" . number_format(0 + $mainValues2['grand_ppn']) . "</td>";
                        $contentStr .= "</tr>";

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "'>&nbsp</td>";
                        $contentStr .= "</tr>";
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        if (strlen($inWord) > 5) {

                            $contentStr .= "<td style='vertical-align: top;' colspan='$colspan' rowspan='2' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                            //                                    $contentStr .= "</tr>";
                        }
                        //                                $contentStr .= "<tr line=".__LINE__.">";
                        //                                arrPrint($mainValues);
                        $contentStr .= "<td style='vertical-falign: middle;' colspan='2' class='text-right text-bold'>Terbayar </td>";
                        $contentStr .= "<td style='vertical-falign: middle;' colspan='' class='text-right text-bold'>" . number_format(0 + $mainValues['nilai_bayar']) . "</td>";
                        $contentStr .= "</tr>";
                        //                                $contentStr .= "</tr>";
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr line=" . __LINE__ . ">";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr line=" . __LINE__ . ">";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }

            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr line=" . __LINE__ . ">";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
                    //                    $contentStr3 .="<div class='col-md-1 text-right'>$dpFields_alias</div>";
                    //                    $contentStr3 .="<div class='col-md-2 font-size-1-2'>".formatField($dp_fields,$dpValueDetils[$dp_fields])."</div>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";

            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }

            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr line=" . __LINE__ . ">
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr line=" . __LINE__ . "></tr>
                </thead>
                <tbody>
                    <tr line=" . __LINE__ . ">
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }

        $p = New Layout("$title", "", $template);
        //arrPrint($elementLabels);
        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }
        //        arrPrint($mainElements);
        //        arrPrint($arrTags);
        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewReceiptBT_":


        //        arrPrint($base);

        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='margin-top:-20px;'>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (isset($elementConfigs[$eKey]['elementType'])) {
                                switch ($elementConfigs[$eKey]['elementType']) {
                                    case "dataModel":
                                        $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) ? $elementConfigs[$eKey]['usedFields'][$e] . ":" : "";
                                        break;
                                    case "dataField":
                                        $colLabel = isset($elementConfigs[$eKey]['labelSrc']) ? $elementConfigs[$eKey]['labelSrc'] . ":" : "";
                                        break;
                                }
                            }
                            else {
                                $colLabel = $e . ":";
                            }


                            if (!is_numeric($e)) {
                                //                                if(!empty($val)){
                                $elementStr .= "<span class=''>$colLabel $val</span><br>";
                                //                                }
                            }
                            else {
                                if (!empty($val)) {
                                    $elementStr .= "<span class=''>$val</span><br>";
                                }
                            }
                        }
                        $elementStr .= "</div>";
                    }

                    $elementLabels[$eKey] = $elementStr;
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";

                foreach ($specHeader as $value) {
                    //                    $elementHdr .= "<div class='panel panel-heading text-center'>";
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                    //                    $elementHdr .= "</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;

            }

        }


        //region produk list
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {

            $no = 0;
            $contentStr = "";
            $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label;
                    $contentStr .= "</th>";
                }
                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {
                    $no++;
                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]));
                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";
                    foreach ($itemLabels as $key => $label) {
                        //                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $iSpec[$key];
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);


                        $contentStr .= "</td>";
                    }
                    $contentStr .= "</tr>";
                }
                //                arrprint($itemLabels);
                //                var_dump($itemLabels);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                        $contentStr .= "<td class='text-right'>";
                        if (isset($mainValues[$key])) {

                            $contentStr .= formatField($key, $mainValues[$key]);
                        }
                        else {
                            $contentStr .= "";
                        }
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                    }
                }

                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    $contentStr .= "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            //                            $contentStr.=$mainValues[$key . "_tax"];


                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }

                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }

                //                if (isset($grandTotal) && $grandTotal > 0) {
                //                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                //                    $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>grand total**</td>";
                //                    $contentStr .= "<td class='text-right'>";
                //
                //                    $contentStr .= formatField("total", $grandTotal);
                //                    $contentStr .= "</td>";
                //                    $contentStr .= "</tr>";
                //                }
            }
            $contentStr .= "</table>";


            //region signatures
            //            $signatureStr = "<div class='table-responsive'>";
            //            $signatureStr = "";
            if (sizeof($signature) > 0) {
                //                $signatureStr .= "<table class='table table-bordered table-condensed'>";
                //                $signatureStr .= "<tr line=".__LINE__.">";
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    //                    $signatureStr .= "<td class='text-center'>";
                    $signatureStr .= "<div class='panel panel-default  text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    //                    $signatureStr .= $iSpecs['caption_department'];
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    //                    $signatureStr .= "</td>";
                    $elementLabels[$iKey] = $signatureStr;
                }
                //                $signatureStr .= "</tr>";
                //                $signatureStr .= "</table>";
                //                $signatureStr = "";
            }
            //            $signatureStr .= "</div>";
            //endregion

            $elementLabels["content"] = $contentStr;
            //            $elementLabels["signatures"] = $signatureStr;
        }
        //endregion

        $p = New Layout("$title", "", $template);

        //        arrPrint($elementLabels);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                //                cekHitam($tValue);
                $arrTags[$tKey] = $tValue;
            }
        }

        //arrPrint($arrTags);
        $p->addTags($arrTags);


        $p->render();

        break;

    case "viewReceiptBT":

        //arrPrint($sumRows);
        //arrPrint($mainElements);

        function FormatCreditCard($cc)
        {
            $cc = str_replace(array('-', ' '), '', $cc);
            $cc_length = strlen($cc);
            $newCreditCard = substr($cc, -4);
            for ($i = $cc_length - 5; $i >= 0; $i--) {
                if ((($i + 1) - $cc_length) % 4 == 0) {
                    $newCreditCard = '-' . $newCreditCard;
                }
                $newCreditCard = $cc[$i] . $newCreditCard;
            }
            return $newCreditCard;
        }

        $no = 0;
        $total_produk_ord_jml = 0;
        $total_produk_diskon = 0;
        $produk_diskon = 0;
        $grandTotal = 0;
        $kembali = 0;
        $tunai = 0;
        $transactionInfoNotaStr = "";
        $headerNotaStr = "";
        $footerNotaStr = "";
        $contentStr = "";


        $maxStringLength = 42;
        $cPrint = "<IMAGE380X120>" . base_url() . "/assets/images/kop_sbmLine2.png<br>";

        $paymentMethodKey = "";

        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {

                $paymentMethodKey = isset($mainElements['paymentMethod']['key']) ? $mainElements['paymentMethod']['key'] : "";

                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    $elementSmallStr = "";
                    $transactionInfoStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                        $elementSmallStr .= "<SMALL><BOLD>#" . strtoupper($eSpec['label']) . "<BR>";
                        $transactionInfoStr .= "<div style='font-size: 10px;' class='text-left text-bold'>#" . strtoupper($eSpec['label']) . "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='margin-top:-20px;'>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) ? $elementConfigs[$eKey]['usedFields'][$e] : $e;
                            if (!is_numeric($e)) {
                                $elementStr .= "<span class=''>$colLabel : $val</span><br>";
                                $elementSmallStr .= "<SMALL>$colLabel : $val<br>";
                                $transactionInfoStr .= "<div style='font-size: 10px;' class='text-left'>$colLabel : $val</div>";
                            }
                            else {
                                if (!empty($val)) {
                                    $elementStr .= "<span class=''>$val</span><br>";
                                    $elementSmallStr .= "<SMALL>$val<br>";
                                    $transactionInfoStr .= "<div style='font-size: 10px;' class='text-left'>$val</div>";
                                }
                            }
                        }
                        $elementStr .= "</div>";
                        $transactionInfoStr .= "<BR>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    $elementSmalls[$eKey] = $elementSmallStr;
                    $transactionInfo[$eKey] = $transactionInfoStr;
                }

                if (sizeof($signature) > 0) {
                    $elementLabels['kasir'] = $cPrint_kasir = $signature['sign_1']['contents'];
                    $elementLabels['customers'] = $cPrint_customers = isset($mainElements['gudang2ID']['labelValue']) ? $mainElements['gudang2ID']['labelValue'] : "";
                    $elementLabels['customers'] = $cPrint_customers !== '' ? $cPrint_customers = $cPrint_customers : $cPrint_customers = isset($signature['sign_2']['contents']) ? $signature['sign_2']['contents'] : "";
                }

                $elementLabels['tanggal'] = $cPrint_tgl = date("Y-m-d", strtotime($main->dtime));
                $elementLabels['hours'] = $cPrint_jam = date("H:i", strtotime($main->dtime));
                $elementLabels['nota'] = $cPrint_nota = isset($main->nomer) ? $main->nomer : 0;
                $elementLabels['jenis_label'] = $cPrint_jenis_label = isset($main->jenis_label) ? $main->jenis_label : "#";

                $transactionInfoNotaStr .= "<div style='font-size: 16px;' class='text-center text-bold'>" . trim(strtoupper($cPrint_jenis_label)) . "</div>";
                $transactionInfoNotaStr .= "<dsh></dsh>";

                $cPrint .= "<CENTER><MEDIUM1><BOLD>" . trim(strtoupper($cPrint_jenis_label)) . "<br>";
                $cPrint .= "<DLINE><BR>";

                if (isset($mainElements['vendorDetails'])) {

                    //                    arrPrint($elementSmalls);
                    $cPrint .= isset($elementSmalls['deliveryDetails']) ? $elementSmalls['deliveryDetails'] : "";
                    $cPrint .= isset($elementSmalls['vendorDetails']) ? $elementSmalls['vendorDetails'] : "";
                    $cPrint .= isset($elementSmalls['fixedElements']) ? $elementSmalls['fixedElements'] : "";
                    $cPrint .= isset($elementSmalls['paymentMethod']) ? $elementSmalls['paymentMethod'] : "";

                    $cPrint .= "<BR>";
                    $cPrint .= "<DLINE>";
                    $cPrint .= "<BR><BR>";

                    $transactionInfoNotaStr .= isset($transactionInfo['deliveryDetails']) ? $transactionInfo['deliveryDetails'] : "";
                    $transactionInfoNotaStr .= isset($transactionInfo['vendorDetails']) ? $transactionInfo['vendorDetails'] : "";
                    $transactionInfoNotaStr .= isset($transactionInfo['fixedElements']) ? $transactionInfo['fixedElements'] : "";
                    $transactionInfoNotaStr .= isset($transactionInfo['paymentMethod']) ? $transactionInfo['paymentMethod'] : "";

                }
                else {

                    if (isset($mainElements['paymentMethod']) || isset($mainElements['returnMethod']) || isset($sumRows) && sizeof($sumRows) > 0) {
                        $elementLabels['customers'] = $cPrint_customers = isset($mainElements['customerDetails']['labelValue']) ? $mainElements['customerDetails']['labelValue'] : $signature['customerSignitures']['contents'];
                    }

                    $cPrint .= "<CENTER><SMALL>$cPrint_tgl|$cPrint_jam|" . trim(strtoupper($cPrint_nota)) . "|" . trim(strtoupper($cPrint_kasir)) . "<BR>";
                    $cPrint .= "<CENTER><SMALL>#" . strtoupper($cPrint_customers) . "<BR>";
                    $cPrint .= "<LINE>";

                    $transactionInfoNotaStr .= "<div style='font-size: 10px;' class='text-center'>$cPrint_tgl|$cPrint_jam|" . trim(strtoupper($cPrint_nota)) . "|" . trim(strtoupper($cPrint_kasir)) . "</div>";
                    $transactionInfoNotaStr .= "<div style='font-size: 10px;' class='text-center'>#" . strtoupper($cPrint_customers) . "</div>";
                }

                if (isset($mainElements['paymentMethod']) || isset($mainElements['returnMethod']) || isset($sumRows) && sizeof($sumRows) > 0) {
                    $cPrint .= "<SMALL>NAMA BARANG / KODE<br>";
                    $cPrint .= "<SMALL>         QTY         HRG         TOTAL  <BR>";

                    if ($mainValues['disc'] > 0) {
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-12 no-padding'>ITEM/KODE</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-center col-xs-3 no-padding'>QTY</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-3 no-padding'>H.SATUAN</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-3 no-padding'>DISC</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-3 no-padding'>TOTAL</div>";
                    }
                    else {
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-12 no-padding'>ITEM/KODE</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-4 no-padding'>QTY</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-4 no-padding'>H.SATUAN</div>";
                        $headerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-4 no-padding'>TOTAL</div>";
                    }


                }
                else {

                    $cPrint .= "<SMALL>NAMA BARANG / KODE           QTY    SATUAN<br>";

                    $headerNotaStr .= "<div style='font-size: 10px;' class='col-xs-8 text-bold no-padding text-left'>ITEM/KODE</div>";
                    $headerNotaStr .= "<div style='font-size: 10px;' class='col-xs-2 text-bold no-padding text-right'>QTY</div>";
                    $headerNotaStr .= "<div style='font-size: 10px;' class='col-xs-2 text-bold no-padding text-right'>SATUAN</div>";
                }

                $cPrint .= "<DLINE>";
                $elementLabels['transaction_info'] = $transactionInfoNotaStr;

            }
        }


        $elementLabels['header_nota'] = $headerNotaStr;
        $contentStr .= "<dline></dline>";
        //region produk list
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {

            foreach ($items as $id => $iSpec) {

                //                arrPrint($detailValues[$id][$valueKey]);

                $no++;
                $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]));
                $contentStr .= "<div>";
                $arrKeysItems = array('');

                $produk_nama = isset($detailValues[$id]['produk_nama']) ? $detailValues[$id]['produk_nama'] : isset($iSpec['produk_nama']) ? $iSpec['produk_nama'] : "--";
                $produk_ord_jml = isset($detailValues[$id]['produk_ord_jml']) ? $detailValues[$id]['produk_ord_jml'] : isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : isset($sumRows) && isset($detailValues[$id]['jml']) ? $detailValues[$id]['jml'] : "";
                $produk_satuan = isset($detailValues[$id]['satuan']) ? $detailValues[$id]['satuan'] : isset($iSpec['satuan']) ? $iSpec['satuan'] : "--";

                $harga = isset($valueKey) && isset($detailValues[$id][$valueKey]) ? $detailValues[$id][$valueKey] : 0;

                $produk_diskon = isset($detailValues[$id]['disc']) ? ($detailValues[$id]['disc'] * $produk_ord_jml) : isset($iSpec['disc']) ? ($iSpec['disc'] * $produk_ord_jml) : 0;
                $add_diskon = isset($mainValues['add_disc']) ? $mainValues['add_disc'] : 0;

                $subtotal = isset($items[$id]['subtotal']) ? $items[$id]['subtotal'] : 0;

                $total_produk_ord_jml += $produk_ord_jml;
                $total_produk_diskon += $produk_diskon;
                $grandTotal += $subtotal;

                $contentStr .= "</div>";

                $item_nama = $produk_nama;
                $item_jml = isset($sumRows) ? isset($detailValues[$id]['jml']) ? number_format($detailValues[$id]['jml']) : number_format($produk_ord_jml) : number_format($produk_ord_jml);
                $item_hrg = number_format($harga);
                $item_subTotal = number_format($subtotal);
                $item_satuan = $produk_satuan;
                $item_disc = $produk_diskon;

                $strCountNama = strlen($item_nama);
                $strCountJml = strlen($item_jml);
                $strCountHrg = strlen($item_hrg);
                $strCountSub = strlen($item_subTotal);
                $strCountSat = strlen($item_satuan);
                $strCountDisc = strlen($item_disc);

                $item_nama_f = "";
                $item_hrg_f = "";
                $item_jml_f = "";
                $item_subTotal_f = "";
                $item_disc_f = number_format($item_disc);

                $maxStringColumn = 0;
                $maxStringColumn1 = 0;
                $maxStringColumn2 = 0;

                if (isset($mainElements['paymentMethod']) || isset($mainElements['returnMethod']) || isset($sumRows) && sizeof($sumRows) > 0) {

                    if ($strCountNama < $maxStringLength) {
                        $spaceRepeat = (int)$maxStringLength - (int)$strCountNama;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_nama_f = "$item_nama$addSpace";
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";
                        }
                    }
                    elseif ($strCountNama == $maxStringLength) {
                        $item_nama_f = "$item_nama";
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";
                        }
                    }
                    else {
                        $item_nama_f = "$item_nama";
                        $vowels = array("a", "e", "i", "o", "u");
                        $item_nama_f = str_replace($vowels, " ", ucwords($item_nama_f));
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";
                        }
                        elseif (strlen($item_nama_f) < $maxStringLength) {
                            $spaceRepeat = (int)$maxStringLength - (int)strlen($item_nama_f);
                            $addSpace = str_repeat(' ', $spaceRepeat);
                            $item_nama_f = "$item_nama_f$addSpace";
                            if (strlen($item_nama_f) == $maxStringLength) {
                                $cPrint .= "<SMALL>$item_nama_f";
                            }
                        }
                        else {
                            $item_nama_f = "$item_nama";
                            $charDot = 3;
                            $item_nama_f = substr($item_nama_f, 0, 39);
                            $item_nama_f = $item_nama_f . str_repeat(".", $charDot);
                            if (strlen($item_nama_f) == $maxStringLength) {
                                $cPrint .= "<SMALL>$item_nama_f";
                            }
                        }
                    }
                    $maxStringColumn = ($maxStringLength / 3);
                    //region jumlah
                    if ($strCountJml < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountJml;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_jml_f = "$addSpace$item_jml";
                    }
                    elseif ($strCountJml == $maxStringColumn) {
                        $item_jml_f = "$item_jml";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion jumlah
                    //region harga
                    if ($strCountHrg < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountHrg;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_hrg_f = "$addSpace$item_hrg";
                    }
                    elseif ($strCountHrg == $maxStringColumn) {
                        $item_hrg_f = "$item_hrg";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion harga
                    //region subTotal
                    if ($strCountSub < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountSub;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_subTotal_f = "$addSpace$item_subTotal";
                    }
                    elseif ($strCountSub == $maxStringColumn) {
                        $item_subTotal_f = "$item_subTotal";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion subTotal

                    $cPrint .= "<SMALL>$item_jml_f$item_hrg_f$item_subTotal_f<br>";

                    if ($mainValues['disc'] > 0) {
                        $contentStr .= "<div style='font-size: 10px;' class='text-left col-xs-12 no-padding'>$item_nama_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-3 no-padding'>$item_jml_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-3 no-padding'>$item_hrg_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-3 no-padding'>$item_disc_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-3 no-padding'>$item_subTotal_f</div>";
                    }
                    else {
                        $contentStr .= "<div style='font-size: 10px;' class='text-left col-xs-12 no-padding'>$item_nama_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-4 no-padding'>$item_jml_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-4 no-padding'>$item_hrg_f</div>";
                        $contentStr .= "<div style='font-size: 10px;' class='text-right col-xs-4 no-padding'>$item_subTotal_f</div>";
                    }


                }
                else {

                    $maxStringColumnNama = 22;
                    $maxStringColumnQty = 10;
                    $maxStringColumnSatuan = 10;

                    $item_nama_f = "";
                    $item_jml_f = "";
                    $item_satuan_f = "";

                    if ($strCountNama < $maxStringColumnNama) {
                        $spaceRepeat = (int)$maxStringColumnNama - (int)$strCountNama;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_nama_f = "$item_nama$addSpace";
                        if (strlen($item_nama_f) == $maxStringColumnNama) {
                            $item_nama_f = "$item_nama_f";
                        }
                    }
                    elseif ($strCountNama == $maxStringColumnNama) {
                        $item_nama_f = "$item_nama";
                        if (strlen($item_nama_f) == $maxStringColumnNama) {
                            $item_nama_f = "$item_nama_f";
                        }
                    }
                    else {
                        $item_nama_f = "$item_nama";
                        $vowels = array("a", "e", "i", "o", "u");
                        $item_nama_f = str_replace($vowels, " ", ucwords($item_nama_f));
                        if (strlen($item_nama_f) == $maxStringColumnNama) {
                            $item_nama_f = "$item_nama_f";
                        }
                        elseif (strlen($item_nama_f) < $maxStringColumnNama) {
                            $spaceRepeat = (int)$maxStringColumnNama - (int)strlen($item_nama_f);
                            $addSpace = str_repeat(' ', $spaceRepeat);
                            $item_nama_f = "$item_nama_f$addSpace";
                            if (strlen($item_nama_f) == $maxStringColumnNama) {
                                $item_nama_f = "<SMALL>$item_nama_f";
                            }
                        }
                        else {
                            $item_nama_f = "$item_nama";
                            $charDot = 3;
                            $item_nama_f = substr($item_nama_f, 0, 29);
                            $item_nama_f = $item_nama_f . str_repeat(".", $charDot);
                            if (strlen($item_nama_f) == $maxStringColumnNama) {
                                $item_nama_f = "$item_nama_f";
                            }
                        }
                    }
                    if ($strCountJml < $maxStringColumnQty) {
                        $spaceRepeat = (int)$maxStringColumnQty - (int)$strCountJml;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_jml_f = "$addSpace$item_jml";
                    }
                    elseif ($strCountJml == $maxStringColumnQty) {
                        $item_jml_f = "$item_jml";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    if ($strCountSat < $maxStringColumnSatuan) {
                        $spaceRepeat = (int)$maxStringColumnSatuan - (int)$strCountSat;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_satuan_f = "$addSpace$item_satuan";
                    }
                    elseif ($strCountSat == $maxStringColumnSatuan) {
                        $item_satuan_f = "$item_satuan";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    $cPrint .= "<SMALL>$item_nama_f$item_jml_f$item_satuan_f<br>";

                    //                    $contentStr .= "<div style='font-size: 10px;' class='col-xs-12 text-bold no-padding text-left'>$item_nama_f$item_jml_f$item_satuan_f</div>";
                    $contentStr .= "<div style='font-size: 10px;' class='col-xs-8 no-padding text-left'>$item_nama</div>";
                    $contentStr .= "<div style='font-size: 10px;' class='col-xs-2 no-padding text-right'>$item_jml</div>";
                    $contentStr .= "<div style='font-size: 10px;' class='col-xs-2 no-padding text-right'>$item_satuan</div>";

                }
            }

        }

        $cPrint .= "<LINE>";

        $totalItems = count($items);
        $elementLabels["content"] = $contentStr;
        $elementLabels["totalItems"] = "ITEM(s)=" . $totalItems;
        $elementLabels["totalUnit"] = "UNIT(s)=" . $total_produk_ord_jml;
        $elementLabels["totalDiskon"] = $total_produk_diskon;

        if ($total_produk_diskon > 0) {
            $elementLabels["hemat"] = number_format($total_produk_diskon);
            $elementLabels["text_hemat"] = "LEBIH HEMAT  ------------> ";
        }
        else {
            $elementLabels["hemat"] = "";
            $elementLabels["text_hemat"] = "";
        }

        $elementLabels["grandTotal"] = number_format($grandTotal);
        $elementLabels["hargaJual"] = number_format($grandTotal);
        $elementLabels["harusDibayar"] = number_format($grandTotal - $total_produk_diskon - $add_diskon);
        $elementLabels["smallPrint"] = "$template";
        $elementLabels["add_disc"] = number_format($add_diskon);

        $elementLabels["paymentMethodText"] = "";
        $elementLabels["paymentMethodValue"] = "";


        if (isset($mainElements['paymentMethod']) || isset($mainElements['returnMethod']) || isset($sumRows) && sizeof($sumRows) > 0) {
            $grandTotalc = number_format($grandTotal);
            $strCountGrandTotal = strlen($grandTotalc);
            $grandTotal_f = "";
            if ($strCountGrandTotal < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - (int)$strCountGrandTotal;
                $addSpace = str_repeat(' ', $spaceRepeat);
                $grandTotal_f = "$addSpace$grandTotalc";
                if (strlen($grandTotal_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$grandTotal_f";
                }
            }
            elseif ($strCountGrandTotal == $maxStringLength) {
                $grandTotal_f = "$grandTotalc";
                if (strlen($grandTotal_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$grandTotal_f";
                }
            }
            else {
                // sepertinya belum mungkin grand total sampai melebihi 42 character
            }
            $cPrint .= "<SMALL><BOLD>                             =============";

            $footerNotaStr .= "<div>&nbsp;</div>";
            $footerNotaStr .= "<div style='font-size: 14px;' class='text-bold text-right col-xs-12 no-padding'><i>$grandTotalc</i></div>";
            $footerNotaStr .= "<div style='font-size: 14px;' class='text-bold text-right col-xs-12 no-padding'>=============</div>";

        }

        $footerNotaStr .= "<div>&nbsp;</div>";

        $items = "ITEM(s)=" . $totalItems;
        $strCountItems = strlen($items);
        $items_f = "";
        if ($strCountItems < $maxStringLength) {
            $spaceRepeat = (int)$maxStringLength - (int)$strCountItems;
            $addSpace = str_repeat(' ', $spaceRepeat);
            $items_f = "$addSpace$items";
            if (strlen($items_f) == $maxStringLength) {
                $cPrint .= "<SMALL>$items_f<br>";
            }
        }
        elseif ($strCountItems == $maxStringLength) {
            $items_f = "$items";
            if (strlen($items_f) == $maxStringLength) {
                $cPrint .= "<SMALL>$items_f<br>";
            }
        }
        else {
            // sepertinya belum mungkin items sampai melebihi 42 character
        }

        $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-12 no-padding'>$items</div>";

        $units = "UNIT(s)=" . $total_produk_ord_jml;
        $strCountUnits = strlen($units);
        $units_f = "";
        if ($strCountUnits < $maxStringLength) {
            $spaceRepeat = (int)$maxStringLength - (int)$strCountUnits;
            $addSpace = str_repeat(' ', $spaceRepeat);
            $units_f = "$addSpace$units";
            if (strlen($units_f) == $maxStringLength) {
                $cPrint .= "<SMALL>$units_f<br>";
            }
        }
        elseif ($strCountUnits == $maxStringLength) {
            $units_f = "$units";
            if (strlen($units_f) == $maxStringLength) {
                $cPrint .= "<SMALL>$units_f<br>";
            }
        }
        else {
            // sepertinya belum mungkin units sampai melebihi 42 character
        }
        $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold text-right col-xs-12 no-padding'>$units</div>";
        $footerNotaStr .= "<div>&nbsp;</div>";

        if (isset($mainElements['paymentMethod'])) {

            if ($mainElements['paymentMethod']['labelValue'] != 'credit') {
                $txtJual = "HARGA ......................:";
                $hrgJual = number_format($grandTotal);
                $strCountTxtJual = strlen($txtJual);
                $strCountJual = strlen($hrgJual);

                $hrgJual_f = "";
                if ($strCountJual < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountJual + (int)$strCountTxtJual);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgJual_f = "$txtJual$addSpace$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                elseif (((int)$strCountJual + (int)$strCountTxtJual) == $maxStringLength) {
                    $hrgJual_f = "$txtJual$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtJual</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgJual</div>";

                $txtDisc = "DISKON TAMBAHAN ............:";
                $hrgDisc = number_format($add_diskon);
                $strCountTxtDisc = strlen($txtDisc);
                $strCountDisc = strlen($hrgDisc);

                $hrgDisc_f = "";
                if ($strCountDisc < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountDisc + (int)$strCountTxtDisc);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgDisc_f = "$txtDisc$addSpace$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                elseif (((int)$strCountDisc + (int)$strCountTxtDisc) == $maxStringLength) {
                    $hrgDisc_f = "$txtDisc$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtDisc</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgDisc</div>";

                $txtTotals = "TOTAL YANG HARUS DIBAYAR....:";
                $hrgTotals = number_format($grandTotal);
                $strCountTxtTotals = strlen($txtTotals);
                $strCountTotals = strlen($hrgTotals);

                $hrgTotals_f = "";
                if ($strCountTotals < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTotals + (int)$strCountTxtTotals);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTotals_f = "$txtTotals$addSpace$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTotals_f";
                    }
                }
                elseif (((int)$strCountTotals + (int)$strCountTxtTotals) == $maxStringLength) {
                    $hrgTotals_f = "$txtTotals$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTotals_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTotals</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTotals</div>";

            }
            else {
                $txtJual = "HARGA ......................:";
                $hrgJual = number_format($grandTotal);
                $strCountTxtJual = strlen($txtJual);
                $strCountJual = strlen($hrgJual);

                $hrgJual_f = "";
                if ($strCountJual < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountJual + (int)$strCountTxtJual);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgJual_f = "$txtJual$addSpace$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                elseif (((int)$strCountJual + (int)$strCountTxtJual) == $maxStringLength) {
                    $hrgJual_f = "$txtJual$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtJual</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgJual</div>";

                $txtDisc = "DISKON TAMBAHAN ............:";
                $hrgDisc = number_format($add_diskon);
                $strCountTxtDisc = strlen($txtDisc);
                $strCountDisc = strlen($hrgDisc);

                $hrgDisc_f = "";
                if ($strCountDisc < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountDisc + (int)$strCountTxtDisc);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgDisc_f = "$txtDisc$addSpace$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                elseif (((int)$strCountDisc + (int)$strCountTxtDisc) == $maxStringLength) {
                    $hrgDisc_f = "$txtDisc$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtDisc</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgDisc</div>";

                $txtTotals = "TOTAL YANG BELUM DIBAYAR....:";
                $hrgTotals = number_format($grandTotal);
                $strCountTxtTotals = strlen($txtTotals);
                $strCountTotals = strlen($hrgTotals);

                $hrgTotals_f = "";
                if ($strCountTotals < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTotals + (int)$strCountTxtTotals);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTotals_f = "$txtTotals$addSpace$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL><UNDERLINE>$hrgTotals_f";
                    }
                }
                elseif (((int)$strCountTotals + (int)$strCountTxtTotals) == $maxStringLength) {
                    $hrgTotals_f = "$txtTotals$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL><UNDERLINE>$hrgTotals_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTotals</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTotals</div>";

                $footerNotaStr .= "<div>&nbsp;</div>";

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-12 text-left no-padding'>#" . strtoupper($mainElements['paymentMethod']['labelValue']) . "</div>";

                $cPrint .= "<SMALL><br>";
                $cPrint .= "<SMALL><BOLD><CENTER>#" . strtoupper($mainElements['paymentMethod']['labelValue']) . "";

            }


        }
        else {

            if (isset($mainElements['returnMethod'])) {

                $txtJual = "HARGA ......................:";
                $hrgJual = number_format($grandTotal);
                $strCountTxtJual = strlen($txtJual);
                $strCountJual = strlen($hrgJual);

                $hrgJual_f = "";
                if ($strCountJual < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountJual + (int)$strCountTxtJual);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgJual_f = "$txtJual$addSpace$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                elseif (((int)$strCountJual + (int)$strCountTxtJual) == $maxStringLength) {
                    $hrgJual_f = "$txtJual$hrgJual";
                    if (strlen($hrgJual_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgJual_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtJual</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgJual</div>";

                $txtDisc = "DISKON TAMBAHAN ............:";
                $hrgDisc = number_format($add_diskon);
                $strCountTxtDisc = strlen($txtDisc);
                $strCountDisc = strlen($hrgDisc);

                $hrgDisc_f = "";
                if ($strCountDisc < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountDisc + (int)$strCountTxtDisc);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgDisc_f = "$txtDisc$addSpace$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                elseif (((int)$strCountDisc + (int)$strCountTxtDisc) == $maxStringLength) {
                    $hrgDisc_f = "$txtDisc$hrgDisc";
                    if (strlen($hrgDisc_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgDisc_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtDisc</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgDisc</div>";

                $txtTotals = "TOTAL YANG AKAN DIRETURN....:";
                $hrgTotals = number_format($grandTotal);
                $strCountTxtTotals = strlen($txtTotals);
                $strCountTotals = strlen($hrgTotals);

                $hrgTotals_f = "";
                if ($strCountTotals < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTotals + (int)$strCountTxtTotals);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTotals_f = "$txtTotals$addSpace$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL><UNDERLINE>$hrgTotals_f";
                    }
                }
                elseif (((int)$strCountTotals + (int)$strCountTxtTotals) == $maxStringLength) {
                    $hrgTotals_f = "$txtTotals$hrgTotals";
                    if (strlen($hrgTotals_f) == $maxStringLength) {
                        $cPrint .= "<SMALL><UNDERLINE>$hrgTotals_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTotals</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTotals</div>";
                $footerNotaStr .= "<div>&nbsp;</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>#" . strtoupper($mainElements['returnMethod']['labelValue']) . "</div>";

                //                if($mainElements['returnMethod']['labelValue']=='cash'){
                //                    $cPrint    .= "<BR>";
                //                    $cPrint    .= "<SMALL><BOLD><CENTER>#".strtoupper($mainElements['returnMethod']['labelValue'])."";
                //                }
                //                else{
                //                    $cPrint    .= "<BR>";
                //                    $cPrint    .= "<SMALL><BOLD><CENTER>#".strtoupper($mainElements['returnMethod']['labelValue'])."";
                //                }

            }


            if (isset($sumRows) && sizeof($sumRows) > 0) {

                $maxStringLabel = 31;
                $maxStringValue = 11;

                foreach ($sumRows as $key => $label) {
                    $cPrint .= "<BR>";
                    $label = strtoupper($label);
                    $strCountLabel = strlen($label);
                    $label_f = "";

                    if ($strCountLabel < $maxStringLabel) {
                        $spaceRepeat = (int)$maxStringLabel - (int)$strCountLabel;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $label_f = "$addSpace$label";
                        if (strlen($label_f) == $strCountLabel) {
                            $label_f = "$label_f";
                        }
                    }
                    elseif ((int)$strCountLabel == $maxStringLabel) {
                        $label_f = "$label";
                        if (strlen($label_f) == $maxStringLabel) {
                            $label_f = "$label_f";
                        }
                    }
                    else {
                        // sepertinya belum mungkin units sampai melebihi 42 character
                    }

                    $values_f = "";

                    if (isset($mainValues[$key])) {

                        $values = number_format($mainValues[$key]);
                        $strCountValues = strlen($values);

                        if ($strCountValues < $maxStringValue) {
                            $spaceRepeat = (int)$maxStringValue - (int)$strCountValues;
                            $addSpace = str_repeat(' ', $spaceRepeat);
                            $values_f = "$addSpace$values";
                            if (strlen($values_f) == (int)$maxStringValue) {
                                $values_f = "$values_f";
                            }
                        }
                        elseif ((int)$strCountValues == (int)$maxStringValue) {
                            $values_f = "$values";
                            if (strlen($values_f) == $maxStringValue) {
                                $values_f = "$values_f";
                            }
                        }
                        else {
                            // sepertinya belum mungkin units sampai melebihi 42 character
                        }

                    }
                    else {
                        $values_f = "          0";
                    }

                    $cPrint .= "<SMALL><BOLD>$label_f$values_f";

                    $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$label</div>";
                    $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>" . $mainValues[$key] . "</div>";

                }
            }
        }


        if ($paymentMethodKey == 'cash') {

            $paymentMethodValue = isset($mainValues['bayar']) ? $mainValues['bayar'] : $grandTotal;
            $kembali = isset($mainValues['kembali']) ? $mainValues['kembali'] : ($paymentMethodValue - $grandTotal);
            $elementLabels["paymentMethodText"] = "TUNAI.......................:";
            $elementLabels["paymentMethodValue"] = number_format($paymentMethodValue);
            $elementLabels["kembaliText"] = "KEMBALI.....................:";
            $elementLabels["kembali"] = number_format($kembali);

            $txtTunai = "TUNAI.......................:";
            $hrgTunai = number_format($paymentMethodValue);
            $strCountTxtTunai = strlen($txtTunai);
            $strCountTunai = strlen($hrgTunai);

            $hrgTunai_f = "";
            if ($strCountTunai < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                $hrgTunai_f = "$txtTunai$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTunai</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTunai</div>";

            $txtKembali = "KEMBALI.....................:";
            $hrgKembali = number_format($kembali);
            $strCountTxtKembali = strlen($txtKembali);
            $strCountKembali = strlen($hrgKembali);

            $hrgKembali_f = "";
            if ($strCountKembali < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                $hrgKembali_f = "$txtKembali$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtKembali</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgKembali</div>";

        }
        elseif ($paymentMethodKey == 'credit_card') {

            //            $type = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account']) ? $masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account'] : $masterGates['credit_account'];
            $type = isset($masterGates['credit_account']) ? $masterGates['credit_account'] : "";

            $cardNumber = isset($masterGates['card_number']) ? $mainValues['card_number'] : "";
            $cardNumber = FormatCreditCard($cardNumber);
            $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

            $cardName = isset($masterGates['card_name']) ? $masterGates['card_name'] : "";
            //arrPrint($mainValues);
            //            if($total_produk_diskon>0){
            $grandTotal = $grandTotal - $total_produk_diskon - $add_diskon;
            //            }else{
            //
            //            }

            $paymentMethodText = "Kartu Kredit";
            $type = str_replace('_', ' ', $type);
            $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
            $elementLabels["paymentMethodText"] = "CC." . $cardNumber . " ........:";
            $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
            $elementLabels["kembaliText"] = "<span class='text-capitalize'>Amount: " . round($grandTotal) . "</span>";
            $elementLabels["kembali"] = " ";

            $txtTunai = "CC." . $cardNumber . " ........:";
            $hrgTunai = number_format($grandTotal);
            $strCountTxtTunai = strlen($txtTunai);
            $strCountTunai = strlen($hrgTunai);

            $hrgTunai_f = "";
            if ($strCountTunai < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                $hrgTunai_f = "$txtTunai$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTunai</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTunai</div>";

            $txtKembali = "Amount: $grandTotal";
            $hrgKembali = " ";
            $strCountTxtKembali = strlen($txtKembali);
            $strCountKembali = strlen($hrgKembali);

            $hrgKembali_f = "";
            if ($strCountKembali < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                $hrgKembali_f = "$txtKembali$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtKembali</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgKembali</div>";


        }
        elseif ($paymentMethodKey == 'debit_card') {

            $type = isset($masterGates['debit_account']) ? $masterGates['debit_account'] : "";
            $cardNumber = isset($masterGates['card_number']) ? $mainValues['card_number'] : "";
            $cardNumber = FormatCreditCard($cardNumber);
            $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

            $cardName = isset($masterGates['card_name']) ? $masterGates['card_name'] : "";
            $paymentMethodText = "Kartu Debit";
            $type = str_replace('_', ' ', $type);

            $grandTotal = $grandTotal - $total_produk_diskon - $add_diskon;

            $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;

            $elementLabels["paymentMethodText"] = "DC." . $cardNumber . " ........:";
            $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
            $elementLabels["kembaliText"] = "<span class='text-capitalize'>Amount: " . round($grandTotal) . "</span>";
            $elementLabels["kembali"] = " ";

            $txtTunai = "DC." . $cardNumber . " ........:";
            $hrgTunai = number_format($grandTotal);
            $strCountTxtTunai = strlen($txtTunai);
            $strCountTunai = strlen($hrgTunai);

            $hrgTunai_f = "";
            if ($strCountTunai < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                $hrgTunai_f = "$txtTunai$hrgTunai";
                if (strlen($hrgTunai_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTunai_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTunai</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTunai</div>";

            $txtKembali = "Amount: $grandTotal";
            $hrgKembali = " ";
            $strCountTxtKembali = strlen($txtKembali);
            $strCountKembali = strlen($hrgKembali);

            $hrgKembali_f = "";
            if ($strCountKembali < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                $hrgKembali_f = "$txtKembali$hrgKembali";
                if (strlen($hrgKembali_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgKembali_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtKembali</div>";
            $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgKembali</div>";

        }
        else {

            $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
            $elementLabels["paymentMethodText"] = "--";
            $elementLabels["paymentMethodValue"] = "--";
            $elementLabels["kembaliText"] = "";
            $elementLabels["kembali"] = "";

            if (isset($mainElements['paymentMethod'])) {
                $txtTunai = " ";
                $hrgTunai = " ";
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f<br>";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f<br>";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtTunai</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgTunai</div>";

                $txtKembali = " ";
                $hrgKembali = " ";
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f<br>";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f<br>";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-9 text-left no-padding'>$txtKembali</div>";
                $footerNotaStr .= "<div style='font-size: 10px;' class='text-bold col-xs-3 text-right no-padding'>$hrgKembali</div>";

            }

        }

        //endregion

        //custom elementLabels

        $elementLabels['footer_nota'] = $footerNotaStr;

        $p = New Layout("$title", "", "application/template/582sr.html");
        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        $cPrint .= "<SMALL>                                          <br>";
        $cPrint .= "<CENTER><SMALL>** Terima Kasih **<br>";
        $cPrint .= "<SMALL>------------------------------------------<br>";


        $arrTags['cPrint'] = $cPrint;

        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewReceiptOpname":

        if (isset($mainElements)) {
            if (sizeof($mainElements) > 0) {
                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='padding: 5px;'>";
                        $elementStr .= "<table>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            if (!empty($val)) {
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                                if (isset($elementConfigs[$eKey]['elementType'])) {
                                    switch ($elementConfigs[$eKey]['elementType']) {
                                        case "dataModel":
                                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) && $elementConfigs[$eKey]['usedFields'][$e] != "" ? $elementConfigs[$eKey]['usedFields'][$e] . "" : "";
                                            break;
                                        case "dataField":
                                            $colLabel = isset($elementConfigs[$eKey]['labelSrc']) && $elementConfigs[$eKey]['labelSrc'] != "" ? $elementConfigs[$eKey]['labelSrc'] . "" : "";
                                            break;
                                    }
                                }
                                else {
                                    $colLabel = $e ? $e : "";
                                }
                                if (!is_numeric($e)) {
                                    //                                    $elementStr .= $colLabel!="" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>$val</td>" : "<td colspan='3'>$val</td>";
                                    $elementStr .= $colLabel != "" ? "<td style='width: 1em;white-space: nowrap;vertical-align: top;'>$colLabel</td><td style='width: 1em;white-space: nowrap;vertical-align: top;'> : </td><td style='vertical-align: top;' class='text-uppercase'>" . $val . "</td>" : "<td colspan='3'>" . $val . "</td>";
                                    /* ==============================================
                                     * format helper diaturdr controler
                                     * ==============================================*/
                                }
                                else {
                                    if (!empty($val)) {

                                        if ($eKey == 'noteDetails') {
                                            $vals = str_replace("<br>", "", $val);
                                            $val = str_replace("\n", '<br>', $vals);
                                        }
                                        //                                        cekHere($eKey);

                                        $elementStr .= "<td colspan='3'>" . $val . "</td>";
                                    }
                                }
                                $elementStr .= "<tr line=" . __LINE__ . ">";
                            }
                        }
                        $elementStr .= "</table>";
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                    if ($eKey == 'so_number') {
                        foreach ($mainElements[$eKey]['contents'] as $ey => $vo) {
                            $elementLabels['so_number'] = $vo;
                        }
                    }
                }
                $elementLabels['footer'] = sizeof($footer) > 0 ? $footer : "";
            }
        }

        if (sizeof($signHeader) > 0) {
            foreach ($signHeader as $key => $specHeader) {
                $elementHdr = "<div>";
                foreach ($specHeader as $value) {
                    $elementHdr .= "<div class='col-md-4 col-xs-4'>$value</div>";
                }
                $elementHdr .= "<div>";
                $elementLabels[$key] = $elementHdr;
            }
        }


        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            $no = 0;
            $total_qty = 0;
            $contentStr = "";
            if (isset($items) && sizeof($items) > 0) {
                $contentStr .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr .= "<tr bgcolor='#f5f5f5'>";
                $contentStr .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";


                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr .= $label . "";
                    $contentStr .= "</th>";
                }


                $contentStr .= "</tr>";
                foreach ($items as $id => $iSpec) {

                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]), array_filter($arrItemsRegistries[$id]));
                    $contentStr .= "<tr line=" . __LINE__ . ">";
                    $contentStr .= "<td align='right'>";
                    $contentStr .= $no;
                    $contentStr .= ".</td>";

                    foreach ($itemLabels as $key => $label) {
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "0";
                        $contentStr .= "<td>";
                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        if (is_numeric($val)) {

                            if (!isset($total_bawah[$key])) {
                                $total_bawah[$key] = 0;
                            }
                            $total_bawah[$key] += $val;
                        }
                    }

                    $contentStr .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items[$id]['note']) && strlen($items[$id]['note']) > 1) {
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\">";
                            $iVal = isset($items[$id]['note']) ? $items[$id]['note'] : "";
                            $string = str_replace("\n", "<br>", $iVal);
                            $string = str_replace("\r", "<br>", $string);
                            $contentStr .= $string;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }

                    $total_qty += isset($iSpec['produk_ord_jml']) ? $iSpec['produk_ord_jml'] : 0;


                }

                if (strlen($inWord) > 5) {
                    $mainColspan = sizeof($itemLabels);
                    $colspan = $mainColspan - 2;
                    $rowspan = sizeof($sumRows) + 1;
                    $colspan2 = $mainColspan - $colspan;
                }
                else {
                    $colspan2 = sizeof($itemLabels);
                    $rowspan = "";
                }

                // arrPrint($mainValues);
                // arrPrint($sumRows);
                //                arrPrint($inWord);
                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    if (strlen($inWord) > 5) {
                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td style='vertical-align: bottom;' colspan='$colspan' rowspan='$rowspan' class='text-left'>In Words :<br> <span class='text-bold text-uppercase'>$inWord</span></td>";
                        $contentStr .= "</tr>";
                    }
                    //                    arrPrint($mainValues);

                    foreach ($sumRows as $key => $label) {

                        //                        if(isset($mainValues[$key]) && $mainValues[$key] > 0){
                        //                        if(isset($mainValues[$key]) && (in_array($key, $zeroAllowed))){
                        if (isset($mainValues[$key])) {
                            if ($mainValues[$key] > 0) {
                                //                                cekHere($mainValues[$key]);
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }
                            elseif (isset($zeroAllowed) && (in_array($key, $zeroAllowed))) {
                                $contentStr .= "<tr line=" . __LINE__ . ">";
                                $contentStr .= "<td colspan='$colspan2' class='text-right'>$label</td>";
                                $contentStr .= "<td class='text-right'>";
                                if (isset($mainValues[$key])) {
                                    $contentStr .= formatField($key, round($mainValues[$key]));
                                }
                                else {
                                    $contentStr .= "0";
                                }
                                $contentStr .= "</td>";
                                $contentStr .= "</tr>";
                            }

                        }
                        //                        cekHere($label." - ".$key." - ".$val);
                    }
                }


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {
                    $contentStr .= "<tr bgcolor='#e5e5e5'>";
                    $contentStr .= "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";
                    $contentStr .= "</tr>";
                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {
                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            $contentStr .= "<td class='text-right'>";
                            if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                            }
                            else {
                                $val = "n/a";
                            }
                            $contentStr .= $val;
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }

                        $contentStr .= "<tr line=" . __LINE__ . ">";
                        $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        $contentStr .= "<td class='text-right'>";

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }

                        $contentStr .= formatField($key, $val);
                        $contentStr .= "</td>";
                        $contentStr .= "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            $contentStr .= "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            $contentStr .= "<td class='text-right'>";
                            $contentStr .= formatField($key . "_tax", $val);
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                        }
                    }
                }


                $contentStr .= "<tr class='text-muted' style='font-weight:bold;'>";
                $contentStr .= "<td></td>";
                foreach ($itemLabels as $key => $label) {
                    $contentStr .= "<td>";
                    if (isset($total_bawah[$key])) {
                        if (is_numeric($total_bawah[$key])) {
                            $contentStr .= formatField($key, $total_bawah[$key]);
                        }
                        else {
                            $contentStr .= "";
                        }
                    }
                    $contentStr .= "</td>";
                }
                $contentStr .= "</tr>";

                $contentStr .= "</table>";
                $contentStr .= "</div>";
            }


            $contentStr2 = "";
            if (isset($items2) && sizeof($items2) > 0) {
                $no = 0;
                $contentStr2 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr2 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr2 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr2 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels2 as $key => $label) {
                    $contentStr2 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr2 .= $label;
                    $contentStr2 .= "</th>";
                }
                $contentStr2 .= "</tr>";
                foreach ($items2 as $id => $iSpec) {
                    $no++;
                    $arrItemsRegistries[$id] = isset($itemsRegistries[$id]) ? $itemsRegistries[$id] : array();
                    $contentStr2 .= "<tr line=" . __LINE__ . ">";
                    $contentStr2 .= "<td align='right'>";
                    $contentStr2 .= $no;
                    $contentStr2 .= ".</td>";
                    foreach ($itemLabels2 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr2 .= "<td>";
                        $contentStr2 .= formatField($key, $val);
                        $contentStr2 .= "</td>";
                    }
                    $contentStr2 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items2[$id]['note']) && strlen($items2[$id]['note']) > 1) {
                            $contentStr2 .= "<tr line=" . __LINE__ . ">";
                            $contentStr2 .= "<td>&nbsp;</td>";
                            $contentStr2 .= "<td colspan='" . sizeof($itemLabels2) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items2[$id]['note']) ? $items2[$id]['note'] : "";
                            $contentStr2 .= $iVal;
                            $contentStr2 .= "</td>";
                            $contentStr2 .= "</tr>";
                        }
                    }
                }
                $contentStr2 .= "</table>";
                $contentStr2 .= "</div>";
            }
            $contentStr4 = "";
            if (isset($items3) && sizeof($items3) > 0) {
                $no = 0;
                $contentStr4 .= "<div class='table-responsive' style='border:0px solid red;'>";
                $contentStr4 .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";
                $contentStr4 .= "<tr bgcolor='#f5f5f5'>";
                $contentStr4 .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($itemLabels3 as $key => $label) {
                    $contentStr4 .= "<th class='text-muted' style='font-weight:bold;'>";
                    $contentStr4 .= $label;
                    $contentStr4 .= "</th>";
                }
                $contentStr4 .= "</tr>";
                foreach ($items3 as $id => $iSpec) {
                    $no++;
                    $arrItems3Registries[$id] = isset($items3Registries[$id]) ? $items3Registries[$id] : array();
                    $contentStr4 .= "<tr line=" . __LINE__ . ">";
                    $contentStr4 .= "<td align='right'>";
                    $contentStr4 .= $no;
                    $contentStr4 .= ".</td>";
                    foreach ($itemLabels3 as $key => $label) {
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );
                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        $val = isset($iSpec[$key]) ? $iSpec[$key] : "";
                        $contentStr4 .= "<td>";
                        $contentStr4 .= formatField($key, $val);
                        $contentStr4 .= "</td>";
                    }
                    $contentStr4 .= "</tr>";
                    if (isset($noteEnabled) && ($noteEnabled == true)) {
                        if (isset($items3[$id]['note']) && strlen($items3[$id]['note']) > 1) {
                            $contentStr4 .= "<tr line=" . __LINE__ . ">";
                            $contentStr4 .= "<td>&nbsp;</td>";
                            $contentStr4 .= "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            $iVal = isset($items3[$id]['note']) ? $items3[$id]['note'] : "";
                            $contentStr4 .= $iVal;
                            $contentStr4 .= "</td>";
                            $contentStr4 .= "</tr>";
                        }
                    }
                }
                $contentStr4 .= "</table>";
                $contentStr4 .= "</div>";
            }


            $contentStr3 = "";
            if (isset($dpValueDetils) && sizeof($dpValueDetils) > 0) {

                $contentStr3 .= "<div class='panel-body'>";
                $contentStr3 .= "<table class='table table-responsive'>";
                foreach ($dpFieldName as $dp_fields => $dpFields_alias) {
                    $contentStr3 .= "<tr line=" . __LINE__ . ">";
                    $contentStr3 .= "<td>$dpFields_alias</td>";
                    $contentStr3 .= "<td class='text-right' style='padding-right: 0px;'>" . number_format(0 + $dpValueDetils[$dp_fields]) . "</td>";
                    $contentStr3 .= "</tr>";
                    //                    $contentStr3 .="<div class='col-md-1 text-right'>$dpFields_alias</div>";
                    //                    $contentStr3 .="<div class='col-md-2 font-size-1-2'>".formatField($dp_fields,$dpValueDetils[$dp_fields])."</div>";
                }
                $contentStr3 .= "</table>";
                $contentStr3 .= "</div>";

            }
            if (sizeof($signature) > 0) {
                foreach ($signature as $iKey => $iSpecs) {
                    $signatureStr = "";
                    $signatureStr .= "<div class='panel panel-default text-center'>";
                    $signatureStr .= "<div class='panel-heading'>";
                    $signatureStr .= isset($iSpecs['label']) ? $iSpecs['label'] : "";
                    $signatureStr .= "</div>";
                    $signatureStr .= "<br><br><br>";
                    $signatureStr .= "<br>";
                    $signatureStr .= "(" . $iSpecs['contents'] . ")";
                    $signatureStr .= "</div>";
                    $elementLabels[$iKey] = $signatureStr;
                }
            }

            $elementLabels["content"] = $contentStr;
            $elementLabels["content_2"] = $contentStr2;
            $elementLabels["content_3"] = $contentStr3;
            $elementLabels["content_4"] = $contentStr4;
        }

        if (isset($mainValues) && isset($mainValues['berat_gross'])) {
            $this->load->helper('he_angka');
            $berat_gross = isset($mainValues['berat_gross']) ? conv_g_kg($mainValues['berat_gross']) : "";
            $volume_gross = isset($mainValues['volume_gross']) ? number_format(conv_mmc_mc($mainValues['volume_gross']), 2) : "";
            $measure = "
            <table class='table table-bordered table-condensed table-hover'>
                <thead>
                    <tr line=" . __LINE__ . ">
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total package (Ctn)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Quantity (Pcs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Weight (Kgs)</th>
                        <th class='bg-grey-1 text-center' width='25%' style='vertical-align:middle;'>Total Measurement (Cbm)</th>
                    </tr>
                    <tr line=" . __LINE__ . "></tr>
                </thead>
                <tbody>
                    <tr line=" . __LINE__ . ">
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$total_qty</td>
                        <td class='text-center'>$berat_gross</td>
                        <td class='text-center'>$volume_gross</td>
                    </tr>
                </tbody>
            </table>";
            $elementLabels["measurement"] = $measure;
        }

        $p = New Layout("$title", "", $template);

        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }


        $p->addTags($arrTags);
        $p->render();

        break;

    case "viewSmallReceipt":

        function FormatCreditCard($cc)
        {
            $cc = str_replace(array('-', ' '), '', $cc);
            $cc_length = strlen($cc);
            $newCreditCard = substr($cc, -4);
            for ($i = $cc_length - 5; $i >= 0; $i--) {
                if ((($i + 1) - $cc_length) % 4 == 0) {
                    $newCreditCard = '-' . $newCreditCard;
                }
                $newCreditCard = $cc[$i] . $newCreditCard;
            }
            return $newCreditCard;
        }

        $paymentMethodKey = "";

        if (isset($mainElements)) {

            //            arrPrint($mainElements);
            if (sizeof($mainElements) > 0) {

                $paymentMethodKey = isset($mainElements['paymentMethod']['key']) ? $mainElements['paymentMethod']['key'] : "";

                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='margin-top:-20px;'>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) ? $elementConfigs[$eKey]['usedFields'][$e] : $e;
                            if (!is_numeric($e)) {
                                $elementStr .= "<span class=''>$colLabel : $val</span><br>";
                            }
                            else {
                                if (!empty($val)) {
                                    $elementStr .= "<span class=''>$val</span><br>";
                                }
                            }
                        }
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                }

                if (sizeof($signature) > 0) {
                    $elementLabels['kasir'] = $signature['sign_1']['contents'];
                    $elementLabels['customers'] = $signature['customerSignitures']['contents'];
                }

                $elementLabels['tanggal'] = date("Y-m-d", strtotime($mainElements['fixedElements']['contents']['Date']));
                $elementLabels['hours'] = date("H:i", strtotime($mainElements['fixedElements']['contents']['Date']));
                $elementLabels['nota'] = isset($mainElements['fixedElements']['contents']['No']) ? $mainElements['fixedElements']['contents']['No'] : "";

            }
        }

        if (isset($headerTablesSmall)) {

            if (sizeof($headerTablesSmall) > 0) {

            }

        }

        //region produk list
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {

            $no = 0;
            $total_produk_ord_jml = 0;
            $total_produk_diskon = 0;
            $produk_diskon = 0;
            $grandTotal = 0;
            $kembali = 0;
            $tunai = 0;
            $contentStr = "";

            foreach ($items as $id => $iSpec) {
                //                arrPrint($iSpec);
                $no++;
                $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]));
                $contentStr .= "<div>";
                $arrKeysItems = array('');

                $produk_ppn = isset($detailValues[$id]['ppn']) ? $detailValues[$id]['ppn'] : $iSpec['ppn'];
                $produk_nama = isset($detailValues[$id]['produk_nama']) ? $detailValues[$id]['produk_nama'] : $iSpec['produk_nama'];
                $produk_ord_jml = isset($detailValues[$id]['produk_ord_jml']) ? $detailValues[$id]['produk_ord_jml'] : $iSpec['produk_ord_jml'];
                $harga = isset($detailValues[$id]['harga_nett1']) ? ($detailValues[$id]['harga_nett1']) : ($iSpec['harga_nett1']);

                $produk_diskon = isset($detailValues[$id]['disc']) ? ($detailValues[$id]['disc'] * $produk_ord_jml) : ($iSpec['disc'] * $produk_ord_jml);
                $add_diskon = isset($mainValues['add_disc']) ? $mainValues['add_disc'] : 0;


                //                $subtotal = isset($detailValues[$id]['subtotal']) ? $detailValues[$id]['subtotal'] : $iSpec['subtotal'];
                $subtotal = isset($items[$id]['subtotal']) ? $items[$id]['subtotal'] : 0;
                $total_produk_ord_jml += $produk_ord_jml;
                $total_produk_diskon += $produk_diskon;
                $grandTotal += $subtotal;

                $contentStr .= "<div style='font-size: .9em;' class='col-xs-12 text-bold no-padding text-left'>";
                $contentStr .= $produk_nama;
                $contentStr .= "</div>";

                if ($mainValues['disc'] > 0) {
                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($produk_ord_jml);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($harga);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($produk_diskon);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-bold text-right'>";
                    $contentStr .= number_format($subtotal);
                    $contentStr .= "</div>";
                }
                else {
                    $contentStr .= "<div class='col-xs-4 no-padding text-center'>";
                    $contentStr .= number_format($produk_ord_jml);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-4 no-padding text-center'>";
                    $contentStr .= number_format($harga);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-4 no-padding text-bold text-right'>";
                    $contentStr .= number_format($subtotal);
                    $contentStr .= "</div>";
                }

                $contentStr .= "</div>";
            }
            //arrPrint($main);

            $totalItems = count($items);
            $elementLabels["content"] = $contentStr;
            $elementLabels["totalItems"] = "ITEM(s)=" . $totalItems;
            $elementLabels["totalUnit"] = "UNIT(s)=" . $total_produk_ord_jml;
            $elementLabels["totalDiskon"] = $total_produk_diskon;

            if ($total_produk_diskon > 0) {
                $elementLabels["hemat"] = number_format($total_produk_diskon);
                $elementLabels["text_hemat"] = "LEBIH HEMAT  ------------> ";
            }
            else {
                $elementLabels["hemat"] = "";
                $elementLabels["text_hemat"] = "";
            }


            $elementLabels["grandTotal"] = number_format($grandTotal);
            $elementLabels["hargaJual"] = number_format($grandTotal);
            $elementLabels["harusDibayar"] = number_format($grandTotal - $total_produk_diskon - $add_diskon);
            $elementLabels["smallPrint"] = "$template";
            $elementLabels["add_disc"] = number_format($add_diskon);

            $elementLabels["paymentMethodText"] = "";
            $elementLabels["paymentMethodValue"] = "";

            if ($paymentMethodKey == 'cash') {

                $paymentMethodValue = isset($mainValues['bayar']) ? $mainValues['bayar'] : $grandTotal;
                $kembali = isset($mainValues['kembali']) ? $mainValues['kembali'] : ($paymentMethodValue - $grandTotal);
                $elementLabels["paymentMethodText"] = "TUNAI.......................:";
                $elementLabels["paymentMethodValue"] = number_format($paymentMethodValue);
                $elementLabels["kembaliText"] = "KEMBALI.....................:";
                $elementLabels["kembali"] = number_format($kembali);

            }
            elseif ($paymentMethodKey == 'credit_card') {
                //                $type = isset($main['paymentMethod_credit_account']) ? $main['paymentMethod_credit_account'] : $main['credit_account'];
                //                $type = isset($main['paymentMethod_' . $paymentMethodKey . '_credit_account']) ? $main['paymentMethod_' . $paymentMethodKey . '_credit_account'] : $main['credit_account'];
                //                $cardNumber = isset($main['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_number'])?$main['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_number']:$main['card_number'];
                //                $cardName = isset($main['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_name'])?$main['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_name']:$main['card_name'];

                $type = isset($main['credit_account']) ? $main['credit_account'] : "";

                $cardNumber = isset($main['card_number']) ? $mainValues['card_number'] : "";
                $cardNumber = FormatCreditCard($cardNumber);
                $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

                $cardName = isset($main['card_name']) ? $main['card_name'] : "";

                $paymentMethodText = "Kartu Kredit";
                $type = str_replace('_', ' ', $type);
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "CC." . $cardNumber . " .....:";
                $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
                $elementLabels["kembaliText"] = "<span class='text-capitalize'>Mr/Ms.$cardName-$grandTotal</span>";
                $elementLabels["kembali"] = " ";
            }
            elseif ($paymentMethodKey == 'debit_card') {
                //                $type = isset($main['paymentMethod_' . $paymentMethodKey . '_debit_account'])?$main['paymentMethod_' . $paymentMethodKey . '_debit_account']:$main['debit_account'];
                $type = isset($main['debit_account']) ? $main['debit_account'] : "";
                //                $cardNumber = isset($main['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_number'])?$main['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_number']:$main['card_number'];
                //                $cardName = isset($main['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_name'])?$main['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_name']:$main['card_name'];
                $cardNumber = isset($main['card_number']) ? $mainValues['card_number'] : "";
                $cardNumber = FormatCreditCard($cardNumber);
                $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

                $cardName = isset($main['card_name']) ? $main['card_name'] : "";
                $paymentMethodText = "Kartu Debit";
                $type = str_replace('_', ' ', $type);
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "DC." . $cardNumber . " .....:";
                $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
                $elementLabels["kembaliText"] = "<span class='text-capitalize'>Mr/Ms.$cardName-$grandTotal</span>";
                $elementLabels["kembali"] = " ";
            }
            else {
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "--";
                $elementLabels["paymentMethodValue"] = "--";
                $elementLabels["kembaliText"] = "";
                $elementLabels["kembali"] = "";
            }
        }
        //endregion

        $p = New Layout("$title", "", "application/template/582sr.html");
        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        $p->addTags($arrTags);
        $p->render();


        break;

    case "viewSmallReceiptBT":

        function FormatCreditCard($cc)
        {
            $cc = str_replace(array('-', ' '), '', $cc);
            $cc_length = strlen($cc);
            $newCreditCard = substr($cc, -4);
            for ($i = $cc_length - 5; $i >= 0; $i--) {
                if ((($i + 1) - $cc_length) % 4 == 0) {
                    $newCreditCard = '-' . $newCreditCard;
                }
                $newCreditCard = $cc[$i] . $newCreditCard;
            }
            return $newCreditCard;
        }

        //smallprint bluetooth
        $maxStringLength = 42;
        $cPrint = "<CENTER><BOLD>SUMBER BERKAT MAKMUR<br>";
        $cPrint .= "<CENTER><SMALL>Jln. Arah Tanjungmera<br>";
        $cPrint .= "<CENTER><SMALL>Manembo Nembo Matuari Bitung<br>";
        $cPrint .= "<CENTER><SMALL>Sulawesi Utara<br>";
        $cPrint .= "<CENTER><SMALL>Telp: (xxx) xxxxxxx<br>";
        $cPrint .= "<CENTER><SMALL>NPWP: 908093693823000<br>";
        $cPrint .= "<SMALL>------------------------------------------<br>";


        $paymentMethodKey = "";

        if (isset($mainElements)) {

            //            arrPrint($mainElements);
            if (sizeof($mainElements) > 0) {

                $paymentMethodKey = isset($mainElements['paymentMethod']['key']) ? $mainElements['paymentMethod']['key'] : "";

                foreach ($mainElements as $eKey => $eSpec) {
                    $elementStr = "";
                    if (isset($eSpec['label'])) {
                        $elementStr .= "<div class='panel panel-heading text-center'>";
                        $elementStr .= $eSpec['label'];
                        $elementStr .= "</div>";
                    }
                    if (sizeof($eSpec['contents'])) {
                        $elementStr .= "<div class='panel-body' style='margin-top:-20px;'>";
                        foreach ($eSpec['contents'] as $e => $val) {
                            $colLabel = isset($elementConfigs[$eKey]['usedFields'][$e]) ? $elementConfigs[$eKey]['usedFields'][$e] : $e;
                            if (!is_numeric($e)) {
                                $elementStr .= "<span class=''>$colLabel : $val</span><br>";
                            }
                            else {
                                if (!empty($val)) {
                                    $elementStr .= "<span class=''>$val</span><br>";
                                }
                            }
                        }
                        $elementStr .= "</div>";
                    }
                    $elementLabels[$eKey] = $elementStr;
                }

                if (sizeof($signature) > 0) {
                    $elementLabels['kasir'] = $cPrint_kasir = $signature['sign_1']['contents'];
                    $elementLabels['customers'] = $cPrint_customers = isset($signature['customerSignitures']['contents']) ? $signature['customerSignitures']['contents'] : "--";
                }

                $elementLabels['tanggal'] = $cPrint_tgl = date("Y-m-d", strtotime($mainElements['fixedElements']['contents']['Date']));
                $elementLabels['hours'] = $cPrint_jam = date("H:i", strtotime($mainElements['fixedElements']['contents']['Date']));
                $elementLabels['nota'] = $cPrint_nota = $mainElements['fixedElements']['contents']['No'];

                $cPrint .= "<CENTER><SMALL><BOLD>$cPrint_tgl $cPrint_jam/$cPrint_nota/$cPrint_kasir<br>";
                $cPrint .= "<CENTER><SMALL>#$cPrint_customers<br>";
                $cPrint .= "<SMALL>------------------------------------------";
                $cPrint .= "<BOLD><SMALL>NAMA BARANG / KODE<br>";
                $cPrint .= "<BOLD><SMALL>         QTY         HRG         TOTAL  <br>";
                $cPrint .= "<SMALL>==========================================";


            }
        }

        if (isset($headerTablesSmall)) {

            if (sizeof($headerTablesSmall) > 0) {

            }

        }

        //arrprint($items);
        //region produk list
        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {

            $no = 0;
            $total_produk_ord_jml = 0;
            $total_produk_diskon = 0;
            $produk_diskon = 0;
            $grandTotal = 0;
            $kembali = 0;
            $tunai = 0;
            $contentStr = "";

            foreach ($items as $id => $iSpec) {
                //                arrPrint($iSpec);
                $no++;
                $items[$id] = array_merge(array_filter($items[$id]), array_filter($detailValues[$id]));
                $contentStr .= "<div>";
                $arrKeysItems = array('');

                //                $produk_ppn = isset($detailValues[$id]['ppn']) ? $detailValues[$id]['ppn'] : $iSpec['ppn'];
                $produk_nama = isset($detailValues[$id]['produk_nama']) ? $detailValues[$id]['produk_nama'] : $iSpec['produk_nama'];
                $produk_ord_jml = isset($detailValues[$id]['produk_ord_jml']) ? $detailValues[$id]['produk_ord_jml'] : $iSpec['produk_ord_jml'];
                $harga = isset($detailValues[$id]['harga_nett1']) ? ($detailValues[$id]['harga_nett1']) : ($iSpec['harga_nett1']);

                $produk_diskon = isset($detailValues[$id]['disc']) ? ($detailValues[$id]['disc'] * $produk_ord_jml) : ($iSpec['disc'] * $produk_ord_jml);
                $add_diskon = isset($mainValues['add_disc']) ? $mainValues['add_disc'] : 0;


                //                $subtotal = isset($detailValues[$id]['subtotal']) ? $detailValues[$id]['subtotal'] : $iSpec['subtotal'];
                $subtotal = isset($items[$id]['subtotal']) ? $items[$id]['subtotal'] : 0;
                $total_produk_ord_jml += $produk_ord_jml;
                $total_produk_diskon += $produk_diskon;
                $grandTotal += $subtotal;

                if ($mainValues['disc'] > 0) {
                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($produk_ord_jml);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($harga);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-center'>";
                    $contentStr .= number_format($produk_diskon);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-3 no-padding text-bold text-right'>";
                    $contentStr .= number_format($subtotal);
                    $contentStr .= "</div>";
                }
                else {
                    $contentStr .= "<div class='col-xs-4 no-padding text-center'>";
                    $contentStr .= number_format($produk_ord_jml);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-4 no-padding text-center'>";
                    $contentStr .= number_format($harga);
                    $contentStr .= "</div>";

                    $contentStr .= "<div class='col-xs-4 no-padding text-bold text-right'>";
                    $contentStr .= number_format($subtotal);
                    $contentStr .= "</div>";
                }

                $contentStr .= "</div>";

                $item_nama = $produk_nama;
                $item_jml = number_format($produk_ord_jml);
                $item_hrg = number_format($harga);
                $item_subTotal = number_format($subtotal);


                $strCountNama = strlen($item_nama);
                $strCountJml = strlen($item_jml);
                $strCountHrg = strlen($item_hrg);
                $strCountSub = strlen($item_subTotal);

                $item_nama_f = "";
                $item_hrg_f = "";
                $item_jml_f = "";
                $item_subTotal_f = "";

                if ($strCountNama > 0) {
                    if ($strCountNama < $maxStringLength) {
                        $spaceRepeat = (int)$maxStringLength - (int)$strCountNama;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_nama_f = "$item_nama$addSpace";
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";

                        }
                    }
                    elseif ($strCountNama == $maxStringLength) {
                        $item_nama_f = "$item_nama";
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";

                        }
                    }
                    else {
                        $item_nama_f = "$item_nama";
                        $vowels = array("a", "e", "i", "o", "u");
                        $item_nama_f = str_replace($vowels, " ", ucwords($item_nama_f));
                        if (strlen($item_nama_f) == $maxStringLength) {
                            $cPrint .= "<SMALL>$item_nama_f";

                        }
                        elseif (strlen($item_nama_f) < $maxStringLength) {
                            $spaceRepeat = (int)$maxStringLength - (int)strlen($item_nama_f);
                            $addSpace = str_repeat(' ', $spaceRepeat);
                            $item_nama_f = "$item_nama_f$addSpace";
                            if (strlen($item_nama_f) == $maxStringLength) {
                                $cPrint .= "<SMALL>$item_nama_f";

                            }
                        }
                        else {
                            $item_nama_f = "$item_nama";
                            $charDot = 3;
                            $item_nama_f = substr($item_nama_f, 0, 39);
                            $item_nama_f = $item_nama_f . str_repeat(".", $charDot);
                            if (strlen($item_nama_f) == $maxStringLength) {
                                $cPrint .= "<SMALL>$item_nama_f";

                            }
                        }
                    }
                }
                if ($strCountJml > 0 && $strCountHrg > 0 && $strCountSub > 0) {
                    $maxStringColumn = ($maxStringLength / 3);
                    //region jumlah
                    if ($strCountJml < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountJml;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_jml_f = "$addSpace$item_jml";
                    }
                    elseif ($strCountJml == $maxStringColumn) {
                        $item_jml_f = "$item_jml";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion jumlah
                    //region harga
                    if ($strCountHrg < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountHrg;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_hrg_f = "$addSpace$item_hrg";
                    }
                    elseif ($strCountHrg == $maxStringColumn) {
                        $item_hrg_f = "$item_hrg";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion harga
                    //region subTotal
                    if ($strCountSub < $maxStringColumn) {
                        $spaceRepeat = (int)$maxStringColumn - (int)$strCountSub;
                        $addSpace = str_repeat(' ', $spaceRepeat);
                        $item_subTotal_f = "$addSpace$item_subTotal";
                    }
                    elseif ($strCountSub == $maxStringColumn) {
                        $item_subTotal_f = "$item_subTotal";
                    }
                    else {
                        // jika lebih dari 14 character gak bisa muncul dulu
                    }
                    //endregion subTotal
                    $cPrint .= "<SMALL>$item_jml_f$item_hrg_f$item_subTotal_f";

                }

            }

            $cPrint .= "<SMALL>------------------------------------------<br>";


            $totalItems = count($items);
            $elementLabels["content"] = $contentStr;
            $elementLabels["totalItems"] = "ITEM(s)=" . $totalItems;
            $elementLabels["totalUnit"] = "UNIT(s)=" . $total_produk_ord_jml;
            $elementLabels["totalDiskon"] = $total_produk_diskon;

            if ($total_produk_diskon > 0) {
                $elementLabels["hemat"] = number_format($total_produk_diskon);
                $elementLabels["text_hemat"] = "LEBIH HEMAT  ------------> ";
            }
            else {
                $elementLabels["hemat"] = "";
                $elementLabels["text_hemat"] = "";
            }


            $elementLabels["grandTotal"] = number_format($grandTotal);
            $elementLabels["hargaJual"] = number_format($grandTotal);
            $elementLabels["harusDibayar"] = number_format($grandTotal - $total_produk_diskon - $add_diskon);
            $elementLabels["smallPrint"] = "$template";
            $elementLabels["add_disc"] = number_format($add_diskon);

            $elementLabels["paymentMethodText"] = "";
            $elementLabels["paymentMethodValue"] = "";


            $grandTotalc = number_format($grandTotal);
            $strCountGrandTotal = strlen($grandTotalc);
            $grandTotal_f = "";
            if ($strCountGrandTotal < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - (int)$strCountGrandTotal;
                $addSpace = str_repeat(' ', $spaceRepeat);
                $grandTotal_f = "$addSpace$grandTotalc";
                if (strlen($grandTotal_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$grandTotal_f";
                }
            }
            elseif ($strCountGrandTotal == $maxStringLength) {
                $grandTotal_f = "$grandTotalc";
                if (strlen($grandTotal_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$grandTotal_f";
                }
            }
            else {
                // sepertinya belum mungkin grand total sampai melebihi 42 character
            }
            $cPrint .= "<SMALL><BOLD>                             =============";


            $items = "ITEM(s)=" . $totalItems;
            $strCountItems = strlen($items);
            $items_f = "";
            if ($strCountItems < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - (int)$strCountItems;
                $addSpace = str_repeat(' ', $spaceRepeat);
                $items_f = "$addSpace$items";
                if (strlen($items_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$items_f";
                }
            }
            elseif ($strCountItems == $maxStringLength) {
                $items_f = "$items";
                if (strlen($items_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$items_f";
                }
            }
            else {
                // sepertinya belum mungkin items sampai melebihi 42 character
            }

            $units = "UNIT(s)=" . $total_produk_ord_jml;
            $strCountUnits = strlen($units);
            $units_f = "";
            if ($strCountUnits < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - (int)$strCountUnits;
                $addSpace = str_repeat(' ', $spaceRepeat);
                $units_f = "$addSpace$units";
                if (strlen($units_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$units_f";
                }
            }
            elseif ($strCountUnits == $maxStringLength) {
                $units_f = "$units";
                if (strlen($units_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$units_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }


            $txtJual = "HARGA ......................:";
            $hrgJual = number_format($grandTotal);
            $strCountTxtJual = strlen($txtJual);
            $strCountJual = strlen($hrgJual);

            $hrgJual_f = "";
            if ($strCountJual < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountJual + (int)$strCountTxtJual);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgJual_f = "$txtJual$addSpace$hrgJual";
                if (strlen($hrgJual_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgJual_f";
                }
            }
            elseif (((int)$strCountJual + (int)$strCountTxtJual) == $maxStringLength) {
                $hrgJual_f = "$txtJual$hrgJual";
                if (strlen($hrgJual_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgJual_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $txtDisc = "DISKON TAMBAHAN ............:";
            $hrgDisc = number_format($add_diskon);
            $strCountTxtDisc = strlen($txtDisc);
            $strCountDisc = strlen($hrgDisc);

            $hrgDisc_f = "";
            if ($strCountDisc < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountDisc + (int)$strCountTxtDisc);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgDisc_f = "$txtDisc$addSpace$hrgDisc";
                if (strlen($hrgDisc_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgDisc_f";
                }
            }
            elseif (((int)$strCountDisc + (int)$strCountTxtDisc) == $maxStringLength) {
                $hrgDisc_f = "$txtDisc$hrgDisc";
                if (strlen($hrgDisc_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgDisc_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }

            $txtTotals = "TOTAL YANG HARUS DIBAYAR....:";
            $hrgTotals = number_format($grandTotal);
            $strCountTxtTotals = strlen($txtTotals);
            $strCountTotals = strlen($hrgTotals);

            $hrgTotals_f = "";
            if ($strCountTotals < $maxStringLength) {
                $spaceRepeat = (int)$maxStringLength - ((int)$strCountTotals + (int)$strCountTxtTotals);
                $addSpace = str_repeat(' ', $spaceRepeat);
                $hrgTotals_f = "$txtTotals$addSpace$hrgTotals";
                if (strlen($hrgTotals_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTotals_f";
                }
            }
            elseif (((int)$strCountTotals + (int)$strCountTxtTotals) == $maxStringLength) {
                $hrgTotals_f = "$txtTotals$hrgTotals";
                if (strlen($hrgTotals_f) == $maxStringLength) {
                    $cPrint .= "<SMALL>$hrgTotals_f";
                }
            }
            else {
                // sepertinya belum mungkin units sampai melebihi 42 character
            }


            if ($paymentMethodKey == 'cash') {

                $paymentMethodValue = isset($mainValues['bayar']) ? $mainValues['bayar'] : $grandTotal;
                $kembali = isset($mainValues['kembali']) ? $mainValues['kembali'] : ($paymentMethodValue - $grandTotal);
                $elementLabels["paymentMethodText"] = "TUNAI.......................:";
                $elementLabels["paymentMethodValue"] = number_format($paymentMethodValue);
                $elementLabels["kembaliText"] = "KEMBALI.....................:";
                $elementLabels["kembali"] = number_format($kembali);

                $txtTunai = "TUNAI.......................:";
                $hrgTunai = number_format($paymentMethodValue);
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $txtKembali = "KEMBALI.....................:";
                $hrgKembali = number_format($kembali);
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }


            }
            elseif ($paymentMethodKey == 'credit_card') {
                $type = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account']) ? $masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account'] : $masterGates['credit_account'];
                //                $cardNumber = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_number'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_number']:$masterGates['card_number'];
                //                $cardName = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_name'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_credit_account_' . $type . '_card_name']:$masterGates['card_name'];

                $type = isset($masterGates['credit_account']) ? $masterGates['credit_account'] : "";

                $cardNumber = isset($masterGates['card_number']) ? $mainValues['card_number'] : "";
                $cardNumber = FormatCreditCard($cardNumber);
                $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

                $cardName = isset($masterGates['card_name']) ? $masterGates['card_name'] : "";

                $paymentMethodText = "Kartu Kredit";
                $type = str_replace('_', ' ', $type);
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "CC." . $cardNumber . " .....:";
                $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
                $elementLabels["kembaliText"] = "<span class='text-capitalize'>Mr/Ms.$cardName-$grandTotal</span>";
                $elementLabels["kembali"] = " ";

                $txtTunai = "CC." . $cardNumber . " .....:";
                $hrgTunai = number_format($grandTotal);
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $txtKembali = "Mr/Ms.$cardName-$grandTotal";
                $hrgKembali = " ";
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

            }
            elseif ($paymentMethodKey == 'debit_card') {
                //                $type = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account']:$masterGates['debit_account'];
                $type = isset($masterGates['debit_account']) ? $masterGates['debit_account'] : "";
                //                $cardNumber = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_number'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_number']:$masterGates['card_number'];
                //                $cardName = isset($masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_name'])?$masterGates['paymentMethod_' . $paymentMethodKey . '_debit_account_' . $type . '_card_name']:$masterGates['card_name'];
                $cardNumber = isset($masterGates['card_number']) ? $mainValues['card_number'] : "";
                $cardNumber = FormatCreditCard($cardNumber);
                $cardNumber = $cardNumber == '' && isset($mainValues['card_number']) ? $mainValues['card_number'] : $cardNumber;

                $cardName = isset($masterGates['card_name']) ? $masterGates['card_name'] : "";
                $paymentMethodText = "Kartu Debit";
                $type = str_replace('_', ' ', $type);
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "DC." . $cardNumber . " .....:";
                $elementLabels["paymentMethodValue"] = number_format($grandTotal);// tidak di pakai
                $elementLabels["kembaliText"] = "<span class='text-capitalize'>Mr/Ms.$cardName-$grandTotal</span>";
                $elementLabels["kembali"] = " ";

                $txtTunai = "DC." . $cardNumber . " .....:";
                $hrgTunai = number_format($grandTotal);
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $txtKembali = "Mr/Ms.$cardName-$grandTotal";
                $hrgKembali = " ";
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

            }
            else {
                $paymentMethodValue = isset($detailValues[$id]['tunai']) ? $detailValues[$id]['tunai'] : $grandTotal;
                $elementLabels["paymentMethodText"] = "--";
                $elementLabels["paymentMethodValue"] = "--";
                $elementLabels["kembaliText"] = "";
                $elementLabels["kembali"] = "";

                $txtTunai = " ";
                $hrgTunai = " ";
                $strCountTxtTunai = strlen($txtTunai);
                $strCountTunai = strlen($hrgTunai);

                $hrgTunai_f = "";
                if ($strCountTunai < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountTunai + (int)$strCountTxtTunai);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgTunai_f = "$txtTunai$addSpace$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                elseif (((int)$strCountTunai + (int)$strCountTxtTunai) == $maxStringLength) {
                    $hrgTunai_f = "$txtTunai$hrgTunai";
                    if (strlen($hrgTunai_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgTunai_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

                $txtKembali = " ";
                $hrgKembali = " ";
                $strCountTxtKembali = strlen($txtKembali);
                $strCountKembali = strlen($hrgKembali);

                $hrgKembali_f = "";
                if ($strCountKembali < $maxStringLength) {
                    $spaceRepeat = (int)$maxStringLength - ((int)$strCountKembali + (int)$strCountTxtKembali);
                    $addSpace = str_repeat(' ', $spaceRepeat);
                    $hrgKembali_f = "$txtKembali$addSpace$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                elseif (((int)$strCountKembali + (int)$strCountTxtKembali) == $maxStringLength) {
                    $hrgKembali_f = "$txtKembali$hrgKembali";
                    if (strlen($hrgKembali_f) == $maxStringLength) {
                        $cPrint .= "<SMALL>$hrgKembali_f";
                    }
                }
                else {
                    // sepertinya belum mungkin units sampai melebihi 42 character
                }

            }
        }
        //endregion

        $p = New Layout("$title", "", "application/template/582sr.html");
        if (sizeof($elementLabels) > 0) {
            foreach ($elementLabels as $tKey => $tValue) {
                $arrTags[$tKey] = $tValue;
            }
        }

        $cPrint .= "<SMALL>                                          ";
        $cPrint .= "<SMALL>                                          ";
        $cPrint .= "<SMALL>                                          <br>";

        $cPrint .= "<CENTER><SMALL>** Terima Kasih **<br>";
        $cPrint .= "<SMALL>------------------------------------------<br>";
        //        $cPrint    .= "<SMALL>------------------------------------------";
        $cPrint .= "<QR>12345678<br>";

        $arrTags['cPrint'] = $cPrint;

        $p->addTags($arrTags);
        $p->render();

        break;

    case "selectPaymentSrc":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();
        $strContent = "";
        $arrayPpnDisabled = (isset($ppnDisabled) && (sizeof($ppnDisabled) > 0)) ? $ppnDisabled : array();
        $kelebihanBayarMethod = (isset($kelebihanBayar)) ? $kelebihanBayar : false;

        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/payment.html");
        //        die($selectProcessor);
        //    arrPrint($items);
        //region onprogress
        if (isset($dueDateReader) && $dueDateReader == "true") {
            $itemLabels["due_date"] = "due date";
            $itemLabels["aging"] = "aging (days)";
        }
        if ($prePrint == true) {
            // cekHere("$prePrint");
            $itemLabels["prePrint"] = "print";
        }


        $strPayment = "";
        $strElements = "";
        if (sizeof($items) > 0) {

            if (isset($isPaymentRadioSelect) && $isPaymentRadioSelect == true) {
                $strContent .= "<div class='card bg-danger text-center text-bold'> HANYA BISA PILIH SALAH SATU </div>";
            }

            $strContent .= "<table class='table cTable-modal table-condensed table-responsive no-padding table-bordered'>";

            $strContent .= "<thead>";
            $strContent .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($itemLabels) > 0) {
                $strContent .= "<th class='text-muted text-center'>";
                $strContent .= "select";
                $strContent .= "</th>";
                foreach ($itemLabels as $key => $label) {
                    $strContent .= "<th class='text-muted'>";
                    $strContent .= $label;
                    $strContent .= "</th>";
                }
            }
            $strContent .= "</tr>";
            $strContent .= "</thead>";

            // arrPrint($items);

            //region tblBody
            $strContent .= "<tbody>";
            foreach ($items as $key => $val) {
                //arrPrint($val);
                $strContent .= "<tr line=" . __LINE__ . ">";
                if (sizeof($itemLabels) > 0) {

                    $qstrLabels = array(
                        "transaksi_id" => "trID",
                        "nomer" => "nomer",
                        "extern_id" => "xID",
                        "tagihan" => "tagihan",
                        "terbayar" => "terbayar",
                        "sisa" => "sisa",
                        "diskon" => "diskon",
                        "extern_nama" => "xID",
                        "tagihan_valas" => "tagihan_valas",
                        "terbayar_valas" => "terbayar_valas",
                        "sisa_valas" => "sisa_valas",
                        "diskon_valas" => "diskon_valas",
                        "valas_id" => "valas_id",
                        "valas_nama" => "valas_nama",
                        "valas_nilai" => "valas_nilai",
                        "id_master" => "id_master",
                        "extern_label2" => "pihakMainName",
                        "extern_nilai2" => "extern_nilai2",
                        "extern_nilai3" => "extern_nilai3",
                        "extern_nilai4" => "extern_nilai4",
                        "pph_23" => "pph_23",
                        "ppn_sisa" => "ppn_payment",
                        "ppn" => "ppn",
                        "extern2_id" => "extern2_id",
                        "extern2_nama" => "extern2_nama",
                        "extern_jenis" => "extern_jenis",
                        "jenis_master" => "jenis_master",
                        //                        "id_master" => "id_master",
                        "target_jenis" => "jenis_source",
                    );
                    $qstr = "";
                    foreach ($qstrLabels as $key => $label) {
                        $qstr .= "&$key=" . $val[$key];
                    }

                    $strContent .= "<td class='" . $val['class_bg'] . "text-muted text-center'>";

                    $checked = "";
                    if (isset($ses_items[$val['transaksi_id']])) {
                        $checked = "checked";
                    }

                    $disabled = "";
                    if (sizeof($arrayPpnDisabled) > 0) {
                        if (in_array($val['transaksi_id'], $arrayPpnDisabled)) {
                            $disabled = "disabled";
                        }
                    }
                    //----------------------------------------------------------------
                    $disabledLockerTransaksi = "";
                    if (isset($lockerDisabled) && (sizeof($lockerDisabled) > 0)) {
                        if (in_array($val['transaksi_id'], $lockerDisabled)) {
                            $disabledLockerTransaksi = "disabled";
                        }
                    }
                    //----------------------------------------------------------------
                    $disabledPaymentSrc = "";
                    if (isset($paymentSrcDisabled) && (sizeof($paymentSrcDisabled) > 0)) {
                        if (in_array($val['transaksi_id'], $paymentSrcDisabled)) {
                            $disabledPaymentSrc = "disabled";
                        }
                    }
                    //----------------------------------------------------------------

                    $strContent .= "<div class='funkyradio-success'>";

                    $strContent .= "<input class='chRadio' type=checkbox $checked $disabled $disabledLockerTransaksi $disabledPaymentSrc value='" . $val['transaksi_id'] . "' id='opt" . $val['transaksi_id'] . "' 
                        onclick=\"document.getElementById('result').src='" . MODUL_PATH . "$selectProcessor/$jenisTr" . "?$qstr&state='+this.checked;\">";
                    $strContent .= "<label for='opt" . $val['transaksi_id'] . "' class='no-padding no-margin' title='select this entry'>";
                    $strContent .= "</label>";
                    $strContent .= "</div class='funkyradio-success'>";

                    $strContent .= "</td>";
                    foreach ($itemLabels as $key => $label) {
                        $strContent .= "<td  class='" . $val['class_bg'] . "'>";
                        if (isset($val[$key])) {
                            $strContent .= strlen($val[$key]) > 0 ? formatField($key, $val[$key]) : "-";
                            if (is_numeric($val[$key])) {
                                if (!isset($total[$key])) {
                                    $total[$key] = 0;
                                }
                                $total[$key] += $val[$key];
                            }
                        }
                        else {
                            $strContent .= "-";
                        }
                        $strContent .= "</td>";


                    }
                }
                $strContent .= "</tr>";
            }
            $strContent .= "</tbody>";
            //endregion

            //region footer summary bawah
            $strContent .= "<tfoot>";
            $strContent .= "<tr bgcolor='#f0f0f0'>";
            $strContent .= "<td>&nbsp;</td>";
            foreach ($itemLabels as $key => $label) {
                if (isset($total[$key])) {
                    $strContent .= "<td class='$key'>";
                    $strContent .= formatField($key, $total[$key]);
                    $strContent .= "</td>";
                }
                else {
                    $strContent .= "<td>&nbsp;</td>";
                }
            }
            $strContent .= "</tr>";
            $strContent .= "</tfoot>";
            //endregion
            $strContent .= "</table>";

            $strPayment .= "<table class='table table-condensed no-padding'>";
            $strBankAcc = "";
            $defValue = isset($ses_outMaster['sisa']) ? $ses_outMaster['sisa'] : 0;
            $defPaymentValue = isset($ses_outMaster['nilai_bayar']) ? $ses_outMaster['nilai_bayar'] : 0;
            $creditAmount = isset($ses_outMaster['creditAmount']) ? $ses_outMaster['creditAmount'] : 0;
            $defaultDisabled = $defPaymentValue > 0 ? "" : "disabled";
            if ($kelebihanBayarMethod == true) {

                $paymentRows = array(
                    " " => "<label>
                            <input type=checkbox 
                            onclick=\"
                            if(this.checked==true){
                            setTimeout(function(){
                            document.getElementById('result').src='" . MODUL_PATH . "Create/buildValues/$jenisTr';
                            document.getElementById('btnSave').disabled=false;
                            },1200);}
                            \"> i confirm that the numbers above are correct</label>",


                    "" => "<input type=button class='btn btn-success btn-block' id='btnSave' value='$btnLabel' disabled 
                        onclick=\"
                                if(parseInt(removeCommas(document.getElementById('nilai_entry').value))<0)
                                {alert('please fill in amount value');} else {$actionTarget}\">",
                );

            }
            else {

                $paymentRows = array(
                    " " => "<label>
                            <input type=checkbox 
                            onclick=\"
                            if(this.checked==true){
                            setTimeout(function(){
                            document.getElementById('result').src='" . MODUL_PATH . "Create/buildValues/$jenisTr';
                            document.getElementById('btnSave').disabled=false;
                            },1200);}
                            
                            \"> i confirm that the numbers above are correct</label>",


                    "" => "<input type=button class='btn btn-success btn-block' id='btnSave' value='$btnLabel' disabled 
                        onclick=\"
                                if(parseInt(removeCommas(document.getElementById('nilai_entry').value))>parseInt(removeCommas(document.getElementById('$tagihanSrc').value)) || parseInt(removeCommas(document.getElementById('nilai_entry').value))<0)
                                {alert('please fill in amount value');}else {$actionTarget}\">",
                );
            }


            foreach ($paymentRows as $key => $val) {
                $strPayment .= "<tr line=" . __LINE__ . ">";
                $strPayment .= "<td>$key</td>";
                $strPayment .= "<td>$val</td>";
                $strPayment .= "</tr>";
            }
            $strPayment .= "</table>";

            if (isset($isPaymentRadioSelect) && $isPaymentRadioSelect == true) {
                $strContent .= "<script>
                                    $(\".chRadio\").change(function(){
                                        $(\".chRadio\").prop('checked',false);
                                        $(this).prop('checked',true);
                                        console.log(this.checked);
                                    });
                               </script>";
            }


            $strContent .= "<script>

//            $(document).ready( function(){
//$('.table.cTable-modal').DataTable();
                    console.log('mode datatable activated');

                    var table = $('.table.cTable-modal').DataTable({
                                    stateSave: false,
                                    order: [[ 10, 'desc' ]],
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[\$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };



//                                        // Total over all pages
//                                        var total6=0;
//                                        jQuery.each( $(api.column(6).data()), function(i, obj){
//                                            total6 += intVal( $(obj).html() );
//                                        });
//
//                                        var total7=0;
//                                        jQuery.each( $(api.column(7).data()), function(i, obj){
//                                            total7 += intVal( $(obj).html() );
//                                        });
//
//                                        var total8=0;
//                                        jQuery.each( $(api.column(8).data()), function(i, obj){
//                                            total8 += intVal( $(obj).html() );
//                                        });
//
//                                        var total9=0;
//                                        jQuery.each( $(api.column(9).data()), function(i, obj){
//                                            total9 += intVal( $(obj).html() );
//                                        });
//
//                                        var total10=0;
//                                        jQuery.each( $(api.column(10).data()), function(i, obj){
//                                            total10 += intVal( $(obj).html() );
//                                        });

                                        // Total over this page
                                        var pageTotal6=0;
                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
                                            pageTotal6 += intVal( $(obj).html() );
                                            console.log( $(obj).html() );
                                        });

                                        var pageTotal7=0;
                                        jQuery.each( $(api.column(6, { page: 'current'}).data()), function(i, obj){
                                            pageTotal7 += intVal( $(obj).html() );
                                        });

                                        var pageTotal8=0;
                                        jQuery.each( $(api.column(7, { page: 'current'}).data()), function(i, obj){
                                            pageTotal8 += intVal( $(obj).html() );
                                        });

                                        var pageTotal9=0;
                                        jQuery.each( $(api.column(8, { page: 'current'}).data()), function(i, obj){
                                            pageTotal9 += intVal( $(obj).html() );
                                        });

                                        var pageTotal10=0;
                                        jQuery.each( $(api.column(9, { page: 'current'}).data()), function(i, obj){
                                            pageTotal10 += intVal( $(obj).html() );
                                        });

                                        // Update footer
                                        $( api.column( 5 ).footer() ).html(
                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal6)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total6)+\"</div>\"
                                        );

                                        $( api.column( 6 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal7)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total7)+\"</div>\"
                                        );

                                        $( api.column( 7 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal8)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total8)+\"</div>\"
                                        );

                                        $( api.column( 8 ).footer() ).html(
                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal9)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total9)+\"</div>\"
                                        );

                                        $( api.column( 9 ).footer() ).html(
                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal10)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total10)+\"</div>\"
                                        );
                                    }
                                });

                    table.on( 'draw', function () {
                        var body = $( table.table().body() );
                        body.unhighlight();
                        body.highlight( table.search() );
                        console.log('highlight');
                    } );

//                });
                    $('#shopping_cart').on('change', function(){
                        console.log('shopping_cart changed');
                    });
                    $('#result').on('change', function(){
                        console.log('result changed');
                    });
             </script>";
        }
        else {
            $strContent = "-the item you specified has no entry-<br>";
            $strContent = "you may want to go back to previous page";
        }
        //endregion

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "jenisTr" => $jenisTr,
                "payment_subtitle" => $paymentSubtitle,
                "profile_name" => $this->session->login['nama'],
                "content" => $strContent,
                "elements" => $strElements,
                "payment_str" => $strPayment,
                "scriptBottom" => $scriptBottom,
                //                "title" => $title,
                //                "sub_title" => $subTitle
            )
        );

        $p->render();


        break;

    case "selectPaymentExternSrc":
        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

//        $p = New Layout("$title", "$subTitle", "application/template/default.html");
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/transaksi_uang_muka.html");
        //arrPrint($dueDate);
//        arrPrint($items);
        $strContent = "";
//        $scriptBottom = "";
        $strContent .= "<div class='box box-solid box-danger'>";
        $strContent .= "<div class='box-header with-border text-uppercase'>";
        $strContent .= "<h4 class='box-title'><span class=\"glyphicon glyphicon-flash blink\"></span> on-going transactions</h4>";
        $strContent .= "</div>";
        $strContent .= "<div class='box-body'>";
        $strContent .= "<div class='table-responsive pembayaran'>";
        if (sizeof($items) > 0) {

            $strContent .= "<table class='table cTable table-condensed table-striped table-bordered'>";
            $strContent .= "<thead>";
            $strContent .= "<tr bgcolor='#f0f0f0'>";
            if (sizeof($itemLabels) > 0) {
                $strContent .= "<td class='text-muted text-right'>";
                $strContent .= "No.";
                $strContent .= "</td>";
                foreach ($itemLabels as $key => $label) {
                    $strContent .= "<td class='text-capitalize text-muted'>";
                    $strContent .= $label;
                    $strContent .= "</td>";
                }
            }
            $strContent .= "</tr>";
            $strContent .= "</thead>";
            $no = 0;
            foreach ($items as $key => $val) {
                $no++;
                $strContent .= "<tr line=" . __LINE__ . ">";
                $strContent .= "<td align='right' class='" . $val['class_marking'] . "'>$no</td>";
                if (sizeof($itemLabels) > 0) {
                    foreach ($itemLabels as $key => $label) {
                        //                        cekHere($key);
                        $classMarking = "";
                        $strContent .= "<td data-order='" . $val[$key] . "' class='" . $val['class_marking'] . "'>";
                        $strContent .= "<a href='javascript:void(0)' title='make a $title with " . $val['extern_nama'] . "' data-toggle='tooltip' data-placement='right' onclick=\"top.BootstrapDialog.show(
                                   {
                                       title:'$title - " . $val['extern_nama'] . "',
                                       message: " . '$' . "('<div></div>').load('" . $val['link'] . "'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                        \" >";
                        $strContent .= formatField($key, $val[$key]);
                        $strContent .= "</a>";
                        $strContent .= "</td>";
                        if (is_numeric($val[$key])) {
                            if (!isset($total[$key])) {
                                $total[$key] = 0;
                            }
                            $total[$key] += $val[$key];
                        }
                    }
                }
                $strContent .= "</tr>";
            }
            $strContent .= "<tfoot>";
            $strContent .= "<tr bgcolor='#f0f0f0'>";
            $strContent .= "<td></td>";
            $strContent .= "<td class='text-muted'>total amount of '$srcLabel'</td>";
            foreach ($itemLabels as $key => $label) {
                if (isset($total[$key])) {
                    $strContent .= "<td class='text-muted'>";
                    $strContent .= formatField($key, $total[$key]);
                    $strContent .= "</td>";
                }
            }
            $strContent .= "</tr>";
            $strContent .= "</tfoot>";
            $strContent .= "</table>";
            $strContent .= "
            <script>
                $(document).ready( function(){

                    console.log('mode datatable activated');

                    var table = $('.table.cTable').DataTable({
                                    stateSave: false,
                                    order: [[ 8, 'desc' ]],
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: -1,
                                    footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[\$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        // Total over all pages
                                        var total2=0;
                                        jQuery.each( $(api.column(2).data()), function(i, obj){
                                            total2 += intVal( $('span', obj).html() );
                                        });

                                        var total3=0;
                                        jQuery.each( $(api.column(3).data()), function(i, obj){
                                            total3 += intVal( $('span', obj).html() );
                                        });

                                        var total4=0;
                                        jQuery.each( $(api.column(4).data()), function(i, obj){
                                            total4 += intVal( $('span', obj).html() );
                                        });

                                        var total5=0;
                                        jQuery.each( $(api.column(5).data()), function(i, obj){
                                            total5 += intVal( $('span', obj).html() );
                                        });


                                        // Total over this page
                                        pageTotal2 = api
                                            .column( 2, { page: 'current'} )
                                            .data()
                                            .reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                            }, 0 );


                                        var pageTotal2=0;
                                        jQuery.each( $(api.column(2, { page: 'current'}).data()), function(i, obj){
                                            pageTotal2 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal3=0;
                                        jQuery.each( $(api.column(3, { page: 'current'}).data()), function(i, obj){
                                            pageTotal3 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal4=0;
                                        jQuery.each( $(api.column(4, { page: 'current'}).data()), function(i, obj){
                                            pageTotal4 += intVal( $('span', obj).html() );
                                        });

                                        var pageTotal5=0;
                                        jQuery.each( $(api.column(5, { page: 'current'}).data()), function(i, obj){
                                            pageTotal5 += intVal( $('span', obj).html() );
                                        });


                                        // Update footer
                                        $( api.column( 2 ).footer() ).html(
                                            \"<div class='text-right text-primary text-bold'>\"+addCommas(pageTotal2)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total2)+\"</div>\"
                                        );

                                        $( api.column( 3 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal3)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total3)+\"</div>\"
                                        );

                                        $( api.column( 4 ).footer() ).html(
                                            \"<div class='text-right text-success text-bold'>\"+addCommas(pageTotal4)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total4)+\"</div>\"
                                        );

                                        $( api.column( 5 ).footer() ).html(
                                            \"<div class='text-right text-danger text-bold'>\"+addCommas(pageTotal5)+\"</div>\"
//                                            + \"<div class='text-right'>\"+addCommas(total5)+\"</div>\"
                                        );

                                    }
                                });

                    table.on( 'draw', function () {
                        var body = $( table.table().body() );
                        body.unhighlight();
                        body.highlight( table.search() );
                        console.log('highlight');
                    } );

                });
            </script>";

        }
        else {
            $strContent = "-the item you specified has no entry-";
        }
        $strContent .= "</div class='responsive'>";
        $strContent .= "</div class='box-body'>";
        $strContent .= "</div class='box box-danger'>";
        //endregion

        echo $strContent;
//        $p->addTags(
//            array(
//                "menu_left" => callMenuLeft(),
//                "trans_menu" => callTransMenu(),
//                "float_menu_atas" => callFloatMenu('atas'),
//                "float_menu_bawah" => callFloatMenu(),
//                "menu_taskbar" => callMenuTaskbar(),
//                "btn_back" => callBackNav(),
//                "profile_name" => $this->session->login['nama'],
//                "content" => $strContent,
//                "self" => isset($thisPage) ? $thisPage : "",
//                "trName" => isset($trName) ? $trName : "",
//                "jenisTr" => $jenisTr,
//            )
//        );
//
//        $p->render();


        break;

    case "viewJembreng":

        $contentStr = "";
        $contentStr .= "<div class='table-responsive'>";
        $contentStr .= "<table class='table table-bordered table-condensed' style='width: 100%;'>";

        $contentStr .= "<tr bgcolor='#f5f5f5'>";
        $contentStr .= "<td></td>";
        foreach ($main as $stepNum => $iSpec) {
            $contentStr .= "<td>";
            $contentStr .= "Step: $stepNum<br>";
            $contentStr .= "Nomer: " . $iSpec['nomer'] . "<br>";
            $contentStr .= "By: " . $iSpec['nama'] . "<br>";
            $contentStr .= "</td>";
        }
        $contentStr .= "</tr>";

        foreach ($items[1] as $iSpecDetail) {
            $contentStr .= "<tr bgcolor='#f5f5f5'>";
            $contentStr .= "<td>" . $iSpecDetail['nama'] . "</td>";

            foreach ($main as $stepNum => $iSpec) {
                $cont = "";
                foreach ($items[$stepNum] as $iSpecDetail) {
                    $cont = "<td>" . $iSpecDetail['jml'] . "</td>";
                }
                $contentStr .= $cont;
            }

            $contentStr .= "</tr>";

        }


        $contentStr .= "</table>";
        $contentStr .= "</div>";


        $p = New Layout("$title", "", $template);
        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
                "title" => $title,
                "content" => $contentStr,
                "signatures" => "",
                "header" => "",
            )
        );

        $p->render();

        break;

    case "viewResume":

        if (isset($signStr)) {

            echo $signStr;
        }
        if (isset($elementStr)) {
            echo $elementStr;
        }

        if (isset($historiEfaktur)) {

            echo $historiEfaktur;
        }
        if (isset($notes_final) && ($notes_final != NULL)) {
            $strn = "<div class='alert alert-danger'>";
            $strn .= "<span style='color:#FFFFFF;font-size:15px;'>$notes_final</span>";
            $strn .= "</div>";
            echo $strn;
        }
        // view jurnal, kalau wewenang allowed dan jurnalnya ada
        if (sizeof($items) > 0) {
            foreach ($items as $cabangID => $subItems) {

                if (sizeof($subItems) > 0) {
                    $cabangNama = isset($cabangData[$cabangID]) ? $cabangData[$cabangID] : "";

                    echo "<h4 class='text-blue'><span class='fa fa-book'></span> journal entries ($cabangNama) " . formatField_he_format("nomer", $title) . "$urutCounter</h4>";

                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-condensed'>";


                    foreach ($subItems as $urut => $mSpec) {

                        echo "<tr bgcolor='#f0f0f0'>";
                        foreach ($headers as $key => $label) {
                            echo "<td>";
                            echo "$label";
                            echo "</td>";
                        }
                        echo "</tr>";

                        foreach ($mSpec as $iSpec) {

                            echo "<tr line=" . __LINE__ . ">";
                            foreach ($headers as $key => $label) {
                                echo "<td>";
                                echo "<a href='" . $iSpec['link'] . "' target='_blank'>";
                                echo formatField_he_format($key, $iSpec[$key]);
                                echo "</a>";
                                echo "</td>";
                                if (is_numeric($iSpec[$key])) {
                                    if (!isset($total[$cabangID][$urut][$key])) {
                                        $total[$cabangID][$urut][$key] = 0;
                                    }
                                    $total[$cabangID][$urut][$key] += $iSpec[$key];
                                }
                            }
                            echo "</tr>";
                        }

                        echo "<tr style='font-size: 15px;font-weight: bold;'>";
                        foreach ($headers as $key => $label) {
                            echo "<td>";
                            if (isset($total[$cabangID][$urut][$key])) {
                                echo formatField_he_format($key, $total[$cabangID][$urut][$key]);
                            }
                            echo "</td>";
                        }
                        echo "</tr>";

                    }


                    echo "</table class='table table-condensed'>";
                    echo "</div class='table-responsive'>";
                }
                else {
                    echo "<div class='text-center text-warning'>";
                    echo "- no journal affected by this transaction -<br><br>";
                    echo "</div class='text-center text-warning'>";
                }
            }
        }
        else {
            echo "<div class='text-center text-warning'>";
            echo "- no journal affected by this transaction -<br><br>";
            echo "</div class='text-center text-warning'>";
        }

        if (sizeof($deliveryDetail) > 0) {
            $alias = $deliveryDetail["alias"];
            $tlp = $deliveryDetail["tlp"];
            $alamat = $deliveryDetail["alamat"];
            $kecamatan = $deliveryDetail["kecamatan"];
            $kabupaten = $deliveryDetail["kabupaten"];
            $propinsi = $deliveryDetail["propinsi"];

            $alamat_f = "$alamat";
            $alamat_f .= strlen($kecamatan) > 2 ? " $kecamatan" : "";
            $alamat_f .= strlen($kabupaten) > 2 ? " $kabupaten" : "";
            $alamat_f .= strlen($propinsi) > 2 ? " $propinsi" : "";
            $tbl = "<style type='text/css'>
                        .delivery>tbody>tr>td {
                            padding: 1px !important;
                        }
                        </style>";
            $tbl .= "<table class='table delivery' style='margin-bottom: unset !important;'>";

            $tbl .= "<tr>";
            $tbl .= "<td width='100px'>up to</td><td>:</td>";
            $tbl .= "<td>$alias</td>";
            $tbl .= "</tr>";
            $tbl .= "<tr>";
            $tbl .= "<td>Alamat</td><td>:</td>";
            $tbl .= "<td>$alamat_f</td>";
            $tbl .= "</tr>";
            $tbl .= "<tr>";
            $tbl .= "<td>Telephone</td><td>:</td>";
            $tbl .= "<td>$tlp</td>";
            $tbl .= "</tr>";

            $tbl .= "</table>";
            echo "<div class='panel'>";
            echo "<div class='panel-body bborder-cek'>";
            echo "<h4 class='title no-padding no-margin'>Delivery Address</h4>";
            echo $tbl;
            echo "</div>";
            echo "</div>";
        }

        if (isset($detil_item_rslt) && sizeof($detil_item_rslt) > 0) {
            //deteksi jika berisi pembatalan transksi yang melibatkan fifo
            echo "<h4 class='text-blue'><span class='fa fa-book'>$detail_title</span></h4>";
            $rsltData = "<table class='table table-bordered table-condensed' border='1' rules='all'>";
            $rsltData .= "<tr>";
            $rsltData .= "<th rowspan='2'>No</th>";
            foreach ($detil_item_rslt_label as $rsl_key => $rslt_label) {
                if (isset($detil_item_rslt_label2[$rsl_key])) {
                    $colspan = sizeof($detil_item_rslt_label2[$rsl_key]);
                    $rowspan = "";
                }
                else {
                    $colspan = "";
                    $rowspan = "2";
                }

                $rsltData .= "<th colspan='$colspan' rowspan='$rowspan'>$rslt_label</th>";
            }
            $rsltData .= "</tr>";
            $rsltData .= "<tr>";
            // arrPrint($detil_item_rslt_label2);
            foreach ($detil_item_rslt_label2 as $jn => $jndata) {
                foreach ($jndata as $jn_key => $jn_label) {
                    $rsltData .= "<th>$jn_label</th>";
                }
            }
            $rsltData .= "</tr>";
            $i = 0;
            $masterQty = 0;
            foreach ($detail_items as $iSpec) {
                $i++;
                $masterQty += $iSpec['qty'];
                $rsltData .= "<tr>";
                // $rsltData .= "<td>$i ".$iSpec['id']."</td>";
                $rsltData .= "<td>$i </td>";
                $datas = array();
                foreach ($detil_item_rslt_label as $rsltKey => $rslt_label) {
                    if (isset($iSpec[$rsltKey])) {
                        $rsltData .= "<td>" . $iSpec[$rsltKey] . " </td>";
                    }
                    else {
                        if (isset($detil_item_rslt[$iSpec['id']][$rsltKey])) {
                            $datas[$rsltKey] = isset($detil_item_rslt[$iSpec['id']][$rsltKey]) ? $detil_item_rslt[$iSpec['id']][$rsltKey] : array();

                        }
                    }

                }
                foreach ($detil_item_rslt_label2 as $jn => $jndata) {
                    foreach ($jndata as $yy => $rrr) {
                        $rsltData_0 = "";
                        $rt = 0;
                        $hasil = "";
                        foreach ($datas[$jn] as $datas_0) {
                            $rt++;
                            $var = formatField_he_format("harga", $datas_0[$yy]);
                            if ($hasil == "") {
                                $hasil .= "$var";
                            }
                            else {
                                $hasil = "$hasil <br>" . "$var";
                            }

                            if (!isset($totalJn[$jn][$yy])) {
                                $totalJn[$jn][$yy] = 0;
                            }
                            $totalJn[$jn][$yy] += $datas_0[$yy];
                        }
                        $rsltData .= "<td>$hasil</td>";
                    }
                }
                $rsltData .= "</tr>";


            }
            if (sizeof($totalJn) > 0) {
                $rsltData .= "<tr>";
                $rsltData .= "<td colspan='2'>TOTAL</td>";
                $rsltData .= "<td colspan=''>$masterQty</td>";
                foreach ($totalJn as $jnKey => $jnVAlues) {
                    // arrPrint($jnVAlues);
                    foreach ($jnVAlues as $rkey => $rValue) {
                        if ($rkey == "harga") {
                            $valSUB = "<span class='text pull-right'>-</span>";
                        }
                        else {
                            $valSUB = formatField_he_format("harga", $rValue);
                        }
                        $rsltData .= "<td colspan=''>$valSUB</td>";
                    }

                }
                $rsltData .= "</tr>";
            }
            // arrprint();
            $rsltData .= "</table>";
            echo $rsltData;

        }
        else {
            if (isset($detail_items) && sizeof($detail_items) > 0) {
                if (isset($itemLabels) && (sizeof($itemLabels) > 1)) {

                    echo "<h4 class='text-blue'><span class='fa fa-book'>$detail_title </span></h4>";

                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        if(is_array($label)){
                            echo $label["label"];
                        }
                        else{
                        echo $label;
                        }
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($detail_items as $iSpec) {
                        //                        arrPrint($iSpec);
                        $id = $iSpec["id"];
                        $no++;
                        $fieldVal = "";


                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels as $key => $label) {
                            echo "<td>";
                            if (substr($key, 0, 1) == "*") {
                                $key_p = str_replace("*", "", $key);
                                $key_ex = explode("#", $key_p);
                                $pair_name = $key_ex[0];
                                $pair_key = $key_ex[1];
                                $pair_key_val = $iSpec[$pair_key];
                                if (sizeof($key_ex) > 1) {
                                    $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                                }
                                else {
                                    $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                                }
                            }
                            else {
                                $fieldVal = isset($iSpec[$key]) ? formatField_he_format($key, $iSpec[$key]) : "";
                            }

                            echo $fieldVal;
                            echo "</td>";
                        }
                        echo "</tr>";
                        if (isset($arrSubDetailDataKolom["nama"][$id])) {
                            $contentStr = "<tr line=" . __LINE__ . ">";
                            $contentStr .= "<td>&nbsp;</td>";
                            $contentStr .= "<td colspan='" . sizeof($itemLabels) . "' style='font-size:12px;'>";
                            foreach ($arrSubDetailDataKolom["nama"][$id] as $sku => $sku_data) {
                                $contentStr .= "$sku : " . formatField_he_format("serial", $sku_data) . "<br>";
                            }
                            $contentStr .= "</td>";
                            $contentStr .= "</tr>";
                            echo $contentStr;
                        }
                        if (($noteEnabled == true) || ((isset($imageEnabled)) && ($imageEnabled == true))) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                                echo $iVal;
                                // echo "</td>";


                            }
                            if (isset($imageEnabled) && ($imageEnabled == true)) {
                                $iVal = isset($iSpec['images']) ? "<a href='' data-toggle='modal' data-target='#myModal'><img src='" . $iSpec['images'] . "' height='50px;' style='float:right;'></a>" : "";
                                echo $iVal;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }

                    if (isset($detail_sumRows) && sizeof($detail_sumRows) > 0) {
                        foreach ($detail_sumRows as $key => $label) {
                            //                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                            $colspanX = sizeof($itemLabels);
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                            echo "<td class='text-right'>";

                            $val = 0;
                            if (isset($detail_main[$key]) && $detail_main[$key] > 0) {
                                $val = $detail_main[$key];
                            }
                            else {
                                $val = 0;
                                //                        if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                //                            $val = $mainAddValues[$key];
                                //                        }
                                //
                            }

                            echo formatField_he_format($key, $val);
                            echo "</td>";
                            echo "</tr>";
                        }
                        //                arrPrint($mainAddValues);

                    }
                    if (isset($addRows) && sizeof($addRows) > 0) {
                        $colspanRows = sizeof($itemLabels);
                        $listAddRows = "";
                        foreach ($addRows as $key => $label) {
                            $listAddRows .= "<tr>";
                            $valAddRows = isset($detail_main[$key]) ? $detail_main[$key] : "0";
                            $listAddRows .= "<td colspan='$colspanRows' align='right'>$label</td>";
                            $listAddRows .= "<td colspan='$colspanRows'>" . formatField($key, $valAddRows) . "</td>";
                            $listAddRows .= "</tr>";
                        }

                        echo $listAddRows;
                        //                    arrPrint($addRows);
                    }

                    //            if (isset($sumAddRows) && sizeof($sumAddRows) > 0) {
                    //                $valAdd = 0;
                    //                foreach ($sumAddRows as $keyAdd => $label) {
                    ////                        cekLime($keyAdd);
                    //                    $colspanX = sizeof($itemLabels2) > 1 ? sizeof($itemLabels2) : sizeof($itemLabels);
                    //                    echo "<tr line=".__LINE__.">";
                    //                    echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                    //                    echo "<td class='text-right'>";
                    //                    $val = 0;
                    //                    if (isset($main[$keyAdd]) && $main[$keyAdd] > 0) {
                    //                        $valAdd = isset($main[$keyAdd]) ? $main[$keyAdd] : 0;
                    //                    }
                    //                    else {
                    //                        if (isset($mainAddValues[$keyAdd]) && $mainAddValues[$keyAdd] > 0) {
                    //                            $valAdd = isset($mainAddValues[$keyAdd]) ? $mainAddValues[$keyAdd] : 0;
                    ////                            cekKuning("$keyAdd, $valAdd");
                    //                        }
                    //                        else {
                    //                            $valAdd = 0;
                    ////                            cekPink("$keyAdd, $valAdd");
                    //                        }
                    //                    }
                    //
                    //                    echo formatField($keyAdd, $valAdd);
                    //                    echo "</td>";
                    //                    echo "</tr>";
                    //                }
                    //            }


                    echo "</table>";
                    echo "</div class='table-responsive'>";


                    // tambahan detail2 isi nota top
                    if (isset($detail2_items) && sizeof($detail2_items) > 0) {
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                        echo "<tr bgcolor='#f5f5f5'>";
                        echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                        foreach ($item2Labels as $key => $label) {
                            echo "<th class='text-muted' style='font-weight:bold;'>";
                            echo $label;
                            echo "</th>";
                        }
                        echo "</tr>";

                        $no = 0;
                        foreach ($detail2_items as $iSpec) {
                            $no++;
                            $fieldVal = "";


                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo ".</td>";
                            foreach ($item2Labels as $key => $label) {
                                echo "<td>";
                                if (substr($key, 0, 1) == "*") {
                                    $key_p = str_replace("*", "", $key);
                                    $key_ex = explode("#", $key_p);
                                    $pair_name = $key_ex[0];
                                    $pair_key = $key_ex[1];
                                    $pair_key_val = $iSpec[$pair_key];
                                    if (sizeof($key_ex) > 1) {
                                        $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                                    }
                                    else {
                                        $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                                    }
                                }
                                else {
                                    $fieldVal = isset($iSpec[$key]) ? formatField_he_format($key, $iSpec[$key]) : "";
                                }

                                echo $fieldVal;
                                echo "</td>";
                            }
                            echo "</tr>";
                        }


                        echo "</table>";
                        echo "</div class='table-responsive'>";
                    }

                    // tambahan detail3 isi nota top
                    if (isset($detail3_items) && sizeof($detail3_items) > 0) {
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                        echo "<tr bgcolor='#f5f5f5'>";
                        echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                        foreach ($item3Labels as $key => $label) {
                            echo "<th class='text-muted' style='font-weight:bold;'>";
                            echo $label;
                            echo "</th>";
                        }
                        echo "</tr>";

                        $no = 0;
                        foreach ($detail3_items as $iSpec) {
                            $no++;
                            $fieldVal = "";


                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo ".</td>";
                            foreach ($item3Labels as $key => $label) {
                                echo "<td>";
                                if (substr($key, 0, 1) == "*") {
                                    $key_p = str_replace("*", "", $key);
                                    $key_ex = explode("#", $key_p);
                                    $pair_name = $key_ex[0];
                                    $pair_key = $key_ex[1];
                                    $pair_key_val = $iSpec[$pair_key];
                                    if (sizeof($key_ex) > 1) {
                                        $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                                    }
                                    else {
                                        $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                                    }
                                    if (is_numeric($fieldVal)) {
                                        if (!isset($sumItems3[$key])) {
                                            $sumItems3[$key] = 0;
                                        }
                                        $sumItems3[$key] += $fieldVal;
                                    }
                                }
                                else {
                                    if (is_numeric($iSpec[$key])) {
                                        if (!isset($sumItems3[$key])) {
                                            $sumItems3[$key] = 0;
                                        }
                                        $sumItems3[$key] += $iSpec[$key];
                                    }

                                    $fieldVal = isset($iSpec[$key]) ? formatField_he_format($key, $iSpec[$key]) : "";
                                }

                                echo $fieldVal;
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                        if (isset($sumItems3)) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>-</td>";
                            foreach ($item3Labels as $key => $label) {
                                echo "<td>";
                                $sumVal = isset($sumItems3[$key]) ? $sumItems3[$key] : "-";
                                echo formatField_he_format($key, $sumVal);
                                echo "</td>";
                            }
                            echo "</tr>";
                        }

                        echo "</table>";
                        echo "</div class='table-responsive'>";
                    }


                    //----------------------------------------
                    if (isset($itemsNotApprove) && sizeof($itemsNotApprove) > 0) {
                        echo "<h4 class='text-blue'><span class='fa fa-book'>$detail_not_approve_title</span></h4>";
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                        echo "<tr bgcolor='#f5f5f5'>";
                        echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                        foreach ($itemLabels as $key => $label) {
                            echo "<th class='text-muted' style='font-weight:bold;'>";
                            echo $label;
                            echo "</th>";
                        }
                        echo "</tr>";

                        $no = 0;
                        foreach ($itemsNotApprove as $iSpec) {
                            $no++;
                            $fieldVal = "";


                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo ".</td>";
                            foreach ($itemLabels as $key => $label) {
                                echo "<td>";
                                if (substr($key, 0, 1) == "*") {
                                    $key_p = str_replace("*", "", $key);
                                    $key_ex = explode("#", $key_p);
                                    $pair_name = $key_ex[0];
                                    $pair_key = $key_ex[1];
                                    $pair_key_val = $iSpec[$pair_key];
                                    if (sizeof($key_ex) > 1) {
                                        $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                                    }
                                    else {
                                        $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                                    }
                                }
                                else {
                                    $fieldVal = isset($iSpec[$key]) ? formatField_he_format($key, $iSpec[$key]) : "";
                                }

                                echo $fieldVal;
                                echo "</td>";
                            }
                            echo "</tr>";
                        }

                        if (isset($mainNotApprove) && sizeof($mainNotApprove) > 0) {
                            foreach ($detail_sumRows as $key => $label) {
                                $colspanX = sizeof($itemLabels);
                                echo "<tr line=" . __LINE__ . ">";
                                echo "<td colspan='" . $colspanX . "' class='text-right'>$label</td>";
                                echo "<td class='text-right'>";

                                $val = 0;
                                if (isset($mainNotApprove[$key]) && $mainNotApprove[$key] > 0) {
                                    $val = $mainNotApprove[$key];
                                }
                                else {
                                    $val = 0;
                                }

                                echo formatField_he_format($key, $val);
                                echo "</td>";
                                echo "</tr>";
                            }
                        }

                        echo "</table>";
                        echo "</div class='table-responsive'>";
                    }
                }
            }
        }


        //----------------------------------------------------
        if (sizeof($main) > 0) {
            $accuracy = isset($main['accuracy']) ? $main['accuracy'] : "";
            $lattitude = isset($main['lattitude']) ? $main['lattitude'] : "";
            $longitude = isset($main['longitude']) ? $main['longitude'] : "";
            $olehName = isset($main['olehName']) ? strtoupper($main['olehName']) : "";
            if (isset($main['accuracy']) && isset($main['lattitude']) && isset($main['longitude'])) {

                echo "<style>#map-canvas{width: 100%;height: 40vh;}</style>";
                echo "<div class='panel'>";
                echo "<h4 class='text-red'>";
                echo "<span class='fa fa-map'></span>&nbsp;location info <a href=\"javascript:void(0)\" class='pull-right' onclick=\"showMaps()\">(view map)</a> </h4>";
                echo "<div class='hidden' id=\"map-canvas\"></div>";
                echo "</div>";
                echo "
                    <script>

                    function showMaps(){

                        $.getScript( 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDSzzQo2ZxKysHg5bn6YeKyukaP_AyvdUM', function( data, textStatus, jqxhr ) {
                          console.log( data );
                          console.log( textStatus );
                          console.log( jqxhr.status );
                          console.log( 'Load was performed.' );
                            var markers = [
                                ['<i class=\"fa fa-user\"></i> $olehName', '$lattitude', '$longitude']
                            ];
                            var mapCanvas = document.getElementById('map-canvas');
                            var mapOptions = {
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                zoom: 20
                            }
                            var map = new google.maps.Map(mapCanvas, mapOptions)
                            var infowindow = new google.maps.InfoWindow(), marker, i;
                            var bounds = new google.maps.LatLngBounds();
                            for (i = 0; i < markers.length; i++) {
                                pos = new google.maps.LatLng(markers[i][1], markers[i][2]);
                                bounds.extend(pos);
                                marker = new google.maps.Marker({
                                    position: pos,
                                    map: map
                                });
                                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                    return function() {
                                        infowindow.setContent(markers[i][0]);
                                        infowindow.open(map, marker);
                                    }
                                })(marker, i));
                                map.fitBounds(bounds);
                            }

                            $(mapCanvas).toggleClass('hidden')

                        });
                    }



                    </script>
                    ";

            }
        }

        echo "<div class='row bg-gray'>";
        echo "<div class='col-md-6'>";
        echo "</div class='col-md-6'>";

        echo "<div class='col-md-6 text-right'>";
        echo "<div class='panel-body'>";
        echo "<a class='btn btn-primary' href='javascript:void(0)' onclick=\"$receiptLink\">";
        echo "<span class='glyphicon glyphicon-print'></span> ";
        echo "print receipt number $title$urutCounter";
        echo "</a>";
        echo "</div class='panel'>";
        echo "</div class='col-md-6'>";

        echo "</div class='row'>";

        echo "<div class='row visible-xs visible-xm hidden-md hidden-lg hidden-xl hidden-xxl hidden-xxxl'>";
        echo "<div class='col-md-12'>
              <div class='text-center panel panel-warning'>
                  printing on mobile devices requires <strong>Quick Printer</strong>&trade; <a href='javascript:void(0);' onclick=\"window.open('https://cdn.mayagrahakencana.com/apk/PRTS.apk')\">download from here</a>
                  <br/>
                  <span class='text-red'>(ignore this warning if you have already  installed the printer driver)</span>
              </div>
              </div class='col-md-12'>";
        echo "</div class='row'>";

        break;

    case "viewResumeDetails":

        $contents = "<div class='table-responsive' style='padding-left: 5px;border:0px solid red;'>";

        if (isset($itemsLabel) && sizeof($itemsLabel) > 0) {

            //region header data customer
            $contents .= "<table width='100%'>";
            if (sizeof($mainFieldsLabel) > 0) {
                foreach ($mainFieldsLabel as $col => $alias) {
                    $contents .= "<tr line=" . __LINE__ . ">";
                    //                $contents .= "<td class='text-uppercase text-grey-3'>$alias</td>";
                    $contents .= "<td class='text-uppercase'>$alias</td>";
                    $val = isset($mainFields[$col]) ? $mainFields[$col] : "";
                    $contents .= "<td>:</td>";
                    $contents .= "<td style='padding-left:10px;'>" . formatField($col, $val) . "</td>";
                    $contents .= "</tr>";
                }
            }
            $contents .= "</table>";
            //endregion

            $contents .= "<div class='panel margin-top-10'>";
            $contents .= "<table class='table table-bordered no-margin table-condensed'>";
            //region header items
            $contents .= "<tr line=" . __LINE__ . ">";
            $contents .= "<th class='bg-info text-center'>" . ucwords('No') . "</th>";
            foreach ($itemsLabel as $colItems => $col_alias) {
                $contents .= "<th class='bg-info text-center'>" . ucwords($col_alias) . "</th>";
            }
            $contents .= "</tr>";
            //endregion

            //region detile items
            if (isset($items) && sizeof($items) > 0) {
                $no = 0;
                foreach ($items as $itemsData) {

                    $no++;
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td class='text-right'>" . formatField('number', $no) . "</td>";
                    foreach ($itemsLabel as $itemCol => $label) {
                        $value = isset($itemsData[$itemCol]) ? $itemsData[$itemCol] : 0;
                        $contents .= "<td>";
                        if (isset($itemsKolomLink) && in_array($itemCol, $itemsKolomLink)) {
                            $link = isset($itemsLink[$itemsData['id']]) ? $itemsLink[$itemsData['id']] : "";
                            $contents .= "<a href='$link' target='_blank'>";
                            $contents .= formatField($itemCol, $value);
                            $contents .= "</a>";
                        }
                        else {
                            $contents .= formatField($itemCol, $value);
                        }


                        $contents .= "</td>";
                    }
                    $contents .= "</tr>";
                }
            }
            else {
                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<td colspan='" . sizeof($itemsLabel) . "' class='text-center text-bold'> details not found!</td>";
                $contents .= "</tr>";
            }
            //endregion

            $jmlKolom = sizeof($itemsLabel);

            //region detile item sum
            $colspanTotal = $jmlKolom > 4 ? $jmlKolom - 3 : 3;
            //            arrPrint($itemsLabel);
            //            cekHere($colspanTotal);
            if (isset($mainSumDetailsFieldsLabel) && sizeof($mainSumDetailsFieldsLabel) > 0) {

                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<td colspan='$colspanTotal' class='text-right table-borderless'>Total item</td>";
                foreach ($mainSumDetailsFieldsLabel as $sumDCol => $sum_Dalias) {

                    $valSum = isset($mainFields[$sumDCol]) ? $mainFields[$sumDCol] : 0;
                    $contents .= "<td>" . formatField($sumDCol, $valSum) . "</td>";
                }
                $contents .= "</tr>";
                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<td colspan='" . ($jmlKolom + 1) . "'></td>";
                $contents .= "</tr>";
            }
            //endregion

            //region rincian sumifelds
            $colspan2 = sizeof($itemsLabel) - 2;
            foreach ($mainSumFieldsLabel as $kolSum => $alias) {

                $val = isset($mainFields[$kolSum]) ? $mainFields[$kolSum] : 0;
                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<td colspan='$colspan2' class='text text-right bottom-borderless text-uppercase text-grey-3'>$alias</td>";
                $contents .= "<td colspan='3'> " . formatField($kolSum, $val) . "</td>";
                $contents .= "</tr>";

            }
            //endregion

            //region sumfields2
            if (sizeof($reviewAddRows) > 0) {
                foreach ($reviewAddRows as $aKol => $aAlias) {
                    $val_row = isset($mainFields[$aKol]) ? $mainFields[$aKol] : 0;
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td colspan='$colspan2' class='text text-right bottom-borderless text-uppercase text-grey-3'>$aAlias</td>";
                    $contents .= "<td colspan='3'> " . formatField($aKol, $val_row) . "</td>";
                    $contents .= "</tr>";
                }
            }
            //endregion
            $contents .= "</table>";
            $contents .= "</div>";


            if (isset($itemsLabel2) && sizeof($itemsLabel2) > 0) {
                $contents .= "<table class='table table-bordered no-margin table-condensed'>";
                //region header2 items
                $contents .= "<tr line=" . __LINE__ . ">";
                $contents .= "<th class='bg-info text-center'>" . ucwords('No') . "</th>";
                foreach ($itemsLabel2 as $colItems => $col_alias) {
                    $contents .= "<th class='bg-info text-center'>" . ucwords($col_alias) . "</th>";
                }
                $contents .= "</tr>";
                //endregion

                //region detile2 items
                if (isset($items2_sum) && sizeof($items2_sum) > 0) {
                    $no = 0;
                    foreach ($items2_sum as $itemsData) {

                        $no++;
                        $contents .= "<tr line=" . __LINE__ . ">";
                        $contents .= "<td class='text-right'>" . formatField('number', $no) . "</td>";
                        foreach ($itemsLabel2 as $itemCol => $label) {
                            $value = isset($itemsData[$itemCol]) ? $itemsData[$itemCol] : 0;
                            $contents .= "<td>";
                            $contents .= formatField($itemCol, $value);
                            $contents .= "</td>";
                        }
                        $contents .= "</tr>";
                    }
                }
                else {
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td colspan='" . sizeof($itemsLabel2) . "' class='text-center text-bold'> details not found!</td>";
                    $contents .= "</tr>";
                }
                //endregion

                $jmlKolom = sizeof($itemsLabel2);

                //region detile item sum
                $colspanTotal = $jmlKolom - 3;
                if (isset($mainSumDetailsFieldsLabel2) && sizeof($mainSumDetailsFieldsLabel2) > 0) {
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td colspan='$colspanTotal' class='text-right table-borderless'>Total item</td>";
                    foreach ($mainSumDetailsFieldsLabel2 as $sumDCol => $sum_Dalias) {

                        $valSum = isset($mainFields[$sumDCol]) ? $mainFields[$sumDCol] : 0;
                        $contents .= "<td>" . formatField($sumDCol, $valSum) . "</td>";
                    }
                    $contents .= "</tr>";
                    $contents .= "<tr line=" . __LINE__ . ">";
                    $contents .= "<td colspan='" . ($jmlKolom + 1) . "'></td>";
                    $contents .= "</tr>";
                }
                //endregion

                $contents .= "</table>";
            }

            $contents .= "</div>";

            $contents .= "<div class='row margin-bottom-10 margin-top-10' style='border-top:0px solid #ddd;padding-top: 10px;'>";


            //region signature
            $siseMd = sizeof($reviewSign) > 0 ? (int)(12 / sizeof($reviewSign)) : 12;
            if (isset($sign) && sizeof($sign) > 0) {
                foreach ($reviewSign as $availStep) {
                    $contensSign = $sign[$availStep];
                    $contents .= "<div class='col-md-$siseMd'>";
                    $contents .= "<div class='text-center text-capitalize'>" . $contensSign['label'] . "</div><br><br>";
                    $contents .= "<div class='text-center text-uppercase'>(" . $contensSign['contents'] . ")</div>";
                    $contents .= "</div>";
                }
            }
            //endregion

        }
        else {
            $contents .= $underMaintenance;
        }


        $contents .= "</div>";

        echo $contents;
        break;

    case "editMainFaktur":
        //         cekHere("followupPreview :: HAHAHA ::");
        // cekHere("detailSizeKey :: $detailSizeKey ::");
        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings = array();
        }
        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }

        echo "<ul class='list-group'>";


        foreach ($mainLabels as $key => $label) {
            echo "<li class='list-group-item'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>";
            if (isset($main->$key)) {
                echo formatField($key, $main->$key);
            }
            else {
                //                cekHere($key);
                if (isset($mainValues[$key])) {
                    //                    cekHere("iki");
                    echo formatField($key, $mainValues[$key]);
                }
                else {
                    echo "";
                }

            }

            echo "</div class='col-md-6'>";
            echo "</div class='row'>";

            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";


        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            //        if (isset($items) && sizeof($items) > 0) {
            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";


            $no = 0;
            if (isset($items) && sizeof($items) > 0) {
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                foreach ($items as $id => $iSpec) {
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";


                    foreach ($itemLabels as $key => $label) {

                        // cekHere($key . " " . $iSpec[$key]);
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );

                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }

                        switch ($detailSizeKey) {
                            default:
                            case "ckd":

                                foreach ($items as $pid => $item) {

                                    $replacers = array(
                                        "volume_new" => "volume_gross",
                                        "sub_volume_new" => "sub_volume_gross",
                                        "berat_new" => "berat_gross",
                                        "sub_berat_new" => "sub_berat_gross",
                                    );

                                    foreach ($replacers as $orig => $new) {
                                        if ($key == $orig) {
                                            $key = $new;
                                        }
                                    }
                                }

                                break;
                            case "cbu":
                                break;
                        }


                        $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        if ($key == "stok") {
                            $val = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                        }
                        else {
                            $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;
                        }
                        //                        $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;

                        if ($allowEdit == true && in_array($key, $editableFields)) {
                            //                            cekKuning(":: $key editable ::");
                            if (is_numeric($val)) {
                                $val += 0;
                                $maxVal = isset($iSpec["max_" . $key]) ? $iSpec["max_" . $key] : $iSpec[$key];
                                $inputType = "number";
                                $addEvent = "";
                                if (!$allowIncrement) {
                                    $addEvent = " oninput=\"if(parseInt(this.value)<1 || parseInt(this.value)>$maxVal){this.value='$maxVal';}\" onblur=\"document.getElementById('result').src='$updateItemFieldTarget?id=$id&key=$key&val='+this.value\" ";
                                }
                                else {
                                    $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&val='+this.value\" ";
                                }

                            }
                            else {
                                $inputType = "text";
                                $addEvent = "";
                            }
                            $strVal = "<input type=$inputType name='$key" . "_" . "$id' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
                            //                            cekMerah(":: $key NOT editable ::");
                            $strVal = formatField($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }

                        echo "<td $tdOpt >$strVal";
                        echo "</td>";
                    }
                    if ($allowEdit == true) {//==delete item
                        if ($allowRemove == false) {

                        }
                        else {
                            echo "<td>";
                            echo "<a href='javascript:void(0)' onclick=\"document.getElementById('result').src='$removeItemTarget?id=$id&ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL';\"><span class='glyphicon glyphicon-remove text-danger'></span></a>";
                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                    if ((($noteEnabled === true)) || (($imageEnabled === true))) {

                        if ((isset($iSpec['note']) && strlen($iSpec['note']) > 1) || (isset($iSpec['images']) && strlen($iSpec['images']) > 1)) {

                            echo "<tr line=" . __LINE__ . ">";

                            echo "<td>&nbsp;</td>";
                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                            if (isset($noteEditabled) && ($noteEditabled === true)) {
                                $key_note = "note";
                                $note_val = isset($iSpec['note']) ? $iSpec['note'] : "";
                                $addEvent = " onblur=\"document.getElementById('result').src='$updateItemFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key_note&val='+this.value\" ";
                                if (isset($noteType)) {
                                    switch ($noteType) {
                                        case "textarea":
                                            $iVal = "<textarea class='form-control text-left' onclick='this.select()' $addEvent>$note_val</textarea>";
                                            break;
                                        case "text":
                                        default:
                                            $iVal = "<input type='text' name='$key_note" . "_" . "$id' class='form-control text-left' value='$note_val' onclick='this.select()' $addEvent>";
                                            break;
                                    }
                                }

                            }
                            else {
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                            }
                            $iVal = str_replace("\n", "<br>", $iVal);
                            $iVal = str_replace("\r", "<br>", $iVal);
                            echo "<div class='row no-padding no-margin'>";
                            echo "<div class='col-md-11'>";
                            echo $iVal;
                            echo "</div>";


                            if (($imageEnabled === true)) {
                                $image_val = isset($iSpec['images']) ? $iSpec['images'] : "";
                                if (strlen($image_val) > 1) {
                                    echo "<div class='col-md-1 text-left'>";
                                    echo "<img src='$image_val' height='50px;' stylee='float: right;'>";
                                    echo "</div>";
                                }
                            }
                            echo "</div>";
                            echo "</td>";

                            echo "</tr>";
                        }

                    }
                }

                if (isset($items2) && sizeof($items2) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels2 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items2 as $iSpec2) {
                        $no++;
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels2 as $key2 => $label2) {
                            $replacers = array(
                                "produk_nama" => "nama",
                                "produk_ord_jml" => "jml",
                            );
                            foreach ($replacers as $orig => $new) {
                                if ($key2 == $orig) {
                                    $key2 = $new;
                                    //                                    cekHere(":: $key2 :: $new ::");
                                }
                            }

                            echo "<td>";
                            if (isset($iSpec2[$key2])) {
                                echo formatField($key2, $iSpec2[$key2]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                        }
                        echo "</tr>";
                        //                    if ($noteEnabled == true) {
                        //                        if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                        //                            echo "<tr line=".__LINE__.">";
                        //                            echo "<td>&nbsp;</td>";
                        //                            echo "<td colspan='" . sizeof($itemLabels) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                        //                            $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                        //                            echo $iVal;
                        //                            echo "</td>";
                        //
                        //                            echo "</tr>";
                        //                        }
                        //
                        //                    }
                    }

                }

                if (isset($items3) && sizeof($items3) > 0) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemLabels3 as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items3 as $iSpec) {
                        $no++;

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td align='right'>";
                        echo $no;
                        echo ".</td>";
                        foreach ($itemLabels3 as $key => $label) {
                            echo "<td>";
                            echo formatField($key, $iSpec[$key]);
                            echo "</td>";
                        }
                        echo "</tr>";
                        if ($noteEnabled == true) {
                            if (isset($iSpec['note']) && strlen($iSpec['note']) > 1) {
                                echo "<tr line=" . __LINE__ . ">";
                                echo "<td>&nbsp;</td>";
                                echo "<td colspan='" . sizeof($itemLabels3) . "' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \'Courier New\', monospace;'>";
                                $iVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                                echo $iVal;
                                echo "</td>";

                                echo "</tr>";
                            }

                        }
                    }
                    if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                        foreach ($sumRows3 as $key => $label) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels3) . "' class='text-right'>$label</td>";
                            echo "<td class='text-right'>";
                            if (isset($mainValues[$key])) {
                                echo formatField($key, $mainValues[$key]);
                            }
                            else {
                                echo "";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }

                if (isset($sumRows) && sizeof($sumRows) > 0) {
                    foreach ($sumRows as $key => $label) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$label</td>";
                        echo "<td class='text-right'>";

                        if (isset($mainValues[$key])) {

                            echo formatField($key, $mainValues[$key]);
                        }
                        else {
                            echo "";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }


                //region child data
                if (isset($items_child) && sizeof($items_child) > 0) {
                    echo "<div class='table-responsive'>";
                    //                    echo "<div class=''>Detail</div>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($itemsChildLabel as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";

                    $no = 0;
                    foreach ($items as $id => $itemSpec) {
                        foreach ($items_child[$id] as $x => $iSpec) {
                            $no++;
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo $no;
                            echo ".</td>";
                            foreach ($itemsChildLabel as $key => $label) {
                                //                                cekHere()test
                                if (isset($itemsChildLabelEditable[$key])) {
                                    $inputType = "text";
                                    $val = $iSpec[$key];
                                    $addEvent = " onblur=\"document.getElementById('result').src='$updateItemChildTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$id&key=$key&x=$x&val='+this.value\" ";
                                    $strVal = "<input type=$inputType name='$id" . "_" . "$x' class='form-control text-right' value='$val' onclick='this.select()' $addEvent>";
                                    $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                                }
                                else {
                                    $strVal = $iSpec[$key];
                                }
                                echo "<td $tdOpt>";
                                echo $strVal;
                                echo "</td>";
                            }
                            echo "</tr>";

                        }
                    }


                }

                //endregion


                if (isset($extValueLabels) && sizeof($extValueLabels) > 0) {

                    echo "<tr bgcolor='#e5e5e5'>";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "' class='text-right'>additional fees</td>";

                    echo "</tr>";

                    foreach ($extValueLabels as $key => $lSpec) {
                        if (isset($lSpec['mdlName']) && strlen($lSpec['mdlName']) > 0) {

                            $mdlName9 = $lSpec['mdlName'];
                            $this->load->model("Mdls/" . $mdlName9);
                            $o9 = new $mdlName9();
                            $tmp9 = $o9->lookupAll()->result();
                            $relPairs = array();
                            if (sizeof($tmp9) > 0) {
                                foreach ($tmp9 as $row9) {
                                    $relPairs[$row9->id] = $row9->nama;
                                }
                            }

                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . " source</td>";
                            echo "<td class='text-right'>";
                            //                            echo $mainValues[$key . "_tax"];

                            if (in_array($key, $extEditableFields)) {
                                $defValue = isset($mainAddFields[$key . "_src"]) ? $mainAddFields[$key . "_src"] : 0;
                                $selKey = $key . "_src";
                                echo "<select name='$selKey' class='form-control'>";
                                if (sizeof($relPairs) > 0) {
                                    foreach ($relPairs as $id => $name) {
                                        $selected = $id == $defValue ? "selected" : "";
                                        echo "<option value='$id' $selected>$name</option>";
                                    }
                                }
                                echo "</select>";
                            }
                            else {

                                if (isset($mainAddFields[$key . "_src"]) && $mainAddFields[$key . "_src"] > 0) {
                                    $val = isset($relPairs[$mainAddFields[$key . "_src"]]) ? $relPairs[$mainAddFields[$key . "_src"]] : "";
                                }
                                else {
                                    $val = "n/a";
                                }

                                echo $val;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>" . $lSpec['label'] . "</td>";
                        echo "<td class='text-right'>";
                        //                        echo $mainValues[$key];

                        $val = 0;
                        if (isset($mainValues[$key]) && $mainValues[$key] > 0) {
                            $val = $mainValues[$key];
                        }
                        else {
                            if (isset($mainAddValues[$key]) && $mainAddValues[$key] > 0) {
                                $val = $mainAddValues[$key];
                            }
                        }
                        if (in_array($key, $extEditableFields)) {
                            $defValue = (0 + $val);
                            echo "<input type=number class='form-control text-right' name='$key' step='1000' value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if(parseInt(this.value)>$defValue || parseInt(this.value)<0){this.value='$defValue';}\">";
                        }
                        else {
                            echo formatField($key, $val);
                        }
                        echo "</td>";
                        echo "</tr>";
                        if (isset($lSpec['taxFactor']) && $lSpec['taxFactor'] > 0) {
                            $val = 0;
                            if (isset($mainValues[$key . "_tax"]) && $mainValues[$key . "_tax"] > 0) {
                                $val = $mainValues[$key . "_tax"];
                            }
                            else {
                                if (isset($mainAddValues[$key . "_tax"]) && $mainAddValues[$key . "_tax"] > 0) {
                                    $val = $mainAddValues[$key . "_tax"];
                                }
                            }
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>tax for " . $lSpec['label'] . "</td>";
                            echo "<td class='text-right'>";
                            //                            echo $mainValues[$key . "_tax"];

                            if (in_array($key, $extEditableFields)) {
                                $defValue = (0 + $val);
                                echo "<input type=number class='form-control text-right' name='$key" . "_tax" . "' step=1000 value='" . ($defValue) . "' min='0' max='" . ($defValue) . "' onkeyup=\"if (parseInt(this.value) > $defValue || parseInt(this.value)<0) {this.value= '$defValue';}\">";
                            }
                            else {
                                echo formatField($key . "_tax", $val);
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                }

                if (isset($mainInputs) && sizeof($mainInputs) > 0) {
                    foreach ($mainInputs as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$key</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (isset($addRows) && sizeof($addRows) > 0) {
                    foreach ($addRows as $key => $val) {
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>$addRowLabels[$key]</td>";
                        echo "<td class='text-right'>";

                        echo formatField($key, $val);
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                //arrPrint($addMainSourceField);
                //region extended add main source
                $no = 0;
                if (isset($addMainSourceField) && sizeof($addMainSourceField) > 0) {
                    echo "<div class='table-responsive'>";
                    //                    echo "<div class=''>Detail</div>";
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr bgcolor='#f5f5f5'>";
                    echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                    foreach ($addMainSourceField as $key => $label) {
                        echo "<th class='text-muted' style='font-weight:bold;'>";
                        echo $label;
                        echo "</th>";
                    }
                    echo "</tr>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td $tdOpt>";
                    echo "1";
                    echo "</td>";
                    foreach ($addMainSourceField as $kol => $alias) {
                        if (isset($addMainSourceEdit[$kol])) {
                            $inputType = $addMainSourceEdit[$kol];
                            $val = isset($mainValues[$kol]) ? $mainValues[$kol] : "";
                            $addEvent = " onblur=\"document.getElementById('result').src='$updateMainSourceTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$kol&&val='+this.value\" ";
                            $strVal = "<input type=$inputType name='$kol' class='form-control text-left' value='$val' onclick='this.select()' $addEvent>";
                            $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                        }
                        else {
                            $strVal = formatField($kol, $mainValues[$kol]);
                        }
                        echo "<td $tdOpt>";
                        echo $strVal;
                        echo "</td>";

                    }
                    echo "</tr>";


                }


                //endregion

                //	            if(isset($main['tagihan'])){
                //		            echo "<tr line=".__LINE__.">";
                //		            echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right'>sisa tagihan</td>";
                //		            echo "<td class='text-right'>";
                //
                //		            echo formatField("tagihan", $main['tagihan']);
                //		            echo "</td>";
                //		            echo "</tr>";
                //	            }
            }


            echo "</table>";

            //cbu-ckd
            if (isset($items) && sizeof($items) > 0) {
                $volume_gross = "";
                $berat_gross = "";
                if (isset($detilSizeBar) && sizeof($detilSizeBar) > 0) {

                    if (isset($mainElements['detilSize'])) {
                        if (in_array('detilSize', $editableElements)) {
                            $editLink = "BootstrapDialog.show(
                                       {
                                           title:'detilSize',
                                            message: $('<div></div>').load('" . $elementEditTarget . "detilSize" . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                            size:BootstrapDialog.SIZE_WIDE,
                                            draggable:false,
                                            closable:true,
                                            }
                                            );
                                           ";

                            echo "<div style='font-size: 14px;' class='text-center col-md-12'>";
                            echo "Anda Sedang Menggunakan Data Ukuran: <span class='text-uppercase text-bold'>$detailSizeKey</span> ";
                            echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                            echo "<span class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i> ganti</span>";
                            echo "</a>";
                            echo "</div>";
                        }
                    }

                    $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                    $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='0' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                                </div>
                             </div>";
                    echo "<div style='background: #ffdecf;padding-top: 6px; padding-bottom:6px;' class='col-md-3 col-lg-3'>
                                <div class='input-group'>
                                <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                                <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                                </div>
                             </div>";
                    echo "&nbsp;";
                }
            }

            if (isset($items) && sizeof($items) > 0) {

                if (sizeof($mainElements) > 0) {

                    echo "<h4>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    foreach ($mainElements as $elName => $aSpec) {
                        //                        cekHere($elName);
                        if (array_key_exists($elName, $elementConfig)) {
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " </span>";
                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";
                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($itemLabels)) . "' bgcolor='#ffffff'>";
                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));
                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            echo "<tr line=" . __LINE__ . ">";
                                            $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                            if (strlen($strLabel) > 0) {
                                                echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                            }
                                            echo "<td align='left' class='text-black'>$val</td>";
                                            echo "</tr>";
                                        }
                                        echo "</table>";
                                    }
                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";
                    echo "</div class='panel-default'>";
                }

                if (strlen($description) > 0) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').
src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+this.value;\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($description);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }

                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }

            echo "</div class='table-responsive'>";


            if (isset($items) && sizeof($items) > 0) {
                echo "<div>";

                // echo "<div class='col-md-2'>";
                echo "<button type='button' class='btn btn-default' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </button>";
                // echo "</div class='col-md-2'>";

                echo "&nbsp;<div class='btn-group'>";
                if (isset($deleteSpec['targetUrl']) != "" && $deleteSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $deleteSpec['warning'] . "')==1){document.getElementById('f1').action='" . $deleteSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $deleteSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($undoSpec['targetUrl']) != "" && $undoSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $undoSpec['warning'] . "')==1){document.getElementById('f1').action='" . $undoSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $undoSpec['label'] . "</button>";
                }
                // echo "</div class='col-md-2'>";

                // echo "<div class='col-md-2'>";
                if (isset($editSpec['targetUrl']) != "" && $editSpec['targetUrl'] != "") {
                    echo "<button type='button' class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' onclick=\"if(confirm('" . $editSpec['warning'] . "')==1){document.getElementById('f1').action='" . $editSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-pencil'></span> " . $editSpec['label'] . "</button>";
                }
                else {
                    echo "<button type='button' disabled class='btn btn-default' style='border:1px #ff7700 solid;color:#ff7700;' ><span class='fa fa-undo'></span> " . $editSpec['label'] . "</button>";
                }
                echo "</div>";

                // echo "<div class='col-md-2'>&nbsp;";
                // echo "</div class='col-md-2'>";
                echo "<div class='btn-group pull-right'>";
                if ((isset($extBtns) && sizeof($extBtns) > 0) || (isset($payBtns) && sizeof($payBtns) > 0)) {
                    // echo "<div class='panel-body'>";
                    if ((isset($extBtns) && sizeof($extBtns) > 0)) {
                        foreach ($extBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if ((isset($payBtns) && sizeof($payBtns) > 0)) {
                        foreach ($payBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    else {
                        echo "<button type='button' disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    echo "<button type='button' disabled class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;'><span class='fa fa-play'></span> " . $approvalSpec['label'] . "</button>";
                    // echo "</div>";
                }
                else {
                    if ((isset($extNewBtns) && sizeof($extNewBtns) > 0)) {
                        foreach ($extNewBtns as $btnKey => $btnStr) {
                            echo $btnStr;
                        }
                    }
                    if (isset($rejectionSpec['targetUrl']) != "" && $rejectionSpec['targetUrl'] != "") {
                        echo "<button type='button' class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;' onclick=\"if(confirm('" . $rejectionSpec['warning'] . "')==1){document.getElementById('f1').action='" . $rejectionSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    else {
                        echo "<button button type='button' disabled class='btn btn-default' style='border:1px #dd3300 solid;color:#dd3300;'><span class='glyphicon glyphicon-ban-circle'></span> " . $rejectionSpec['label'] . "</button>";
                    }
                    if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                        echo "<button button type='button' class='btn btn-success' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</button>";
                    }
                    else {
                        echo "&nbsp;";
                    }
                }


                if (isset($xShipmentBtn['targetUrl']) != "" && $xShipmentBtn['targetUrl'] != "") {
                    echo "<span class='btn btn-default ' style='border:1px #fff solid;color:#ff7700;' onclick=\"if(confirm('" . $xShipmentBtn['warning'] . "')==1){document.getElementById('f1').action='" . $xShipmentBtn['targetUrl'] . "';document.getElementById('f1').submit();}\"><span class='fa fa-remove'></span> " . $xShipmentBtn['label'] . "</span>";
                }

                echo "</div>";

                echo "</div>"; // 2669

                echo "<div class='row' style='margin-top: 60px;'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";
                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";
                echo "</div>";
                echo "</div class='row'>";
            }
            echo "</form>";
        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();
        }

        break;

    case "index_multi":
        $arrBlacklist = array(
            "no",
        );

        if (strlen($errMsg) > 0) {
            $error = "<div class='alert alert-danger-dot text-center'><span>$errMsg</span></div>";
        }
        else {
            $error = "";
        }

        $title = isset($title) ? $title : "";
        $subTitle = isset($subTitle) ? $subTitle : "";
        $arrayHistoryLabels = isset($arrayHistoryLabels) ? $arrayHistoryLabels : array();
        $arrayHistory = isset($arrayHistory) ? $arrayHistory : array();

        $p = New Layout("$title", "$subTitle", "application/template/transaksi_index2.html");


        //arrPrint($arrayProgressLabels);
        //        matiHEre();
        $strHistFooter = "";
        $strRecapFooter = "";
        $strRecap = "";
        $strHist = "";
        $addLinkStr = "";
        $onpropDisplayView = "";
        $propDisplay = "";
        $str_group = "";
        $altDisplay = "";
        $disabledLockerTransaksi = "";
        $strOnprog = "";
        $strOnprog2 = "";
        $strOnprog3 = "";

        if (sizeof($arrayOnProgress) > 0) {
            foreach ($arrayOnProgress as $item => $itemsDetails) {
                $qstr = "";
                if ($item == "itemsSrc") {
                    $selectedProcessor = $selectProcessor['itemsSrc'];
                    $strOnprog .= "<table id='arrayOnProgress' class='02 table stripe table-condensed table-bordered no-padding'>";
                    $strOnprog .= "<thead>";
                    $strOnprog .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($arrayProgressLabels['itemsSrc']) > 0) {
                        $strOnprog .= "<th class=''>select</th>";
                        foreach ($arrayProgressLabels['itemsSrc'] as $key => $label) {
                            $strOnprog .= "<th class=''>";
                            if (is_array($label)) {
                                $strOnprog .= isset($label['label']) ? $label['label'] : "-";
                            }
                            else {
                                $strOnprog .= $label;
                            }
                            $strOnprog .= "</th>";
                        }
                    }
                    $strOnprog .= "</tr>";
                    $strOnprog .= "</thead>";
                    $strOnprog .= "<tbody>";
                    $nop = 0;
                    foreach ($itemsDetails as $key => $val) {
                        $nop++;
                        $qstrLabels = array(
                            "transaksi_id" => "trID",
                            "nomer" => "nomer",
                            "extern_id" => "xID",
                            "tagihan" => "tagihan",
                            "terbayar" => "terbayar",
                            "sisa" => "sisa",
                            "diskon" => "diskon",
                            "extern_nama" => "xID",
                            "tagihan_valas" => "tagihan_valas",
                            "terbayar_valas" => "terbayar_valas",
                            "sisa_valas" => "sisa_valas",
                            "diskon_valas" => "diskon_valas",
                            "valas_id" => "valas_id",
                            "valas_nama" => "valas_nama",
                            "valas_nilai" => "valas_nilai",
                            "id_master" => "id_master",
                            "extern_label2" => "pihakMainName",
                            "extern_nilai2" => "extern_nilai2",
                            "extern_nilai3" => "extern_nilai3",
                            "extern_nilai4" => "extern_nilai4",
                            "pph_23" => "pph_23",
                            "ppn_sisa" => "ppn_payment",
                            "ppn" => "ppn",
                            "extern2_id" => "extern2_id",
                            "extern2_nama" => "extern2_nama",
                            "extern_date2" => "extern_date2",
                            "extern_jenis" => "extern_jenis",
                            "jenis_master" => "jenis_master",
                            //                        "id_master" => "id_master",
                        );
                        $qstr = "";
                        foreach ($qstrLabels as $key => $label) {
                            $qstr .= "&$key=" . $val[$key];
                        }
                        $checked = "";
                        $disabled = "";
                        $strOnprog .= "<tr line=" . __LINE__ . ">";
                        $strOnprog .= "<td class='text-center'><input class='chRadio' type=checkbox $checked $disabled $disabledLockerTransaksi value='" . $val['transaksi_id'] . "' id='opt" . $val['transaksi_id'] . "' onclick=\"document.getElementById('result').src='" . base_url() . "$selectedProcessor/$jenisTr" . "?$qstr&state='+this.checked;\"></td>";
                        if (sizeof($arrayProgressLabels['itemsSrc']) > 0) {
                            foreach ($arrayProgressLabels['itemsSrc'] as $key => $label) {

                                $strOnprog .= "<td>";
                                //                                $strOnprog .= formatField($key, $val[$key]);
                                if (isset($defaultItemTrgEditable) && in_array($key, $defaultItemTrgEditable)) {

                                    //                                    ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&
                                    $pymSrcID = $val['id'];
                                    $pymSrcTrID = $val['transaksi_id'];
                                    $pymSrcMasterID = $val['id_master'];

                                    $updateItemFieldTarget = $editItemTrg . "$pymSrcID/$pymSrcTrID/$pymSrcMasterID";
                                    $inputType = "text";
                                    $addEvent = " oonkeyup=\"this.value=addCommas(this.value);\" 
                                                onblur=\"top.$('#result').load('$updateItemFieldTarget?id=$pymSrcID&key=$key&val='+this.value)\" ";
                                    $strOnprog .= "<input type=$inputType name='$key" . "_" . "$nop' class='form-control text-right' value='" . $val[$key] . "' onclick='this.select()' $addEvent>";
                                }
                                else {
                                    $strOnprog .= formatField($key, $val[$key]);
                                }
                                $strOnprog .= "</td>";
                            }
                        }
                        $strOnprog .= "</tr>";
                    }
                    $strOnprog .= "</tbody>";

                    //region footer summary bawah
                    $strOnprog .= "<tfoot>";
                    $strOnprog .= "<tr bgcolor='#f0f0f0'>";
                    $strOnprog .= "<td>&nbsp;</td>";
                    foreach ($arrayProgressLabels['itemsSrc'] as $key => $label) {
                        if (isset($sumFooter['itemsSrc'][$key])) {
                            $strOnprog .= "<td class='$key'>";
                            $strOnprog .= formatField($key, $sumFooter['itemsSrc'][$key]);
                            //                            $strOnprog .=$sumFooter['itemsSrc'][$key];
                            $strOnprog .= "</td>";
                        }
                        else {
                            $strOnprog .= "<td>&nbsp;</td>";
                        }
                    }
                    $strOnprog .= "</tr>";
                    $strOnprog .= "</tfoot>";
                    //endregion
                    $strOnprog .= "</table>";
                }
                else {

                    $selectedProcessor = $selectProcessor['items'];
                    $strOnprog2 .= "<table id='arrayOnProgreshs' class='02 table stripe table-condensed table-bordered no-padding'>";
                    $strOnprog2 .= "<thead>";
                    $strOnprog2 .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($arrayProgressLabels['items']) > 0) {
                        $strOnprog2 .= "<th class=''>select</th>";
                        foreach ($arrayProgressLabels['items'] as $key => $label) {
                            $strOnprog2 .= "<th class=''>";
                            if (is_array($label)) {
                                $strOnprog2 .= isset($label['label']) ? $label['label'] : "-";
                            }
                            else {
                                $strOnprog2 .= $label;
                            }
                            $strOnprog2 .= "</th>";
                        }
                    }
                    $strOnprog2 .= "</tr>";
                    $strOnprog2 .= "</thead>";
                    $strOnprog2 .= "<tbody>";
                    $no = 0;
                    foreach ($itemsDetails as $key => $val) {
                        //                        arrPrintPink($val);
                        $no++;
                        $qstrLabels = array(
                            "transaksi_id" => "trID",
                            "nomer" => "nomer",
                            "extern_id" => "xID",
                            "tagihan" => "tagihan",
                            "terbayar" => "terbayar",
                            "sisa" => "sisa",
                            "diskon" => "diskon",
                            "extern_nama" => "xID",
                            "tagihan_valas" => "tagihan_valas",
                            "terbayar_valas" => "terbayar_valas",
                            "sisa_valas" => "sisa_valas",
                            "diskon_valas" => "diskon_valas",
                            "valas_id" => "valas_id",
                            "valas_nama" => "valas_nama",
                            "valas_nilai" => "valas_nilai",
                            "id_master" => "id_master",
                            "extern_label2" => "pihakMainName",
                            "extern_nilai2" => "extern_nilai2",
                            "extern_nilai3" => "extern_nilai3",
                            "extern_nilai4" => "extern_nilai4",
                            "pph_23" => "pph_23",
                            "ppn_sisa" => "ppn_payment",
                            "ppn" => "ppn",
                            "extern2_id" => "extern2_id",
                            "extern2_nama" => "extern2_nama",
                            "extern_date2" => "extern_date2",
                            "extern_jenis" => "extern_jenis",
                            "jenis_master" => "jenis_master",
                            //                        "id_master" => "id_master",
                        );
                        $qstr = "";
                        foreach ($qstrLabels as $key => $label) {
                            $qstr .= "&$key=" . $val[$key];
                        }
                        $checked = "";
                        $disabled = "";
                        $strOnprog2 .= "<tr line=" . __LINE__ . ">";
                        $strOnprog2 .= "<td class='text-center'><input class='chRadio' type=checkbox $checked $disabled $disabledLockerTransaksi value='" . $val['transaksi_id'] . "' id='opt" . $val['transaksi_id'] . "' onclick=\"document.getElementById('result').src='" . base_url() . "$selectedProcessor/$jenisTr" . "?$qstr&state='+this.checked;\"></td>";
                        if (sizeof($arrayProgressLabels['items']) > 0) {
                            foreach ($arrayProgressLabels['items'] as $key => $label) {

                                $strOnprog2 .= "<td>";
                                if (isset($defaultItemTrgEditable) && in_array($key, $defaultItemTrgEditable)) {

                                    //                                    ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&
                                    $pymSrcID = $val['id'];
                                    $pymSrcTrID = $val['transaksi_id'];
                                    $pymSrcMasterID = $val['id_master'];

                                    $updateItemFieldTarget = $editItemTrg . "$pymSrcID/$pymSrcTrID/$pymSrcMasterID";
                                    $inputType = "text";
                                    $addEvent = " oonkeyup=\"this.value=addCommas(this.value);\" 
                                                onblur=\"top.$('#result').load('$updateItemFieldTarget?id=$pymSrcID&key=$key&val='+this.value)\" ";
                                    $strOnprog2 .= "<input type=$inputType name='$key" . "_" . "$no' class='form-control text-right' value='" . $val[$key] . "' onclick='this.select()' $addEvent>";
                                }
                                else {
                                    $strOnprog2 .= formatField($key, $val[$key]);
                                }
                                $strOnprog2 .= "</td>";
                            }
                        }
                        $strOnprog2 .= "</tr>";
                    }
                    $strOnprog2 .= "</tbody>";

                    //region footer summary bawah
                    $strOnprog2 .= "<tfoot>";
                    $strOnprog2 .= "<tr bgcolor='#f0f0f0'>";
                    $strOnprog2 .= "<td>&nbsp;</td>";
                    foreach ($arrayProgressLabels['items'] as $key => $label) {
                        if (isset($sumFooter['itemsSrc'][$key])) {
                            $strOnprog2 .= "<td class='$key'>";
                            $strOnprog2 .= formatField($key, $sumFooter['items'][$key]);
                            //                            $strOnprog .=$sumFooter['itemsSrc'][$key];
                            $strOnprog2 .= "</td>";
                        }
                        else {
                            $strOnprog2 .= "<td>&nbsp;</td>";
                        }
                    }
                    $strOnprog2 .= "</tr>";
                    $strOnprog2 .= "</tfoot>";
                    //endregion

                    $strOnprog2 .= "</table>";
                }


            }

            //            $strOnprogFooter = "<a class='btn btn-default' href='" . base_url() . $this->uri->segment(1) . "/viewIncomplete/" . $jenisTr . "'><span class='glyphicon glyphicon-time'></span> complete list ...</a>";
        }
        else {
            $strOnprog = "-the item you specified has no entry-";
            $strOnprogFooter = "";
        }


        $strOnprog3 .= "<div class='col-lg-1 no-padding'></div>";
        $strOnprog3 .= "<div class='col-lg-10 no-padding'>
                            <div class='panel'>
                                <div class='panel-header'>
                                    <span class='pull-left'></span>
                                </div>
                                <div class='box-body no-padding' id='shopping_cart'>
                                    <div class='panel-body'>
                                        <div class='text-danger'>- <strong>you have not chosen any item yet</strong> -<br>
                                        <small>you can do so by selecting items from available selectors</small><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";
        $strOnprog3 .= "<div class='col-lg-1 no-padding'></div>";


        //region tombol
        $strOnprog3 .= "<table class='table table-condensed no-padding'>";
        $strBankAcc = "";
        $defValue = isset($ses_outMaster['sisa']) ? $ses_outMaster['sisa'] : 0;
        $defPaymentValue = isset($ses_outMaster['nilai_bayar']) ? $ses_outMaster['nilai_bayar'] : 0;
        $creditAmount = isset($ses_outMaster['creditAmount']) ? $ses_outMaster['creditAmount'] : 0;
        $defaultDisabled = $defPaymentValue > 0 ? "" : "disabled";

        $paymentRows = array(
            " " => "<label>
                            <input type=checkbox 
                            onclick=\"
                            if(this.checked==true){
                            setTimeout(function(){
                            document.getElementById('result').src='" . base_url() . "ValueGate/buildValues/$jenisTr';
                            document.getElementById('btnSave').disabled=false;
                            },1200);}
                            //else{
                            //document.getElementById('btnSave').disabled=true;this.checked=false;
                            //if(document.getElementById('nilai_entry')){
                            //hiliteDiv('nilai_entry');document.getElementById('nilai_entry').focus();document.getElementById('nilai_entry').select();
                            //}
                            //}
                            \"> i confirm that the numbers above are correct</label>",


            "" => "<input type=button class='btn btn-success btn-block' id='btnSave' value='$btnLabel' disabled 
                        onclick=\"
                                if(parseInt(removeCommas(document.getElementById('nilai_entry').value))>parseInt(removeCommas(document.getElementById('nilai_sisa').value)) || parseInt(removeCommas(document.getElementById('nilai_entry').value))<0)
                                {alert('please fill in amount value');}else {$actionTarget}\">",
        );


        foreach ($paymentRows as $key => $val) {
            $strOnprog3 .= "<tr line=" . __LINE__ . ">";
            $strOnprog3 .= "<td>$key</td>";
            $strOnprog3 .= "<td>$val</td>";
            $strOnprog3 .= "</tr>";
        }
        $strOnprog3 .= "</table>";

        if (isset($isPaymentRadioSelect) && $isPaymentRadioSelect == true) {
            $strOnprog .= "<script>
                                    $(\".chRadio\").change(function(){
                                        $(\".chRadio\").prop('checked',false);
                                        $(this).prop('checked',true);
                                        console.log(this.checked);
                                    });
                               </script>";
        }

        arrPrint($btnLabel);
        //endregion

        $p->addTags(
            array(
                "error_msg" => $error,
                "jenisTr" => $jenisTr . $str_group,
                "trName" => $trName,
                "alt_display" => $altDisplay,
                "prop_display" => $propDisplay,

                "menu_left" => callMenuLeft(),
                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),

                "prePre_title" => isset($prePreTitle) ? $prePreTitle : "",
                "prePre_content" => isset($strOnprePre) ? $strOnprePre : "",
                "prePre_footer" => isset($strOnprePreFooter) ? $strOnprePreFooter : "",

                "onprogress_title" => "",
                "onprogress_content" => "",
                "onprogress_footer" => isset($strOnprogFooter) ? $strOnprogFooter : "",

                "onprogressView_title" => isset($onprogressViewTitle) ? $onprogressViewTitle : "",
                "onprogressView_subtitle" => isset($onprogressViewSubTitle) ? $onprogressViewSubTitle : "",
                "onprogressView_content" => "",
                "onprop_display_view" => $onpropDisplayView,
                "item_src" => $strOnprog,
                "items" => $strOnprog2,
                "items_btn" => $strOnprog3,
                "add_link" => $addLinkStr,
                "history_title" => $historyTitle,
                "history_content" => $strHist,
                "history_footer" => $strHistFooter,
                "recap_title" => $recapTitle,
                "recap_content" => $strRecap,
                "recap_footer" => $strRecapFooter,
                "profile_name" => $this->session->login['nama'],
                "newTrTarget" => isset($addLink['link']) ? $addLink['link'] . $str_group : "javascript:void(0)",
                "newTrDisp" => isset($addLink['link']) ? "inline-table" : "none",
                "scriptBottom" => isset($scriptBottom) ? $scriptBottom : "",
            )
        );

        $p->render();

        break;

    case "followupMultiPreview":
        echo "<div id='followupPreview' >";
        if (isset($msgWarning) && sizeof($msgWarning)) {
            $msgWarnings = $msgWarning;
            echo "<div class='alert alert-danger text-center'>";
            foreach ($msgWarnings as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";

            $arrSwals = array(
                "type" => "warning",
                "title" => "<span style='color: red;'>Perhatian..</span>",
                "html" => $newWarningLabel,
                "allowOutsideClick" => false,
                // "imageUrl"            => img_bitzer(),
                "background" => "#34abeb",
                "confirmButtonText" => "Close",
                "confirmButtonColor" => "#ff0055",
            );

            echo swalAlert($arrSwals);
        }
        else {
            $msgWarnings = array();
        }
        if (isset($msgWarning2) && sizeof($msgWarning2)) {
            $msgWarnings2 = $msgWarning2;
            echo "<div class='alert alert-danger text-center font-size-1-5'>";
            foreach ($msgWarnings2 as $msgSpec) {
                echo $msgSpec['label'] . "<br>";
            }
            echo "</div class='alert alert-warning'>";

            $newWarningLabel = "<span style='color: yellow;'>";
            $newWarningLabel .= $msgSpec['label'];
            $newWarningLabel .= "<div class='font-size-0-7 margin-top-20'>silahkan tutup notifikasi ini untuk melanjutkan transaksi</div>";
            $newWarningLabel .= "</span>";
            $arrSwals = array(
                "type" => "warning",
                "title" => "<span style='color: red;'>Perhatian</span>",
                "html" => $newWarningLabel,
                "allowOutsideClick" => false,
                // "imageUrl"            => img_bitzer(),
                "background" => "#34abeb",
                "confirmButtonText" => "Close",
                "confirmButtonColor" => "#ff0055",
            );

            echo swalAlert($arrSwals);
        }
        else {
            $msgWarnings2 = array();
        }

        if (sizeof($stepLabels) > 0) {
            echo "<div class='text-center alert alert-info-dot text-grey' style='overflow: hidden;'>";
            // echo "<div class='text-center alert alert-info-dot text-grey' style='font-size:1.2em;'>";
            // echo createStateMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo createStateHorizontalMap($currentStep, sizeof($stepLabels), $stepLabels, $jenisTr);
            echo "</div class=''>";
        }
        // arrPrint($main);
        echo "<ul class='list-group'>";


        foreach ($mainLabels as $key => $label) {
            echo "<li class='list-group-item'>";
            echo "<div class='row'>";
            echo "<div class='col-md-3 text-muted'>";
            echo $label;
            echo "</div class='col-md-4'>";
            echo "<div class='col-md-6'>" . $main[$key];
            if (isset($main->$key)) {
                if (is_array($main[$key])) {
                    $rslt_isi = "";
                    foreach ($main[$key] as $isi) {
                        if ($rslt_isi == "") {
                            $rslt_isi = $isi;
                        }
                        else {
                            $rslt_isi = $rslt_isi . ", $isi";
                        }
                    }
                    echo formatField($key, $rslt_isi);
                }
                else {
                    // cekMErah("sini");
                    echo formatField($key, $main[$key]);
                }
            }


            echo "</div class='col-md-6'>";
            echo "</div class='row'>";

            echo "</li class='list-group-item'>";
        }
        echo "</ul class='list-group'>";
        //mainsub
        $mainSub = "<div class='table-responsive'>";
        $mainSub .= "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
        $mainSub .= "<thead>";
        $mainSub .= "<tr bgcolor='#f0f0f0'>";
        $mainSub .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";

        foreach ($subMainLabel as $keys => $label) {
            $mainSub .= "<th class='text-muted' style='font-weight:bold;'>";
            $mainSub .= $label;
            $mainSub .= "</th>";
        }
        $mainSub .= "</tr>";
        $mainSub .= "</thead>";
        $mainSub .= "<tbody>";
        $mainSub .= "<tr>";
        $mainSub .= "<td class='text-muted' style='font-weight:bold;'>1</td>";
        foreach ($subMainLabel as $keys => $label) {
            $mainSub .= "<td >";
            $mainSub .= formatField_he_format($keys, $main[$keys]);
            $mainSub .= "</td>";
        }
        $mainSub .= "</tr>";
        $mainSub .= "</tbody>";

        $mainSub .= "</table>";
        $mainSub .= "</div class='table-responsive'>";

        echo $mainSub;

        if ((isset($items) && sizeof($items) > 0) || (isset($items2) && sizeof($items2) > 0)) {
            //        if (isset($items) && sizeof($items) > 0) {
            echo "<form id='f1' name='f1' method='post' target='result'>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
            $no = 0;
            if (isset($items) && sizeof($items) > 0) {
                echo "<thead>";
                echo "<tr bgcolor='#f0f0f0'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                foreach ($subItemLabels as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                // arrPrint($subData);
                $subTotalITems = 0;
                foreach ($items as $id => $iSpec) {
                    // arrPrint($iSpec);
                    // arrPrint($subData[$id]);
                    if (array_key_exists($id, $msgWarnings)) {
                        $addStyle = "background-color:yellow;color:#000000;";
                    }
                    else {
                        $addStyle = "";
                    }

                    $no++;
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td align='right' style='$addStyle'>";
                    echo $no;
                    echo ".</td>";
                    foreach ($subItemLabels as $key => $label) {
                        // cekMErah($key);
                        $replacers = array(
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "jml",
                        );

                        foreach ($replacers as $orig => $new) {
                            if ($key == $orig) {
                                $key = $new;
                            }
                        }
                        if ($key == "details") {
                            $subItems = "";
                            if (isset($subData[$key][$id])) {
                                // ceklIme(sizeof($subData[$key][$id]));
                                $subItems .= "<div class='table-responsive' sstyle='border:1px solid red;'>";
                                $subItems .= "<table class='table table-bordered table-condensed' style='margin-top:0px;'>";
                                $subTotal = 0;
                                foreach ($subData[$key][$id] as $jn => $tmp) {
                                    if (sizeof($tmp) > 0) {
                                        $subItems .= "<tr bgcolor='#f5f5f5'>";
                                        $subItems .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                                        foreach ($subItemLabel2 as $k => $kLabel) {
                                            $subItems .= "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>$kLabel</th>";
                                        }
                                        $subItems .= "</tr>";
                                        $ix = 0;
                                        $total = 0;
                                        foreach ($tmp as $data) {
                                            $subItems .= "<tr>";

                                            // arrPrint($data);
                                            $ix++;
                                            $total += $data["sub_harga"];
                                            $subItems .= "<td>$ix</td>";
                                            foreach ($subItemLabel2 as $k => $kLabel) {

                                                $subItems .= "<td>";
                                                $subItems .= isset($data[$k]) ? formatField_he_format($k, $data[$k]) : "-";
                                                $subItems .= "</td>";
                                            }
                                            $subItems .= "</tr>";
                                        }
                                        // cekHitam($total);

                                        $subTotal += $total;
                                    }
                                }
                                $subTotalITems += $subTotal;
                                $subItems .= "<tr>";
                                $subItems .= "<td colspan='6' class='text-right'>total </td>";
                                $subItems .= "<td>";
                                $subItems .= formatField_he_format("subtotal", $subTotal);
                                $subItems .= "</td>";
                                $subItems .= "</tr>";
                                $subItems .= "</table>";
                                $subItems .= "</div>";
                            }
                            $strVal = $subItems;
                        }
                        else {
                            $subVal = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            if ($key == "stok") {
                                $val = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            }
                            elseif ($key == "stok_center") {
                                $val = isset($iSpec[$key]) ? $iSpec[$key] : 0;
                            }
                            else {
                                $val = isset($detailValues[$id][$key]) ? $detailValues[$id][$key] : $subVal;
                            }
                            $strVal = formatField_he_format($key, $val);
                            $tdOpt = "style='$addStyle'";
                        }
                        echo "<td $tdOpt >$strVal";

                        echo "</td>";
                    }

                    echo "</tr>";
                }
                echo "<tr>";
                echo "<td colspan='3' >Total Packing list</td>";
                echo "<td class='pull-right no-border'>" . formatField_he_format("subtotal", $subTotalITems) . "</td>";
                echo "</tr>";


                // matiHere();

            }
            echo "</tbody>";
            echo "</table>";
            //region extended add main source
            $no = 0;
            if (isset($addMainSourceField) && sizeof($addMainSourceField) > 0) {
                echo "<div class='table-responsive'>";
                echo "<h4 style='margin-bottom:-20px;'>Garansi</h4>";
                echo "<table class='table table-bordered table-condensed'>";
                echo "<tr bgcolor='#f5f5f5'>";
                echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>No.</th>";
                foreach ($addMainSourceField as $key => $label) {
                    echo "<th class='text-muted' style='font-weight:bold;'>";
                    echo $label;
                    echo "</th>";
                }
                echo "</tr>";
                echo "<tr line=" . __LINE__ . ">";
                echo "<td $tdOpt>";
                echo "1";
                echo "</td>";
                foreach ($addMainSourceField as $kol => $alias) {
                    if (isset($addMainSourceEdit[$kol])) {
                        $inputType = $addMainSourceEdit[$kol];
                        $val = isset($main[$kol]) ? $main[$kol] : "";
                        $addEvent = " onblur=\"document.getElementById('result').src='$updateMainSourceTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&id=$kol&&val='+this.value\" ";
                        $strVal = "<input type=$inputType name='$kol' class='form-control text-left' value='$val' onclick='this.select()' $addEvent>";
                        $tdOpt = "style='margin:0px;padding:0px;$addStyle' ";
                    }
                    else {
                        $strVal = formatField_he_format($kol, $main[$kol], "", "");
                    }
                    echo "<td $tdOpt>";
                    echo $strVal;
                    echo "</td>";

                }
                echo "</tr>";
                echo "</table class='table table-bordered table-condensed'>";
                echo "</div class='table-responsive'>";
            }
            //endregion
//            arrPrintWebs($extractedSumSubItems);
//            arrPrintPink($additionalPackinglist);
//            cekHere($checklistnote_cek);
            if (isset($additionalPackinglist) && (sizeof($additionalPackinglist) > 0)) {
                if (isset($additionalPackinglist['enabled']) && ($additionalPackinglist['enabled'] == true)) {
                    if (isset($extractedSumSubItems) && (sizeof($extractedSumSubItems) > 0)) {
                        echo "<div class='table-responsive'>";
//                        echo "<div class='box box-solid box-danger no-margin'>";
                        echo "<h4 style='margin-bottom:-20px;'>Daftar item (finish goods) yang belum packinglist</h4>";
//cekHere("cek subitem");
                        echo "<table class='table table-bordered table-condensed' style='background:#ffffff;'>";
                        echo "<thead>";
                        echo "<tr bgcolor='#f0f0f0'>";
                        echo "<th class='text-muted' style='font-weight:bold;' align='right' width='5'>no.</th>";
                        foreach ($additionalPackinglist['header'] as $key => $val) {
                            echo "<th class='text-muted' style='font-weight:bold;'>";
                            echo $val;
                            echo "</th>";
                        }
                        echo "</tr bgcolor='#f0f0f0'>";
                        echo "</thead>";

                        echo "<tbody>";
                        $nom = 0;
                        $sumSubItems = array();
                        $total_items = sizeof($extractedSumSubItems);
                        foreach ($extractedSumSubItems as $pID => $spec) {
                            $nom++;
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right' style='$addStyle'>";
                            echo $nom;
                            echo ".</td>";
                            foreach ($additionalPackinglist['header'] as $key => $val) {
                                echo "<td>";
                                echo isset($spec[$key]) ? formatField_he_format($key, $spec[$key], "", "") : "-";
                                echo "</td>";
                                if (isset($spec[$key]) && is_numeric($spec[$key])) {
                                    if (!isset($sumSubItems[$key])) {
                                        $sumSubItems[$key] = 0;
                                    }
                                    $sumSubItems[$key] += $spec[$key];
                                }
                            }
                            echo "</tr>";
                        }
                        echo "<tr>";
                        echo "<td align='right' colspan='3'>Total</td>";
                        echo "<td align='right'>" . formatField("qty", $sumSubItems['qty'], "", "") . "</td>";
                        echo "<td align='left'>-</td>";
                        echo "</tr>";
                        echo "</tbody>";

                        echo "<table>";
                        echo "</table>";
                        echo "</div class='panel panel-danger'>";
                        echo "<div class='alert alert-danger'>";

                        $msgNote = "Lanjutkan Close Project dengan menutup $total_items item, " . $sumSubItems['qty'] . " unit";
                        $checklistNoteEncode = blobEncode($msgNote);
                        echo "<input type='checkbox' value='' 
                            onclick=\"document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode';\">";
                        echo "&nbsp; <span>$msgNote.</span>";
                        echo "</div>";
                    }
                    else {
                        echo "<div class='alert alert-danger'>";

                        $msgNote = "Lanjutkan Close Project.";
                        $checklistNoteEncode = blobEncode($msgNote);
                        $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";
                        echo "<input type='checkbox' value='' $checked
                            onclick=\"document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode';\">";
                        echo "&nbsp; <span>$msgNote.</span>";
                        echo "</div>";
                    }
                }
                else {
                    echo "<div class='alert alert-danger'>";

                    $msgNote = "Lanjutkan Close Project.";
                    $checklistNoteEncode = blobEncode($msgNote);
                    $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";
                    echo "<input type='checkbox' value='' $checked
                            onclick=\"document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode';\">";
                    echo "&nbsp; <span>$msgNote.</span>";
                    echo "</div>";
                }
            }
            else {
                echo "<div class='alert alert-danger'>";

                $msgNote = "Lanjutkan Close Project.";
                $checklistNoteEncode = blobEncode($msgNote);
                $checked = isset($checklistnote_cek) && ($checklistnote_cek == 1) ? "checked" : "";
                echo "<input type='checkbox' value='' $checked
                            onclick=\"document.getElementById('result').src='" . $checklistNotePaired . "?checklistnote=$checklistNoteEncode';\">";
                echo "&nbsp; <span>$msgNote.</span>";
                echo "</div>";
            }

            if (isset($items) && sizeof($items) > 0) {
                if (sizeof($mainElements) > 0) {
                    //                    arrPrint($mainElements);
                    echo "<h4>$title details</h4>";
                    echo "<div class='panel panel-default' style='background:#f0f0f0;'>";
                    echo "<table class='table table-bordered table-condensed'>";
                    foreach ($mainElements as $elName => $aSpec) {
                        //                        cekBiru("$elName");
                        if (array_key_exists($elName, $elementConfig)) {
                            //                            cekKuning("$elName");
                            echo "<tr line=" . __LINE__ . ">";
                            echo "<td align='right'>";
                            echo "<span class='text-muted'>" . $aSpec['label'] . " &nbsp;&nbsp;&nbsp;</span>";
                            if (in_array($elName, $editableElements)) {
                                $editLink = "BootstrapDialog.show(
                                   {
                                       title:'$elName',
                                        message: $('<div></div>').load('" . $elementEditTarget . $elName . "?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL'),
                                        size:BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );
                                       ";
                                echo "<span class='pull-right'>";
                                echo "<a href='javascript:void(0)' class='text-muted' onclick=\"$editLink\">";
                                echo "<span class='glyphicon glyphicon-pencil'></span>";
                                echo "</a>";
                                echo "</span class='pull-right'>";
                            }

                            echo "</td>";
                            echo "<td colspan='" . (sizeof($subMainLabel)) . "' bgcolor='#ffffff'>";
                            switch ($elementConfig[$elName]['elementType']) {
                                case "dataModel":
                                    $elContents = unserialize(base64_decode($aSpec['contents']));

                                    if (sizeof($elContents) > 0) {
                                        echo "<table class='tables table-condensed'>";
                                        foreach ($elContents as $label => $val) {
                                            if ($val != "") {
                                                echo "<tr line=" . __LINE__ . ">";
                                                $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                                if (strlen($strLabel) > 0) {
                                                    echo "<td align='left' class='text-muted'>" . $strLabel . "</td>";
                                                }
                                                echo "<td align='left' class='text-black'>$val</td>";
                                                echo "</tr>";
                                            }


                                        }
                                        echo "</table>";
                                    }
                                    else {
                                        //                                        echo "<table class='tables table-condensed'>";
                                        //                                        echo "<tr line=".__LINE__.">";
                                        //                                        $strLabel = isset($elementConfig[$elName]['usedFields'][$label]) ? $elementConfig[$elName]['usedFields'][$label] : "";
                                        //                                        echo "<td align='left' class='text-black'>$strLabel harus dipilih</td>";
                                        //                                        echo "</tr>";
                                        //                                        echo "</table>";

                                        $msg = "<span class='glyphicon glyphicon-arrow-left'></span> &nbsp;&nbsp;silahkan " . $aSpec['label'] . " dipilih ulang dengan klik icon pensil sebelah kiri.";
                                        echo "<table class='tables table-condensed'>";
                                        echo "<tr line=" . __LINE__ . ">";
                                        echo "<td align='left' class='text-red' style='font-size: 15px;'>$msg</td>";
                                        echo "</tr>";
                                        echo "</table>";
                                    }
                                    break;
                                case "dataField":
                                    echo $aSpec['value'];
                                    break;
                            }
                            echo "</td>";
                            echo "</tr>";
                        }

                    }
                    echo "</table>";
                    echo "</div class='panel-default'>";
                }

                // if (strlen($description) > 0) { // mendeteksi jumlah karakter catatan, kalau lebih dari 0 maka ditampilkan. berlaku semua transaksi.
                if (isset($description)) { // mendeteksi gerbang catatan (main), bila ada maka ditampilkan. berlaku semua transaksi.
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note</span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";

                    // bila bisa mengedit catatan dan mau edit maka editlah.
                    if (isset($noteEditabled) && ($noteEditabled == true)) {
                        $key_note = "description";
                        $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($description);
                        echo "</textarea>";
                    }
                    // bila tidak bisa mengedit catatan, maka lihat saja
                    else {
                        if (strlen($description) > 0) {

                            echo nl2br($description);
                        }
                        else {
                            echo "-";
                        }
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }

                if (isset($descriptionAdditionalRule) && ($descriptionAdditionalRule['enabled'] == true)) {
                    echo "<table class='table table-bordered table-condensed'>";
                    echo "<tr line=" . __LINE__ . ">";
                    echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                    echo "<span class='text-muted'>description note (from current step) </span><br>";
                    echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";
                    if (isset($descriptionAdditionalRule['editabled']) && ($descriptionAdditionalRule['editabled'] == true)) {
                        $key_note = "description_additional";
                        $addEvent_description = " onblur=\"document.getElementById('result').src='$updateMainFieldTarget?ravPrev=$rawPrevURL&rawBuilderURL=$rawBuilderURL&key=$key_note&val='+encodeURIComponent(this.value);\"";
                        echo "<textarea class='form-control text-left' $addEvent_description>";
                        echo nl2br($descriptionAdditional);
                        echo "</textarea>";
                    }
                    else {
                        echo nl2br($descriptionAdditional);
                    }

                    echo "</span><br>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                else {
                    //                    arrPrint($descriptionAdditionalPreviews);
                    //                    cekHere(sizeof($descriptionAdditionalPreviews));
                    if (sizeof($descriptionAdditionalPreviews) > 0) {
                        echo "<table class='table table-bordered table-condensed'>";
                        echo "<tr line=" . __LINE__ . ">";
                        echo "<td colspan='" . (sizeof($itemLabels) + 1) . "'>";
                        echo "<span class='text-muted'>description note (dari step sebelumnya) </span><br>";
                        echo "<span class='' style='font-style:italic;font-family:Monaco, Menlo, Consolas, \"Courier New\", monospace;'>";

                        $val_result = "";
                        foreach ($descriptionAdditionalPreviews as $ii => $iiVal) {
                            if ($val_result == "") {
                                $val_result = $iiVal;
                            }
                            else {
                                $val_result .= "<br>" . $iiVal;
                            }
                        }
                        echo nl2br($val_result);


                        echo "</span><br>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                }


                if (isset($msgWarning2) && sizeof($msgWarning2)) {
                    $msgWarnings2 = $msgWarning2;
                    echo "<div class='alert alert-danger text-center font-size-1-5'>";
                    foreach ($msgWarnings2 as $msgSpec) {
                        echo $msgSpec['label'] . "<br>";
                    }
                    echo "</div class='alert alert-warning'>";
                }
                else {
                    $msgWarnings2 = array();
                }
            }

            echo "</div class='table-responsive'>";


            if (isset($items) && sizeof($items) > 0) {

                $new_beforeStepLabels = isset($beforeStepLabels) ? $beforeStepLabels : "";
                $new_beforeAllStepLabels = isset($beforeAllStepLabels) ? $beforeAllStepLabels : "";

                echo "<div>";
                echo "<button type='button' class='btn btn-default margin' data-dismiss='modal' onclick=\"enableShopCart();document.getElementById('result').src='$clearContentTarget';\"><span class='glyphicon glyphicon-chevron-left'></span> close </button>";
                // echo "<div class='bbtn-group'>";
                echo "<div class='btn-group pull-right'>";
                if (isset($approvalSpec['targetUrl']) != "" && $approvalSpec['targetUrl'] != "") {
                    echo "<button button type='button' class='btn btn-success margin' style='border:1px #008800 solid;color:#ffffff;' onclick=\"if(confirm('" . $approvalSpec['warning'] . "')==1){this.disabled=true;document.getElementById('f1').action='" . $approvalSpec['targetUrl'] . "';document.getElementById('f1').submit();top.open_holdon();}\"><span class='glyphicon glyphicon-ok'></span> " . $approvalSpec['label'] . "</button>";
                }
                else {
                    echo "&nbsp;";
                }


                // echo "</div>";

                echo "</div>"; // 2669

                if (isset($definitionButton) && sizeof($definitionButton) > 0) {

                    echo "<div class='row' style='margin-top: 100px;margin-bottom:-30px;font-size: larger;'>";
                    echo "<div class='panel-body'>";
                    echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                    if (isset($beforeStepWarning) && ($beforeStepWarning != NULL)) {
                        echo "<strong>$beforeStepWarning</strong>";
                        echo "<hr>";
                        echo "<br>";
                    }
                    foreach ($definitionButton as $lButton => $kButton) {
                        echo "<strong>$lButton</strong> : $kButton";
                        echo "<br>";
                    }

                    echo "</div class='col-md-12 text-center'>";
                    echo "</div class='panel-body'>";
                    echo "</div class='row'>";
                }


                echo "<div class='row' style='margin-top: 20px;'>";
                echo "<div class='panel-body'>";
                echo "<div class='col-md-12 text-center alert' style='border:1px #cccccc dotted;background:#e5e5e5;line-height:16px;'>";
                echo "<small>";
                echo $saveWarning;
                echo "</small>";
                echo "</div class='col-md-12 text-center'>";
                echo "</div class='panel-body'>";
                echo "</div class='row'>";
            }
            else {
                echo "<div class='row'>";
                echo "<div class='col-md-12 text-center'>";
                echo "<span class='text-danger'>cannot continue this entry to the next step</span><br>";
                echo "<a class='btn btn-primary' data-dismiss='modal'>okay, got it!</a>";
                echo "</div>";
                echo "</div class='row'>";
            }

            echo "</form>";
        }
        else {
            echo "belum ada item yang dipilih!<br>";
            echo "anda bisa memilih item dengan mengklik dan mengetikkan namanya di kotak kiri halaman.<br>";
            die();
        }
        echo "</div id='followupPreview' >";
        break;

    case "master_project":

        break;
}