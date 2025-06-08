<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

// Validar reCAPTCHA
$recaptcha_secret = 'AQUI_TU_SECRET_KEY';
$recaptcha_response = $_POST['g-recaptcha-response'];

$verify_response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$response_data = json_decode($verify_response);

if(!$response_data->success) {
    die('Error de validación reCAPTCHA. Por favor intentá de nuevo.');
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.mail.me.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mejido@me.com';
    $mail->Password   = 'lpio-fmws-gydo-dgad'; // App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('mejido@me.com', 'Formulario Web Asegurarte');
    $mail->addAddress('mejido@me.com');
    $mail->addReplyTo($_POST['email'], $_POST['name']);

    $mail->isHTML(true);
    $mail->Subject = 'Nuevo contacto desde el formulario (con CAPTCHA)';
    $mail->Body    = "
        <h3>Nuevo contacto</h3>
        <p><strong>Nombre:</strong> {$_POST['name']}</p>
        <p><strong>Email:</strong> {$_POST['email']}</p>
        <p><strong>Teléfono:</strong> {$_POST['phone']}</p>
    ";
    $mail->AltBody = "Nombre: {$_POST['name']}\nEmail: {$_POST['email']}\nTeléfono: {$_POST['phone']}";

    $mail->send();

    // Confirmación al usuario
    $mail_confirm = new PHPMailer(true);
    $mail_confirm->isSMTP();
    $mail_confirm->Host       = 'smtp.mail.me.com';
    $mail_confirm->SMTPAuth   = true;
    $mail_confirm->Username   = 'mejido@me.com';
    $mail_confirm->Password   = 'lpio-fmws-gydo-dgad';
    $mail_confirm->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail_confirm->Port       = 587;

    $mail_confirm->setFrom('mejido@me.com', 'Asegurarte');
    $mail_confirm->addAddress($_POST['email'], $_POST['name']);

    $mail_confirm->isHTML(true);
    $mail_confirm->Subject = 'Gracias por contactarnos';
    $mail_confirm->Body    = "
        <h3>Hola {$_POST['name']},</h3>
        <p>Gracias por contactarte con <strong>Asegurarte</strong>.<br>
        Hemos recibido tu mensaje y nos pondremos en contacto a la brevedad.</p>
        <p>Saludos cordiales,<br>El equipo de Asegurarte.</p>
    ";
    $mail_confirm->AltBody = "Hola {$_POST['name']},\nGracias por contactarte con Asegurarte.\nHemos recibido tu mensaje y nos pondremos en contacto a la brevedad.\nSaludos cordiales.";

    $mail_confirm->send();

    header('Location: gracias_lindo.html');
    exit();
} catch (Exception $e) {
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}
?>