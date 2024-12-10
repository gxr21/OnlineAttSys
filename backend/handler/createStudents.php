<?php

require_once '../database/DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST['first_name']);
    $middleName = htmlspecialchars($_POST['middle_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $stage = htmlspecialchars($_POST['stage']);

    try {
        $db = new DB('students');
        $result = $db->create([
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'stage' => $stage
        ]);
        if ($result) {
            echo json_encode(["status" => "success", "message" => "تم تسجيل الطالب بنجاح."]);
        } else {
            echo json_encode(["status" => "error", "message" => "حدث خطأ أثناء التسجيل."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "طلب غير صالح."]);
}
?>
