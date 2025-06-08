<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 2; // DEBUG activado
    $mail->isSMTP();
    $mail->Host       = 'smtp.mail.me.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mejido@me.com';
    $mail->Password   = 'lpio-fmws-gydo-dqad'; // Poné tu App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('mejido@me.com', 'Formulario Web Asegurarte');
    $mail->addAddress('mejido@me.com');
    $mail->addReplyTo($_POST['email'], $_POST['name']);

    $mail->isHTML(true);
    $mail->Subject = 'Nuevo contacto desde el formulario';
    $mail->Body    = "
        <h3>Nuevo contacto</h3>
        <p><strong>Nombre:</strong> {$_POST['name']}</p>
        <p><strong>Email:</strong> {$_POST['email']}</p>
        <p><strong>Teléfono:</strong> {$_POST['phone']}</p>
    ";
    $mail->AltBody = "Nombre: {$_POST['name']}\nEmail: {$_POST['email']}\nTeléfono: {$_POST['phone']}";

    $mail->send();
    echo "Mensaje enviado correctamente.";
} catch (Exception $e) {
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}
?>
