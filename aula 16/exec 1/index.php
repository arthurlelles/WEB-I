<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <ul>
        <?php
        $notas = [35, 77, 65, 49, 28, 95];
        foreach ($notas as $i => $nota) {
            $status = $nota >= 60 ? "Aprovado" : "Reprovado";
            echo "<li>Aluno " . ($i + 1) . " - Nota: $nota - $status</li>";
        }
        ?>
    </ul>

</body>
</html>