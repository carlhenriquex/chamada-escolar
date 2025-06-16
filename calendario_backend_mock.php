<?php

function lerCalendarioJson() {
    $caminhoArquivo = __DIR__ . '/calendario_database_mock.json';
    if (!file_exists($caminhoArquivo)) {
        return [];
    }
    $conteudo = file_get_contents($caminhoArquivo);
    $lista = json_decode($conteudo, true);
    return is_array($lista) ? $lista : [];
}

?>