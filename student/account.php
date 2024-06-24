<?php
ob_start();
session_start();

// Checking if the session is valid
if (!isset($_SESSION['name']) || $_SESSION['name'] !== 'oasis') {
    header('location: ../login.php');
    exit();
}

// Include database connection
include('connect.php');

$error_msg = '';
$success_msg = '';

try {
    // Process form submission for updating student information
    if (isset($_POST['done'])) {
        // Validate form fields
        if (empty($_POST['name']) || empty($_POST['dept']) || empty($_POST['batch']) || empty($_POST['email'])) {
            throw new Exception("All fields are required");
        }

        // Sanitize inputs
        $sid = $_POST['id'];
        $name = htmlspecialchars($_POST['name']);
        $dept = htmlspecialchars($_POST['dept']);
        $batch = htmlspecialchars($_POST['batch']);
        $semester = htmlspecialchars($_POST['semester']);
        $email = htmlspecialchars($_POST['email']);

        // Update student information in the database
        $stmt = $conn->prepare("UPDATE students SET st_name=?, st_dept=?, st_batch=?, st_sem=?, st_email=? WHERE st_id=?");
        $stmt->bind_param("sssssi", $name, $dept, $batch, $semester, $email, $sid);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $success_msg = 'Updated successfully';
        } else {
            throw new Exception("Failed to update record");
        }

        $stmt->close();
    }

    // Fetch student details for editing
    if (isset($_POST['sr_btn'])) {
        $sr_id = $_POST['sr_id'];
        $stmt = $conn->prepare("SELECT * FROM students WHERE st_id=?");
        $stmt->bind_param("i", $sr_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($data = $result->fetch_assoc()) {
            // Display the form with pre-filled data for updating
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Attendance Management System 1.0 - Update Account</title>
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<header>
    <h1>Online Attendance Management System 1.0</h1>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="students.php">Students</a>
        <a href="report.php">My Report</a>
        <a href="account.php">My Account</a>
        <a href="../logout.php">Logout</a>
    </div>
</header>

<center>
    <div class="row">
        <div class="content">
            <h3>Update Account</h3>
            <br>
            <p>
                <?php
                if (!empty($success_msg)) {
                    echo $success_msg;
                }
                if (!empty($error_msg)) {
                    echo $error_msg;
                }
                ?>
            </p>
            <br>
            <form method="post" action="" class="form-horizontal col-md-6 col-md-offset-3">
                <div class="form-group">
                    <label for="input1" class="col-sm-3 control-label">Registration No.</label>
                    <div class="col-sm-7">
                        <input type="text" name="sr_id" class="form-control" id="input1"
                               placeholder="Enter registration number"/>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary col-md-3 col-md-offset-7" value="Go!" name="sr_btn"/>
            </form>
            <div class="content"></div>

            <?php if (!empty($data)) : ?>
                <form action="" method="post" class="form-horizontal col-md-6 col-md-offset-3">
                    <table class="table table-striped">
                        <tr>
                            <td>Registration No.:</td>
                            <td><?php echo htmlspecialchars($data['st_id']); ?></td>
                        </tr>
                        <tr>
                            <td>Student's Name:</td>
                            <td><input type="text" name="name" value="<?php echo htmlspecialchars($data['st_name']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Department:</td>
                            <td><input type="text" name="dept" value="<?php echo htmlspecialchars($data['st_dept']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Batch:</td>
                            <td><input type="text" name="batch" value="<?php echo htmlspecialchars($data['st_batch']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Semester:</td>
                            <td><input type="text" name="semester" value="<?php echo htmlspecialchars($data['st_sem']); ?>"></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><input type="text" name="email" value="<?php echo htmlspecialchars($data['st_email']); ?>"></td>
                        </tr>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['st_id']); ?>">
                        <tr>
                            <td></td>
                            <td><input type="submit" class="btn btn-primary col-md-3 col-md-offset-7" value="Update" name="done"></td>
                        </tr>
                    </table>
                </form>
            <?php endif; ?>
        </div>
    </div>
</center>
</body>
</html>
<?php
        } else {
            $error_msg = "No student found with this registration number.";
        }
        $stmt->close();
    }
} catch (Exception $e) {
    $error_msg = $e->getMessage();
}
?>
