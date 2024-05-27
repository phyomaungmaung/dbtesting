<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM users WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "User not found!";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id='$id'";
    } else {
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id='$id'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        Username: <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required><br>
        <!-- Email: <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required><br> -->
        Password: <input type="password" name="password"><br>
        <p>Leave password blank if you don't want to change it</p>
        <button type="submit">Update</button>
    </form>
</body>
</html>
