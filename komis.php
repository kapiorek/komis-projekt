<?php
// Połączenie z bazą danych
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "komis";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $marka = $_POST['marka'];
    $model = $_POST['model'];
    $rocznik = $_POST['rocznik'];
    $przebieg = $_POST['przebieg'];

    $sql = "INSERT INTO samochody (marka, model, rocznik, przebieg) VALUES ('$marka', '$model', '$rocznik', '$przebieg')";

    if ($conn->query($sql) === TRUE) {
        echo "Nowy samochód został dodany";
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM samochody WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Samochód został usunięty";
    } else {
        echo "Błąd podczas usuwania: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['edit'];
    $marka = $_POST['marka'];
    $model = $_POST['model'];
    $rocznik = $_POST['rocznik'];
    $przebieg = $_POST['przebieg'];

    $sql = "UPDATE samochody SET marka='$marka', model='$model', rocznik='$rocznik', przebieg='$przebieg' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Samochód został zaktualizowany";
    } else {
        echo "Błąd podczas aktualizacji: " . $conn->error;
    }
}


$sql = "SELECT * FROM samochody ORDER BY marka, model";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Komis Samochodowy</title>
</head>
<body>

<h2>Dodaj Nowy Samochód</h2>
<form method="post" action="">
    <label>Marka:</label><br>
    <input type="text" name="marka"><br>
    <label>Model:</label><br>
    <input type="text" name="model"><br>
    <label>Rocznik:</label><br>
    <input type="text" name="rocznik"><br>
    <label>Przebieg:</label><br>
    <input type="text" name="przebieg"><br>
    <input type="submit" value="Dodaj Samochód">
</form>

<h2>Lista Samochodów</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Marka</th>
        <th>Model</th>
        <th>Rocznik</th>
        <th>Przebieg</th>
        <th>edyt/usun</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["marka"] . "</td>";
            echo "<td>" . $row["model"] . "</td>";
            echo "<td>" . $row["rocznik"] . "</td>";
            echo "<td>" . $row["przebieg"] . "</td>";
            echo "<td><a href='?edit=" . $row["id"] . "'>Edytuj</a> | <a href='?delete=" . $row["id"] . "'>Usuń</a></td>";
            echo "</tr>";
        }
    } else {
        echo "Brak danych w bazie";
    }
    ?>
</table>

<?php

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM samochody WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    ?>
    <h2>Edytuj Samochód</h2>
    <form method="post" action="">
        <input type="hidden" name="edit" value="<?php echo $row['id']; ?>">
        <label>Marka:</label><br>
        <input type="text" name="marka" value="<?php echo $row['marka']; ?>"><br>
        <label>Model:</label><br>
        <input type="text" name="model" value="<?php echo $row['model']; ?>"><br>
        <label>Rocznik:</label><br>
        <input type="text" name="rocznik" value="<?php echo $row['rocznik']; ?>"><br>
        <label>Przebieg:</label><br>
        <input type="text" name="przebieg" value="<?php echo $row['przebieg']; ?>"><br>
        <input type="submit" value="Zapisz zmiany">
    </form>
    <?php
}
?>

</body>
</html>

<?php
$conn->close();
?>
