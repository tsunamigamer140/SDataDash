<?php
/* Your Database Name */

$DB_NAME = 'plantsensornetwork';

/* Database Host */
$DB_HOST = 'localhost';

/* Your Database User Name and Passowrd */
$DB_USER = 'root';
$DB_PASS = 'AAA';
  /* Establish the database connection */
  $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }

  $sql = "SELECT reading_time, humperc, sens_id FROM humidity ORDER BY reading_time DESC"; /*select items to display from the sensordata table in the data base*/

   /* select all the weekly tasks from the table googlechart */
  $result = $mysqli->query($sql);

  $rows = array();
  $table = array();
  $table['cols'] = array(

    array('label' => 'Time', 'type' => 'string'),
    array('label' => 'Sensor', 'type' => 'number'),
    array('label' => 'Humidity', 'type' => 'number'));

    /* Extract the information from $result */
    foreach($result as $r) {

      $temp = array();

      // The following line will be used to slice the Pie chart

      $temp[] = array('v' => (string) $r['reading_time']); 
      $temp[] = array('v' => (int) $r['sens_id']); 
      $temp[] = array('v' => (int) $r['humperc']); 

      $rows[] = array('c' => $temp);
    }

$table['rows'] = $rows;

// convert data into JSON format
$jsonTable = json_encode($table);
//echo $jsonTable;


?>



<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="5" >
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>

	<title> Humidity Data </title>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable(<?=$jsonTable?>);

        var options = {
          title: 'Company Performance',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>

</head>

<body>
    <a href="http://localhost/path/to/your/home.php">Home</a>
    <h1>HUMIDITY DATA</h1>
    
    <div id="chart_div"></div>

<?php
echo '<table cellspacing="5" cellpadding="5">
      <tr> 
        <th>Date $ Time</th> 
        <th>Sensor ID</th> 
        <th>Humidity &#37;</th>     
      </tr>';

      $jsonIterator = new RecursiveIteratorIterator(
        new RecursiveArrayIterator(json_decode($jsonTable, TRUE)),
        RecursiveIteratorIterator::SELF_FIRST);
        
    foreach ($jsonIterator as $key => $val) {
        if($key == 'c'){
            echo '<tr>';
            foreach ($val as $kkey => $vval){
                foreach ($vval as $kkkey => $vvval){
                    echo '<td>'.$vvval.'</td>';
                }
            }
            echo '</tr>';
        }


        #echo '<tr> 
		#		<td>' . '</td> 
         #       <td>' . '</td> 
          #      <td>' . '</td>   
           #   </tr>';

    }
?> 
</table>

</body>
</html>