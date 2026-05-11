<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://holidayvillage.pl');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

$BOT_TOKEN = '8626973754:AAEhza1RjoXkDZf_O0ZCA84B4wdD825ybQc';
$CHAT_ID   = '1091562321';

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || empty($data['phone'])) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

function esc(string $s): string {
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}

$name     = esc($data['name']     ?? '—');
$phone    = esc($data['phone']    ?? '—');
$date     = esc($data['date']     ?? '—');
$interest = esc($data['interest'] ?? '—');
$lang     = strtoupper(esc($data['lang'] ?? '?'));
$ts       = (new DateTime('now', new DateTimeZone('Europe/Warsaw')))->format('d.m.Y H:i');

$text = "🌿 *Новая заявка — Holiday Village*\n\n"
      . "👤 *Имя:* {$name}\n"
      . "📞 *Телефон:* {$phone}\n"
      . "📅 *Дата визита:* {$date}\n"
      . "🎯 *Интерес:* {$interest}\n"
      . "🌐 *Язык:* {$lang}\n"
      . "🕐 *Время:* {$ts}";

$payload = json_encode([
    'chat_id'    => $CHAT_ID,
    'text'       => $text,
    'parse_mode' => 'Markdown',
]);

$ch = curl_init("https://api.telegram.org/bot{$BOT_TOKEN}/sendMessage");
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_SSL_VERIFYPEER => true,
]);
$res = curl_exec($ch);
curl_close($ch);

$tg = json_decode($res, true);
echo json_encode(['ok' => $tg['ok'] ?? false]);
