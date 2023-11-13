<?php
    // Index.php

    include 'Lab5Common/Class_Lib.php';
    session_start();
    include 'Lab5Common/Header.php';
    include 'Lab5Common/Footer.php';
    include 'Lab5Common/Functions.php';

    
    if (isset($_SESSION['studentId'])) {
        
        $navbarLink = '<a href="Logout.php">Logout</a>';
    } else {
        
        $navbarLink = '<a href="Login.php">Login</a>';
    }
?>

<html>
<head>
    <title>Algonquin College Bookstore</title>
    <link rel="stylesheet" type="text/css" href="Contents/BookStore.css" />
</head>
<body>
    <h2>&nbsp Welcome to Algonquin College Online Course Registration</h2><br>
    <p>&nbsp &nbsp If you have never used this before, you need to <a href="NewUser.php">sign up</a> first.</p>
    <p>&nbsp &nbsp If you have already signed up, you can <?php echo $navbarLink; ?> now.</p>

</body>
</html>
