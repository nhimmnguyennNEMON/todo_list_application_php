<?php
session_start();

// Function to generate CAPTCHA
function generateCaptcha() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $captcha = '';

    $max = strlen($characters) - 1;
    for ($i = 0; $i < 6; $i++) {
        $captcha .= $characters[mt_rand(0, $max)]; // Nối captcha vừa generate random vào biến $captcha
    }
    $_SESSION['captcha'] = $captcha;
    return $captcha;
}

// Function to verify CAPTCHA
function verifyCaptcha($userInput) {
    if (isset($_SESSION['captcha']) && !empty($userInput)) {
        $captcha = $_SESSION['captcha'];
        if ($userInput === $captcha) {
            unset($_SESSION['captcha']); // Xóa giá trị CAPTCHA sau khi kiểm tra thành công
            return true; // CAPTCHA đúng
        }
    }
    return false; // CAPTCHA không đúng hoặc không tồn tại
}

// Sử dụng các hàm này:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'generate') {
            // Gọi hàm để tạo CAPTCHA và trả về giá trị
            $generatedCaptcha = generateCaptcha();
            echo $generatedCaptcha; // Trả về giá trị CAPTCHA để hiển thị trong trang HTML
        } elseif ($_POST['action'] === 'verify') {
            $userInput = $_POST['userInput'];
            // Gọi hàm để kiểm tra CAPTCHA
            $isCaptchaValid = verifyCaptcha($userInput);
            echo $isCaptchaValid;
        }
    }
}

?>
