<!DOCTYPE html>
<html>
<head>
    <title>Lista de Nomes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin: 20px 0;
        }
        input[type="text"] {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"], .edit-btn, .delete-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn {
            background-color: #FF0000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .delete-btn svg {
            fill: white;
        }
        .edit-form {
            display: none;
        }
        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 3px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
    <script>
        function showEditForm(id) {
            document.getElementById('edit-form-' + id).style.display = 'block';
        }
    </script>
</head>
<body>
    <h1>Lista de Nome</h1>
    <form method="GET" action="">
        <input type="text" name="nome" placeholder="Digite um nome">
        <input type="submit" value="Pesquisar">
    </form>
    <form method="POST" action="">
        <input type="text" name="new_nome" placeholder="Digite um novo nome">
        <input type="submit" value="Adicionar">
    </form>
    <table>
        <tr>
            <th>ID</th>
            <th>nome</th>
            <th>Ações</th>
        </tr>
        <?php
        // Conectar ao banco de dados
        $servername = "db";
        $username = "admin";
        $password = "admin";
        $dbname = "SRE";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexão
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Adicionar novo registro
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_nome"])) {
            $new_nome = $_POST["new_nome"];
            $result = $conn->query("SELECT MAX(id) AS max_id FROM Pessoa");
            $row = $result->fetch_assoc();
            $new_id = $row["max_id"] + 1;
            $sql = "INSERT INTO Pessoa (id, nome) VALUES ('$new_id', '$new_nome')";
            if ($conn->query($sql) === TRUE) {
                echo "Novo registro adicionado com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        }

        // Excluir registro
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
            $delete_id = $_POST["delete_id"];
            $sql = "DELETE FROM Pessoa WHERE id='$delete_id'";
            if ($conn->query($sql) === TRUE) {
                echo "Registro excluído com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        }

        // Atualizar nome do registro
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_id"]) && isset($_POST["edit_nome"])) {
            $edit_id = $_POST["edit_id"];
            $edit_nome = $_POST["edit_nome"];
            $sql = "UPDATE Pessoa SET nome='$edit_nome' WHERE id='$edit_id'";
            if ($conn->query($sql) === TRUE) {
                echo "Registro atualizado com sucesso!";
            } else {
                echo "Erro: " . $sql . "<br>" . $conn->error;
            }
        }

        // Selecionar dados da tabela
        $nome = isset($_GET['nome']) ? $_GET['nome'] : '';
        $sql = "SELECT id, nome FROM Pessoa WHERE nome LIKE '%$nome%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Exibir dados em cada linha
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["id"]. "</td><td>" . $row["nome"]. "</td>
                <td>
                    <form method='POST' action='' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='" . $row["id"] . "'>
                        <button type='submit' class='delete-btn'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
                                <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2 0A.5.5 0 0 1 8 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2 .5a.5.5 0 0 0-.5-.5H9v6a.5.5 0 0 0 1 0V6h.5a.5.5 0 0 0 .5-.5zM14 4H1V3h1V2a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v1h1v1zm-1 0H3V2a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v2z'/>
                            </svg>
                        </button>
                    </form>
                    <button class='edit-btn' onclick='showEditForm(" . $row["id"] . ")'>Editar</button>
                    <form id='edit-form-" . $row["id"] . "' method='POST' action='' class='edit-form'>
                        <input type='hidden' name='edit_id' value='" . $row["id"] . "'>
                        <input type='text' name='edit_nome' value='" . $row["nome"] . "''>
                        <input type='submit' value='Salvar' class='edit-btn'>
                    </form>
                </td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>0 resultados</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
