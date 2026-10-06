<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->exec("CREATE DATABASE IF NOT EXISTS sicar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE sicar;");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS departamento (
            dep_id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(255) NOT NULL,
            status TINYINT NOT NULL DEFAULT 1,
            `system` TINYINT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categoria (
            cat_id INT AUTO_INCREMENT PRIMARY KEY,
            dep_id INT NOT NULL,
            nombre VARCHAR(255) NOT NULL,
            status TINYINT NOT NULL DEFAULT 1,
            `system` TINYINT NOT NULL DEFAULT 0,
            FOREIGN KEY (dep_id) REFERENCES departamento(dep_id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS articulo (
            art_id INT AUTO_INCREMENT PRIMARY KEY,
            clave VARCHAR(100) NOT NULL UNIQUE,
            claveAlterna VARCHAR(100) NOT NULL DEFAULT '',
            descripcion VARCHAR(255) NOT NULL,
            servicio TINYINT NOT NULL DEFAULT 0,
            localizacion VARCHAR(255) NOT NULL DEFAULT '',
            invMin DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            invMax DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            factor DECIMAL(10,4) NOT NULL DEFAULT 1.0000,
            precioCompra DECIMAL(12,2) NOT NULL,
            preCompraProm DECIMAL(12,2) NOT NULL,
            margen1 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            margen2 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            margen3 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            margen4 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            precio1 DECIMAL(12,2) NOT NULL,
            precio2 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            precio3 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            precio4 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            mayoreo1 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            mayoreo2 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            mayoreo3 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            mayoreo4 DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            existencia DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            caracteristicas TEXT NOT NULL,
            cuentaPredial VARCHAR(255) NOT NULL DEFAULT '',
            status TINYINT NOT NULL DEFAULT 1,
            unidadCompra INT NOT NULL DEFAULT 1,
            unidadVenta INT NOT NULL DEFAULT 1,
            cat_id INT NOT NULL,
            imp_id INT NOT NULL DEFAULT 1,
            FOREIGN KEY (cat_id) REFERENCES categoria(cat_id)
        ) ENGINE=InnoDB;
    ");

    echo "SICAR database and tables created successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
