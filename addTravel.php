<?php
require_once 'database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $user_id = $_SESSION['user_id'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $country = $_POST['country'];

    $countryQuery = $connection->prepare("SELECT id FROM Country WHERE country = ?");
    $countryQuery->bind_param("s", $country);
    $countryQuery->execute();
    $countryResult = $countryQuery->get_result();

    if ($countryResult->num_rows > 0) {
        $countryID = $countryResult->fetch_assoc()['id'];

        $stmtInsert = $connection->prepare("INSERT INTO Travel (userID, month, year, countryID) VALUES (?, ?, ?, ?)");
        $stmtInsert->bind_param("issi", $user_id, $month, $year, $countryID);
        if ($stmtInsert->execute()) {
            $newTravelID = $connection->insert_id;
            echo '
                <form id="redirectForm" action="AddNewTravel2.php" method="post">
                    <input type="hidden" name="travel_id" value="' . htmlspecialchars($newTravelID) . '">
                </form>
                <script>document.getElementById("redirectForm").submit();</script>';
            exit();
        }
    }
    echo "Country not found or error adding travel.";
}
?>
