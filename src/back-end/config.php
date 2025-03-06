<?php
declare(strict_types=1);

return [
    'db' => [
        'host'   => 'db', // Docker servis za MySQL
        'dbname' => 'mkspark_db',
        'user'   => 'root',
        'pass'   => 'rootpassword',
    ],
    'mail' => [
        'host'     => 'milosu.com',
        'port'     => 465,
        'username' => 'test@milosu.com',
        'password' => 'Mkspark@123',
    ]
];
