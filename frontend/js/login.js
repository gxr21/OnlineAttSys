document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');
    const successMessage = document.getElementById('success-message');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        errorMessage.textContent = '';
        successMessage.textContent = '';
        if (!username || !password) {
            errorMessage.textContent = 'اسم المستخدم وكلمة المرور مطلوبة.';
            return;
        }
        fetch('../../backend/handler/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                successMessage.textContent = data.message;
                setTimeout(() => {
                    window.location.href = '../html/dashboard.php';
                }, 1000);
            } else {
                errorMessage.textContent = data.message;
            }
        })
        .catch(error => {
            errorMessage.textContent = 'حدث خطأ أثناء تسجيل الدخول. حاول مرة أخرى.';
        });
    });
});