<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="5" >
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>

	<title> Pressure Data </title>

</head>

<body>
    <a href="http://localhost/path/to/your/home.php">Home</a>
    <h1>PRESSURE DATA</h1>
<?php
$servername = "localhost";
$username = "root";
$password = "AAA";
$dbname = "BBB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT reading_time, press, sens_id FROM pressure ORDER BY reading_time DESC"; /*select items to display from the sensordata table in the data base*/

echo '<table cellspacing="5" cellpadding="5">
      <tr> 
        <th>Date $ Time</th> 
        <th>Pressure</th> 
        <th>Sensor ID</th>     
      </tr>';
 
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_reading_time = $row["reading_time"];
        $row_sensor = $row["sens_id"];
        $row_press = $row["press"];
        
        // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
       // $row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));
      
        // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 4 hours"));
      
        echo '<tr> 
				<td>' . $row_reading_time . '</td> 
                <td>' . $row_press . '</td> 
                <td>' . $row_sensor . '</td>   
              </tr>';
    }
    $result->free();
}

$conn->close();
?> 
</table>

</body>
</html>