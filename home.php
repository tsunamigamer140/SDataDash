<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="5" >
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>

	<title> Sensor Network </title>

    <script type="text/javascript">
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light1", // "light2", "dark1", "dark2"
	animationEnabled: false, // change to true		
	title:{
		text: "Basic Column Chart"
	},
	data: [
	{
		// Change type to "bar", "area", "spline", "pie",etc.
		type: "column",
		dataPoints: [
			{ label: "apple",  y: 10  },
			{ label: "orange", y: 15  },
			{ label: "banana", y: 25  },
			{ label: "mango",  y: 30  },
			{ label: "grape",  y: 28  }
		]
	}
	]
});
chart.render();

}
</script>

</head>

<body>

    <h1>Plant Sensor Network</h1>
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

$sql = "SELECT * FROM plants ORDER BY plant_id ASC"; /*select items to display from the sensordata table in the data base*/

echo '<table cellspacing="5" cellpadding="5">
      <tr> 
        <th><a href="http://localhost/path/to/your/temper.php">Temperature</a></th> 
        <th><a href="http://localhost/path/to/your/hummer.php">Humidity</a></th> 
        <th><a href="http://localhost/path/to/your/pressy.php">Pressure</a></th>     
        <th><a href="http://localhost/path/to/your/sensy.php">Sensors</a></th>
        <th><a href="http://localhost/path/to/your/netter.php">Sensor Networks</a></th>
        <th><a href="http://localhost/path/to/your/planter.php">Plants</a></th>
      </tr>
      </table>';

$sql2 = "SELECT t.reading_time, t.fahr, n.sens_loc, p.pname, t.sens_id FROM temp as t, networks as n, plants as p, sensors as s 
WHERE t.sens_id=s.sens_id and s.net_id = n.network_id and s.plant_id=p.plant_id and t.fahr in (select max(fahr) from temp);";

if ($result = $conn->query($sql2)) {
    $row = $result->fetch_assoc();
    $row_reading_time = $row["reading_time"];
    $row_fahr = $row["fahr"];
    $row_sens_loc = $row["sens_loc"];
    $row_pname = $row["pname"];
    $row_sens_id = $row["sens_id"];
    echo '<p> Max Temperature: ' . $row_fahr . ",\tLocation: " . $row_sens_loc . ",\tPlant: " . $row_pname . ",Time: " . $row_reading_time . '</p>';

}

$sql3 = "SELECT t.reading_time, t.fahr, n.sens_loc, p.pname, t.sens_id FROM temp as t, networks as n, plants as p, sensors as s
WHERE t.sens_id=s.sens_id and s.net_id = n.network_id and s.plant_id=p.plant_id and t.fahr in (select min(fahr) from temp);";

if ($result = $conn->query($sql3)) {
    $row = $result->fetch_assoc();
    $row_reading_time = $row["reading_time"];
    $row_fahr = $row["fahr"];
    $row_sens_loc = $row["sens_loc"];
    $row_pname = $row["pname"];
    $row_sens_id = $row["sens_id"];
    echo '<p> Min Temperature: ' . $row_fahr . ",\tLocation: " . $row_sens_loc . ",\tPlant: " . $row_pname . ",Time: " . $row_reading_time . '</p>';
}
$conn->close();
?> 

<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"> </script>
</body>
</html>