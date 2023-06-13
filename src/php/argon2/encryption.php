<?php

function encryptWithArgon2($data)
{
    // Encriptar la contraseña utilizando Argon2
    $time_cost = 8; // Costo de tiempo para Argon2
    $memory_cost = 131072; // Costo de memoria para Argon2
    $threads = 1; // Número de hilos para Argon2

    // Genera el hash de la variable utilizando Argon2
    $hashedData = password_hash($data, PASSWORD_ARGON2I, [
        'time_cost' => $time_cost,
        'memory_cost' => $memory_cost,
        'threads' => $threads
    ]);

    return $hashedData;
}

function verifyWithArgon2(string $userInput, string $storedHash): bool
{
    return password_verify($userInput, $storedHash);
}
