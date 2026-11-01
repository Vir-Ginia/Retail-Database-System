<?php
// Connect to the Oracle database with predefined credentials
$connection = oci_connect(getenv('ORACLE_USER'), getenv('ORACLE_PASSWORD'), getenv('ORACLE_CONNECTION_STRING'));

// Check if the connection was successful
if ($connection) {
    // Retrieve the search term from the GET request
    $search = isset($_GET['search']) ? htmlspecialchars(strtoupper($_GET['search']), ENT_QUOTES) : '';
    // Retrieve the selected search column from the GET request, default to 'Employee_ID' if not set
    $search_column = isset($_GET['search_column']) ? $_GET['search_column'] : 'Employee_ID';
    // Retrieve sorting preferences
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'Employee_ID';

    // Determine the ORDER BY clause based on the sorting preference
    switch ($sort) {
        case 'name_asc':
            $order_by = "ORDER BY Employee_name ASC";
            break;
        case 'name_desc':
            $order_by = "ORDER BY Employee_name DESC";
            break;
        case 'position_asc':
            $order_by = "ORDER BY Position ASC";
            break;
        case 'position_desc':
            $order_by = "ORDER BY Position DESC";
            break;
        case 'dob_asc':
            $order_by = "ORDER BY DOB ASC";
            break;
        case 'dob_desc':
            $order_by = "ORDER BY DOB DESC";
            break;
        default:
            $order_by = "ORDER BY Employee_ID"; // Default sort order
    }

    // Prepare the SQL query
    $sql = "SELECT * FROM Employee WHERE UPPER($search_column) LIKE '%" . $search . "%' $order_by";

    // Parse and execute the SQL query
    $statement = oci_parse($connection, $sql);
    if (!oci_execute($statement)) {
        $e = oci_error($statement);
        echo htmlentities($e['message'], ENT_QUOTES);
        echo "<br>SQL execution failed.<br>\n";
    }
} else {
    $e = oci_error($connection);
    echo htmlentities($e['message'], ENT_QUOTES);
    echo "Connection failed.<br>\n";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employees</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        #left-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            padding: 20px;
            height: 100vh;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #ffffff;
            box-shadow: -2px 0 5px rgba(0,0,0,0.1);
            z-index: 999;
        }
        #left-sidebar h3 {
            color: #003366;
            margin-top: 0;
        }
        #left-sidebar ul {
            list-style: none;
            padding: 0;
        }
        #left-sidebar ul li a {
            display: block;
            padding: 10px;
            color: #003366;
            text-decoration: none;
            font-weight: bold;
        }
        #left-sidebar ul li a:hover {
            background-color: #ddd;
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
            box-shadow: 0 12px 16px rgba(0,0,0,0.2);
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-right: 1px solid #ddd;
            color: #666;
        }
        th {
            background-color: #003366;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:nth-child(odd) {
            background-color: #fff;
        }
        tr:last-child td {
            border-bottom: none;
        }
        form {
            text-align: center;
            padding-top: 20px;
            margin-left: 220px; /* Adjust form positioning to accommodate sidebar */
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }
        input[type="submit"] {
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border-color: #007BFF;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        h1 {
            text-align: center;
            color: #003366;
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
            <li><a href="8Collection.php">Collection</a></li>
        </ul>
    </div>
    <div>
        <h1>Employee Information</h1>
        <form method="get">
            <input type="text" id="search" name="search" placeholder="Enter Employee information..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES) : ''; ?>" style="width: 340px;">
            <select name="search_column">
                <option value="Employee_ID" <?php echo $search_column == 'Employee_ID' ? 'selected' : ''; ?>>Employee ID</option>
                <option value="Employee_name" <?php echo $search_column == 'Employee_name' ? 'selected' : ''; ?>>Employee Name</option>
                <option value="Position" <?php echo $search_column == 'Position' ? 'selected' : ''; ?>>Position</option>
                <option value="DOB" <?php echo $search_column == 'DOB' ? 'selected' : ''; ?>>Date of Birth</option>
            </select>
            <select name="sort" onchange="this.form.submit()">
                <option value="Employee_ID">Default</option>
                <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Name A to Z</option>
                <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Name Z to A</option>
                <option value="position_asc" <?php echo $sort == 'position_asc' ? 'selected' : ''; ?>>Position A to Z</option>
                <option value="position_desc" <?php echo $sort == 'position_desc' ? 'selected' : ''; ?>>Position Z to A</option>
                <option value="dob_asc" <?php echo $sort == 'dob_asc' ? 'selected' : ''; ?>>Age Youngest First</option>
                <option value="dob_desc" <?php echo $sort == 'dob_desc' ? 'selected' : ''; ?>>Age Oldest First</option>
            </select>
            <input type="submit" value="Search">
        </form>
        <?php if (isset($statement) && $statement) : ?>
            <table>
            <tr>
            <?php
            $numcols = oci_num_fields($statement);
            for ($i = 1; $i <= $numcols; $i++) {
                $colname = oci_field_name($statement, $i);
                echo "<th>$colname</th>";
            }
            echo "</tr>";
            while ($row = oci_fetch_array($statement, OCI_ASSOC + OCI_RETURN_NULLS)) {
                echo "<tr>";
                foreach ($row as $item) {
                    echo "<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>";
                }
                echo "</tr>";
            }
            ?>
            </table>
        <?php
        oci_free_statement($statement);
        oci_close($connection);
        endif; ?>
    </div>
</body>
</html>

