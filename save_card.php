<?php

declare(strict_types=1);

require __DIR__ . '/lib.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = sanitizeText($_POST['name'] ?? '');
if ($name === '') {
    header('Location: index.php?error=' . urlencode('Name is required.'));
    exit;
}

$phones = sanitizeArray($_POST['phones'] ?? [], 3);
$emails = sanitizeArray($_POST['emails'] ?? [], 2);
$whatsapp = preg_replace('/\D+/', '', sanitizeText($_POST['whatsapp'] ?? ''));

$linkLines = preg_split('/\r\n|\r|\n/', (string) ($_POST['links_text'] ?? '')) ?: [];
$links = [];
foreach ($linkLines as $line) {
    $line = sanitizeText($line);
    if ($line !== '') {
        $links[] = normalizeUrl($line);
    }
}

$socialInputs = $_POST['social'] ?? [];
$social = [];
$allowedSocial = ['facebook', 'linkedin', 'youtube', 'instagram', 'tiktok'];
foreach ($allowedSocial as $platform) {
    $url = normalizeUrl(sanitizeText($socialInputs[$platform] ?? ''));
    if ($url !== '') {
        $social[$platform] = $url;
    }
}

$logoPath = '';
if (!empty($_FILES['logo']['name']) && ($_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
    if (($_FILES['logo']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        header('Location: index.php?error=' . urlencode('Image upload failed.'));
        exit;
    }

    $tmpPath = $_FILES['logo']['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmpPath) ?: '';
    finfo_close($finfo);

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        header('Location: index.php?error=' . urlencode('Only JPG, PNG, GIF, WEBP are supported.'));
        exit;
    }

    ensureStorage();
    $filename = sprintf('%s.%s', bin2hex(random_bytes(8)), $allowed[$mime]);
    $destination = UPLOAD_DIR . '/' . $filename;
    if (!move_uploaded_file($tmpPath, $destination)) {
        header('Location: index.php?error=' . urlencode('Failed to save uploaded image.'));
        exit;
    }
    $logoPath = 'uploads/' . $filename;
}

$card = [
    'id' => generateId($name),
    'name' => $name,
    'address' => sanitizeText($_POST['address'] ?? ''),
    'phones' => $phones,
    'emails' => $emails,
    'whatsapp' => $whatsapp,
    'links' => $links,
    'social' => $social,
    'logo' => $logoPath,
    'createdAt' => date('c'),
];

persistCard($card);

header('Location: index.php?saved=1');
exit;
