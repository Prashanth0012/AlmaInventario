<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$report_type = "";
$query = "";

if(isset($_POST['generate']) || isset($_POST['export'])){

    $report_type = $_POST['report_type'];

    if($report_type == "products"){
    $query = "SELECT 
                p.product_id AS Product_ID,
                p.product_name AS Product_Name,
                c.category_name AS Category,
                s.supplier_name AS Supplier,
                p.price AS Price,
                p.stock AS Stock
              FROM products p
              JOIN categories c ON p.category_id = c.id
              JOIN suppliers s ON p.supplier_id = s.id
              ORDER BY p.product_id ASC";
    }

    if($report_type == "sales"){
        $query = "SELECT 
                    s.id AS Sale_ID,
                    s.customer_name AS Customer_Name,
                    s.total_amount AS Total_Amount,
                    s.sale_date AS Sale_Date
                FROM sales s";
    }

    if($report_type == "stock"){
        $query = "SELECT 
                    st.id AS Transaction_ID,
                    p.product_name AS Product_Name,
                    st.type AS Transaction_Type,
                    st.quantity AS Quantity,
                    st.date AS Transaction_Date
                FROM stock_transactions st
                JOIN products p ON st.product_id = p.product_id
                ORDER BY st.id ASC";
    }

    if($report_type == "login"){
        $query = "SELECT 
                    l.id AS Log_ID,
                    u.username AS Username,
                    l.login_time AS Login_Time,
                    l.logout_time AS Logout_Time
                FROM login_logs l
                JOIN users u ON l.user_id = u.id";
    }

    if($report_type == "purchases"){
        $query = "SELECT 
                    purchase_id AS Purchase_ID,
                    purchase_date AS Purchase_Date,
                    total_amount AS Total_Amount
                FROM purchases";
    }
    
}

if(isset($_POST['export'])){

    $result = mysqli_query($conn,$query);
    $format = $_POST['format'];

    if($format == "csv"){
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=report.csv");

        $fields = mysqli_fetch_fields($result);
        foreach($fields as $field){
            echo $field->name.",";
        }
        echo "\n";

        while($row = mysqli_fetch_assoc($result)){
            foreach($row as $value){
                echo $value.",";
            }
            echo "\n";
        }
        exit();
    }

    if($format == "excel"){
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");

        echo "<table border='1'><tr>";

        $fields = mysqli_fetch_fields($result);
        foreach($fields as $field){
            echo "<th>".$field->name."</th>";
        }
        echo "</tr>";

        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            foreach($row as $value){
                echo "<td>".$value."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        exit();
    }

    if($format == "pdf"){
        require('../fpdf/fpdf.php');

        $pdf = new FPDF('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);

        $fields = mysqli_fetch_fields($result);
        foreach($fields as $field){
            $pdf->Cell(40,8,$field->name,1);
        }
        $pdf->Ln();

        while($row = mysqli_fetch_assoc($result)){
            foreach($row as $value){
                $pdf->Cell(40,8,$value,1);
            }
            $pdf->Ln();
        }

        $pdf->Output('D','report.pdf');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports Center</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>

<body class="page-body">

<div class="page-container">
<a href="dashboard.php" class="btn-back">Back</a>

<h2 class="page-title">Reports Center</h2>
<?php include "navbar.php"; ?>

<br>

<div class="form-card">
<form method="POST" class="form-inline">
<select name="report_type" class="form-input" required>
<option value="">Select Report</option>
<option value="products">Products Report</option>
<option value="sales">Sales Report</option>
<option value="purchases">Purchases Report</option>
<option value="stock">Stock Report</option>
<option value="login">Login/Logout Report</option>
</select>
<button type="submit" name="generate" class="btn-primary">Generate</button>
</form>
</div>

<?php if(isset($_POST['generate'])){ 
$result = mysqli_query($conn,$query);
?>

<div class="form-card">
<form method="POST" class="form-inline">
<input type="hidden" name="report_type" value="<?php echo $report_type; ?>">
<select name="format" class="form-input" required>
<option value="">Download Format</option>
<option value="csv">CSV</option>
<option value="excel">Excel</option>
<option value="pdf">PDF</option>
</select>
<button type="submit" name="export" class="btn-success">Download</button>
</form>
</div>

<div class="table-card">
<table class="data-table">
<tr>
<?php
$fields = mysqli_fetch_fields($result);
foreach($fields as $field){
    echo "<th>".$field->name."</th>";
}
echo "</tr>";

while($row = mysqli_fetch_assoc($result)){
    echo "<tr>";
    foreach($row as $value){
        echo "<td>".$value."</td>";
    }
    echo "</tr>";
}
?>
</table>
</div>

<?php } ?>

<br>



</div>

</body>
</html>