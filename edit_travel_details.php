<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Travel Details</title>
    <link rel="stylesheet" href="edit_travel_details.css">
</head>
<body>

    <div class="container">
        <h1>Edit Travel Details</h1>
        <form action="User’shomepage.html" method="post">
            <div class="section">
                <label for="travel-time-month">Travel Time:</label>
                <select id="travel-time-month" name="travel-time-month">
                    <option value="July">July</option>
                </select>
                <select id="travel-time-year" name="travel-time-year">
                    <option value="2024">2024</option>
                </select>
            </div>

            <div class="section">
                <label for="country">Country:</label>
                <select id="country" name="country">
                    <option value="Japan">Japan</option>
                </select>
            </div>

            <div class="section">
                <h2>Place 1</h2>
                <label for="place-name">Place Name:</label>
                <input type="text" id="place-name" name="place-name" value="Mount Fuji">
                
                <label for="location-city">Location/City:</label>
                <input type="text" id="location-city" name="location-city" value="Honshu">
                
                <label for="description">Description:</label>
                <textarea id="description" name="description">A beautiful volcanic mountain in the Japanese island of Honshu.</textarea>
                
                <div class="photo-upload-section">
                    <label for="upload-photo">Upload Photo:</label>
                    <input type="file" id="upload-photo" name="upload-photo">
                    
                    <div class="current-photo">
                        <label>Current Photo:</label>
                        <img src="\images\MountFuji.jpg" alt="Current Travel Photo">
                    </div>
                </div>
            </div>

            <input type="submit"></input>
        </form>
    </div>
</body>
</html>