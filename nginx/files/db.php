<?php
$db_host = 'localhost';
$db_user = 'roman';
$db_pwd = '1111';
$database = 'testdb';
$table = 'test';

$connection = mysqli_connect($db_host, $db_user, $db_pwd);
if (!$connection) {
    die("Can't connect to database: " . mysqli_connect_error());
}

if (!mysqli_select_db($connection, $database)) {
    die("Can't select database: " . mysqli_error($connection));
}

$result = mysqli_query($connection, "SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed: " . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблиця</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            padding: 12px 15px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s;
        }
        .header {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .count {
            background-color: #e7f3ff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header">Данні з таблиці: <?php echo $table; ?></h1>
        
        <?php
        $fields_num = mysqli_num_fields($result);
        $rows_count = mysqli_num_rows($result);
        ?>
        
        <div class="count">
            Знайдені записи: <?php echo $rows_count; ?>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <?php
                        for($i = 0; $i < $fields_num; $i++) {
                            $field = mysqli_fetch_field($result);
                            echo "<th>{$field->name}</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                       foreach($row as $cell) {
                            echo "<td>" . htmlspecialchars($cell) . "</td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($connection);
?>
