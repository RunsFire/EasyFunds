<?php
$data =json_decode(file_get_contents('php://input'),true);
header("Content-Type: text/csv");
header("Content-Disposition: attachement; filename=('export.csv')");

$output = fopen('php://output','w');
foreach($data as $row){
    fputcsv($output,$row,";");

}

fclose($output);