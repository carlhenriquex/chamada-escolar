<?php
require_once __DIR__ . "/calendario_backend_mock.php";
$dados = lerCalendarioJson();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário</title>
    <link rel="stylesheet" href="css/calendario.css">
</head>
<body>
    <table>
        <thead>
            <tr>
                <?php if (!empty($dados)): ?>
                    <?php foreach (array_keys($dados[0]) as $coluna): ?>
                        <th><?php echo htmlspecialchars($coluna); ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados as $linha): ?>
                <tr>
                    <?php foreach ($linha as $valor): ?>
                        <td><?php echo htmlspecialchars($valor); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>