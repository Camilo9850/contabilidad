<?php
// Este archivo te ayudará a verificar la estructura de la tabla sistema_usuarios
// y los usuarios existentes en tu base de datos

// Configuración de la base de datos desde el archivo .env
$host = '127.0.0.1';
$dbname = 'e-comerce'; // Basado en tu archivo .env
$username = 'root';
$password = ''; // Vacío según tu archivo .env

try {
    // Crear conexión
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>Conexión a la base de datos exitosa</h3>";
    
    // Obtener estructura de la tabla
    echo "<h3>Estructura de la tabla sistema_usuarios:</h3>";
    $stmt = $pdo->query("DESCRIBE sistema_usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>".$column['Field']."</td>";
        echo "<td>".$column['Type']."</td>";
        echo "<td>".$column['Null']."</td>";
        echo "<td>".$column['Key']."</td>";
        echo "<td>".$column['Default']."</td>";
        echo "<td>".$column['Extra']."</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Obtener usuarios existentes
    echo "<h3>Usuarios existentes en sistema_usuarios:</h3>";
    $stmt = $pdo->query("SELECT * FROM sistema_usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    if (count($usuarios) > 0) {
        // Imprimir encabezados
        $first = $usuarios[0];
        echo "<tr>";
        foreach ($first as $key => $value) {
            echo "<th>".$key."</th>";
        }
        echo "</tr>";
        
        // Imprimir filas
        foreach ($usuarios as $usuario) {
            echo "<tr>";
            foreach ($usuario as $value) {
                echo "<td>".htmlspecialchars($value)."</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td>No hay usuarios registrados</td></tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}