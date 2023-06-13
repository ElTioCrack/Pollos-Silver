<?php 
/**
 * Encripta una variable utilizando el algoritmo Argon2.
 *
 * @param string $data La variable a encriptar.
 * @param int $time_cost El costo de tiempo para el algoritmo Argon2.
 * @param int $memory_cost El costo de memoria para el algoritmo Argon2.
 * @param int $threads El número de hilos para el algoritmo Argon2.
 * @return string El valor encriptado de la variable.
 */
function encryptWithArgon2($data, $time_cost, $memory_cost, $threads) {
    // Genera el hash de la variable utilizando Argon2
    $hashedData = password_hash($data, PASSWORD_ARGON2I, [
        'time_cost' => $time_cost,
        'memory_cost' => $memory_cost,
        'threads' => $threads
    ]);

    return $hashedData;
}

// Ejemplo de uso:
$data = "3"; // Variable a encriptar
$time_cost = 8; // Costo de tiempo para Argon2
$memory_cost = 131072; // Costo de memoria para Argon2
$threads = 1; // Número de hilos para Argon2

// Encripta la variable utilizando Argon2
$hash = encryptWithArgon2($data, $time_cost, $memory_cost, $threads);

echo "Variable encriptada: " . $hash;

function verifyWithArgon2($input, $hash) {
    return password_verify($input, $hash);
}

$isValid = verifyWithArgon2($data, $hash);
if ($isValid) {
    echo "La entrada coincide con el hash encriptado.";
} else {
    echo "La entrada no coincide con el hash encriptado.";
}