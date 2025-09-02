<?php

require_once 'conn.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : null;

        if ($email && $password && $confirmPassword) {
            if ($password !== $confirmPassword) {
                throw new Exception("As senhas não coincidem.");
            }

            if (!$conn || $conn->connect_error) {
                throw new Exception("Falha na conexão com o banco de dados: ");
        }

        $hashed_password = password_hash($password, PASSWORD_ARGON2I);

        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
    

        if ($stmt) {
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                throw new Exception("Erro ao executar a cadastro: " . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception("Erro ao preparar a consulta: " . $conn->error);
        }
    } else {
        throw new Exception("Email, senha e confirmação de senha são obrigatórios.");
    }
} else {
    throw new Exception("Método de requisição inválido.");
}
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
} finally {
    if (isset($conn) && !$conn->connect_error) {
        $conn->close();
    }
}