<?php
function isAuthorized() {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    // Token fixo (em produção, usar JWT ou API keys por utilizador)
    return $token === 'Bearer MEU_TOKEN_SECRETO_123';
}

function sendUnauthorized() {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
