<?php

declare(strict_types=1);

require __DIR__ . '/lib.php';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = sanitizeText($_POST['id'] ?? '');
$cards = loadCards();

if ($id !== '' && isset($cards[$id])) {
    $logo = sanitizeText((string) ($cards[$id]['logo'] ?? ''));
    if ($logo !== '') {
        $path = __DIR__ . '/' . ltrim($logo, '/');
        if (is_file($path)) {
            @unlink($path);
        }
    }

    unset($cards[$id]);
    saveCards($cards);
}

header('Location: index.php?deleted=1');
exit;
