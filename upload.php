<?php

$spaceName = 'your-space-name';
$region = 'your-region'; // e.g., nyc3
$accessKey = 'your-access-key';
$secretKey = 'your-secret-key';
$endpoint = "https://{$region}.digitaloceanspaces.com";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $filePath = $file['tmp_name'];
    $fileName = basename($file['name']);
    $key = "uploads/" . time() . "_" . $fileName;

    $date = gmdate('D, d M Y H:i:s T');
    $stringToSign = "PUT\n\n{$file['type']}\n{$date}\n/{$spaceName}/{$key}";
    $signature = base64_encode(hash_hmac('sha1', $stringToSign, $secretKey, true));
    $authorization = "AWS {$accessKey}:{$signature}";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "{$endpoint}/{$key}");
    curl_setopt($curl, CURLOPT_PUT, true);
    curl_setopt($curl, CURLOPT_INFILE, fopen($filePath, 'r'));
    curl_setopt($curl, CURLOPT_INFILESIZE, filesize($filePath));
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Date: {$date}",
        "Authorization: {$authorization}",
        "Content-Type: {$file['type']}",
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpCode === 200) {
        echo json_encode(['fileUrl' => "{$endpoint}/{$key}"]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'File upload failed']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>
