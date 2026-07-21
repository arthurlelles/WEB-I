<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
        $produtos = [
            ["nome" => "Notebook", "preco" => "R$ 2.500,00", "quantidade" => 8],
            ["nome" => "Fone de Ouvido", "preco" => "R$ 79,90", "quantidade" => 30],
            ["nome" => "Mouse", "preco" => "R$ 39,90", "quantidade" => 20],
        ];
    ?>
    <table border="1" cellpadding="6">
        <tr><th>Nome</th><th>Preço</th><th>Quantidade</th></tr>
            <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= $p["nome"] ?></td>
                <td><?= $p["preco"] ?></td>
                <td><?= $p["quantidade"] ?></td>
            </tr>
            <?php endforeach; ?>
    </table>


</body>
</html>