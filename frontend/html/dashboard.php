<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: ../../frontend/html/login.html");
    exit();
}

if ($_SESSION['role'] === 'admin') {
   
} elseif ($_SESSION['role'] === 'teacher') {
    header("Location: ../../frontend/html/teacherDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h2>لوحة التحكم</h2>
            <button onclick="showTab('manage-students')">إدارة الطلاب</button>
            <button onclick="showTab('manage-subjects')">إدارة المواد</button>
        </div>
        <div class="main-content">
            <div id="manage-students" class="tab active">
                <h2>إدارة الطلاب</h2>
                <form id="student-form" action="../../backend/handler/createStudents.php" method="POST">
                    <label for="first-name">الاسم الأول:</label>
                    <input type="text" id="first-name" name="first_name" placeholder="الاسم الأول" required>
                    
                    <label for="middle-name">الاسم الثاني:</label>
                    <input type="text" id="middle-name" name="middle_name" placeholder="الاسم الثاني" required>
                    
                    <label for="last-name">الاسم الأخير:</label>
                    <input type="text" id="last-name" name="last_name" placeholder="الاسم الأخير" required>
                    
                    <label for="stage">المرحلة:</label>
                    <select id="stage" name="stage" required>
                        <option value="1">الأولى</option>
                        <option value="2">الثانية</option>
                        <option value="3">الثالثة</option>
                        <option value="4">الرابعة</option>
                    </select>
                    
                    <button type="submit">إضافة طالب</button>
                </form>
                <div id="student-response" style="margin-top: 10px; color: green;"></div>
            </div>

            <div id="manage-subjects" class="tab">
                <h2>إدارة المواد</h2>
                <form id="subject-form" action="../../backend/handler/createSubjects.php" method="POST">
                    <label for="subject-name">اسم المادة:</label>
                    <input type="text" id="subject-name" name="subject_name" placeholder="اسم المادة" required>
                    
                    <label for="stage">المرحلة:</label>
                    <select id="stage" name="stage" required>
                        <option value="1">الأولى</option>
                        <option value="2">الثانية</option>
                        <option value="3">الثالثة</option>
                        <option value="4">الرابعة</option>
                    </select>
                    
                    <label for="total-hours">عدد الساعات:</label>
                    <input type="number" id="total-hours" name="total_hours" placeholder="عدد الساعات" required>
                    
                    <button type="submit">إضافة مادة</button>
                </form>
                <div id="subject-response" style="margin-top: 10px; color: green;"></div>
            </div>
    <script src="../js/dashboard.js"></script>
    <script>
        document.getElementById('student-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                document.getElementById('student-response').innerText = result.message;
            } catch (error) {
                document.getElementById('student-response').innerText = "حدث خطأ في الاتصال بالخادم.";
            }
        });

        document.getElementById('subject-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                document.getElementById('subject-response').innerText = result.message;
            } catch (error) {
                document.getElementById('subject-response').innerText = "حدث خطأ في الاتصال بالخادم.";
            }
        });
    </script>
</body>
</html>
