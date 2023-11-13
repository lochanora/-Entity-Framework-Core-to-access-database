<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<?php
    include 'Lab5Common/Class_Lib.php';
    session_start();   
    include 'Lab5Common/Functions.php';  
    $validatorError = "";
    $selectedCourse = array();
    
    if ($_SESSION['studentIdTxt'] == null)
    {
        $_SESSION['activePage'] = "CurrentRegistration.php";
        header('Location: Login.php');
        exit;
    }
    
    //Connection to DBO
    $dbConnection = parse_ini_file("Lab5Common/db_connection.ini");        	
    extract($dbConnection);
    $myPdo = new PDO($dsn, $user, $password);
    
    //submit button
    if(isset($_POST['submit']))
    {  
        if (isset($_POST['selectedCourse']))
        { 
            foreach ($_POST['selectedCourse'] as $row) 
            {
                
                $sql = "DELETE FROM registration WHERE registration.CourseCode = :courseCode ";  
                $pStmt = $myPdo->prepare($sql);
                $pStmt->execute(array(':courseCode' => $row));
                $pStmt->commit;      
            }
        }
        else 
        {
            $validatorError = "You must select at least one checkbox!";
        }                
    }
    include 'Lab5Common/Header.php';   
?>



<?php     
    include 'Lab5Common/Footer.php';
?>