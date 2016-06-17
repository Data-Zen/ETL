<?php
$servername = "158.85.128.197";
$username = "pbpaul";
$password = "3gPJfsaP";
$dbname = "buypuppy_manager";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM buypuppy_manager.active_sites;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
     // output data of each row
     while($row = $result->fetch_assoc()) {
         echo "\nState: ". $row["state"]. " - Breed: ". $row["breed"]. " - Active: " . $row["active"] . "<br>";
     }
} else {
     echo "0 results";
}

$conn->close();
?>  
