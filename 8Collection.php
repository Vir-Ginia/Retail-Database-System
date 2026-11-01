<?php
// Connect to the sql
$connection = oci_connect(getenv('ORACLE_USER'), getenv('ORACLE_PASSWORD'), getenv('ORACLE_CONNECTION_STRING'));

// Check if the connection was successful
if ($connection) {
    //  search from the GET request
    $search = isset($_GET['search']) ? htmlspecialchars(strtoupper($_GET['search']), ENT_QUOTES) : '';
    //  selected search column from the GET request, default to 'Collection_ID' if not set
    $search_column = isset($_GET['search_column']) ? $_GET['search_column'] : 'Collection_ID';
    // sorting preferences
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'Collection_ID';

    //ORDER BY clause based on the sorting preference
    switch ($sort) {
        case 'price_asc':
            $order_by = "ORDER BY Purchase_Price ASC";
            break;
        case 'price_desc':
            $order_by = "ORDER BY Purchase_Price DESC";
            break;
        case 'date_asc':
            $order_by = "ORDER BY Collection_date ASC";
            break;
        case 'date_desc':
            $order_by = "ORDER BY Collection_date DESC";
            break;
        default:
            $order_by = "ORDER BY Collection_ID"; // Default sort order
    }

    // SQL query
    $sql = "SELECT * FROM Collection WHERE UPPER($search_column) LIKE '%" . $search . "%' $order_by";

    //execute the SQL query
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
    <title>Collections</title>
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
            margin-left: 220px;
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
            <li><a href="8Collection.php">Collections</a></li>
        </ul>
    </div>
    <div>
        <h1>Collection Information</h1>
        <form method="get">
            <input type="text" id="search" name="search" placeholder="Enter Collection information..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES) : ''; ?>" style="width: 340px;">
            <select name="search_column">
                <option value="Collection_ID" <?php echo $search_column == 'Collection_ID' ? 'selected' : ''; ?>>Collection ID</option>
                <option value="Store_ID" <?php echo $search_column == 'Store_ID' ? 'selected' : ''; ?>>Store_ID</option>
                <option value="Employee_ID" <?php echo $search_column == 'Employee_ID' ? 'selected' : ''; ?>>Employee_ID</option>
                <option value="Product_ID" <?php echo $search_column == 'Product_ID' ? 'selected' : ''; ?>>Product_ID</option>
                <option value="Customer_no" <?php echo $search_column == 'Customer_no' ? 'selected' : ''; ?>>Customer_no</option>
                <option value="Purchasee_ID" <?php echo $search_column == 'Purchasee_ID' ? 'selected' : ''; ?>>Purchasee_ID</option>
                <option value="Purchase_Price" <?php echo $search_column == 'Purchase_Price' ? 'selected' : ''; ?>>PURCHASE price</option>
                <option value="Last_four_cardNumbers" <?php echo $search_column == 'Last_four_cardNumbers' ? 'selected' : ''; ?>>Last_four_cardNumbers</option>
                <option value="Collection_date" <?php echo $search_column == 'Collection_date' ? 'selected' : ''; ?>>Collection_date</option>
                <option value="Collection_Weight_KG" <?php echo $search_column == 'Collection_Weight_KG' ? 'selected' : ''; ?>>Weight (KG)</option>
            </select>
            <select name="sort" onchange="this.form.submit()">
                <option value="Collection_ID">Default</option>
                <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price Low to High</option>
                <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price High to Low</option>
                <option value="date_asc" <?php echo $sort == 'date_asc' ? 'selected' : ''; ?>>Name new to old</option>
                <option value="date_desc" <?php echo $sort == 'date_desc' ? 'selected' : ''; ?>>Name old to new</option>
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







