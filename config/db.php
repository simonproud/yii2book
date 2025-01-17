<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf(
        'mysql:host=%s;dbname=%s',
        getenv('DB_HOST'),
        getenv('DB_NAME')
    ),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'charset' => getenv('DB_CHARSET'),

    // Enable schema caching for production
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];