<?php
// Set SQL PLUS LOG IN
$connection = oci_connect(getenv('ORACLE_USER'), getenv('ORACLE_PASSWORD'), getenv('ORACLE_CONNECTION_STRING'));

if (!$connection) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Tables Search</title>
    <style>
        #top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            background: #ffffff;
            border-bottom: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        #top-navbar h3 {
            margin-right: 20px;
            color: #003366;
        }
        #top-navbar ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
        }
        #top-navbar ul li {
            margin-right: 10px;
        }
        #top-navbar ul li a {
            padding: 10px;
            color: #003366;
            text-decoration: none;
            font-weight: bold;
        }
        #top-navbar ul li a:hover {
            background-color: #ddd;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
        }
        th {
            text-align: left;
        }
    </style>
</head>
<body>
<div id="left-sidebar">
        <h3>Navigation</h3>
        <ul>
            <li><a href="Home.html">Home</a></li>
            <li><a href="1Customer.php">Customers</a></li>
            <li><a href="5Product.php">Products</a></li>
            <li><a href="2Supplier.php">Suppliers</a></li>
            <li><a href="4Employee.php">Employees</a></li>
            <li><a href="3Store.php">Stores</a></li>
            <li><a href="6Purchase.php">Purchases</a></li>
            <li><a href="7Delivery.php">Deliveries</a></li>
            <li><a href="8Collection.php">Collections</a></li>
        </ul>
    </div>
    <h1>Database Search</h1>
    <form method="get">
        <input type="text" name="search" placeholder="Enter search terms..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
<?php
if ($search !== '') {
    echo "<h2>Results for '". htmlspecialchars($search) ."'</h2>";
}

$tables = ['Customer', 'Supplier', 'Store', 'Employee', 'Product', 'Purchase', 'Delivery', 'Collection'];

foreach ($tables as $table) {
    echo "<h3>In Table: $table</h3>";

    $sql = "SELECT * FROM $table WHERE 1=0 ";
    $s = oci_parse($connection, "SELECT * FROM $table WHERE ROWNUM = 1");
    oci_execute($s);
    $ncols = oci_num_fields($s);

    for ($i = 1; $i <= $ncols; $i++) {
        $colname = oci_field_name($s, $i);
        if (oci_field_type($s, $i) == 'VARCHAR2' || oci_field_type($s, $i) == 'CHAR') {
            if ($search) {
                $sql .= " OR LOWER($colname) LIKE LOWER('%$search%')";
            }
        }
    }

    if ($search) {
        $sql = str_replace("WHERE 1=0 OR", "WHERE", $sql);
    }

    $statement = oci_parse($connection, $sql);
    if (!oci_execute($statement)) {
        $e = oci_error($statement);
        echo "Error executing query: " . htmlentities($e['message']) . "<br>\n";
        continue;
    }

    $numcols = oci_num_fields($statement);
    echo "<table><tr>";
    for ($i = 1; $i <= $numcols; $i++) {
        echo "<th>" . htmlspecialchars(oci_field_name($statement, $i)) . "</th>";
    }
    echo "</tr>";

    while ($row = oci_fetch_assoc($statement)) {
        echo "<tr>";
        foreach ($row as $item) {
            echo "<td>" . htmlspecialchars($item) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table><br>";
}

oci_close($connection);
?>
</body>
</html>







