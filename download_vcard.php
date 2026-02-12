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

function vcfEscape(string $value): string
{
    return str_replace(["\\", ';', ',', "\n", "\r"], ['\\\\', '\\;', '\\,', '\\n', ''], $value);
}

$fullName = vcfEscape($card['name']);
$org = vcfEscape($card['name']);
$address = vcfEscape(str_replace(["\r", "\n"], ' ', $card['address']));
$phonePrimary = vcfEscape($card['phones'][0] ?? '');
$emailPrimary = vcfEscape($card['emails'][0] ?? '');
$urlPrimary = vcfEscape($card['links'][0]['url'] ?? '');

$vcard = [
    'BEGIN:VCARD',
    'VERSION:3.0',
    'FN:' . $fullName,
    'ORG:' . $org,
];

if ($phonePrimary !== '') {
    $vcard[] = 'TEL;TYPE=CELL:' . $phonePrimary;
}

if (($card['whatsapp'] ?? '') !== '') {
    $vcard[] = 'TEL;TYPE=WORK,VOICE:' . vcfEscape($card['whatsapp']);
}

if ($emailPrimary !== '') {
    $vcard[] = 'EMAIL;TYPE=INTERNET:' . $emailPrimary;
}

if ($address !== '') {
    $vcard[] = 'ADR;TYPE=WORK:;;' . $address . ';;;;';
}

if ($urlPrimary !== '') {
    $vcard[] = 'URL:' . $urlPrimary;
}

$vcard[] = 'END:VCARD';
$content = implode("\r\n", $vcard) . "\r\n";

header('Content-Type: text/vcard; charset=utf-8');
header('Content-Disposition: attachment; filename="' . createSlug($card['name']) . '.vcf"');
header('Content-Length: ' . strlen($content));

echo $content;
