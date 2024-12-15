<?php
$content_type = "text/csv";
$filename = "export.csv";
if (isset($_GET["type"]) && $_GET["type"] == "xls") {
    $content_type = "application/vnd.ms-excel";
    $filename = "export.xls";
}
$data = json_decode(file_get_contents('php://input'), true);
header("Content-Type:" . $content_type);
header("Content-Disposition: attachement; filename=('" . $filename . "')");

$output = fopen('php://output', 'w');
foreach ($data as $row) {
    fputcsv($output, $row, ";");
}

fclose($output);
