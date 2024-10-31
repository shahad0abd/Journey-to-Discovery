<?php

require_once 'database.php';
session_start(); 
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
    
if( $_SERVER['REQUEST_METHOD']=="POST"){
       $Month = $_POST['month'];
       $Year = $_POST['year'];
       $Country = $_POST['country'];
       
       $sqlstat = "SELECT id FROM Country WHERE country = '".$Country."'";
       $stmt = mysqli_query($connection, $sqlstat);
       
       if ($stmt && mysqli_num_rows($stmt) > 0){
       $row = mysqli_fetch_assoc($stmt);
            $countryID = $row['id'];
       } else {
        echo "Country not found.";
        exit;
    }
       $stmtInsert = mysqli_prepare($connection, "INSERT INTO Travel (userID, month, year, countryID) VALUES (?, ?, ?, ?)");

       if ($stmtInsert === false) {
        die("Error preparing insert statement: " . mysqli_error($connection));
    }
       mysqli_stmt_bind_param($stmtInsert, "issi", $_SESSION['user_id'], $Month, $Year, $countryID);
       mysqli_stmt_execute($stmtInsert);
       
if (mysqli_stmt_affected_rows($stmtInsert) > 0) {
    echo "Travel details successfully inserted!";
    $newTravelID = mysqli_insert_id($connection);
    
    echo '
        <form id="redirectForm" action="AddNewTravel2.php" method="post">
            <input type="hidden" name="travel_id" value="' . htmlspecialchars($newTravelID) . '">
        </form>
        <script>
            document.getElementById("redirectForm").submit(); 
        </script>
        ';
        exit(); 
} else {
    echo "Error: No rows affected. " . mysqli_stmt_error($stmtInsert);
}
    
}

mysqli_stmt_close($stmtInsert);
mysqli_close($connection);

?>

