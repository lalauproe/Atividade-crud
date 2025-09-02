<?php

require_once 'conn.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;

        if ($email && $password) { 
            $sql = "SELECT * FROM users WHERE email = ?";
            $smtm = $conn->prepare($sql);

            if ($smtm) {
                $smtm->bind_param("s", $email);
                $smtm->execute();
                $result = $smtm->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['email'] = $user['email'];
                        header("Location: index.php");
                        exit();
                    } else {
                        throw new Exception("Email ou Senha incorretos!.");
                    }
                }else {
                    throw new Exception("Email ou Senha incorretos!.");
                }
                $smtm->close();
            } else {
                throw new Exception("Erro na preparação da consulta: " . $conn->error);
            }
        } else {
            throw new Exception("Por favor, preencha todos os campos.");
        }
    } else {
        throw new Exception("Método de requisição inválido.");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
} finally {
    $conn->close();
}