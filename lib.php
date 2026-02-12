<?php

declare(strict_types=1);

const DATA_FILE = __DIR__ . '/data/cards.json';
const UPLOAD_DIR = __DIR__ . '/uploads';

function ensureStorage(): void
{
    if (!is_dir(dirname(DATA_FILE))) {
        mkdir(dirname(DATA_FILE), 0775, true);
    }

    if (!file_exists(DATA_FILE)) {
        file_put_contents(DATA_FILE, json_encode([], JSON_PRETTY_PRINT));
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0775, true);
    }
}

function loadCards(): array
{
    ensureStorage();
    $raw = file_get_contents(DATA_FILE);
    $cards = json_decode($raw ?: '[]', true);
    return is_array($cards) ? $cards : [];
}

function saveCards(array $cards): void
{
    ensureStorage();
    file_put_contents(DATA_FILE, json_encode($cards, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function sanitizeText(?string $value): string
{
    return trim((string) $value);
}

function sanitizeArray(array $values, int $limit): array
{
    $clean = [];
    foreach ($values as $value) {
        $value = sanitizeText((string) $value);
        if ($value !== '') {
            $clean[] = $value;
        }

        if (count($clean) >= $limit) {
            break;
        }
    }

    return $clean;
}

function normalizeUrl(string $url): string
{
    if ($url === '') {
        return '';
    }

    if (!preg_match('#^https?://#i', $url)) {
        $url = 'https://' . $url;
    }

    return $url;
}

function createSlug(string $name): string
{
    $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($name));
    $slug = trim((string) $slug, '-');
    return $slug !== '' ? $slug : 'card';
}

function generateId(string $name): string
{
    return createSlug($name) . '-' . substr(bin2hex(random_bytes(4)), 0, 8);
}

function getCardById(string $id): ?array
{
    $cards = loadCards();
    return $cards[$id] ?? null;
}

function persistCard(array $card): void
{
    $cards = loadCards();
    $cards[$card['id']] = $card;
    saveCards($cards);
}

function appUrlPath(string $path): string
{
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($dir === '' || $dir === '.') {
        $dir = '';
    }

    return $dir . '/' . ltrim($path, '/');
}

function cardPublicUrl(string $id): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = appUrlPath('card.php?id=' . urlencode($id));
    return sprintf('%s://%s%s', $scheme, $host, $path);
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
