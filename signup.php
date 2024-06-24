<?php

include('connect.php');

$error_msg = '';
$success_msg = '';

try {
    if(isset($_POST['signup'])) {
        // Validate and sanitize inputs
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $uname = filter_var($_POST['uname'], FILTER_SANITIZE_STRING);
        $pass = $_POST['pass']; // Password remains as entered by user
        $fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
        $type = $_POST['type']; // Assuming radio button selection is validated on client-side

        // Basic validation
        if(empty($email) || empty($uname) || empty($pass) || empty($fname) || empty($phone) || empty($type)) {
            throw new Exception("All fields are required.");
        }

        // Create a PDO instance
        $pdo = new PDO("mysql:host=$servername;dbname=attsystem", $username, $password);

        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement using placeholders to prevent SQL injection
        $stmt = $pdo->prepare("INSERT INTO admininfo(username, password, email, fname, phone, type) VALUES (:username, :password, :email, :fname, :phone, :type)");

        // Bind parameters
        $stmt->bindParam(':username', $uname);
        $stmt->bindParam(':password', $pass); // Password is stored as entered by user
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':type', $type);

        // Execute query
        if($stmt->execute()) {
            $success_msg = "Signup Successfully!";
        } else {
            throw new Exception("Error in signing up. Please try again.");
        }
    }
} catch(Exception $e) {
    $error_msg = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Attendance Management System 1.0 - Signup</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Online Attendance Management System 1.0</h1>
    </header>
    <center>
        <h1>Signup</h1>
        <div class="content">
            <div class="row">
                <?php if(!empty($success_msg)) echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($success_msg) . '</div>'; ?>
                <?php if(!empty($error_msg)) echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error_msg) . '</div>'; ?>
                <form method="post" class="form-horizontal col-md-6 col-md-offset-3">
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-7">
                            <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Your email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputUsername" class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-7">
                            <input type="text" name="uname" class="form-control" id="inputUsername" placeholder="Choose username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-7">
                            <input type="password" name="pass" class="form-control" id="inputPassword" placeholder="Choose a strong password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputFullName" class="col-sm-3 control-label">Full Name</label>
                        <div class="col-sm-7">
                            <input type="text" name="fname" class="form-control" id="inputFullName" placeholder="Your full name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPhone" class="col-sm-3 control-label">Phone Number</label>
                        <div class="col-sm-7">
                            <input type="text" name="phone" class="form-control" id="inputPhone" placeholder="Your phone number" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Role</label>
                        <div class="col-sm-7">
                            <label><input type="radio" name="type" value="student" checked> Student</label>
                            <label><input type="radio" name="type" value="teacher"> Teacher</label>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary col-md-2 col-md-offset-8" value="Signup" name="signup">
                </form>
            </div>
            <br>
            <p><strong>Already have an account? <a href="index.php">Login here.</a></strong></p>
        </div>
    </center>
</body>
</html>
