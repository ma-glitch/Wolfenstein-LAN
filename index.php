<?php
#connect til db
$servername = "localhost";
$username = "root";
$password = "Root";
$dbname = "lan";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
#legg til ny pamelding
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $navn = $_POST["navn"];
    $email = $_POST["email"];
    $adresse = $_POST["adresse"];
    $postnr = $_POST["postnr"];


    $sql = "INSERT INTO pameldinger (navn, epost, adresse, postnr) VALUES ('$navn', '$email', '$adresse', '$postnr')";
    if ($conn->query($sql) === TRUE) {        
        echo "success";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM pameldinger";
$result = $conn->query($sql);

$pameldinger = array();
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $pameldinger[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form data

    // Redirect to the same page
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>påmelding System</title>
</head>
<body>
    <h1>påmelding System</h1>

    <h2>Submit en påmelding</h2>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="navn">Navn:</label>
        <input type="text" name="navn" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="adresse">adresse:</label>
        <input type="text" name="adresse" required><br>

        <label for="postnr">postnr:</label>
        <input type="number" max="9999" name="postnr" required><br>

        <input type="submit" value="Submit">
    </form>
<form id="adminForm">
    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <input type="submit" value="Sign In">
    <button id="signOutButton" style="display: none;">Sign Out</button>
</form>

<div id="adminPowers" style="display: none;">
    <table>
        <thead>
            <tr>
                <th>Navn</th>
                <th>Epost</th>
                <th>Adresse</th>
                <th>Postnr</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pameldinger as $pamelding): ?>
                <tr>
                    <td><?php echo $pamelding['navn']; ?></td>
                    <td><?php echo $pamelding['epost']; ?></td>
                    <td><?php echo $pamelding['adresse']; ?></td>
                    <td><?php echo $pamelding['postnr']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Display pamelding information -->
<div id="pameldingInfo" style="display: none;"></div>
</body>
<link rel="stylesheet" href="style.css">
</html>

<script>
document.getElementById('adminForm').addEventListener('submit', function(event) {
    event.preventDefault();
    var password = document.getElementById('password').value;
    if (password === 'password') {
        document.getElementById('adminPowers').style.display = 'block';
        document.getElementById('signOutButton').style.display = 'inline';
    } else {
        alert('Incorrect password');
    }
});
document.getElementById('signOutButton').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('password').value = '';
    document.getElementById('adminPowers').style.display = 'none';
    this.style.display = 'none';
});
// Attach event listener to pamelding buttons
document.querySelectorAll('.pameldingButton').forEach(function(button) {
    button.addEventListener('click', function() {
        var pameldingId = this.dataset.pameldingId;

        // Fetch pamelding information
        fetch('get_pamelding.php?id=' + pameldingId)
            .then(response => response.json())
            .then(data => {
                // Display pamelding information
                var pameldingInfo = document.getElementById('pameldingInfo');
                pameldingInfo.style.display = 'block';
                pameldingInfo.textContent = JSON.stringify(data);
            });
    });
});

</script>





