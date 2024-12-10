<?php

require_once '../database/DB.php';

header('Content-Type: application/json');

$response = ['status' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subjectName = htmlspecialchars($_POST['subject_name']);
    $stage = htmlspecialchars($_POST['stage']);
    $totalHours = htmlspecialchars($_POST['total_hours']);

    try {
        $db = new DB('subjects');
        $result = $db->create([
            'name' => $subjectName,
            'stage' => $stage,
            'total_hours' => $totalHours
        ]);

        if ($result) {
            $response['status'] = true;
            $response['message'] = "تم تسجيل المادة بنجاح.";
        } else {
            $response['message'] = "حدث خطأ أثناء التسجيل.";
        }
    } catch (Exception $e) {
        $response['message'] = "خطأ: " . $e->getMessage();
    }
} else {
    $response['message'] = "طلب غير صالح.";
}

echo json_encode($response);
?>
