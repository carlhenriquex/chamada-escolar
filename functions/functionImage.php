<?php

function processarUploadImagem($inputName, $pastaDestino)
{
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $arquivo = $_FILES[$inputName];

    // Verifica tamanho (2MB máx)
    if ($arquivo['size'] > 2 * 1024 * 1024) {
        redirecionar("Imagem muito grande. Tamanho máximo permitido: 2MB.", false);
    }

    // Valida erro de envio
    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        redirecionar("Erro ao enviar a imagem. Código: " . $arquivo['error'], false);
    }

    // Validação por MIME real (opcional mas mais seguro)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeReal = finfo_file($finfo, $arquivo['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeReal, $permitidos)) {
        redirecionar("Formato de imagem não permitido. Use JPEG, PNG ou WEBP.", false);
    }

    // Gera nome seguro para o arquivo
    $nomeOriginal = pathinfo($arquivo['name'], PATHINFO_FILENAME);
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    // Remove acentos e símbolos especiais
    $nomeSanitizado = preg_replace('/[^\w\-]/u', '_', $nomeOriginal);
    if (function_exists('iconv')) {
        $nomeSanitizado = iconv('UTF-8', 'ASCII//TRANSLIT', $nomeSanitizado);
    }
    $nomeFinal = $nomeSanitizado . "_" . uniqid() . "." . $extensao;

    // Cria pasta se não existir
    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0755, true);
    }

    $caminhoCompleto = rtrim($pastaDestino, '/') . '/' . $nomeFinal;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
        redirecionar("Falha ao salvar a imagem no servidor.", false);
    }

    return $nomeFinal;
}
