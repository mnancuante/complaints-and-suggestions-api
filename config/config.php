<?php
// aqui guardamos la configuración de la DB
return [
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'dbname' => $_ENV['DB_NAME'],
    ],
    'jwt' => [
        'secret_key' => $_ENV['JWT_SECRET'],
        'expiration' => $_ENV['JWT_EXPIRATION']
    ]
];
