<?php
// Konfigurasi Database (Laptop C1 - Yudhis)
define('DB_HOST', getenv('DB_HOST') ?: '100.115.105.74');
define('DB_NAME', getenv('DB_NAME') ?: 'uploads');
define('DB_USER', getenv('DB_USER') ?: 'appuser');
define('DB_PASS', getenv('DB_PASS') ?: 'apppassword');

// Konfigurasi MinIO (Laptop C2 - Matth)
define('MINIO_HOST',   getenv('MINIO_HOST')   ?: '100.120.75.92');
define('MINIO_PORT',   getenv('MINIO_PORT')   ?: '9000');
define('MINIO_KEY',    getenv('MINIO_KEY')    ?: 'admin');
define('MINIO_SECRET', getenv('MINIO_SECRET') ?: 'password123');

// Konfigurasi SQS LocalStack (Laptop C2 - Matth)
define('SQS_ENDPOINT',  getenv('SQS_ENDPOINT')  ?: 'http://100.120.75.92:4566');
define('SQS_QUEUE_URL', getenv('SQS_QUEUE_URL') ?: 'http://100.120.75.92:4566/000000000000/upload-queue');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}

function getS3(): \Aws\S3\S3Client {
    return new \Aws\S3\S3Client([
        'version'                 => 'latest',
        'region'                  => 'us-east-1',
        'endpoint'                => 'http://' . MINIO_HOST . ':' . MINIO_PORT,
        'use_path_style_endpoint' => true,
        'credentials'             => [
            'key'    => MINIO_KEY,
            'secret' => MINIO_SECRET,
        ],
    ]);
}

function getSQS(): \Aws\Sqs\SqsClient {
    return new \Aws\Sqs\SqsClient([
        'version'     => 'latest',
        'region'      => 'us-east-1',
        'endpoint'    => SQS_ENDPOINT,
        'credentials' => ['key' => 'test', 'secret' => 'test'],
    ]);
}
