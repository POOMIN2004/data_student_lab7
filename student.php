<?php
// กำหนดค่า Access-Control-Allow-Origin ให้เครื่องอื่น ๆ สามารถเรียกใช้งานหน้านี้ได้
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$link = mysqli_connect('localhost', 'root', '', 'cake');
mysqli_set_charset($link, 'utf8');
$requestMethod = $_SERVER["REQUEST_METHOD"];

// ตรวจสอบหากใช้ Method GET
if ($requestMethod == 'GET') {
    if (isset($_GET['student_code']) && !empty($_GET['student_code'])) {
        $student_code = $_GET['student_code'];
        // คำสั่ง SQL กรณี มีการส่งค่า student_code มาให้แสดงเฉพาะข้อมูลของ code นั้น
        $sql = "SELECT * FROM students WHERE student_code = '$student_code'";
    } else {
        // คำสั่ง SQL แสดงข้อมูลทั้งหมด
        $sql = "SELECT * FROM student";
    }
    $result = mysqli_query($link, $sql);
    $arr = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $arr[] = $row;
    }
    echo json_encode($arr);
}

// ตรวจสอบการเรียกใช้งานว่าเป็น Method POST หรือไม่
if ($requestMethod == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $student_code = $data['student_code'];
    $student_name = $data['student_name'];
    $gender = $data['gender'];

    // คำสั่ง SQL สำหรับเพิ่มข้อมูลใหม่
    $sql = "INSERT INTO student (student_code, student_name, gender) VALUES ('$student_code', '$student_name', '$gender')";
    $result = mysqli_query($link, $sql);

    if ($result) {
        echo json_encode(['status' => 'ok', 'message' => 'Insert Data Complete']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error']);
    }
}

// ตรวจสอบการเรียกใช้งานว่าเป็น Method PUT หรือไม่
if ($requestMethod == 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $student_code = $data['student_code'];
    $student_name = $data['student_name'];
    $gender = $data['gender'];

    // คำสั่ง SQL สำหรับแก้ไขข้อมูลใน Database โดยจะแก้ไขเฉพาะข้อมูลตามค่า student_code ที่ส่งมา
    $sql = "UPDATE student SET student_name = '$student_name', gender = '$gender' WHERE student_code = '$student_code'";
    $result = mysqli_query($link, $sql);

    if ($result) {
        echo json_encode(['status' => 'ok', 'message' => 'Update Data Complete']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error']);
    }
}

// ตรวจสอบการเรียกใช้งานว่าเป็น Method DELETE หรือไม่
if ($requestMethod == 'DELETE') {
    if (isset($_GET['student_code']) && !empty($_GET['student_code'])) {
        $student_code = $_GET['student_code'];
        // คำสั่ง SQL สำหรับลบข้อมูลใน Database ตามค่า student_code ที่ส่งมา
        $sql = "DELETE FROM student WHERE student_code = '$student_code'";
        $result = mysqli_query($link, $sql);

        if ($result) {
            echo json_encode(['status' => 'ok', 'message' => "Delete Data ($student_code) Complete"]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error']);
        }
    }
}
?>
