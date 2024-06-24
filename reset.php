<?php
include('connect.php');

if(isset($_POST['reset'])) {
    $email = $_POST['email'];
    
    // Validate email (you can add more validation as needed)
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="alert alert-danger" role="alert">Invalid email format!</div>';
    } else {
        try {
            // Prepare and execute the query
            $stmt = $conn->prepare("SELECT password FROM admininfo WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result) {
?>
                <div class="content">
                    <p>
                        Hi there!<br>
                        You requested for a password recovery.<br>
                        You may <a href="index.php">Login here</a> and enter this key as your password to login.<br>
                        Recovery key: <mark><?php echo htmlspecialchars($result['password']); ?></mark><br>
                        Regards,<br>
                        Online Attendance Management System 1.0
                    </p>
                </div>
<?php
            } else {
?>
                <div class="content">
                    <p>Email is not associated with any account. Contact OAMS 1.0</p>
                </div>
<?php
            }
        } catch(PDOException $e) {
            echo '<div class="alert alert-danger" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Attendance Management System 1.0 - Password Reset</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Online Attendance Management System 1.0</h1>
        <div class="navbar">
            <a href="index.php">Login</a>
        </div>
    </header>

    <center>
        <div class="content">
            <div class="row">
                <form method="post" class="form-horizontal col-md-6 col-md-offset-3">
                    <h3>Recover your password</h3>
                    <div class="form-group">
                        <label for="input1" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" id="input1" placeholder="Your email" required>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary col-md-2 col-md-offset-10" value="Go" name="reset" />
                </form>
            </div>
        </div>
    </center>

</body>
</html>
