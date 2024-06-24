<?php
if(isset($_POST['login'])) {
    try {
        // Checking empty fields
        if(empty($_POST['username'])) {
            throw new Exception("Username is required!");
        }
        if(empty($_POST['password'])) {
            throw new Exception("Password is required!");
        }

        // Establishing connection with db (including connect.php)
        require_once('connect.php');

        // Checking login info into database
        $stmt = $conn->prepare("SELECT * FROM admininfo WHERE username=:username AND password=:password AND type=:type");
        $stmt->bindParam(":username", $_POST['username']);
        $stmt->bindParam(":password", $_POST['password']);
        $stmt->bindParam(":type", $_POST['type']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            session_start();
            $_SESSION['name'] = "oasis";

            // Redirect based on user type
            if($_POST["type"] == 'teacher') {
                header('Location: teacher/index.php');
                exit;
            } elseif($_POST["type"] == 'student') {
                header('Location: student/index.php');
                exit;
            } elseif($_POST["type"] == 'admin') {
                header('Location: admin/index.php');
                exit;
            }
        } else {
            throw new Exception("Username, Password, or Role is wrong, try again!");
        }
    } catch(Exception $e) {
        $error_msg = $e->getMessage();
        echo $error_msg; // Display error message
    }

    // Closing the statement and connection
    $stmt = null; // Close statement
    $conn = null; // Close connection
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Attendance Management System</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Online Attendance Management System 1.0</h1>
        </header>
        
        <h2>Login</h2>
        
        <?php
        // Printing error message
        if(isset($error_msg)) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error_msg) . '</div>';
        }
        ?>

        <form method="post" class="form-horizontal">
            <div class="form-group">
                <label for="username" class="col-sm-3 control-label">Username</label>
                <div class="col-sm-7">
                    <input type="text" name="username" class="form-control" id="username" placeholder="Your username" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="col-sm-3 control-label">Password</label>
                <div class="col-sm-7">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Your password" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Role</label>
                <div class="col-sm-7">
                    <label class="radio-inline">
                        <input type="radio" name="type" value="student" checked> Student
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="type" value="teacher"> Teacher
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="type" value="admin"> Admin
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-primary" name="login">Login</button>
                    <button type="reset" class="btn btn-default">Reset</button>
                </div>
            </div>
        </form>

        <p><strong>Forgot your password? <a href="reset.php">Reset here.</a></strong></p>
        <p><strong>Don't have an account? <a href="signup.php">Sign up here.</a></strong></p>
    </div>
</body>
</html>
