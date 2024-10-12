<?php
$servername = "localhost";
$username = "root";
$password = "AAAAA";    //replace with MyPHPAdmin password
$dbname = "BBBBB";      //replace with database name

$api_key_value = "XXXXXXXXXXXX";    ///insert an API key (not dynamically generated)

$api_key= $sensor = $senType = $network = $plant = $location = $value1 = $value2 = $value3 = "";  //initialise local variables to an empty string

if ($_SERVER["REQUEST_METHOD"] == "POST") { //checking for a POST message
    $api_key = $_POST;

    $log = "API KEY: ".$api_key.PHP_EOL;    //loggin API key
    //Save string to log, use FILE_APPEND to append.
    file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND); //creating/appending to a .log file with current date

    if($api_key == $api_key_value) {    //comparing API key value from HTTP POST request
        $sensor = test_input($_POST["sensor"]); 
        $senType = test_input($_POST["senType"]);
        $network = test_input($_POST["network"]);
        $plant = test_input($_POST["plant"]);
        $value1 = test_input($_POST["value1"]);
        $value2 = test_input($_POST["value2"]);
        $value3 = test_input($_POST["value3"]); //the above lines of code gets the sensor parameters and data by passing it through the test_input() function
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sencheck = "SELECT * FROM sensors WHERE sens_id='".$sensor."'";    //checking if the sensor ID has been created in the 'sensor' table in the database

        $result = $conn->query($sencheck);

        if (mysqli_num_rows($result) === 0) {                               //if no sensor found, then:
            echo "New Sensor detected!";
            $senew = "INSERT INTO sensors (sens_id, type, net_id, plant_id) VALUES ('".$sensor."','".$senType."','".$network."','".$plant."')";  //create new record for the sensor
            
            if ($conn->query($senew) === TRUE) {
                echo "New sensor created successfully!";

                //below, we create new columns for each sensor in each of the temperature, humidity and pressure tables and then insert the data

                $temp = "ALTER TABLE temp ADD".$sensor."int(6)";    

                if($conn->query($temp) === TRUE) {
                    echo "New temperature column created";
                }

                $temp = "ALTER TABLE humidity ADD".$sensor."int(6)";

                if($conn->query($temp) === TRUE) {
                    echo "New humidity column created";
                }

                $temp = "ALTER TABLE pressure ADD".$sensor."int(6)";

                if($conn->query($temp) === TRUE) {
                    echo "New pressure column created";
                }
                
            } 
            else {
                echo "Error: " . $senew . "<br>" . $conn->error;
            }
         } else { //if sensor does exist in the 'sensor' table:
            //$tempval = (((int)$value1-32) * (5/9)); converts temperature from fahrenheit to celsius
		
            $sql1 = "INSERT INTO temp (".$sensor.")
            VALUES ('" . $value1 . "')";
            
            $sql2 = "INSERT INTO humidity (".$sensor.")
            VALUES ('" . $value2 . "')";
            
            $sql3 = "INSERT INTO pressure (".$sensor.")
            VALUES ('" . $value3 . "')";
            
            if ($conn->query($sql1) === TRUE) {
                echo "New temp created successfully";
            } 
            else {
                echo "Error: " . $sql1 . "<br>" . $conn->error;
            }
            
            if ($conn->query($sql2) === TRUE) {
                echo "New humidity created successfully";
            } 
            else {
                echo "Error: " . $sql2 . "<br>" . $conn->error;
            }
            
            if ($conn->query($sql3) === TRUE) {
                echo "New pressure created successfully";
            } 
            else {
                echo "Error: " . $sql3 . "<br>" . $conn->error;
            }

            $conn->close();
        }  
    }
    else {  //if wrong api key in HTTP POST message
        echo "Wrong API Key provided.";
    }

}
else {
    echo $_SERVER["REQUEST_METHOD"];
    echo "No data posted with HTTP POST.";
}

function test_input($data) {        //takes in a segment of the HTTP POST message and trims trailing and heading spaces, strips slashes and special HTML characters, and return the resultant string
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}