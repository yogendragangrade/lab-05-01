<?php header('Content-Type: application/json');


$servername = 'inventory-db-instance-1.cvvq9b4jiajl.us-east-1.rds.amazonaws.com';
$username = 'master';
$password = 'lab-password';
$database = 'inventory';
$table = 'products';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die(json_encode([
    	'error' => true,
    	'message' => 'Database connection failed, ' . mysqli_connect_error()
    ]));
}


// http://localhost:2001/data.php?operation=get
if ($_GET['operation'] === 'get') {
	$sql = 'SELECT * from ' . $table;

	$result = $conn->query($sql);

	$products = [];

    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

	die(json_encode([
		'error' => false,
		'products' => $products
	]));


// http://localhost:2001/data.php?operation=add&product_name=soap&product_quantity=5&product_price=45
} elseif ($_GET['operation'] === 'add') {
	if (!isset($_GET['product_name']) || !isset($_GET['product_quantity']) || !isset($_GET['product_price'])) {
		die(json_encode([
	    	'error' => true,
	    	'message' => 'one or more missing params'
	    ]));
	}

	$sql = 'INSERT into ' . $table . '(name, quantity, price) values("'. htmlentities($_GET['product_name']) . '", "' . htmlentities($_GET['product_quantity']) . '", "' . htmlentities($_GET['product_price']) . '")';

	if ($conn->query($sql) === true) {
		die(json_encode([
			'error' => false,
			'message' => 'Data inserted successfully'
		]));
	} else {
		die(json_encode([
			'error' => true,
			'message' => mysqli_error($conn)
		]));
	}


// http://localhost:2001/data.php?operation=update&product_id=1&product_name=pant&product_quantity=5&product_price=45
} elseif ($_GET['operation'] === 'update') {
	if (!isset($_GET['product_id']) || !isset($_GET['product_name']) || !isset($_GET['product_quantity']) || !isset($_GET['product_price'])) {
		die(json_encode([
	    	'error' => true,
	    	'message' => 'one or more missing params'
	    ]));
	}

	$sql = 'UPDATE ' . $table . ' SET name="'. htmlentities($_GET['product_name']) . '", quantity="' . htmlentities($_GET['product_quantity']) . '", price="' . htmlentities($_GET['product_price']) . '" WHERE id = ' . htmlentities($_GET['product_id']);

	if ($conn->query($sql) === true) {
		die(json_encode([
			'error' => false,
			'message' => 'Data updated successfully'
		]));
	} else {
		die(json_encode([
			'error' => true,
			'message' => mysqli_error($conn)
		]));
	}


// http://localhost:2001/data.php?operation=delete&product_id=1
} elseif ($_GET['operation'] === 'delete') {
	if (!isset($_GET['product_id'])) {
		die(json_encode([
	    	'error' => true,
	    	'message' => 'one or more missing params'
	    ]));
	}

	$sql = 'DELETE FROM ' . $table . ' WHERE id=' . htmlentities($_GET['product_id']);

	if ($conn->query($sql) === true) {
		die(json_encode([
			'error' => false,
			'message' => 'Data deleted successfully'
		]));
	} else {
		die(json_encode([
			'error' => true,
			'message' => mysqli_error($conn)
		]));
	}
	
} else {
	die(json_encode([
		'error' => true,
		'message' => 'invalid/missing operation param'
	]));
}

// close connection
mysqli_close($conn);