<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $dbs = $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
    echo "DATABASES:\n";
    print_r($dbs);

    if (in_array('sicar', $dbs)) {
        echo "\nTABLES IN sicar:\n";
        $pdo->exec('USE sicar');
        $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        print_r($tables);

        if (in_array('articulo', $tables)) {
            echo "\nCOLUMNS IN sicar.articulo:\n";
            $cols = $pdo->query('DESCRIBE articulo')->fetchAll(PDO::FETCH_ASSOC);
            print_r($cols);
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
