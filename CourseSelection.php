<?php error_reporting (E_ALL ^ E_NOTICE); ?>

<?php
    include 'Lab5Common/Class_Lib.php';
    session_start();
    include 'Lab5Common/Footer.php';
    include 'Lab5Common/Header.php';
    include 'Lab5Common/Functions.php';
    $validatorError = "";
    
    if ($_SESSION['studentIdTxt'] == null)
    { 
        $_SESSION['activePage'] = "CourseSelection.php";        
        header('Location: Login.php');
        exit;
    }
    

    $dbConnection = parse_ini_file("Lab5Common/db_connection.ini");        	
    extract($dbConnection);
    $myPdo = new PDO($dsn, $user, $password);
    

    $sql = "SELECT * FROM Semester ";
    $pStmt = $myPdo->prepare($sql); 
    $pStmt->execute(); 
    

    foreach ($pStmt as $row)
    {
         $term = array( $row['SemesterCode'], $row['Year'], $row['Term']  );
         $termsArray[] = $term;
    }
    $_SESSION['termsArraySession'] = $termsArray;       
    
      
    $sql = "SELECT Course.CourseCode CourseCode, Title, WeeklyHours "
            . " FROM Course INNER JOIN Registration "
            . " ON Course.CourseCode = Registration.CourseCode "
            . " INNER JOIN semester ON registration.SemesterCode = semester.SemesterCode "
            . " WHERE Registration.StudentID = :studendId AND semester.SemesterCode = :semesterCode ";
    $pStmt = $myPdo->prepare($sql);
    $pStmt->execute (array(':studendId' => $_SESSION['studentIdTxt'], ':semesterCode' => $_POST['selectTerm']));
    $courseById = $pStmt->fetchAll();
    $totalRegisteredHours = 0;            
    foreach ($courseById as $row)
    {
        $totalRegisteredHours = $totalRegisteredHours + $row[2];
    }
    $_SESSION['totalRegisteredHours'] = $totalRegisteredHours; 
       
    
    if(isset($_POST['submit1']))
    {
        if (isset($_POST['selectedCourse']))
        { 
           
            foreach ($_POST['selectedCourse'] as $row)
            {
                $sql = "SELECT WeeklyHours FROM Course WHERE CourseCode = :courseCode";
                $pStmt = $myPdo->prepare($sql);
                $pStmt->execute([':courseCode' => $row]);
                $courseHours = $pStmt->fetch();
                $totalRegisteredHours = $totalRegisteredHours + $courseHours[0]; //total of hours user is trying to register for
            }
            
            if ($totalRegisteredHours <= 16) 
            {
                foreach ($_POST['selectedCourse'] as $row)
                {
                    $sql = "INSERT INTO registration VALUES (:StudentID, :CourseCode, :SemesterCode)";
                    $pStmt = $myPdo->prepare($sql); 
                    $pStmt->execute(array( ':StudentID'=> $_SESSION['studentIdTxt'] , ':CourseCode' => $row, ':SemesterCode' => $_POST['selectTerm']));
                    $pStmt->commit;
                }
                $_SESSION['totalRegisteredHours'] = $totalRegisteredHours; 

            }
            else 
            {
                $validatorError = "Your selection exceeds the maximum weekly hours!";
            }
        }
        else 
        {
            $validatorError = "You need to select at least one course!";
        }   
    }

?>
    
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
                  
    </body>
</html>
