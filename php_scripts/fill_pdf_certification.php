<?php
require __DIR__ . '/../backend/vendor/autoload.php';
use setasign\Fpdi\Fpdi;


// --- Database connection (same as your .env in Laravel) ---
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "business_permit_license"; // ⚠️ change to your actual DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// --- Get record ID from URL ---
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid record ID.");
}

// --- Fetch record from database ---
$sql = "SELECT * FROM bplo_records WHERE ID = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Record not found.");
}

$data = $result->fetch_assoc();

// --- FPDI setup ---
function getOrdinalSuffix($number) {
    if (!in_array(($number % 100), [11, 12, 13])) {
        switch ($number % 10) {
            case 1: return "ST";
            case 2: return "ND";
            case 3: return "RD";
        }
    }
    return "TH";
}

$pdf = new Fpdi();
$pdf->AddPage();
$pdf->setSourceFile(__DIR__ . '/../template/CERTIFICATION_TEMPLATE_v3.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 215.9, 330.2); // long bond size

$pdf->SetFont('Times', '', 12);

// --- Hardcoded values ---
$operator_name   = strtoupper(trim($data['FNAME'] . " " . $data['MNAME'] . " " . $data['LNAME']));
$barangay        = strtoupper($data['BARANGAY']);
$make            = strtoupper($data['MAKE']);
$chassis_no      = strtoupper($data['CHASSIS_NO']);
$plate_no        = strtoupper($data['PLATE']);
$date_registered = date("F j, Y", strtotime($data['DATE'] ?? date("Y-m-d")));


// --- Write text on template ---
$pdf->SetXY(60, 135);
$pdf->Write(10, $operator_name);

$pdf->SetXY(35, 140);
$pdf->Write(10, $barangay);

$pdf->SetXY(27, 130);
$pdf->Write(10, $make);

$pdf->SetXY(70, 130);
$pdf->Write(10, $chassis_no);

$pdf->SetXY(129, 130);
$pdf->Write(10, $plate_no);

$pdf->SetXY(150, 75);
$pdf->Write(12, $date_registered);



// Output PDF
$pdf->Output('I', 'Filled_Application.pdf');
?>
