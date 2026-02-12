<?php

declare(strict_types=1);

require __DIR__ . '/lib.php';

$id = sanitizeText($_GET['id'] ?? '');
$card = $id !== '' ? getCardById($id) : null;

if (!$card) {
    http_response_code(404);
    echo 'Card not found';
    exit;
}

$shareUrl = cardPublicUrl($card['id']);
$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=' . urlencode($shareUrl);

$iconMap = [
    'phone' => '☎',
    'whatsapp' => '🟢',
    'email' => '✉',
    'link' => '🌐',
    'facebook' => 'f',
    'linkedin' => 'in',
    'youtube' => '▶',
    'instagram' => '◉',
    'tiktok' => '♪',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= escape($card['name']) ?></title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body class="card-page">
<main class="vcard">
    <button class="share-btn" type="button" data-share-url="<?= escape($shareUrl) ?>" aria-label="Share card">⋯</button>

    <?php if ($card['logo'] !== ''): ?>
        <img class="avatar" src="<?= escape($card['logo']) ?>" alt="<?= escape($card['name']) ?>">
    <?php endif; ?>

    <h1><?= escape($card['name']) ?></h1>
    <?php if ($card['address'] !== ''): ?>
        <p class="desc"><?= nl2br(escape($card['address'])) ?></p>
    <?php endif; ?>

    <a class="btn primary full" href="download_vcard.php?id=<?= escape($card['id']) ?>">Save Contact</a>

    <section class="icon-grid">
        <?php foreach ($card['phones'] as $index => $phone): ?>
            <a class="icon-item" href="tel:<?= escape($phone) ?>">
                <span class="icon"><?= $iconMap['phone'] ?></span>
                <span>Phone <?= $index + 1 ?></span>
            </a>
        <?php endforeach; ?>

        <?php if ($card['whatsapp'] !== ''): ?>
            <a class="icon-item" href="https://wa.me/<?= escape($card['whatsapp']) ?>" target="_blank" rel="noopener noreferrer">
                <span class="icon"><?= $iconMap['whatsapp'] ?></span>
                <span>WhatsApp</span>
            </a>
        <?php endif; ?>

        <?php foreach ($card['emails'] as $index => $email): ?>
            <a class="icon-item" href="mailto:<?= escape($email) ?>">
                <span class="icon"><?= $iconMap['email'] ?></span>
                <span>Email <?= $index + 1 ?></span>
            </a>
        <?php endforeach; ?>

        <?php foreach ($card['links'] as $link): ?>
            <a class="icon-item" href="<?= escape($link['url']) ?>" target="_blank" rel="noopener noreferrer">
                <span class="icon"><?= $iconMap['link'] ?></span>
                <span><?= escape($link['label']) ?></span>
            </a>
        <?php endforeach; ?>

        <?php foreach ($card['social'] as $platform => $link): ?>
            <a class="icon-item" href="<?= escape($link) ?>" target="_blank" rel="noopener noreferrer">
                <span class="icon"><?= escape($iconMap[$platform] ?? '•') ?></span>
                <span><?= escape(ucfirst($platform)) ?></span>
            </a>
        <?php endforeach; ?>
    </section>

    <div id="share-modal" class="modal hidden" role="dialog" aria-modal="true" aria-label="Share this card">
        <div class="modal-content">
            <h3>Share this card</h3>
            <input id="share-link" type="text" readonly value="<?= escape($shareUrl) ?>">
            <button id="copy-link" class="btn" type="button">Copy Link</button>
            <img src="<?= escape($qrUrl) ?>" alt="QR code for business card link" class="qr">
            <button id="close-modal" class="btn" type="button">Close</button>
        </div>
    </div>
</main>
<script src="assets/app.js"></script>
</body>
</html>
