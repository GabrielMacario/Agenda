<?php

session_start();

include_once('connection.php');
include_once('url.php');

// Envio do post create

$data = $_POST;

if (!empty($data)) {

    if ($data['type'] === 'create') {
        $name = $data['name'];
        $phone = $data['phone'];
        $observations = $data['observations'];

        $queryId = 'SELECT MAX(id_exibido) + 1 as next_id FROM contacts';
        $stmtID = $conn->prepare($queryId);
        $stmtID->execute();
        $nextId = $stmtID->fetch();
        $idExibido = $nextId['next_id'];

        if ($idExibido == 0 || $idExibido == null) {
            $idExibido = 1;
        }

        $queryNumber = 'SELECT id_exibido FROM contacts ORDER BY id_exibido ASC';
        $stmtID = $conn->prepare($queryNumber);
        $stmtID->execute();
        $totalId = $stmtID->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 1; $i <= count($totalId); $i++) {
            $idAtual = (int)$totalId[$i - 1]['id_exibido'];

            if ($i != $idAtual) {
                $idExibido = $i;
                break;
            }
        }

        $query = 'INSERT INTO contacts (id_exibido, name, phone, observations) VALUES (:id_exibido, :name, :phone, :observations)';
        $stmt = $conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':observations', $observations);
        $stmt->bindParam(':id_exibido', $idExibido);


        try {

            $stmt->execute();
            $_SESSION['msg'] = 'Contato criado com sucesso';
        } catch (PDOException $e) {
            // erro na conexão
            $erro = $e->getMessage();
            echo 'Erro $erro';
        }
    } elseif ($data['type'] === 'edit') {

        // edit

        $name = $data['name'];
        $phone = $data['phone'];
        $observations = $data['observations'];
        $id = $data['id'];

        $query = 'UPDATE contacts 
                  SET name = :name, phone = :phone, observations = :observations
                  WHERE id = :id';

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':observations', $observations);
        $stmt->bindParam(':id', $id);

        try {

            $stmt->execute();
            $_SESSION['msg'] = 'Contato atualizado com sucesso';
        } catch (PDOException $e) {
            // erro na conexão
            $erro = $e->getMessage();
            echo 'Erro $erro';
        }
    } elseif ($data['type'] === 'delete') {

        $id = $data['id'];

        $query = 'DELETE FROM contacts WHERE id = :id';

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':id', $id);

        try {

            $stmt->execute();
            $_SESSION['msg'] = 'Contato deletado com sucesso';
        } catch (PDOException $e) {
            // erro na conexão
            $erro = $e->getMessage();
            echo 'Erro $erro';
        }
    }

    // redirect home

    header('Location:' . $BASE_URL . '../index.php');
} else {

    // Show

    $id;

    if (!empty($_GET)) {
        $id = $_GET['id'];
    }

    // retona o dado de um contato

    if (!empty($id)) {

        $query = 'SELECT * FROM contacts WHERE id = :id';

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        $contact = $stmt->fetch();
    } else {

        // retorna todos os contatos

        $contacts = [];

        $query = 'SELECT * FROM contacts';

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $contacts = $stmt->fetchAll();
    }
}


// Fechar conexão

$conn = null;
