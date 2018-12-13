<?php
header('Content-Type: application/json');

$response = array();

require_once '../conf/db_connect.php';
$con = new DB_CONNECT();
$con = $con->get_connect();

$result = $con->query("SELECT * FROM orders");

if($con->error) {
	die('Unable to read row from DB [' . $con->error . ']');
}

if($result->num_rows > 0) {
	$response["orders"] = array();
	
	while ($row = mysqli_fetch_array($result)) {
        $order = array();
		$user = array();
		$car = array();
		
        $order["order_id"] = $row["order_id"];
        $order["start_date"] = $row["start_date"];
		$order["end_date"] = $row["end_date"];
		$order["user"] = array();
		$order["car"] = array();
		$car["car_id"] = $row["car_id"];
		$user["user_id"] = $row["user_id"];
		
		if($user["user_id"] != null)
		{
			$userResult = $con->query("SELECT * FROM users WHERE user_id = ".$user["user_id"]);

			if($con->error) {
				die('Unable to read row from DB [' . $con->error . ']');
			}
			$userRow = mysqli_fetch_array($userResult);
			$user = array();
			$user["user_id"] = $userRow["user_id"];
			$user["user_name"] = $userRow["user_name"];
			$user["user_email"] = $userRow["user_email"];
			$user["user_password"] = $userRow["user_password"];

			array_push($order["user"], $user);
		}
		
		if($car["car_id"] != null)
		{
			$carResult = $con->query("SELECT * FROM cars WHERE car_id = ".$car["car_id"]);

			if($con->error) {
				die('Unable to read row from DB [' . $con->error . ']');
			}
			$carRow = mysqli_fetch_array($carResult);
			$car = array();
			$car["car_id"] = $carRow["car_id"];
			$car["car_name"] = $carRow["car_name"];
			$car["car_price"] = $carRow["car_price"];
			
			array_push($order["car"], $car);
		}

        array_push($response["orders"], $order);
    }
	
	$response["success"] = 1;
	
	echo json_encode($response);
} else {
	$response["success"] = 0;
    $response["message"] = "No order found";
 
    echo json_encode($response);
}

?>