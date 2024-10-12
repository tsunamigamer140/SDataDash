<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="5" >
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>

	<title> Sensor Data </title>
</head>

<body>
    <a href="http://localhost/path/to/your/home.php">Home</a>
    <h1>SENSOR DATA</h1>
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

$sql = "SELECT * FROM sensors ORDER BY sens_id ASC"; /*select items to display from the sensordata table in the data base*/

echo '<table cellspacing="5" cellpadding="5">
      <tr> 
        <th>Sensor ID</th> 
        <th>Sensor Type</th> 
        <th>Network ID</th>     
        <th>Plant ID</th>
      </tr>';
 
if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_sens_id = $row["sens_id"];
        $row_type = $row["type"];
        $row_net_id = $row["net_id"];
        $row_plant_id = $row["plant_id"];
        
        // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
       // $row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));
      
        // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 4 hours"));
      
        echo '<tr> 
				<td>' . $row_sens_id . '</td> 
                <td>' . $row_type . '</td> 
                <td>' . $row_net_id . '</td>  
                <td>' . $row_plant_id . '</td> 
              </tr>';
    }
    $result->free();
}

$conn->close();
?> 
</table>

</body>
</html>