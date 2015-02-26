<?php


function databaseConnect(){
	
	$servername = "mysql6.000webhost.com";
	$username = "a8960111_tetris";
	$password = "1234pass";
	// Create connection
	$conn = mysqli_connect($servername, $username, $password);
	// Check connection
	if (!$conn) {
    	die("Connection failed: " . mysqli_connect_error());
	}


// Create database
	$sql = "CREATE DATABASE IF NOT EXISTS a8960111_tetris";
	if (mysqli_query($conn, $sql)) {
	    //echo "Database";
	} 
	else {
	    echo "Error creating database: " . mysqli_error($conn);
	}

	// sql to create table
	$sql = "CREATE TABLE IF NOT EXISTS `a8960111_tetris`.`scores` (
	  `id` INT NOT NULL AUTO_INCREMENT,	
	  `name` VARCHAR(255) NOT NULL,
	  `score` INT NOT NULL,
	  PRIMARY KEY (`id`))";
	
	if ($conn->query($sql) === TRUE) {
	    //echo "Table";
	} 
	
	else {
	    echo "Error creating table: " . $conn->error;
	}
	
	return $conn;
}
function findMinScore(){
	$conn = databaseConnect();
	$sql = "SELECT MIN(score) as MIN FROM `a8960111_tetris`.`scores`";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output
	   $row = $result->fetch_assoc();
	   $min = 	$row["MIN"];
	   //echo $min."<br>";
	   return $min;
	    
	}
	closeConnection($conn);
}

function findNumberRecords(){
	$conn = databaseConnect();
	$sql = "SELECT count(score) as COUNT FROM `a8960111_tetris`.`scores`";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output
		$row = $result->fetch_assoc();
		$count = $row["COUNT"];
		//echo $count."<br>";
	
	}
	return $count;
}

function deleteMin($min, $score, $conn){
		$sql = "DELETE FROM `a8960111_tetris`.`scores` 
		WHERE score = '$min' ORDER BY id LIMIT 1";
		if ($conn->query($sql) === TRUE) {
			//echo "Record deleted successfully<br>";
		} 
		else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
}
	
function insertNew($name, $score,$conn){
		
	$sql = "INSERT INTO `a8960111_tetris`.`scores` (name, score)
	VALUES ('$name', '$score')";

	if ($conn->query($sql) === TRUE) {
		//echo "New record created successfully<br>";
	} 
	else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	
}

function displayRecords(){
	$conn = databaseConnect();
	$sql = "SELECT name, score FROM `a8960111_tetris`.`scores` ORDER BY score DESC, id ASC";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		$i=1;
		echo '<div class="vertical" id="table"><div class="top"></div><table>';
		echo '<tr><th>Rank</th><th>Name</th><th>Score</th></tr>';
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo '<tr>';
			echo '<td>',$i++,'.</td>';
			echo '<td>',$row["name"],'</td>';
			echo '<td>',$row["score"],'</td></tr>';
		}
		echo '</table><button class="divButtons" onclick="window.open(\'index.html\',\'_self\')" id = "startButton">Back to Tetris</button></div>';
	
	}
	else {
		echo "No High Scores here";
	}
	
	closeConnection($conn);
}

function closeConnection($conn){
	$conn->close();
}

function handleScore($name, $score){
	
	//echo "In SCORES";
	$conn = databaseConnect();
	
	$min = findMinScore();
	$count = findNumberRecords();

	
	
	if ($score > $min && $count >= 10){
		//echo "deleting...";
		deleteMin($min, $score, $conn);
	}
	
	if ($score > $min || $count < 10 ){
		insertNew($name, $score, $conn);
	}
	closeConnection($conn);
}


$score = 0;
$name = "";


if(!empty($_POST["score"]) && !empty($_POST["name"])) {
	$score = $_POST["score"];
	$name = $_POST["name"];

	handleScore($name, $score);
		
	header("Location: scores.php", true, 303);
	exit();
	
}


?>

<!doctype html>

<html lang="en">
<head>
<title>Tetris Scores by Kseniya Serbina</title>
</head>
<style>
div.pos_fixed {
    position: fixed;
    top: 50px;
    left: 250px;
	padding: 50px;
	border-width: 2px;
	border-style: solid;
	border-color: #dd2685;
	background-color: white;
	margin-left: 20px;
	margin-top: 5px;
	border-radius: 5px;
	text-align: center;
	font-size: large;
	color: #2685dd;
	font-family: Georgia;
	vertical-align: text-top;
}

#table {
    float: left;
	background-color: white;
    text-align:center;
    width: auto;
}

table{
	padding: 50px;
}
th {
    padding-right: 20px;
    padding-left: 20px;
	padding-bottom: 20px;
    font-family: Georgia;
	font-size: 20px;
	color: #dd2685;
}
td{
	padding: 5px;
	font-family: Georgia;
	font-size: 20px;
	color: #2685dd;
}

.vertical{
	float: left;
	background-color: white;
	width: 225px;
	height: 500px
}
.divButtons{
	width: 320px;
	height: 50px;
	border-width: 1px;
	border-color: #dd2685;
	background-color: white;
	margin-left: 20px;
	margin-top: 5px;
	margin-bottom: 5px;
	border-radius: 5px;
	text-align: center;
	font-size: large;
	padding-top: 5px;
	color: #dd2685;
	font-family: Georgia;
}

.divButtons:hover {
	color: 	white;
	background-color: #dd2685;
}
.top {
	height: 20px;
}
</style>
<body>

<!-- left-->
	<div class="vertical">
		<div class="top"></div>
		<div class="top"></div>

		

	</div>

	

<?php 
	displayRecords();
?>
<div class="vertical">
		<div class="top"></div>
		<div class="top"></div>

		

	</div>
</body>
</html>

