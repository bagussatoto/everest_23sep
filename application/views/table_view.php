<!DOCTYPE html>
<html>
<head>
    <title>Data Table</title>

    <title>Data Table</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/colreorder/1.6.2/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <style>
        .table-wrapper {
            margin: 20px 0; /* Add spacing around the table */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            white-space: nowrap; /* Prevent text from wrapping */
            overflow: hidden; /* Hide overflowed content */
            text-overflow: ellipsis; /* Add ellipsis for overflowed content */
            max-width: 250px; /* Set a maximum width for the cells */
        }
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none;
        }
    </style>
</head>
<body>
<div id="query" style="display: none;"><?= $query ?></div>
<div class="table-wrapper">
    <table id="dataTable" class="display">
        <thead>
        <tr>
            <?php foreach ($kolom_config as $kolom => $config): ?>
                <th><?php echo $config['alias']; ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($datas as $row): ?>
            <tr>
                <?php foreach ($kolom_config as $kolom => $config): ?>
                    <td title="<?php echo isset($row[$kolom]) ? $row[$kolom] : ''; ?>">
                        <?php echo isset($row[$kolom]) ? $row[$kolom] : ''; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            colReorder: true, // Enable column reordering
            dom: 'Blfrtip', // Add buttons for column visibility
            stateSave:true, // Enable state saving
            buttons: [
                'colvis', // Column visibility button
                {
                    text: 'Show/Hide Query', // Custom button text
                    action: function () {
                        $('#query').toggle(); // Toggle the visibility of the query div
                    }
                },
                'excel' // Button to export to Excel
            ],
            pageLength: 15, // Default number of rows per page
            lengthMenu: [ // Options for rows per page
                [10,15,20, 25, 50, 100, -1], // Values
                [10,15,20, 25, 50, 100, "All"] // Labels
            ],
            lengthChange: true // Ensure the page length dropdown is enabled
        });

        // Add event listener for header click
        $('#dataTable thead th').on('click', function (event) {
            event.stopPropagation(); // Prevent interference with sorting
            const headerText = $(this).text(); // Get header text

            // Copy to clipboard
            navigator.clipboard.writeText(headerText).then(() => {
                showToast(`Header "${headerText}" copied to clipboard!`);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        });

        // Function to show toast notification
        function showToast(message) {
            const toast = $('<div class="toast"></div>').text(message);
            $('body').append(toast);
            toast.fadeIn(400).delay(2000).fadeOut(400, function () {
                $(this).remove();
            });
        }
    });
</script>
</body>
</html>