<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../database/DB.php';
    header('Content-Type: application/json');
    $db = new DB('users');
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $password = htmlspecialchars(trim($_POST['password'] ?? ''));
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
        exit;
    }
    $user = $db->find(['username' => $username, 'password' => $password]);
    if($user){
        session_start();
        $_SESSION['role'] = $user->role;
        $_SESSION['state'] = 'true';
        echo json_encode(['success' => true, 'message' => 'Login successful.']);
    }
    else{
        echo json_encode(['success' => false, 'message' => 'Login false.']);
    }
}
