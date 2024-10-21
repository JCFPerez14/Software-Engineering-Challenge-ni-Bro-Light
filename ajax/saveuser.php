<?php 
$servername = "localhost";
$uname = "root";
$pass = "";
$dbname = "db_cnbro";

// Create connection
$conn = new mysqli($servername, $uname, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect POST data
$id = $_POST['idx'];
$f_name = $_POST['fname'];
$sel = $_POST['sel_date'];
$gen = $_POST['gender'];
$section = $_POST['sec'];
$pass = $_POST['pword'];

// Check if all fields have values (not null)
if (empty($f_name) || empty($sel) || empty($gen) || empty($section) || empty($pass)) {
    echo "No changes made. All fields are required.";
} else {
    // Hash the password before inserting it into the database
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO tbl_user (fname, sel_date, gender, sec, pword) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $f_name, $sel, $gen, $section, $hashed_pass);

    // Execute the statement
    if ($stmt->execute()) {
        echo "1";  // Success response
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
?>
