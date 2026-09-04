<?php

$jsonFile = __DIR__ . '/excel_prices.json';
$excelData = json_decode(file_get_contents($jsonFile), true);

foreach ($excelData as $i => $row) {
    echo sprintf(
        "Row %2d | Sheet: %-5s | Model: %-15s | Type: %-25s | Detail: %-35s | Price: $%s\n",
        $i + 1,
        $row['sheet'],
        $row['model'],
        $row['type'],
        $row['detail'],
        number_format($row['price'], 2)
    );
}
