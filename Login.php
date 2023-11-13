<?php
error_reporting(E_ALL);
include 'Lab5Common/Class_Lib.php';
session_start();
include 'Lab5Common/Header.php';
include 'Lab5Common/Functions.php';

if (isset($_SESSION['studentId']) && isset($_SESSION['nameTxt'])) {
    header('Location: CourseSelection.php');
    exit;
}

$studentIdTxt = $_POST["studentIdTxt"] ?? '';
$passwordTxt = $_POST["passwordTxt"] ?? '';
$studentIdError = "";
$passwordError = "";
$validateError = "";


if (isset($_POST['submit'])) {
    
    if (empty($studentIdTxt)) {
        $studentIdError = "Student ID cannot be blank!";
    }

    if (empty($passwordTxt)) {
        $passwordError = "Password cannot be blank!";
    }

    if (empty($studentIdError) && empty($passwordError)) {
        $dbConnection = parse_ini_file("Lab5Common/db_connection.ini");
        extract($dbConnection);
        $myPdo = new PDO($dsn, $user, $password);

        $sqlStatement = "SELECT * FROM Student WHERE BINARY StudentId = :PlaceHolderStudentID AND Password = :PlaceHolderPassword";
        $pStmt = $myPdo->prepare($sqlStatement);
        $pStmt->execute(array(':PlaceHolderStudentID' => $studentIdTxt, ':PlaceHolderPassword' => $passwordTxt));
        $chkAccount = $pStmt->fetch();

        if ($chkAccount) {
            $_SESSION['studentId'] = $chkAccount['StudentId'];
            $_SESSION['nameTxt'] = $chkAccount['Name'];

            if ($_SESSION['activePage'] == "CurrentRegistration.php") {
                header('Location: CurrentRegistration.php');
                exit;
            } else {
                header('Location: CourseSelection.php');
                exit;
            }
        } else {
            $validateError = "Incorrect ID and/or password!";
        }
    }
}

if (isset($_POST['clear'])) {
    $studentIdTxt = '';
    $passwordTxt = '';
}

include 'Lab5Common/Footer.php';
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<h1>&nbsp &nbsp &nbsp Log In</h1><br>
<h4>&nbsp &nbsp You need to <a href="NewUser.php">sign up</a> if you are a new user!</h4><br> <br>

<form method='post' action="Login.php">

    <div class='col-lg-4' style='color:red'> <?php print $validateError; ?></div><br>
    <div class='form-group row'>
        <label for='studentId' class='col-lg-2 col-form-label'><b>&nbsp &nbsp Student ID:</b> </label>
        <div class='col-lg-4'>
            <input type='text' class='form-control' id='studentIdTxt' value='<?php print $studentIdTxt; ?>' name='studentIdTxt'>
        </div>
        <div class='col-lg-4' style='color:red'> <?php print $studentIdError; ?></div>
    </div><br>

    <div class='form-group row'>
        <label for='password' class='col-lg-2 col-form-label'><b>&nbsp &nbsp Password:</b> </label>
        <div class='col-lg-4'>
            <input type='password' class='form-control' id='passwordTxt' value='<?php print $passwordTxt; ?>' name='passwordTxt'>
        </div>
        <div class='col-lg-4' style='color:red'> <?php print $passwordError; ?></div>
    </div><br>

    <div class='form-group row'>
        <button type='submit' name='submit' class='btn btn-primary col-lg-1'>Submit</button>
        <div class='col-lg-10'>
            <button type='submit' name='clear' class='btn btn-primary col-lg-1'>Clear</button>
        </div>
    </div>
</form>

</body>
</html>
