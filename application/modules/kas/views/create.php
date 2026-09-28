<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "hutangKeKonsumen_0":

        $var = "";
        $var .= "<div style='background-color: #ffffee'>";
        $var .= "<table class='table table-condensed table-striped table-bordered'>
            <thead>
            <tr>";
        foreach ($headers as $key => $header) {
            $label = isset($header['label']) ? $header['label'] : $key;
            $var .= "<th title='$key'>$label</th>";
        }
        // $var .= "<th title='extern_nama'>Komsumen</th>";
        foreach ($extern2_ids as $id => $nama):
            $nama_f = htmlspecialchars($nama);
            $var .= "<th>$nama_f ($id)</th>";
        endforeach;
        $var .= "<th>total</th>";
        $var .= "</tr>";
        $var .= "</thead>";


        $var .= "<tbody>";
        foreach ($rows as $row):

            $extern_id = $row['extern_id'];
            $var .= "<tr title='$extern_id'>";
            foreach ($headers as $key => $header) {
                $nilai = htmlspecialchars($row[$key]);
                $var .= "<td>$nilai</td>";
            }

            $sum_kanan = 0;
            foreach ($extern2_ids as $id => $nama):
                $nilai_asli = $row['kredit'][$id];
                $nili_kredit = isset($row['kredit'][$id]) ? number_format($row['kredit'][$id], 0) : '0.00';
                $var .= "<td style='text-align:right'>";
                $var .= $nili_kredit;
                $var .= "</td>";

                $sum_kanan += $nilai_asli;
                if (!isset($sum_bawah[$id])) {
                    $sum_bawah[$id] = 0;
                }
                $sum_bawah[$id] += $nilai_asli;
            endforeach;

            $sum_kanan_f = isset($sum_kanan) ? number_format($sum_kanan, 0) : '0.00';

            $var .= "<td style='text-align:right'>";
            $var .= $sum_kanan_f;
            $var .= "</td>";
            if (!isset($sum_bawah['total'])) {
                $sum_bawah['total'] = 0;
            }
            $sum_bawah['total'] += $sum_kanan;

            $var .= "</tr>";
        endforeach;
        $var .= "</tbody>";

        $colspanHeader = count($headers);
        $var .= "<foot>";
        $var .= "<tr>";
        $var .= "<td colspan='$colspanHeader'>Total</td>";
        foreach ($extern2_ids as $id => $nama):
            $nilai_bawah = $sum_bawah[$id];
            $nilai_bawah_f = isset($nilai_bawah) ? number_format($nilai_bawah, 0) : '0.00';
            $var .= "<td style='text-align:right'>$nilai_bawah_f</td>";
        endforeach;
        $nilai_bawah_total = $sum_bawah['total'];
        $nilai_bawah_total_f = isset($nilai_bawah_total) ? number_format($nilai_bawah_total, 0) : '0.00';

        $var .= "<td style='text-align:right'>$nilai_bawah_total_f</td>";
        $var .= "</tr>";
        $var .= "</foot>";


        $var .= "</table>
        </div>";

        echo $var;

        break;

    case "hutangKeKonsumen_1":
        $var = "";
        $var .= "<div style='background-color: #ffffee'>";

        // Add control panel
        $var .= "<div class='table-controls' style='margin-bottom: 10px; padding: 10px; background: #f5f5f5; border-radius: 5px;'>";
        $var .= "<strong>Table Options:</strong> ";

        // Column visibility controls
        $var .= "<span style='margin-left: 15px;'>Show Columns:</span>";
        $colIndex = count($headers);
        foreach ($extern2_ids as $id => $nama) {
            $nama_f = htmlspecialchars($nama);
            $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' checked> $nama_f</label>";
            $colIndex++;
        }
        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' checked> Total</label>";

        // Row limit controls
        $var .= "<span style='margin-left: 15px;'>Show:</span>";
        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='10'> Top 10</label>";
        $var .= "<label style='margin-left: 5px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='all' checked> All</label>";
        $var .= "</div>";

        $var .= "<table class='table table-condensed table-striped table-bordered' id='hutang-table'>
        <thead>
        <tr>";
        foreach ($headers as $key => $header) {
            $label = isset($header['label']) ? $header['label'] : $key;
            $var .= "<th title='$key'>$label</th>";
        }
        foreach ($extern2_ids as $id => $nama):
            $nama_f = htmlspecialchars($nama);
            $var .= "<th class='toggleable-column'>$nama_f ($id)</th>";
        endforeach;
        $var .= "<th class='toggleable-column'>total</th>";
        $var .= "</tr>";
        $var .= "</thead>";

        $var .= "<tbody>";
        foreach ($rows as $i => $row):
            $extern_id = $row['extern_id'];
            $var .= "<tr title='$extern_id'" . ($i >= 10 ? " class='extra-row'" : "") . ">";
            foreach ($headers as $key => $header) {
                $nilai = htmlspecialchars($row[$key]);
                $var .= "<td>$nilai</td>";
            }

            $sum_kanan = 0;
            foreach ($extern2_ids as $id => $nama):
                $nilai_asli = $row['kredit'][$id];
                $nili_kredit = isset($row['kredit'][$id]) ? number_format($row['kredit'][$id], 0) : '0.00';
                $var .= "<td style='text-align:right' class='toggleable-column'>";
                $var .= $nili_kredit;
                $var .= "</td>";

                $sum_kanan += $nilai_asli;
                if (!isset($sum_bawah[$id])) {
                    $sum_bawah[$id] = 0;
                }
                $sum_bawah[$id] += $nilai_asli;
            endforeach;

            $sum_kanan_f = isset($sum_kanan) ? number_format($sum_kanan, 0) : '0.00';

            $var .= "<td style='text-align:right' class='toggleable-column'>";
            $var .= $sum_kanan_f;
            $var .= "</td>";
            if (!isset($sum_bawah['total'])) {
                $sum_bawah['total'] = 0;
            }
            $sum_bawah['total'] += $sum_kanan;

            $var .= "</tr>";
        endforeach;
        $var .= "</tbody>";

        $colspanHeader = count($headers);
        $var .= "<tfoot>";
        $var .= "<tr>";
        $var .= "<td colspan='$colspanHeader'>Total</td>";
        foreach ($extern2_ids as $id => $nama):
            $nilai_bawah = $sum_bawah[$id];
            $nilai_bawah_f = isset($nilai_bawah) ? number_format($nilai_bawah, 0) : '0.00';
            $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_f</td>";
        endforeach;
        $nilai_bawah_total = $sum_bawah['total'];
        $nilai_bawah_total_f = isset($nilai_bawah_total) ? number_format($nilai_bawah_total, 0) : '0.00';

        $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_total_f</td>";
        $var .= "</tr>";
        $var .= "</tfoot>";

        $var .= "</table>
    </div>";

        // Add jQuery script
        $var .= "
    <script>
    $(document).ready(function() {
        // Initialize column visibility from localStorage
        var savedColumns = JSON.parse(localStorage.getItem('hutangTableColumns')) || {};
        $('.toggle-column').each(function() {
            var colIndex = $(this).data('column');
            if (savedColumns[colIndex] !== undefined) {
                $(this).prop('checked', savedColumns[colIndex]);
                toggleColumn(colIndex, savedColumns[colIndex]);
            }
        });
        
        // Initialize row limit from localStorage
        var savedRowLimit = localStorage.getItem('hutangTableRowLimit') || 'all';
        $('.row-limit[value=\"' + savedRowLimit + '\"]').prop('checked', true);
        toggleRows(savedRowLimit);
        
        // Column toggle event
        $('.toggle-column').change(function() {
            var colIndex = $(this).data('column');
            var isVisible = $(this).is(':checked');
            
            // Save to localStorage
            savedColumns[colIndex] = isVisible;
            localStorage.setItem('hutangTableColumns', JSON.stringify(savedColumns));
            
            toggleColumn(colIndex, isVisible);
        });
        
        // Row limit event
        $('.row-limit').change(function() {
            var limit = $(this).val();
            localStorage.setItem('hutangTableRowLimit', limit);
            toggleRows(limit);
        });
        
        function toggleColumn(colIndex, show) {
            if (show) {
                $('#hutang-table thead th:nth-child(' + (colIndex + 1) + ')').show();
                $('#hutang-table tbody td:nth-child(' + (colIndex + 1) + ')').show();
                $('#hutang-table tfoot td:nth-child(' + (colIndex + 1) + ')').show();
            } else {
                $('#hutang-table thead th:nth-child(' + (colIndex + 1) + ')').hide();
                $('#hutang-table tbody td:nth-child(' + (colIndex + 1) + ')').hide();
                $('#hutang-table tfoot td:nth-child(' + (colIndex + 1) + ')').hide();
            }
        }
        
        function toggleRows(limit) {
            if (limit === '10') {
                $('.extra-row').hide();
            } else {
                $('.extra-row').show();
            }
        }
    });
    </script>
    ";

        echo $var;
        break;

    case "hutangKeKonsumen_2":
        $var = "";
        $var .= "<div style='background-color: #ffffee'>";

        // Add control panel
        $var .= "<div class='table-controls' style='margin-bottom: 10px; padding: 10px; background: #f5f5f5; border-radius: 5px;'>";
        $var .= "<strong>Table Options:</strong> ";

        // Column visibility controls
        $var .= "<span style='margin-left: 15px;'>Show Columns:</span>";
        $colIndex = count($headers);
        foreach ($extern2_ids as $id => $nama) {
            $nama_f = htmlspecialchars($nama);
            $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' checked> $nama_f</label>";
            $colIndex++;
        }
        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' checked> Total</label>";

        // Row limit controls
        $var .= "<span style='margin-left: 15px;'>Show:</span>";
        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='10'> Top 10</label>";
        $var .= "<label style='margin-left: 5px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='all' checked> All</label>";

        // Value filter controls
        $var .= "<span style='margin-left: 15px;'>Filter:</span>";
        $var .= "<select id='filter-column' style='margin-left: 5px;'>";
        $var .= "<option value=''>-- Select Column --</option>";
        $colIndex = count($headers);
        foreach ($extern2_ids as $id => $nama) {
            $nama_f = htmlspecialchars($nama);
            $var .= "<option value='$colIndex'>$nama_f</option>";
            $colIndex++;
        }
        $var .= "<option value='$colIndex'>Total</option>";
        $var .= "</select>";
        $var .= "<button id='apply-filter' style='margin-left: 5px;'>Show >100</button>";
        $var .= "<button id='reset-filter' style='margin-left: 5px;'>Reset Filter</button>";
        $var .= "</div>";

        $var .= "<table class='table table-condensed table-striped table-bordered' id='hutang-table'>
        <thead>
        <tr>";
        foreach ($headers as $key => $header) {
            $label = isset($header['label']) ? $header['label'] : $key;
            $var .= "<th title='$key'>$label</th>";
        }
        foreach ($extern2_ids as $id => $nama):
            $nama_f = htmlspecialchars($nama);
            $var .= "<th class='toggleable-column'>$nama_f ($id)</th>";
        endforeach;
        $var .= "<th class='toggleable-column'>total</th>";
        $var .= "</tr>";
        $var .= "</thead>";

        $var .= "<tbody>";
        foreach ($rows as $i => $row):
            $extern_id = $row['extern_id'];
            $var .= "<tr title='$extern_id' data-row-index='$i'" . ($i >= 10 ? " class='extra-row'" : "") . ">";
            foreach ($headers as $key => $header) {
                $nilai = htmlspecialchars($row[$key]);
                $var .= "<td>$nilai</td>";
            }

            $sum_kanan = 0;
            foreach ($extern2_ids as $id => $nama):
                $nilai_asli = $row['kredit'][$id];
                $nili_kredit = isset($row['kredit'][$id]) ? number_format($row['kredit'][$id], 0) : '0.00';
                $var .= "<td style='text-align:right' class='toggleable-column' data-raw-value='$nilai_asli'>";
                $var .= $nili_kredit;
                $var .= "</td>";

                $sum_kanan += $nilai_asli;
                if (!isset($sum_bawah[$id])) {
                    $sum_bawah[$id] = 0;
                }
                $sum_bawah[$id] += $nilai_asli;
            endforeach;

            $sum_kanan_f = isset($sum_kanan) ? number_format($sum_kanan, 0) : '0.00';

            $var .= "<td style='text-align:right' class='toggleable-column' data-raw-value='$sum_kanan'>";
            $var .= $sum_kanan_f;
            $var .= "</td>";
            if (!isset($sum_bawah['total'])) {
                $sum_bawah['total'] = 0;
            }
            $sum_bawah['total'] += $sum_kanan;

            $var .= "</tr>";
        endforeach;
        $var .= "</tbody>";

        $colspanHeader = count($headers);
        $var .= "<tfoot>";
        $var .= "<tr>";
        $var .= "<td colspan='$colspanHeader'>Total</td>";
        foreach ($extern2_ids as $id => $nama):
            $nilai_bawah = $sum_bawah[$id];
            $nilai_bawah_f = isset($nilai_bawah) ? number_format($nilai_bawah, 0) : '0.00';
            $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_f</td>";
        endforeach;
        $nilai_bawah_total = $sum_bawah['total'];
        $nilai_bawah_total_f = isset($nilai_bawah_total) ? number_format($nilai_bawah_total, 0) : '0.00';

        $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_total_f</td>";
        $var .= "</tr>";
        $var .= "</tfoot>";

        $var .= "</table>
    </div>";

        // Add jQuery script
        $var .= "
    <script>
    $(document).ready(function() {
        // Initialize column visibility from localStorage
        var savedColumns = JSON.parse(localStorage.getItem('hutangTableColumns')) || {};
        $('.toggle-column').each(function() {
            var colIndex = $(this).data('column');
            if (savedColumns[colIndex] !== undefined) {
                $(this).prop('checked', savedColumns[colIndex]);
                toggleColumn(colIndex, savedColumns[colIndex]);
            }
        });
        
        // Initialize row limit from localStorage
        var savedRowLimit = localStorage.getItem('hutangTableRowLimit') || 'all';
        $('.row-limit[value=\"' + savedRowLimit + '\"]').prop('checked', true);
        toggleRows(savedRowLimit);
        
        // Initialize filter column from localStorage
        var savedFilterColumn = localStorage.getItem('hutangTableFilterColumn');
        if (savedFilterColumn) {
            $('#filter-column').val(savedFilterColumn);
        }
        
        // Column toggle event
        $('.toggle-column').change(function() {
            var colIndex = $(this).data('column');
            var isVisible = $(this).is(':checked');
            
            // Save to localStorage
            savedColumns[colIndex] = isVisible;
            localStorage.setItem('hutangTableColumns', JSON.stringify(savedColumns));
            
            toggleColumn(colIndex, isVisible);
        });
        
        // Row limit event
        $('.row-limit').change(function() {
            var limit = $(this).val();
            localStorage.setItem('hutangTableRowLimit', limit);
            toggleRows(limit);
        });
        
        // Apply filter button
        $('#apply-filter').click(function() {
            var columnIndex = $('#filter-column').val();
            if (!columnIndex) {
                alert('Please select a column first');
                return;
            }
            
            localStorage.setItem('hutangTableFilterColumn', columnIndex);
            applyValueFilter(columnIndex);
        });
        
        // Reset filter button
        $('#reset-filter').click(function() {
            $('#filter-column').val('');
            localStorage.removeItem('hutangTableFilterColumn');
            resetValueFilter();
        });
        
        function toggleColumn(colIndex, show) {
            if (show) {
                $('#hutang-table thead th:nth-child(' + (colIndex + 1) + ')').show();
                $('#hutang-table tbody td:nth-child(' + (colIndex + 1) + ')').show();
                $('#hutang-table tfoot td:nth-child(' + (colIndex + 1) + ')').show();
            } else {
                $('#hutang-table thead th:nth-child(' + (colIndex + 1) + ')').hide();
                $('#hutang-table tbody td:nth-child(' + (colIndex + 1) + ')').hide();
                $('#hutang-table tfoot td:nth-child(' + (colIndex + 1) + ')').hide();
            }
        }
        
        function toggleRows(limit) {
            if (limit === '10') {
                $('.extra-row').hide();
            } else {
                $('.extra-row').show();
            }
        }
        
        function applyValueFilter(columnIndex) {
            $('#hutang-table tbody tr').each(function() {
                var cell = $(this).find('td:nth-child(' + (parseInt(columnIndex) + 1 + ')');
                var rawValue = parseFloat(cell.attr('data-raw-value')) || 0;
                
                if (rawValue > 100) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        
        function resetValueFilter() {
            $('#hutang-table tbody tr').show();
            // Reapply row limit setting
            toggleRows($('.row-limit:checked').val());
        }
        
        // Apply saved filter on page load
        if (savedFilterColumn) {
            applyValueFilter(savedFilterColumn);
        }
    });
    </script>
    ";

        echo $var;
        break;

    /*ok jadi*/
    case "hutangKeKonsumen_3":
        $var = "";
        $var .= "<div style='background-color: #ffffee'>";

        // Add control panel
        $var .= "<div class='table-controls' style='margin-bottom: 10px; padding: 10px; background: #f5f5f5; border-radius: 5px;'>";
        $var .= "<strong>Table Options:</strong> ";

        // Column visibility controls
        $var .= "<span style='margin-left: 15px;'>Show Columns:</span>";
        $colIndex = count($headers);
        foreach ($extern2_ids as $id => $nama) {
            $nama_f = htmlspecialchars($nama);
            $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' checked> $nama_f</label>";
            $colIndex++;
        }
        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' checked> Total</label>";

        // Row limit controls
        $var .= "<span style='margin-left: 15px;'>Show:</span>";
        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='10'> Top 10</label>";
        $var .= "<label style='margin-left: 5px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='all' checked> All</label>";

        // Value filter controls
        $var .= "<span style='margin-left: 15px;'>Filter:</span>";
        $var .= "<select id='filter-column' style='margin-left: 5px;'>";
        $var .= "<option value=''>-- Select Column --</option>";
        $colIndex = count($headers) + 1; // Start from first data column
        foreach ($extern2_ids as $id => $nama) {
            $nama_f = htmlspecialchars($nama);
            $var .= "<option value='$colIndex'>$nama_f</option>";
            $colIndex++;
        }
        $var .= "<option value='$colIndex'>Total</option>";
        $var .= "</select>";
        $var .= "<button id='apply-filter' style='margin-left: 5px;'>Show >100</button>";
        $var .= "<button id='reset-filter' style='margin-left: 5px;'>Reset Filter</button>";
        $var .= "</div>";

        $var .= "<table class='table table-condensed table-striped table-bordered' id='hutang-table'>
        <thead>
        <tr>";
        foreach ($headers as $key => $header) {
            $label = isset($header['label']) ? $header['label'] : $key;
            $var .= "<th title='$key'>$label</th>";
        }
        foreach ($extern2_ids as $id => $nama):
            $nama_f = htmlspecialchars($nama);
            $var .= "<th class='toggleable-column'>$nama_f ($id)</th>";
        endforeach;
        $var .= "<th class='toggleable-column'>total</th>";
        $var .= "</tr>";
        $var .= "</thead>";

        $var .= "<tbody>";
        foreach ($rows as $i => $row):
            $extern_id = $row['extern_id'];
            $var .= "<tr title='$extern_id' data-row-index='$i'" . ($i >= 10 ? " class='extra-row'" : "") . ">";
            foreach ($headers as $key => $header) {
                $nilai = htmlspecialchars($row[$key]);
                $var .= "<td>$nilai</td>";
            }

            $sum_kanan = 0;
            foreach ($extern2_ids as $id => $nama):
                $nilai_asli = $row['kredit'][$id];
                $nili_kredit = isset($row['kredit'][$id]) ? number_format($row['kredit'][$id], 0) : '0.00';
                $var .= "<td style='text-align:right' class='toggleable-column' data-raw-value='" . htmlspecialchars($nilai_asli) . "'>";
                $var .= $nili_kredit;
                $var .= "</td>";

                $sum_kanan += $nilai_asli;
                if (!isset($sum_bawah[$id])) {
                    $sum_bawah[$id] = 0;
                }
                $sum_bawah[$id] += $nilai_asli;
            endforeach;

            $sum_kanan_f = isset($sum_kanan) ? number_format($sum_kanan, 0) : '0.00';

            $var .= "<td style='text-align:right' class='toggleable-column' data-raw-value='" . htmlspecialchars($sum_kanan) . "'>";
            $var .= $sum_kanan_f;
            $var .= "</td>";
            if (!isset($sum_bawah['total'])) {
                $sum_bawah['total'] = 0;
            }
            $sum_bawah['total'] += $sum_kanan;

            $var .= "</tr>";
        endforeach;
        $var .= "</tbody>";

        $colspanHeader = count($headers);
        $var .= "<tfoot>";
        $var .= "<tr>";
        $var .= "<td colspan='$colspanHeader'>Total</td>";
        foreach ($extern2_ids as $id => $nama):
            $nilai_bawah = $sum_bawah[$id];
            $nilai_bawah_f = isset($nilai_bawah) ? number_format($nilai_bawah, 0) : '0.00';
            $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_f</td>";
        endforeach;
        $nilai_bawah_total = $sum_bawah['total'];
        $nilai_bawah_total_f = isset($nilai_bawah_total) ? number_format($nilai_bawah_total, 0) : '0.00';

        $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_total_f</td>";
        $var .= "</tr>";
        $var .= "</tfoot>";

        $var .= "</table>
    </div>";

        // Add jQuery script
        $var .= "
    <script>
    $(document).ready(function() {
        // Initialize column visibility from localStorage
        var savedColumns = JSON.parse(localStorage.getItem('hutangTableColumns')) || {};
        $('.toggle-column').each(function() {
            var colIndex = $(this).data('column');
            if (savedColumns[colIndex] !== undefined) {
                $(this).prop('checked', savedColumns[colIndex]);
                toggleColumn(colIndex, savedColumns[colIndex]);
            }
        });
        
        // Initialize row limit from localStorage
        var savedRowLimit = localStorage.getItem('hutangTableRowLimit') || 'all';
        $('.row-limit[value=\"' + savedRowLimit + '\"]').prop('checked', true);
        toggleRows(savedRowLimit);
        
        // Initialize filter column from localStorage
        var savedFilterColumn = localStorage.getItem('hutangTableFilterColumn');
        if (savedFilterColumn) {
            $('#filter-column').val(savedFilterColumn);
            // Apply filter after a short delay to ensure DOM is ready
            setTimeout(function() { applyValueFilter(savedFilterColumn); }, 100);
        }
        
        // Column toggle event
        $('.toggle-column').change(function() {
            var colIndex = $(this).data('column');
            var isVisible = $(this).is(':checked');
            
            // Save to localStorage
            savedColumns[colIndex] = isVisible;
            localStorage.setItem('hutangTableColumns', JSON.stringify(savedColumns));
            
            toggleColumn(colIndex, isVisible);
        });
        
        // Row limit event
        $('.row-limit').change(function() {
            var limit = $(this).val();
            localStorage.setItem('hutangTableRowLimit', limit);
            toggleRows(limit);
        });
        
        // Apply filter button
        $('#apply-filter').click(function() {
            var columnIndex = $('#filter-column').val();
            if (!columnIndex) {
                alert('Please select a column first');
                return;
            }
            
            localStorage.setItem('hutangTableFilterColumn', columnIndex);
            applyValueFilter(columnIndex);
        });
        
        // Reset filter button
        $('#reset-filter').click(function() {
            $('#filter-column').val('');
            localStorage.removeItem('hutangTableFilterColumn');
            resetValueFilter();
        });
        
        function toggleColumn(colIndex, show) {
            var colSelector = colIndex + 1; // nth-child is 1-based
            if (show) {
                $('#hutang-table thead th:nth-child(' + colSelector + ')').show();
                $('#hutang-table tbody td:nth-child(' + colSelector + ')').show();
                $('#hutang-table tfoot td:nth-child(' + colSelector + ')').show();
            } else {
                $('#hutang-table thead th:nth-child(' + colSelector + ')').hide();
                $('#hutang-table tbody td:nth-child(' + colSelector + ')').hide();
                $('#hutang-table tfoot td:nth-child(' + colSelector + ')').hide();
            }
        }
        
        function toggleRows(limit) {
            if (limit === '10') {
                $('.extra-row').hide();
            } else {
                $('.extra-row').show();
            }
        }
        
        function applyValueFilter(columnIndex) {
            $('#hutang-table tbody tr').each(function() {
                var cell = $(this).find('td:nth-child(' + columnIndex + ')');
                var rawValue = parseFloat(cell.attr('data-raw-value')) || 0;
                
                if (rawValue > 100) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        
        function resetValueFilter() {
            $('#hutang-table tbody tr').show();
            // Reapply row limit setting
            toggleRows($('.row-limit:checked').val());
        }
    });
    </script>
    ";

        echo $var;
        break;

    /*paling  ok*/
    case "hutangKeKonsumen_4":
        $defaultVisibleColumns = ['total']; // kolom-kolom eksternal yang tampil secara default
        $defaultRowLimit = 10; // ubah nilai ini untuk menentukan batas tampilan baris default

        $var = "";
        $var .= "<div style='background-color: #ffffee'>";

        // Add control panel
        $var .= "<div class='table-controls' style='padding: 10px; background: #f5f5f5; border-radius: 5px;'>";
        $var .= "<strong>Hutang ke Konsumen</strong> ";

        // Column visibility controls
        // $var .= "<span style='margin-left: 15px;'>Show Columns:</span>";
        // $colIndex = count($headers) + 1; // +1 karena ada kolom No
        // foreach ($extern2_ids as $id => $nama) {
        //     $nama_f = htmlspecialchars($nama);
        //     $checked = in_array($id, $defaultVisibleColumns) ? "checked" : "";
        //     $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' $checked> $nama_f</label>";
        //     $colIndex++;
        // }
        // $checked = in_array('total', $defaultVisibleColumns) ? "checked" : "";
        // $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' $checked> Total</label>";

        // Row limit controls
        $var .= "<span style='margin-left: 15px;'>Tampilkan:</span>";
        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='$defaultRowLimit'> $defaultRowLimit terbaru</label>";
        $var .= "<label style='margin-left: 5px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='all' checked> Semua</label>";

        // Value filter controls
        // $var .= "<span style='margin-left: 15px;'>Filter:</span>";
        // $var .= "<select id='filter-column' style='margin-left: 5px;'>";
        // $var .= "<option value=''>-- Select Column --</option>";
        // $colIndex = count($headers) + 2; // karena kolom No + header biasa
        // foreach ($extern2_ids as $id => $nama) {
        //     $nama_f = htmlspecialchars($nama);
        //     $var .= "<option value='$colIndex'>$nama_f</option>";
        //     $colIndex++;
        // }
        // $var .= "<option value='$colIndex'>Total</option>";
        // $var .= "</select>";
        // $var .= "<button id='apply-filter' style='margin-left: 5px;'>Show >100</button>";
        // $var .= "<button id='reset-filter' style='margin-left: 5px;'>Reset Filter</button>";
        $var .= "</div>";
// onclick="document.getElementById('result').src='https://demo.mayagrahakencana.com/everest_24jun/kas/_processPihak/select/9467/MdlCustomer_and_pre?id=2952'"
        $var .= "<table class='table table-condensed table-striped table-bordered no-margin' id='hutang-table'>
    <thead>
    <tr>";
        $var .= "<th>No</th>";
        foreach ($headers as $key => $header) {
            $label = isset($header['label']) ? $header['label'] : $key;
            $var .= "<th title='$key'>$label</th>";
        }
        foreach ($extern2_ids as $id => $nama) {
            $nama_f = htmlspecialchars($nama);
            $var .= "<th class='toggleable-column defhide'>$nama_f ($id)</th>";
        }
        $var .= "<th class='toggleable-column'>Total</th>";
        $var .= "</tr>";
        $var .= "</thead>";

        $var .= "<tbody>";
        $nom = 0;
        foreach ($rows as $i => $row):
            $nom++;

            $classdef = $nom > $defaultRowLimit ? "defhide" : "";
            $extern_id = $row['extern_id'];
            $var .= "<tr title='$extern_id' data-row-index='$i'" . ($i >= $defaultRowLimit ? " class='extra-row $classdef'" : "") . ">";
            $var .= "<td style='text-align:right'>$nom</td>"; // No

            foreach ($headers as $key => $header) {
                $nilai = htmlspecialchars($row[$key]);

                if (isset($header['link'])) {
                    $link = $header['link'] . "?id=$extern_id";
                    $nilai_l = "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='$link';clearShopingCart();\">$nilai</a>";
                }
                else {
                    $nilai_l = $nilai;
                }

                $var .= "<td>$nilai_l</td>";
            }

            $sum_kanan = 0;
            foreach ($extern2_ids as $id => $nama):
                $nilai_asli = $row['kredit'][$id];
                $nili_kredit = isset($row['kredit'][$id]) ? number_format($row['kredit'][$id], 0) : '0.00';
                $var .= "<td style='text-align:right' class='toggleable-column defhide' data-raw-value='" . htmlspecialchars($nilai_asli) . "'>";
                $var .= $nili_kredit;
                $var .= "</td>";

                $sum_kanan += $nilai_asli;
                if (!isset($sum_bawah[$id])) {
                    $sum_bawah[$id] = 0;
                }
                $sum_bawah[$id] += $nilai_asli;
            endforeach;

            $sum_kanan_f = isset($sum_kanan) ? number_format($sum_kanan, 0) : '0.00';
            $var .= "<td style='text-align:right' class='toggleable-column' data-raw-value='" . htmlspecialchars($sum_kanan) . "'>";
            $var .= $sum_kanan_f;
            $var .= "</td>";

            if (!isset($sum_bawah['total'])) {
                $sum_bawah['total'] = 0;
            }
            $sum_bawah['total'] += $sum_kanan;

            $var .= "</tr>";
        endforeach;
        $var .= "</tbody>";

        $colspanHeader = count($headers) + 1; // +1 untuk kolom No
        $var .= "<tfoot><tr class='$classdef'>";
        $var .= "<td colspan='$colspanHeader'>Total</td>";
        foreach ($extern2_ids as $id => $nama) {
            $nilai_bawah = $sum_bawah[$id];
            $nilai_bawah_f = isset($nilai_bawah) ? number_format($nilai_bawah, 0) : '0.00';
            $var .= "<td style='text-align:right' class='toggleable-column defhide'>$nilai_bawah_f</td>";
        }
        $nilai_bawah_total = $sum_bawah['total'];
        $nilai_bawah_total_f = isset($nilai_bawah_total) ? number_format($nilai_bawah_total, 0) : '0.00';
        $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_total_f</td>";
        $var .= "</tr></tfoot>";
        $var .= "</table></div>";

        // $linkShoppingCart = base_url() . "kas/_shoppingCart/reset/$jenisTr";
        // SCRIPT
        $var .= "
    <script>
    $(document).ready(function() {
        $('.defhide').addClass('hidden');
        var savedColumns = JSON.parse(localStorage.getItem('hutangTableColumns')) || {};
        $('.toggle-column').each(function() {
            var colIndex = $(this).data('column');
            if (savedColumns[colIndex] !== undefined) {
                $(this).prop('checked', savedColumns[colIndex]);
                toggleColumn(colIndex, savedColumns[colIndex]);
            }
        });

        var savedRowLimit = localStorage.getItem('hutangTableRowLimit') || 'all';
        $('.row-limit[value=\"' + savedRowLimit + '\"]').prop('checked', true);
        toggleRows(savedRowLimit);

        var savedFilterColumn = localStorage.getItem('hutangTableFilterColumn');
        if (savedFilterColumn) {
            $('#filter-column').val(savedFilterColumn);
            setTimeout(function() { applyValueFilter(savedFilterColumn); }, 100);
        }

        $('.toggle-column').change(function() {
            var colIndex = $(this).data('column');
            var isVisible = $(this).is(':checked');
            savedColumns[colIndex] = isVisible;
            localStorage.setItem('hutangTableColumns', JSON.stringify(savedColumns));
            toggleColumn(colIndex, isVisible);
        });

        $('.row-limit').change(function() {
            var limit = $(this).val();
            console.log('limit:', limit);
            localStorage.setItem('hutangTableRowLimit', limit);
            toggleRows(limit);
        });

        $('#apply-filter').click(function() {
            var columnIndex = $('#filter-column').val();
            if (!columnIndex) {
                alert('Please select a column first');
                return;
            }
            localStorage.setItem('hutangTableFilterColumn', columnIndex);
            applyValueFilter(columnIndex);
        });

        $('#reset-filter').click(function() {
            $('#filter-column').val('');
            localStorage.removeItem('hutangTableFilterColumn');
            resetValueFilter();
        });

        function toggleColumn(colIndex, show) {
            var colSelector = colIndex + 1;
            $('#hutang-table thead th:nth-child(' + colSelector + ')').toggle(show);
            $('#hutang-table tbody td:nth-child(' + colSelector + ')').toggle(show);
            $('#hutang-table tfoot td:nth-child(' + colSelector + ')').toggle(show);
        }

        function toggleRows_old(limit) {
            if (limit === '10' || limit === '$defaultRowLimit') {
                $('.extra-row').hide();
            } else {
                $('.extra-row').show();
            }
        }
        
        function toggleRows(limit) {
            if (limit === '$defaultRowLimit') {
                $('.defhide').addClass('hidden');
            } else {
                $('.extra-row').removeClass('hidden');
            }
        }

        function applyValueFilter(columnIndex) {
            $('#hutang-table tbody tr').each(function() {
                var cell = $(this).find('td:nth-child(' + columnIndex + ')');
                var rawValue = parseFloat(cell.attr('data-raw-value')) || 0;
                if (rawValue > 100) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        function resetValueFilter() {
            $('#hutang-table tbody tr').show();
            toggleRows($('.row-limit:checked').val());
        }
        
        
    });
    
    function clearShopingCart() {
          $('#result').load('$linkShoppingCart');
        }
        
    </script>";

        echo $var;
        break;

    case "hutangKeKonsumen":
        $defaultVisibleColumns = ['total']; // kolom-kolom eksternal yang tampil secara default
        $defaultRowLimit = 10; // ubah nilai ini untuk menentukan batas tampilan baris default

        $var = "";
        $var .= "<div class='box box-solid box-info' style='margin-top: 5px;'>";

        // Add control panel
        $var .= "<div class='table-controls box-header'>";
        $var .= "<h3 class='box-title'>Hutang ke Konsumen</h3> ";

        $var .= "<div class='pull-right box-tools'>";
        $var .= "<button type=\"button\" class=\"btn btn-info btn-sm pull-right\" data-widget=\"collapse\" data-toggle=\"tooltip\" title=\"\" style=\"margin-right: 5px;\" data-original-title=\"Collapse\">
                  <i class=\"fa fa-minus\"></i></button>";
        $var .= "</div>";

        // Column visibility controls
        // $var .= "<span style='margin-left: 15px;'>Show Columns:</span>";
        // $colIndex = count($headers) + 1; // +1 karena ada kolom No
        // foreach ($extern2_ids as $id => $nama) {
        //     $nama_f = htmlspecialchars($nama);
        //     $checked = in_array($id, $defaultVisibleColumns) ? "checked" : "";
        //     $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' $checked> $nama_f</label>";
        //     $colIndex++;
        // }
        // $checked = in_array('total', $defaultVisibleColumns) ? "checked" : "";
        // $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' $checked> Total</label>";

        // Row limit controls
        // $var .= "<span style='margin-left: 15px;'>Tampilkan:</span>";
        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='$defaultRowLimit'> $defaultRowLimit terbaru</label>";
        $var .= "<label style='margin-left: 5px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='all' checked> Semua</label>";

        // Value filter controls
        // $var .= "<span style='margin-left: 15px;'>Filter:</span>";
        // $var .= "<select id='filter-column' style='margin-left: 5px;'>";
        // $var .= "<option value=''>-- Select Column --</option>";
        // $colIndex = count($headers) + 2; // karena kolom No + header biasa
        // foreach ($extern2_ids as $id => $nama) {
        //     $nama_f = htmlspecialchars($nama);
        //     $var .= "<option value='$colIndex'>$nama_f</option>";
        //     $colIndex++;
        // }
        // $var .= "<option value='$colIndex'>Total</option>";
        // $var .= "</select>";
        // $var .= "<button id='apply-filter' style='margin-left: 5px;'>Show >100</button>";
        // $var .= "<button id='reset-filter' style='margin-left: 5px;'>Reset Filter</button>";
        $var .= "</div>";

        $var .= "<div class='box-body'>";
        $var .= "<table class='table table-condensed table-striped table-bordered no-margin' id='hutang-table'>
    <thead>
    <tr class='bg-primary'>";
        $var .= "<th>No</th>";
        foreach ($headers as $key => $header) {
            $label = isset($header['label']) ? $header['label'] : $key;
            $var .= "<th title='$key'>$label</th>";
        }
        foreach ($extern2_ids as $id => $nama) {
            $nama_f = htmlspecialchars($nama);
            $var .= "<th class='toggleable-column defhide'>$nama_f ($id)</th>";
        }
        $var .= "<th class='toggleable-column'>Total</th>";
        $var .= "</tr>";
        $var .= "</thead>";

        $var .= "<tbody>";
        $nom = 0;
        foreach ($rows as $i => $row):
            $nom++;

            $classdef = $nom > $defaultRowLimit ? "defhide" : "";
            $extern_id = $row['extern_id'];
            $var .= "<tr title='$extern_id' data-row-index='$i'" . ($i >= $defaultRowLimit ? " class='extra-row $classdef'" : "") . ">";
            $var .= "<td style='text-align:right'>$nom</td>"; // No

            foreach ($headers as $key => $header) {
                $nilai = htmlspecialchars($row[$key]);

                if (isset($header['link'])) {
                    $link = $header['link'] . "?id=$extern_id";
                    $nilai_l = "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='$link';clearShopingCart();\">$nilai</a>";
                }
                else {
                    $nilai_l = $nilai;
                }

                $var .= "<td>$nilai_l</td>";
            }

            $sum_kanan = 0;
            foreach ($extern2_ids as $id => $nama):
                $nilai_asli = $row['kredit'][$id];
                $nili_kredit = isset($row['kredit'][$id]) ? number_format($row['kredit'][$id], 0) : '0.00';
                $var .= "<td style='text-align:right' class='toggleable-column defhide' data-raw-value='" . htmlspecialchars($nilai_asli) . "'>";
                $var .= $nili_kredit;
                $var .= "</td>";

                $sum_kanan += $nilai_asli;
                if (!isset($sum_bawah[$id])) {
                    $sum_bawah[$id] = 0;
                }
                $sum_bawah[$id] += $nilai_asli;
            endforeach;

            $sum_kanan_f = isset($sum_kanan) ? number_format($sum_kanan, 0) : '0.00';
            $var .= "<td style='text-align:right' class='toggleable-column' data-raw-value='" . htmlspecialchars($sum_kanan) . "'>";
            $var .= $sum_kanan_f;
            $var .= "</td>";

            if (!isset($sum_bawah['total'])) {
                $sum_bawah['total'] = 0;
            }
            $sum_bawah['total'] += $sum_kanan;

            $var .= "</tr>";
        endforeach;
        $var .= "</tbody>";

        $colspanHeader = count($headers) + 1; // +1 untuk kolom No
        $var .= "<tfoot><tr class='$classdef'>";
        $var .= "<td colspan='$colspanHeader'>Total</td>";
        foreach ($extern2_ids as $id => $nama) {
            $nilai_bawah = $sum_bawah[$id];
            $nilai_bawah_f = isset($nilai_bawah) ? number_format($nilai_bawah, 0) : '0.00';
            $var .= "<td style='text-align:right' class='toggleable-column defhide'>$nilai_bawah_f</td>";
        }
        $nilai_bawah_total = $sum_bawah['total'];
        $nilai_bawah_total_f = isset($nilai_bawah_total) ? number_format($nilai_bawah_total, 0) : '0.00';
        $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_total_f</td>";
        $var .= "</tr></tfoot>";
        $var .= "</table>";
        $var .= "</div>";

        $var .= "</div>";

        // $linkShoppingCart = base_url() . "kas/_shoppingCart/reset/$jenisTr";
        // SCRIPT
        $var .= "
        <script>
    $(document).ready(function() {
        $('.defhide').addClass('hidden');
        var savedColumns = JSON.parse(localStorage.getItem('hutangTableColumns')) || {};
        $('.toggle-column').each(function() {
            var colIndex = $(this).data('column');
            if (savedColumns[colIndex] !== undefined) {
                $(this).prop('checked', savedColumns[colIndex]);
                toggleColumn(colIndex, savedColumns[colIndex]);
            }
        });

        var savedRowLimit = localStorage.getItem('hutangTableRowLimit') || '$defaultRowLimit';
        $('.row-limit[value=\"' + savedRowLimit + '\"]').prop('checked', true);
        toggleRows(savedRowLimit);

        var savedFilterColumn = localStorage.getItem('hutangTableFilterColumn');
        if (savedFilterColumn) {
            $('#filter-column').val(savedFilterColumn);
            setTimeout(function() { applyValueFilter(savedFilterColumn); }, 100);
        }

        $('.toggle-column').change(function() {
            var colIndex = $(this).data('column');
            var isVisible = $(this).is(':checked');
            savedColumns[colIndex] = isVisible;
            localStorage.setItem('hutangTableColumns', JSON.stringify(savedColumns));
            toggleColumn(colIndex, isVisible);
        });

        $('.row-limit').change(function() {
            var limit = $(this).val();
            console.log('limit:', limit);
            localStorage.setItem('hutangTableRowLimit', limit);
            toggleRows(limit);
        });

        $('#apply-filter').click(function() {
            var columnIndex = $('#filter-column').val();
            if (!columnIndex) {
                alert('Please select a column first');
                return;
            }
            localStorage.setItem('hutangTableFilterColumn', columnIndex);
            applyValueFilter(columnIndex);
        });

        $('#reset-filter').click(function() {
            $('#filter-column').val('');
            localStorage.removeItem('hutangTableFilterColumn');
            resetValueFilter();
        });

        function toggleColumn(colIndex, show) {
            var colSelector = colIndex + 1;
            $('#hutang-table thead th:nth-child(' + colSelector + ')').toggle(show);
            $('#hutang-table tbody td:nth-child(' + colSelector + ')').toggle(show);
            $('#hutang-table tfoot td:nth-child(' + colSelector + ')').toggle(show);
        }

        function toggleRows_old(limit) {
            if (limit === '10' || limit === '$defaultRowLimit') {
                $('.extra-row').hide();
            } else {
                $('.extra-row').show();
            }
        }
        
        function toggleRows(limit) {
            if (limit === '$defaultRowLimit') {
                $('.defhide').addClass('hidden');
            } else {
                $('.extra-row').removeClass('hidden');
            }
        }

        function applyValueFilter(columnIndex) {
            $('#hutang-table tbody tr').each(function() {
                var cell = $(this).find('td:nth-child(' + columnIndex + ')');
                var rawValue = parseFloat(cell.attr('data-raw-value')) || 0;
                if (rawValue > 100) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        function resetValueFilter() {
            $('#hutang-table tbody tr').show();
            toggleRows($('.row-limit:checked').val());
        }
        
        
    });
    
    function clearShopingCart() {
          $('#result').load('$linkShoppingCart');
        }
        
    </script>";

        echo $var;
        break;

    case "langsung_simple":
        // cekHere(__LINE__);
        $strHead = "";
        $strHead .= "<tr class='text-uppercase'>";
        $strHead .= "<td>no</td>";
        foreach ($arrHeaders as $kolom => $arrHeader) {
            $hLabel = isset($arrHeader['label']) ? $arrHeader['label'] : $kolom;
            $strHead .= "<th title='$kolom'>$hLabel</th>";
        }
        $strHead .= "</tr>";

        $strBody = "";
        $no = 0;
        $modul_path = isset($modul_path) ? $modul_path : base_url() . "penjualan/";
        // cekHere("$modul_path");
        $jenistr = isset($jenisTr) ? $jenisTr : "582";
        $strGet = $_SERVER['QUERY_STRING'];
        $pengenal_kolom = "";
        $pengenal_link = "";
        $pengenal_masterid = "";
        // cekHijau(count($master_data));
        foreach ($master_data as $master_datum) {
            $no++;
            $strBody .= "<tr >";
            $strBody .= "<td>$no</td>";

            $autoTr = array();
            $jml_autoTr = 0;
            foreach ($arrHeaders as $kolom => $attrs) {
                // $nilai = $master_datum[$kolom];
                // $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : (is_numeric($master_datum[$kolom]) ? 0 : "-");
                $nilai = isset($master_datum[$kolom]) ? $master_datum[$kolom] : "";

                if (isset($attrs['data_order'])) {
                    $nilai_order = isset($master_datum[$attrs['data_order']]) ? $master_datum[$attrs['data_order']] : "";
                    $data_order = "daya-order='$nilai_order'";
                }
                else {

                    $data_order = "";
                }


                $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                $attr = str_replace("{pengenal}", $kolom . $data_id, $attr);
                $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                $nilai_f = isset($attrs['format']) ? ($nilai != null ? $attrs['format']($format_key, $nilai, $jenistr, $modul_path) : $nilai) : $nilai;
                $str_logic = "";
                if (isset($attrs['logics'])) {
                    $ltexs = isset($attrs['logics']['text']) ? $attrs['logics']['text'] : "";
                    $lnilai = $attrs['logics']['nilai']; // tidak berguna ni, coba cari cari yg lain

                    $str_logic = $nilai < 0 ? "<br><div class='meta'>$ltexs</div>" : '';
                }

                if (isset($attrs['links'])) {
                    $heading_key = isset($attrs['links']['heading']) ? $attrs['links']['heading'] : "";
                    $heading_mdl = isset($master_datum[$heading_key]) ? urlencode($master_datum[$heading_key]) : $nilai;
                    $link_title = isset($attrs['links']['title']) ? $attrs['links']['title'] : "";
                    $reqKey = isset($attrs['links']['key']) ? $attrs['links']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "none";
                    $linking = isset($attrs['links']['target']) ? $attrs['links']['target'] . "?$strGet" . "&$reqKey=$reqValue&ky=$reqKey" : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkModal = modalDialogBtn("$heading_mdl", $linkDetile);
                    $nilai_link = isset($attrs['links']['target']) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>" : $nilai_f;
                }
                elseif (isset($attrs['collapsible'])) {
                    $layout_in = isset($attrs['collapsible']['layout']) ? $attrs['collapsible']['layout'] : "";
                    $layout_str = isset($attrs['collapsible']['layout']) ? "&layout=$layout_in" : "";
                    $reqKey = isset($attrs['collapsible']['key']) ? $attrs['collapsible']['key'] : "";
                    $reqValue = isset($master_datum[$reqKey]) ? $master_datum[$reqKey] : "";
                    $key_get_tambahan = "";
                    if ($reqKey != false) {
                        $key_get_tambahan = "&$reqKey=$reqValue&ky=$reqKey";
                    }
                    $linking = isset($attrs['collapsible']['target']) ? $attrs['collapsible']['target'] . "?$strGet" . $key_get_tambahan . $layout_str : "";
                    $linkDetile = base_url() . $linking . "";
                    $linkDetile = "https://san.mayagrahakencana.com/" . $linking . "";
                    $pengenal_kolom = $kolom;
                    $pengenal_link = $linkDetile;
                    $pengenal_masterid = "master" . $reqValue;

                    $nilai_link = "<a href='JavaScript:void(0);' $attr title='testing'>$nilai_f</a>";

                    $jml_autoTr++;
                    $autoTr[$kolom] = $kolom;
                }
                else {
                    // $linking = isset($attrs['link']) ? $attrs['link'] . "/" : "";
                    // $linkDetile = base_url() . $linking . "";
                    // $linkModal = modalDialogBtn("$nilai", $linkDetile);
                    $nilai_link = $nilai_f;
                }

                // if(isset($attrs['collapsible'])){
                //
                //     $nilai_link = "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='$link_title'>$nilai_f</a>";
                // }
                // else{
                //     $nilai_link = $nilai_f;
                // }

                $strBody .= "<td $data_order title2='$pengenal_link' title3='$pengenal_masterid' $attr>$nilai_link $str_logic</td>";

                if (isset($attrs['summary'])) {
                    if (!isset($totals[$kolom])) {
                        $totals[$kolom] = 0;
                    }
                    $totals[$kolom] += $nilai;
                }


            }
            $strBody .= "</tr>";

        }

        // cekBiru("$jml_autoTr");
        // arrPrintHijau($autoTr);
        $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        /* ---------------------------------------------------------------------------------------
         * generator scriptuntuk masuk ke js
         * ---------------------------------------------------------------------------------------*/
        $auto_tr = "";
        foreach ($autoTr as $item_kolom) {
            $auto_tr .= " $('#$tbl_id tbody').on('click', \"td.dt-nama-$item_kolom$tbl_id\", function () {
                         var tr = $(this).closest('tr');
                         var title2 = $(this).attr('title2');
                         var title3 = $(this).attr('title3');
                         var row = datareview$tbl_id.row(tr);
                         if(row.child.isShown()) {
                             row.child.hide();
                             tr.removeClass('shown');
                         }
                         else{
                             row.child(showChildProduk(title2,title3)).show();
                             loaderData(title3,title2);
                             tr.addClass('shown');
                         }
                     }); \n";
        }
        // ---------------------------------------------------------------------------------------
        $kolom_tanpa_format = array(
            "fulldate"
        );
        $strFoot = "";
        $strFoot .= "<tr class='bg-danger font-size-1-2'>";
        $strFoot .= "<th></th>";
        foreach ($arrHeaders as $kolom => $attrs) {
            $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
            $fNilai_f = isset($attrs['format']) && (!array($format_key, $kolom_tanpa_format)) ? $attrs['format']($format_key, $fNilai) : $fNilai;
            // $label = $attrs['label'];
            $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : (isset($attrs['attr']) ? $attrs['attr'] : "");

            $strFoot .= "<th $attr>";
            $strFoot .= $fNilai_f;
            $strFoot .= "</th>";
        }

        // arrPrintHijau($summariLabels);
        // arrPrintKuning($summariNilais);
        // arrPrint($summariSubjectLabels);
        $sum_btn = "";
        $sum_atas_btn = "";
        $sum_atas = "";
        if (isset($summariLabels)) {
            $sum_atas_btn .= "Berdasarkan: ";
            $sum_atas .= "<div class=\'row\'>";
            foreach ($summariNilais as $kei_1 => $sumValues) {
                $kei_1_label = $summariSubjectLabels[$kei_1];

                $sum_btn .= "<button type='button' class='btn btn-info text-uppercase' title='$kei_1' onclick=\"$('#$kei_1').fadeToggle();\">$kei_1_label</button>";

                $sum_atas .= "<div id='$kei_1' class='panel panel-default' style='display: none; float: left;margin-right: 5px;margin-top: 3px;'>";
                $sum_atas .= "<table class='table table-borderer table-striped table-hover-color-red'>";
                // $sum_atas .= "<tr class='text-uppercase bg-grey-2'><th>$kei_1_label</th><th>akumulasi transaksi</th>  </tr>";
                // cekKuning("$kei_1");
                //  arrPrintPink($sumValues);
                /* -------------------
                 * header
                 * -------------------*/
                $sum_atas .= "<tr class='text-uppercase bg-grey-2'>";
                if (isset($summariLabels[$kei_1]['header'])) {
                    foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {
                        $header_label = isset($headerParams['label']) ? $headerParams['label'] : $headerKey;
                        $sum_atas .= "<th>$header_label</th>";
                    }
                }
                else {
                    $sum_atas .= "<th colspan='2'>$kei_1_label</th>";
                }
                $sum_atas .= "</tr>";

                /* -------------------
                 * body
                 * -------------------*/
                $sumValueBawah = 0;
                foreach ($sumValues as $kei_2 => $sumValue) {
                    $sum_atas .= "<tr class='text-uppercase'>";
                    if (is_array($sumValue)) {
                        // arrPrint($sumValue);
                        foreach ($sumValue as $itemkey => $itemSumm) {
                            // cekKuning("$itemkey");
                            // arrPrint($itemSumm);
                            // $sumValue_f = is_numeric($itemSumm) ? number_format($itemSumm) : $itemSumm;
                            // $sum_atas .= "<td>$sumValue_f</td>";


                        }

                        //     ------------------------------

                        foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {

                            // $header_label = isset($headerParams['label']) ? $headerParams['label'] : $headerKey;

                            $itemSumm = $sumValue[$headerKey];
                            $sumValue_f = is_numeric($itemSumm) ? number_format($itemSumm) : $itemSumm;
                            $sumAttr = isset($headerParams['attr']) ? $headerParams['attr'] : "";

                            $sum_atas .= "<td $sumAttr>$sumValue_f</td>";

                            /* ------ -------------
                                ngesumm unutk footer
                            -----------------------*/
                            if (isset($headerParams['summary']) && ($headerParams['summary'] == true)) {
                                if (!isset($sumValueBawahs[$kei_1][$headerKey])) {
                                    $sumValueBawahs[$kei_1][$headerKey] = 0;
                                }
                                $sumValueBawahs[$kei_1][$headerKey] += $sumValue[$headerKey];
                            }

                        }
                    }
                    else {

                        $kei_2_label = $summariLabels[$kei_1][$kei_2];
                        $sumValue_f = number_format($sumValue);
                        // $sum_atas .= "<tr class='text-uppercase'>";
                        $sum_atas .= "<td>$kei_2_label</td>";
                        $sum_atas .= "<td class='text-right'>$sumValue_f</td>";
                        // $sum_atas .= "</tr>";

                        $sumValueBawah += $sumValue;
                    }
                    $sum_atas .= "</tr>";
                }

                /* ----------------------------
                 * footer
                 * ------------------------------*/
                $sum_atas .= "<tr class='text-uppercase bg-grey-2'>";
                if (isset($summariLabels[$kei_1]['header'])) {
                    $num = 0;
                    foreach ($summariLabels[$kei_1]['header'] as $headerKey => $headerParams) {
                        $num++;

                        $nilai_bawah = isset($sumValueBawahs[$kei_1][$headerKey]) ? $sumValueBawahs[$kei_1][$headerKey] : '-';
                        $nilai_bawah_f = $num == 1 ? "Total" : number_format($nilai_bawah);
                        $sum_atas .= "<th title='$headerKey'>$nilai_bawah_f</th>";
                    }
                }
                else {
                    $sumValueBawah_f = number_format($sumValueBawah);
                    $sum_atas .= "<th>total</th><th class='text-right'>$sumValueBawah_f</th>";
                }
                $sum_atas .= "</tr>";

                // $sumValueBawah_f = number_format($sumValueBawah);
                // $sum_atas .= "<tr class='text-uppercase bg-grey-2'><th>total</th><th class='text-right'>$sumValueBawah_f</th></tr>";


                $sum_atas .= "</table>";
                $sum_atas .= "</div>";
            }
            $sum_atas .= "</div>";

            $sum_atas_btn .= "<div class='btn-group'>";
            $sum_atas_btn .= $sum_btn;
            $sum_atas_btn .= "</div>";
        }

        $getQuery = http_build_query($_GET);
        // arrPrintHijau($getQuery);
        // cekHere();
        $link_data = MODUL_PATH . "Kas/cekRekening?$getQuery";
        $strTblPre = "";
        if (isset($preloader)) {
            $getbid = isset($_GET['xt']) ? $_GET['xt'] : "";
            $strTblPre .= "<div style='margin-bottom: 10px;overfloww: hidden;'>";
            foreach ($preloader as $item) {
                $bid = $item->id;
                $bnama = $item->nama;
                $btn_warna = $getbid == $bid ? "btn-danger" : "btn-primary";

                $strTblPre .= "<div class='btn-group'>";
                $strTblPre .= "<button type='button' class='btn $btn_warna btn-flatt' onclick=\"$('#sum_null').load('$link_data&xt=$bid&xta=0');open_holdon();\">$bnama</button> ";
                $strTblPre .= "<button type='button' class='btn $btn_warna btn-flatt dropdown-toggle' data-toggle='dropdown' aria-expanded='false'><span class='caret'></span><span class='sr-only'></span></button> ";

                /*------------rekening------------*/
                $banakan = $bankDataInduk[$bid];
                $strTblPre .= "<ul class='dropdown-menu border-cek' role='menu'>";
                if (count($banakan)) {
                    foreach ($banakan as $item => $rekening) {
                        $strTblPre .= "<li><a href='javascript:void(0);' onclick=\"$('#sum_null').load('$link_data&xt=$bid&xta=$item');open_holdon();\">$rekening </a></li>";
                    }
                }
                else {
                    $strTblPre .= "<li><a href='#'>tidak data rekening</a></li>";
                }

                $strTblPre .= "</ul>";

                $strTblPre .= "</div> ";
            }
            // cekHere("$getbid");
            // $btn_warna_all = $getbid == "morlin monrom0" ? "btn-danger" : "btn-primary";
            $btn_warna_all = $getbid == "0" ? "btn-danger" : "btn-primary";
            $strTblPre .= "<button type='button' class='btn $btn_warna_all btn-flatt' onclick=\"$('#sum_null').load('$link_data&xt=0&xta=0');open_holdon();\">Semua</button> ";
            $strTblPre .= "</div>";
        }

        // $tbl_id = isset($data_id) ? $data_id : "data_ok1";
        $strTbl = "";
        $strTbl .= "<style type='text/css'>
            .bg-grey-2 {
                background-color: #EFEBEF !important;
            }
        </style>";
        // matiHere(__LINE__);

        $strTbl .= "<div style='margin-bottom: 10px;overflow: hidden;'>";
        $strTbl .= "<div id='summary_btn_$data_id'>$sum_atas_btn</div>";
        $strTbl .= "<div id='summary_datas_$data_id'>$sum_atas</div>";
        $strTbl .= "<div id='summary_atas_$data_id'></div>";
        $strTbl .= "</div>";
        // -------------------------------------------------------------------------------------------------
        $strTbl .= "<div class='table-responsive tblid_$tbl_id'>";
        $strTbl .= "<table class='table table-condensade table-striped table-hover-color-red' id='$tbl_id'>";
        // $strTbl .= "<caption class='hidden'>testing</caption>";
        $strTbl .= "<thead>";
        $strTbl .= $strHead;
        $strTbl .= "</thead>";
        $strTbl .= "<tbody>";
        $strTbl .= $strBody;
        $strTbl .= "</tbody>";
        $strTbl .= "<tfoot>";
        $strTbl .= $strFoot;
        $strTbl .= "</tfoot>";
        $strTbl .= "</table>";
        $strTbl .= "</div>";

        $modalSize = isset($modal_size) ? "$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl')" : "";
        $autoTr_json = json_encode($autoTr);
        $strTbl .= "<script>
                $modalSize
                               
                $(document).ready( delay_v2( function(){
                      // Setup - add a text input to each footer cell
                    $('table#$tbl_id thead th').each( function () {
                        var title = $(this).text();
                        var title_str =  title.replace(' ', '_');
                        // var nilai =  $('#'+title_str).val(data.title_str);
                        
                        var nilai ='';
                        
                        $(this).append( '<br> <input id=\"'+title_str+'\" class=\"filter btn-block\" type=\"text\" style=\"widthh: 50px;\" placeholder=\"Search\" value=\"'+nilai+'\"/>' );
                    });
                    
                    var datareview$tbl_id = $('table#$tbl_id').DataTable({
                                    initComplete: function () {
                                        // Apply the search
                                        this.api().columns().every( function () {
                                            var that = this;
                                        
                                            $( 'input', this.header() ).on( 'keyup change clear', function () {
                                                if ( that.search() !== this.value ) {
                                                    that
                                                        .search( this.value )
                                                        .draw();
                                                }
                                            });
                                            
                                            $('input', this.header()).on('click', function(e) {
                                                e.stopPropagation();
                                            });                                                                                        
                                                                                        
                                        });
                                        
                                        close_holdon();
                                                                                                                                       
                                            },
                                    stateLoadCallback: function(settings) {
                                            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                                        },
                                        
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: true,
                                    processing: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
                                            ],
                                    columnDefs: [
                                        {
                                            searchable: false,
                                            orderable: false,
                                            targets: 0
                                        }
                                    ],
                                    footerCallback: function ( row, data, start, end, display ) {
                                        var api = this.api(), data;

                                        // Remove the formatting to get integer data for summation
                                        var intVal = function ( i ) {
                                            return typeof i === 'string' ?
                                                i.replace(/[$,]/g, '')*1 :
                                                typeof i === 'number' ?
                                                    i : 0;
                                        };

                                        var arrayFooter = $('tfoot>tr>th');
                                        var dpageTotal = [];
                                        var jml_kolom = (arrayFooter.length) - 1;
                                        jQuery.each(arrayFooter, function(i,d){
                                            var id_n_index = parseFloat(i);
                                            // console.log(id_n_index);
                                            dpageTotal[id_n_index] = 0;
                                            jQuery.each( $(api.column(id_n_index, { page: 'current'}).data() ), function(ii = '', obj = ''){
                                                var pos = obj.indexOf('<');
                                                if(pos!==-1){
                                                    dpageTotal[id_n_index] += intVal( $(obj).html() );
                                                }
                                                else{
                                                    dpageTotal[id_n_index] += intVal( obj );
                                                }
                                            });
                                            // console.log(dpageTotal[id_n_index]);
                                        //
                                            if( !isNaN(dpageTotal[id_n_index]) && id_n_index > 1){
                                                $( api.column(id_n_index).footer() ).html(
                                                    \" <div class='text-right text-primary text-bold'> \"+addCommas(dpageTotal[id_n_index].toFixed(2))+\" </div> \"
                                                );
                                        //        
                                        //         if((id_n_index > 4) && (id_n_index < jml_kolom)){                                                
                                        //             if ($('#ph_'+id_n_index).length === 0) {
                                        //            
                                        //                 $( api.column(id_n_index).header() ).append(
                                        //                     \" <div class='text-center text-primary' id='ph_\"+id_n_index+\"'>**</div> \"
                                        //                 );
                                        //             }                                                                                                                                          
                                        //            
                                        //             $(\"#ph_\"+id_n_index).html(
                                        //                 \"<b>\"+addCommas(dpageTotal[id_n_index].toFixed(2))+\"</b>\"
                                        //             );
                                        //         }
                                            }
                                             else {
                                                $( api.column(id_n_index).footer() ).html(
                                                    \"<div class='text-right text-primary text-bold'>---</div>\"
                                                );
                                            }
                                        });
                                    }
                    });
                    
                    // datareview$tbl_id.on('order.dt search.dt', function () {
                    //     let i = 1;
                    //     datareview$tbl_id.cells(null, 0, {
                    //         search: 'applied', order: 'applied'
                    //         }).every(function (cell) {
                    //             this.data(i++);
                    //         });
                    // }).draw();
        
                     // -----------------------------------------------------                     
                     $auto_tr            
                    //  ----------------------------------------------------------
                                    }, 500));

                   </script>";
        $strTbl .= "<script>
                $('.table-responsive.tblid_$tbl_id').floatingScroll();
                $('.table-responsive.tblid_$tbl_id').scroll(
                    delay_v2(function () {
                        $('table#$tbl_id').DataTable().fixedHeader.adjust();
                    }, 200)
                );
                
               
                // ------------------------------------------------------
                function showChildProduk(d,m) {
                      // var rand = Math.floor(Math.random() * 10000);
                      // var rand = Math.floor(Date.now() / 1000);

                      var str_id = m;
                      var table = \"<div style='margin-left:25px;background-color: bisque;' id='\"+str_id+\"'>loading data ..... .....</div>\";

                      return table;                    
                }
                
                function loaderData(id,isi) {
                  // console.log(isi);
                  // console.log('uye');

                    $('#'+id).load(isi);
                }
                // ------------------------------------------------------
                
                </script>";


        if (isset($summary_on_top)) {
            // $penjualan_total = isset($totals["transaksi_nilai"]) ? $totals["transaksi_nilai"] : 0;
            // $penjualan_total = number_format($penjualan_total);

            $sum_bawah_data = $summariNilais;
            $jml_baris_data = count($master_data);
            $sum_bawah_data = $summary_on_top;
            $sum_atas = "";
            $strTbl .= "<style>
                .info-box-content {
                    margin-left: 60px;
                }
                .info-box {
                    min-height: 60px;
                    background-color: #00ef4c12;
                }
                .info-box-icon {
                    height: 60px;
                    width: 60px;
                    line-height: 60px;
                }
            </style>";
            $sum_atas .= "<div class=\'row\'>***";

            foreach ($sum_bawah_data as $kei => $sum_bawah_datum) {

                $label = isset($sum_bawah_datum['label']) ? $sum_bawah_datum['label'] : $kei;
                $icon_fa = isset($sum_bawah_datum['icon_fa']) ? "fa fa-" . $sum_bawah_datum['icon_fa'] : "";
                $icon_bg = isset($sum_bawah_datum['icon_bg']) ? $sum_bawah_datum['icon_bg'] : "bg-aqua";
                $col_lebar = isset($sum_bawah_datum['col_lebar']) ? $sum_bawah_datum['col_lebar'] : "col-md-4 col-xl-3 col-xxl-2";

                $nilai_key = isset($sum_bawah_datum['nilai']) ? $sum_bawah_datum['nilai'] : 0;
                if (strstr($nilai_key, ".")) {
                    $nilai_0 = isset($sum_bawah_datum['nilai']) ? str_replace(".", "", $sum_bawah_datum['nilai']) : 0;
                    $nilai = $$nilai_0;
                }
                else {
                    // arrPrintKuning($totals);
                    // cekHere("$nilai_key");
                    $nilai_0 = isset($totals[$nilai_key]) ? $totals[$nilai_key] : 0;
                    $nilai = number_format($nilai_0);
                }
                $nilai_tpl = isset($sum_bawah_datum['nilai_tpl']) ? str_replace("{nilai}", $nilai, $sum_bawah_datum['nilai_tpl']) : "";


                $sum_atas .= "<div class=\'$col_lebar \'>";
                $sum_atas .= "<div class=\'info-box\'>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-gear-outline \'></i></span>";
                // $sum_atas .= "<span class=\'info-box-icon bg-aqua \'><i class=\'ion ion-ios-list-outline \'></i></span>";
                $sum_atas .= "<span class=\'info-box-icon $icon_bg \'><i class=\'$icon_fa \'></i></span>";

                $sum_atas .= "<div class=\'info-box-content\'>";
                $sum_atas .= "<span class=\'info-box-text\'>$label</span>";
                $sum_atas .= "<span class=\'info-box-number\'>$nilai_tpl</span>";
                $sum_atas .= "</div>";

                $sum_atas .= "</div>";

                $sum_atas .= "</div>";
            }
            // $sum_atas .= $p->layout_box_info($sum_bawah_data);
            $sum_atas .= "</div>";

            $strTbl .= "<script>
                $('#summary_atas_$data_id').append('$sum_atas');
            </script>";
        }
        // $content = "";
        // $content .= $strTbl;
        $url_now = current_url();
        $params = $_SERVER['QUERY_STRING'];
        $thislink = $url_now . "?" . $params;
        // cekKuning();
        // cekBiru($thislink);
        $reloader = "";
        if (isset($loader_div)) {
            $reloader = "<a href='JavaScript:;' onclick=\"$('#$loader_div').load('$thislink')\"><i class='fa fa-refresh'></i></a>";
        }
        $p = New Layout();
        if (isset($sum_satu)) {
            $str = "";
            $str .= "<div id='sum_satu_'></div>";
            $str .= "<script>$('#sum_satu_').load('$sum_satu');</script>";

            echo $str;
        }
        if (isset($sum_dua)) {
            $str = "";
            $str .= "<div id='sum_dua_'></div>";
            $str .= "<script>$('#sum_dua_').load('$sum_dua');</script>";
            echo $str;
        }
        /* ---------------------------------------------------------------------------------------------
         * penampil di browser PERCUSTOMER
         * ---------------------------------------------------------------------------------------------*/
        if (isset($sum_tiga)) {
            $str = "";
            $str .= "<div id='sum_tiga'></div>";
            $str .= "<script>$('#sum_tiga').load('$sum_tiga');</script>";

            echo $str;
        }
        $color_bar = isset($color_bar) ? $color_bar : "box-danger";
        $p->setLayoutBoxCss("$color_bar");
        $btn_colaps = "<button class='btn btn-sm btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>";
        $p->setLayoutBoxHeading("$reloader $title", $btn_colaps);
        $p->setLayoutBoxBody(true);

        echo $strTblPre;

        if (isset($layout) && $layout == false) {
            // echo __LINE__;
            echo "<div style='padding: 10px;'>$strSummary</div>";
            echo "<div style='padding: 10px;'>$strTbl</div>";
        }
        else {
            echo $p->layout_box($strTbl);
        }

        break;

    case "hutangTanpaIdentitas":
        $defaultVisibleColumns = ['total']; // kolom-kolom eksternal yang tampil secara default
        $defaultRowLimit = 10; // ubah nilai ini untuk menentukan batas tampilan baris default

        $var = "";
        $var .= "<div class='box box-solid box-info' style='margin-top: 5px;'>";

        // Add control panel
        $var .= "<div class='table-controls box-header'>";
        $var .= "<h3 class='box-title'>$label</h3> ";

        $var .= "<div class='pull-right box-tools'>";
        $var .= "<button type=\"button\" class=\"btn btn-info btn-sm pull-right\" data-widget=\"collapse\" data-toggle=\"tooltip\" title=\"\" style=\"margin-right: 5px;\" data-original-title=\"Collapse\">
                  <i class=\"fa fa-minus\"></i></button>";
        $var .= "</div>";

        // Column visibility controls
        // $var .= "<span style='margin-left: 15px;'>Show Columns:</span>";
        // $colIndex = count($headers) + 1; // +1 karena ada kolom No
        // foreach ($extern2_ids as $id => $nama) {
        //     $nama_f = htmlspecialchars($nama);
        //     $checked = in_array($id, $defaultVisibleColumns) ? "checked" : "";
        //     $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' $checked> $nama_f</label>";
        //     $colIndex++;
        // }
        // $checked = in_array('total', $defaultVisibleColumns) ? "checked" : "";
        // $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='checkbox' class='toggle-column' data-column='$colIndex' $checked> Total</label>";

        // Row limit controls
        // $var .= "<span style='margin-left: 15px;'>Tampilkan:</span>";
//        $var .= "<label style='margin-left: 10px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='$defaultRowLimit'> $defaultRowLimit terbaru</label>";
//        $var .= "<label style='margin-left: 5px; cursor: pointer;'><input type='radio' name='row-limit' class='row-limit' value='all' checked> Semua</label>";

        // Value filter controls
        // $var .= "<span style='margin-left: 15px;'>Filter:</span>";
        // $var .= "<select id='filter-column' style='margin-left: 5px;'>";
        // $var .= "<option value=''>-- Select Column --</option>";
        // $colIndex = count($headers) + 2; // karena kolom No + header biasa
        // foreach ($extern2_ids as $id => $nama) {
        //     $nama_f = htmlspecialchars($nama);
        //     $var .= "<option value='$colIndex'>$nama_f</option>";
        //     $colIndex++;
        // }
        // $var .= "<option value='$colIndex'>Total</option>";
        // $var .= "</select>";
        // $var .= "<button id='apply-filter' style='margin-left: 5px;'>Show >100</button>";
        // $var .= "<button id='reset-filter' style='margin-left: 5px;'>Reset Filter</button>";
        $var .= "</div>";

        $var .= "<div class='box-body'>";
        $var .= "<table class='table table-condensed table-striped table-bordered no-margin' id='hutang-table'>
    <thead>
    <tr class='bg-primary'>";
        $var .= "<th>No</th>";
        foreach ($headers as $key => $header) {
            $label = isset($header['label']) ? $header['label'] : $key;
            $var .= "<th title='$key'>$label</th>";
        }
        foreach ($extern2_ids as $id => $nama) {
            $nama_f = htmlspecialchars($nama);
            $var .= "<th class='toggleable-column defhide'>$nama_f ($id)</th>";
        }
//        $var .= "<th class='toggleable-column'>Total</th>";
        $var .= "</tr>";
        $var .= "</thead>";

        $var .= "<tbody>";
        $nom = 0;
        foreach ($rows as $i => $row):
            $nom++;

            $classdef = $nom > $defaultRowLimit ? "defhide" : "";
            $extern_id = $row['extern_id'];
            $var .= "<tr title='$extern_id' data-row-index='$i'" . ($i >= $defaultRowLimit ? " class='extra-row $classdef'" : "") . ">";
            $var .= "<td style='text-align:right'>$nom</td>"; // No

            foreach ($headers as $key => $header) {
                $nilai = htmlspecialchars($row[$key]);

                if (isset($header['link'])) {
                    $link = $header['link'] . "?id=$extern_id";
                    $nilai_l = "<a href='javascript:void(0);' onclick=\"document.getElementById('result').src='$link';clearShopingCart();\">$nilai</a>";
                }
                else {
                    $nilai_l = is_numeric($row[$key]) ? number_format($row[$key]) : $nilai;
                }

                $var .= "<td>$nilai_l</td>";
            }

            $sum_kanan = 0;
            foreach ($extern2_ids as $id => $nama):
                $nilai_asli = $row['kredit'][$id];
                $nili_kredit = isset($row['kredit'][$id]) ? number_format($row['kredit'][$id], 0) : '0.00';
                $var .= "<td style='text-align:right' class='toggleable-column defhide' data-raw-value='" . htmlspecialchars($nilai_asli) . "'>";
                $var .= $nili_kredit;
                $var .= "</td>";

                $sum_kanan += $nilai_asli;
                if (!isset($sum_bawah[$id])) {
                    $sum_bawah[$id] = 0;
                }
                $sum_bawah[$id] += $nilai_asli;
            endforeach;

            $sum_kanan_f = isset($sum_kanan) ? number_format($sum_kanan, 0) : '0.00';
//            $var .= "<td style='text-align:right' class='toggleable-column' data-raw-value='" . htmlspecialchars($sum_kanan) . "'>";
//            $var .= $sum_kanan_f;
//            $var .= "</td>";

            if (!isset($sum_bawah['total'])) {
                $sum_bawah['total'] = 0;
            }
            $sum_bawah['total'] += $sum_kanan;

            $var .= "</tr>";
        endforeach;
        $var .= "</tbody>";

        $colspanHeader = count($headers) + 1; // +1 untuk kolom No

//        $var .= "<tfoot><tr class='$classdef'>";
//        $var .= "<td colspan='$colspanHeader'>Total</td>";
//        foreach ($extern2_ids as $id => $nama) {
//            $nilai_bawah = $sum_bawah[$id];
//            $nilai_bawah_f = isset($nilai_bawah) ? number_format($nilai_bawah, 0) : '0.00';
//            $var .= "<td style='text-align:right' class='toggleable-column defhide'>$nilai_bawah_f</td>";
//        }
//        $nilai_bawah_total = $sum_bawah['total'];
//        $nilai_bawah_total_f = isset($nilai_bawah_total) ? number_format($nilai_bawah_total, 0) : '0.00';
//        $var .= "<td style='text-align:right' class='toggleable-column'>$nilai_bawah_total_f</td>";
//        $var .= "</tr></tfoot>";

        $var .= "</table>";
        $var .= "</div>";

        $var .= "</div>";

        // $linkShoppingCart = base_url() . "kas/_shoppingCart/reset/$jenisTr";
        // SCRIPT
        $var .= "
        <script>
    $(document).ready(function() {
        $('.defhide').addClass('hidden');
        var savedColumns = JSON.parse(localStorage.getItem('hutangTableColumns')) || {};
        $('.toggle-column').each(function() {
            var colIndex = $(this).data('column');
            if (savedColumns[colIndex] !== undefined) {
                $(this).prop('checked', savedColumns[colIndex]);
                toggleColumn(colIndex, savedColumns[colIndex]);
            }
        });

        var savedRowLimit = localStorage.getItem('hutangTableRowLimit') || '$defaultRowLimit';
        $('.row-limit[value=\"' + savedRowLimit + '\"]').prop('checked', true);
        toggleRows(savedRowLimit);

        var savedFilterColumn = localStorage.getItem('hutangTableFilterColumn');
        if (savedFilterColumn) {
            $('#filter-column').val(savedFilterColumn);
            setTimeout(function() { applyValueFilter(savedFilterColumn); }, 100);
        }

        $('.toggle-column').change(function() {
            var colIndex = $(this).data('column');
            var isVisible = $(this).is(':checked');
            savedColumns[colIndex] = isVisible;
            localStorage.setItem('hutangTableColumns', JSON.stringify(savedColumns));
            toggleColumn(colIndex, isVisible);
        });

        $('.row-limit').change(function() {
            var limit = $(this).val();
            console.log('limit:', limit);
            localStorage.setItem('hutangTableRowLimit', limit);
            toggleRows(limit);
        });

        $('#apply-filter').click(function() {
            var columnIndex = $('#filter-column').val();
            if (!columnIndex) {
                alert('Please select a column first');
                return;
            }
            localStorage.setItem('hutangTableFilterColumn', columnIndex);
            applyValueFilter(columnIndex);
        });

        $('#reset-filter').click(function() {
            $('#filter-column').val('');
            localStorage.removeItem('hutangTableFilterColumn');
            resetValueFilter();
        });

        function toggleColumn(colIndex, show) {
            var colSelector = colIndex + 1;
            $('#hutang-table thead th:nth-child(' + colSelector + ')').toggle(show);
            $('#hutang-table tbody td:nth-child(' + colSelector + ')').toggle(show);
            $('#hutang-table tfoot td:nth-child(' + colSelector + ')').toggle(show);
        }

        function toggleRows_old(limit) {
            if (limit === '10' || limit === '$defaultRowLimit') {
                $('.extra-row').hide();
            } else {
                $('.extra-row').show();
            }
        }
        
        function toggleRows(limit) {
            if (limit === '$defaultRowLimit') {
                $('.defhide').addClass('hidden');
            } else {
                $('.extra-row').removeClass('hidden');
            }
        }

        function applyValueFilter(columnIndex) {
            $('#hutang-table tbody tr').each(function() {
                var cell = $(this).find('td:nth-child(' + columnIndex + ')');
                var rawValue = parseFloat(cell.attr('data-raw-value')) || 0;
                if (rawValue > 100) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        function resetValueFilter() {
            $('#hutang-table tbody tr').show();
            toggleRows($('.row-limit:checked').val());
        }
        
        
    });
    
    function clearShopingCart() {
          $('#result').load('$linkShoppingCart');
        }
        
    </script>";

        echo $var;
        break;
}

