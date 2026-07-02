<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['image'])) {
    header('Location: index.php');
    exit;
}

$file     = $_FILES['image'];
$ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed  = ['jpg','jpeg','png','gif'];

if (!in_array($ext, $allowed)) {
    die('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
}

$filename = uniqid('img_') . '.' . $ext;

try {
    $s3 = getS3();
    $s3->putObject([
        'Bucket'      => 'uploads',
        'Key'         => $filename,
        'SourceFile'  => $file['tmp_name'],
        'ContentType' => $file['type'],
        'ACL'         => 'public-read',
    ]);

    $fileUrl = 'http://' . MINIO_HOST . ':' . MINIO_PORT . '/uploads/' . $filename;

    $db = getDB();
    $db->prepare(
        'INSERT INTO files (name, url, size_bytes, created_at) VALUES (?, ?, ?, NOW())'
    )->execute([$filename, $fileUrl, $file['size']]);

    $sqs     = getSQS();
    $payload = json_encode([
        'Records' => [[
            's3' => [
                'bucket' => ['name' => 'uploads'],
                'object' => ['key'  => $filename, 'size' => $file['size']],
            ]
        ]]
    ]);
    $sqs->sendMessage([
        'QueueUrl'    => SQS_QUEUE_URL,
        'MessageBody' => $payload,
    ]);

    error_log('[Upload] Success: ' . $filename . ' -> SQS queued');
} catch (Exception $e) {
    error_log('[Upload] Error: ' . $e->getMessage());
    die('Upload gagal: ' . $e->getMessage());
}

header('Location: index.php');
exit;
