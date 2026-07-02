<?php
require_once 'config.php';
$db    = getDB();
$files = $db->query('SELECT * FROM files ORDER BY created_at DESC LIMIT 20')->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resilient Infrastructure — File Manager</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; background: #f0f2f5; }
        h1   { color: #1F4E79; }
        .upload-form { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .file-grid   { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; }
        .file-card   { background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.08); text-align: center; }
        .file-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
        .file-name   { font-size: 12px; color: #555; margin-top: 8px; word-break: break-all; }
        .btn         { background: #1F4E79; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .btn:hover   { background: #2E75B6; }
        .server-info { font-size: 12px; color: #888; margin-top: 4px; }
    </style>
</head>
<body>
    <h1>File Manager</h1>
    <p class="server-info">Server: <?= gethostname() ?> | <?= date('Y-m-d H:i:s') ?></p>
    <div class="upload-form">
        <h3>Upload Gambar</h3>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/jpeg,image/png,image/gif" required style="margin-right:10px">
            <button type="submit" class="btn">Upload</button>
        </form>
    </div>
    <h3>File Tersimpan (<?= count($files) ?> file)</h3>
    <?php if (empty($files)): ?>
        <p>Belum ada file. Upload gambar pertama Anda!</p>
    <?php else: ?>
    <div class="file-grid">
        <?php foreach ($files as $f): ?>
        <div class="file-card">
            <?php $imgUrl = $f['thumbnail'] ?: $f['url']; ?>
            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($f['name']) ?>"
                 onerror="this.src='https://placehold.co/180x150?text=Processing'">
            <div class="file-name"><?= htmlspecialchars($f['name']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</body>
</html>
