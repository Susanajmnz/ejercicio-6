<?php
// Inicia una sesión
session_start();

// Función para analizar la entrada del usuario
function parse_input($input) {
    $numbers = [];
    // Divide la entrada por comas
    $parts = explode(',', $input);

    // Recorre cada parte de la entrada
    foreach ($parts as $part) {
        // Verifica si la parte contiene un guión, indicando un rango
        if (strpos($part, '-') !== false) {
            list($start, $end) = explode('-', $part);
            // Verifica si ambos extremos del rango son numéricos y válidos
            if (is_numeric($start) && is_numeric($end) && $start <= $end) {
                // Añade el rango de números al array
                $numbers = array_merge($numbers, range($start, $end));
            }
        } else {
            // Si no es un rango, verifica si la parte es numérica y la añade al array
            if (is_numeric($part)) {
                $numbers[] = $part;
            }
        }
    }
    // Devuelve un array con números únicos
    return array_unique($numbers);
}

// Verifica si el método de la petición es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene la entrada del usuario
    $input = $_POST['input'];
    // Analiza la entrada del usuario
    $numbers = parse_input($input);

    // Verifica si el array de números no está vacío
    if (!empty($numbers)) {
        $_SESSION['tablas'] = $numbers;
    } else {
        // Si el formato es incorrecto, establece un mensaje de error en la sesión
        $_SESSION['mensaje'] = "Formato incorrecto. "
        . "Introduce números del 1 al 9 separados por comas o con guiones para representar rangos.";
        // Redirige al usuario a la página de inicio
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Tablas</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
    <h1>Tablas de Multiplicar</h1>
    <?php
    // Verifica si las tablas están establecidas en la sesión
    if (isset($_SESSION['tablas'])) {
        // Recorre cada número de las tablas
        foreach ($_SESSION['tablas'] as $num) {
            // Verifica si el número está entre 1 y 9
            if ($num >= 1 && $num <= 10) {
                echo "<h2>Tabla del $num</h2>";
                echo "<table>";
                // Genera la tabla de multiplicar para el número
                for ($i = 1; $i <= 10; $i++) {
                    $result = $num * $i;
                    echo "<tr><td>$num x $i</td><td>$result</td></tr>";
                }
                echo "</table>";
            }
        }
    } else {
        // Si no hay tablas para mostrar, muestra un mensaje
        echo "<p>No se encontraron tablas para mostrar.</p>";
    }
    ?>
    <a href="index.php">Volver</a>
</body>
</html>
