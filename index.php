<?php

declare(strict_types=1);

require __DIR__ . '/lib.php';

$cards = loadCards();
$success = isset($_GET['saved']) ? 'Business card saved successfully.' : null;
$deleted = isset($_GET['deleted']) ? 'Card deleted.' : null;
$error = isset($_GET['error']) ? urldecode((string) $_GET['error']) : null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VCard Studio</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<main class="layout">
    <section class="panel">
        <h1>VCard Studio</h1>
        <p>Create and manage multiple virtual business cards.</p>

        <?php if ($success): ?>
            <div class="notice success"><?= escape($success) ?></div>
        <?php endif; ?>
        <?php if ($deleted): ?>
            <div class="notice success"><?= escape($deleted) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="notice error"><?= escape($error) ?></div>
        <?php endif; ?>

        <form action="save_card.php" method="post" enctype="multipart/form-data" class="card-form">
            <h2>Basic Details</h2>
            <label>Business / Person Name <input type="text" name="name" required></label>
            <label>Address / Description <textarea name="address" rows="3"></textarea></label>
            <label>Logo / Profile Image <input type="file" name="logo" accept="image/jpeg,image/png,image/gif,image/webp"></label>

            <h2>Contact</h2>
            <div class="grid-2">
                <label>Phone #1 <input type="text" name="phones[]" placeholder="+60..."></label>
                <label>Phone #2 <input type="text" name="phones[]"></label>
                <label>Phone #3 <input type="text" name="phones[]"></label>
                <label>WhatsApp Number <input type="text" name="whatsapp" placeholder="6012xxxxxxx"></label>
            </div>

            <h2>Email (max 2)</h2>
            <div class="grid-2">
                <label>Email #1 <input type="email" name="emails[]"></label>
                <label>Email #2 <input type="email" name="emails[]"></label>
            </div>

            <h2>Web Links</h2>
            <div id="links-wrapper" class="stack"></div>
            <button class="btn" type="button" id="add-link">+ Add Link</button>

            <h2>Social Media</h2>
            <div class="grid-2">
                <label>Facebook <input type="url" name="social[facebook]" placeholder="https://facebook.com/..."></label>
                <label>LinkedIn <input type="url" name="social[linkedin]" placeholder="https://linkedin.com/in/..."></label>
                <label>YouTube <input type="url" name="social[youtube]"></label>
                <label>Instagram <input type="url" name="social[instagram]"></label>
                <label>TikTok <input type="url" name="social[tiktok]"></label>
            </div>

            <button type="submit" class="btn primary">Save Business Card</button>
        </form>

        <template id="link-template">
            <div class="link-row">
                <input type="text" name="link_labels[]" placeholder="Label (e.g. Appointment)">
                <input type="url" name="link_urls[]" placeholder="https://...">
                <button type="button" class="btn remove-link">Remove</button>
            </div>
        </template>
    </section>

    <section class="panel">
        <h2>Saved Cards (<?= count($cards) ?>)</h2>
        <?php if (!$cards): ?>
            <p>No cards yet. Create your first card.</p>
        <?php else: ?>
            <ul class="card-list">
                <?php foreach ($cards as $card): ?>
                    <li>
                        <div>
                            <strong><?= escape($card['name']) ?></strong>
                            <small><?= escape($card['createdAt']) ?></small>
                        </div>
                        <div class="card-actions">
                            <a class="btn" href="card.php?id=<?= escape($card['id']) ?>" target="_blank">Open</a>
                            <a class="btn" href="download_vcard.php?id=<?= escape($card['id']) ?>">vCard</a>
                            <form method="post" action="delete_card.php" onsubmit="return confirm('Delete this card?');">
                                <input type="hidden" name="id" value="<?= escape($card['id']) ?>">
                                <button class="btn danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</main>
<script src="assets/app.js"></script>
</body>
</html>
