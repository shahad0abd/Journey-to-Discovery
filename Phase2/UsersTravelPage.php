<?php
require_once 'database.php';
session_start();

   # $userID = $user['id'];
   # echo $userID;
    
 if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); 
    exit();
} else{
    $user_id = $_SESSION['user_id'];
    $sqluser = "SELECT * FROM User WHERE id =".$_SESSION['user_id'];
    $result = mysqli_query($connection, $sqluser);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);}
}
$sqlTravel = "SELECT * FROM Travel WHERE userID=".$_SESSION['user_id'];
$result2 = mysqli_query($connection, $sqlTravel);


?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>User's Travel Page</title>
		<style>
		@import url("usertravel.css");
		</style>
	</head>
	
	<body>
	
		<header>
			<nav>
                            <?php
                            echo "<h4 id='name'>".$user['firstName']."'s Travels </h4>"
                            ?>
			
			
			<ul class="links">
				<li><a href="User’shomepage.php">Back to Homepage</a></li>
                                <li><a href="index.php">Log Out</a></li>
			</ul>
			
			</nav>
		</header>
		<main>
		<div class="main">
		<div class="caption">
			<h2>All Travels</h2>
                        <a href="AddNewTravel1.php" class="addLink">Add New Travel</a>
			</div>
			<div class="table-container">
			<table>
				
				<thead>
				<tr>
					<th rowspan="2" class="firstthree">Travel</th>
					<th rowspan="2" class="firstthree">Travel Time</th>
					<th rowspan="2" class="firstthree">Country</th>
					<th colspan="6">Places</th>
				</tr>
				<tr>
					<th>Place Name</th>
					<th>Location</th>
					<th>Description</th>
					<th>Photo</th>
					<th>Likes</th>
					<th>Comments</th>
				</tr>
				</thead>
				<tbody>
				
				<tr>
                                    <?php
                                    if($result2 && mysqli_num_rows($result2) > 0){
                                        $index = 1;
                                   while($travel = mysqli_fetch_assoc($result2)){
                                       
                                        $sqlCountry = "SELECT * FROM Country WHERE id=".$travel['countryID'];
                                        $result3 = mysqli_query($connection, $sqlCountry);
                                        $country = mysqli_fetch_assoc($result3);
                                        
					echo "<td rowspan='2'>{$index}<br><br>";
					echo "<a href='' class='editLinks'>Edit Travel Details</a><br>";
                                        echo "-<br>";
					echo "<a href='' class='editLinks'>Delete Travel</a></td>";
                                        echo "<td rowspan='2'>".$travel['month']."<br>".$travel['year']."</td>";
					echo "<td rowspan='2'>".$country['country']."</td>";
                                        
                                        
                                        $sqlPlace = "SELECT * FROM Place WHERE travelID=".$travel['id'];
                                        $result4 = mysqli_query($connection, $sqlPlace);
                                        
                                        if($result4 && mysqli_num_rows($result4) > 0){
                                            while($place = mysqli_fetch_assoc($result4)){
                                                echo "<td>".$place['name']."</td>";       
                                                echo "<td>".$place['location']."</td>";
                                                echo "<td>".$place['description']."</td>";
                                                echo "<td><img src=".$place['photoFileName']." alt='Mountain Fuji' width='80' ></td>";
                                                $sqlLikes = "SELECT COUNT(*) AS likeCount FROM `Like` WHERE placeID=".$place['id'];
                                                $result5 = mysqli_query($connection, $sqlLikes);
                                                $likes = mysqli_fetch_assoc($result5);
                                                echo "<td>&#9829; ".$likes['likeCount']."</td>";
                                                $allComments = "";
                                                $sqlComments = "SELECT * FROM `Comment` WHERE placeID = ".$place['id'];
                                                $commentsResult = mysqli_query($connection, $sqlComments);
                                       
                                                while($comment = mysqli_fetch_assoc($commentsResult)){
                                                    $sqlUserName = "SELECT u.firstName 
                                                                    FROM `User` u
                                                                    WHERE u.id = ".$comment['userID'];

                                                    $userResult = mysqli_query($connection, $sqlUserName);
                                                    $userNames = mysqli_fetch_assoc($userResult);
                                                    $allComments .= $userNames['firstName'] . ":<br>" . $comment['comment'] . "<br><br>";
                                                }
                                                echo "<td>" . $allComments . "</td>";
                                        
                                        }}
                                        $index++;
                                    }}
                                    else {
                                         echo "<tr><td colspan='9'>No travels found.</td></tr>";
                                         }
                                    ?>
				</tr>
				
				
				
				</tbody>
			</table>
			</div>
		</div>
		</main>

	</body>

</html>

<?php
mysqli_close($connection);
?>