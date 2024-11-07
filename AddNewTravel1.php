<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Travel</title>
    <link rel="stylesheet" href="usertravel.css">
</head>
<body>
    <main>
        <div class="NewTravel">
            <h1>New Travel</h1>
            <form class="newTravel" method="POST" action="addTravel.php">
                <div class="travelMonth">
                    <label for="travelTime">Travel Time: 
                        <select name="month" id="travelTime" required>
                            <option disabled selected>Select month</option>
                            <!-- Months options -->
                            <?php
                            $months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            foreach ($months as $month) {
                                echo "<option value=\"$month\">$month</option>";
                            }
                            ?>
                        </select>
                        <select name="year" required>
                            <option disabled selected>Select year</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                        </select>
                    </label>
                </div>
                <div class="Country">
                    <label for="Country">Country: 
                        <select name="country" id="Country" required>
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
