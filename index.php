<?php
header('Content-Type: application/json');
$metodo = $_SERVER['REQUEST_METHOD'];
switch ($metodo) {
    case 'GET':
        if ($_GET['action'] == 'personal') {
            try {
                $DBH = new PDO("mysql:host=localhost;dbname=utez;port=3306", "root", "");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            if (isset($_GET['id'])) { //muestra el registro con id
                $resultado = $DBH->prepare('SELECT * FROM personal WHERE employeeNumber = :p');
                $resultado->bindParam(':p', $_GET['id']);
                $resultado->execute();
                $response = $resultado->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($response, JSON_PRETTY_PRINT);
            } else { //muestra todos los registros
                $resultado = $DBH->prepare('SELECT * FROM personal');
                $resultado->execute();
                $response = $resultado->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($response, JSON_PRETTY_PRINT);
            }
        }

        if ($_GET['action'] == 'position') {
            try {
                $DBH = new PDO("mysql:host=localhost;dbname=utez;port=3306", "root", "");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $resultado = $DBH->prepare('SELECT * FROM position');
            $resultado->execute();
            $response = $resultado->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
        break;
    case 'POST'; //Insercion
        if ($_GET['action'] == 'personal') {
            $jsonData = json_decode(file_get_contents("php://input"));
            try {
                $conn = new PDO("mysql:host=localhost;dbname=utez;port=3306", "root", "");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $query = $conn->prepare('INSERT INTO personal (name, surname, lastname, salary, birthday, position_id)
                VALUES (:name, :surname, :lastname, :salary, :birthday, :position_id)');
            $query->bindParam(":name", $jsonData->name);
            $query->bindParam(":surname", $jsonData->surname);
            $query->bindParam(":lastname", $jsonData->lastname);
            $query->bindParam(":salary", $jsonData->salary);
            $query->bindParam(":birthday", $jsonData->birthday);
            $query->bindParam(":position_id", $jsonData->position);

            $result = $query->execute();
            echo $result;

            if ($result) {
                echo ("Personal registrada correctamente. Code $result");
            } else {
                echo ("Error al registrar. Code $result");
            }
        }
        break;

    case 'PUT':
        break;

    case 'DELETE':
        break;

    default:
        echo 'Metodo no soportado';
        break;
}
