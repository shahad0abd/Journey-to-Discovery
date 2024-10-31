
<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add New Travel</title>
<style>
@import url("usertravel.css");
</style>
</head>

<body>
	
	<main>
		<div class="NewTravel">
			<h1>New Travel</h1>
			<form class="newTravel" method="POST" action="addTravel.php" >
			
			<div class="travelMonth">
				<label for="travelTime">Travel Time: 
				<select name="month" id="travelTime">
					<option disabled selected>Select month</option>
					<option value="January">January</option>
					<option value="February">February</option>
					<option value="March">March</option>
					<option value="April">April</option>
					<option value="May">May</option>
					<option value="June">June</option>
					<option value="July">July</option>
					<option value="August">August</option>
					<option value="September">September</option>
					<option value="October">October</option>
					<option value="November">November</option>
					<option value="December">December</option>
				</select>
				<select name ="year">
					<option disabled selected>Select year</option>
					<option value="2022">2022</option>
					<option value="2023">2023</option>
					<option value="2024">2024</option>
				</select>
				</label>
				</div>
				<div class="Country">
				<label for="Country">Country: 
				<select name="country" id="Country">
				<option disabled selected>Select country</option>
					<option value="Japan">Japan</option>
					<option value="USA">USA</option>
					<option value="Saudi Arabia">Saudi Arabia</option>
					<option value="France">France</option>
				</select>
				</label>
				</div>
				
				<input type="submit" value="Next" id="nextButton">
			</form>
	
		</div>
	</main>
			
</body>
</html>

