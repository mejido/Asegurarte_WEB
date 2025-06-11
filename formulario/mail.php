<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.mail.me.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mejido@me.com';
    $mail->Password   = '1234'; // App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Codificación
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // Mail a Asegurarte
    $mail->setFrom('mejido@me.com', 'Formulario Web Asegurarte');
    $mail->addAddress('bajatuscostos@asegurarte.com');
    $mail->addAddress('mejido@me.com'); // Tu correo
    $mail->addReplyTo($_POST['email'], $_POST['name']);

    $mail->isHTML(true);
    $mail->Subject = 'Nuevo contacto desde asegurarte.com';
    $mail->Body    = "
        <h3>Nuevo contacto desde asegurarte.com</h3>
        <p><strong>Nombre:</strong> {$_POST['name']}</p>
        <p><strong>Email:</strong> {$_POST['email']}</p>
        <p><strong>Teléfono:</strong> {$_POST['phone']}</p>
    ";
    $mail->AltBody = "Nombre: {$_POST['name']}\nEmail: {$_POST['email']}\nTeléfono: {$_POST['phone']}";

    $mail->send();

    // Mail de confirmación al cliente
    $mail_confirm = new PHPMailer(true);
    $mail_confirm->isSMTP();
    $mail_confirm->Host       = 'smtp.mail.me.com';
    $mail_confirm->SMTPAuth   = true;
    $mail_confirm->Username   = 'mejido@me.com';
    $mail_confirm->Password   = '1234';
    $mail_confirm->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail_confirm->Port       = 587;

    $mail_confirm->CharSet = 'UTF-8';
    $mail_confirm->Encoding = 'base64';

    $mail_confirm->setFrom('mejido@me.com', 'Asegurarte');
    $mail_confirm->addAddress($_POST['email'], $_POST['name']);

    $mail_confirm->isHTML(true);
    $mail_confirm->Subject = 'Gracias por contactarte';
    $mail_confirm->Body = "
        <h3>¡Hola " . htmlspecialchars($_POST['name']) . "!</h3>
        <p>Gracias por contactarte con <strong>Asegurarte</strong>.</p>
        <p>Hemos recibido tu mensaje y uno de nuestros asesores se pondrá en contacto con vos a la brevedad.</p>
        <p>¡Gracias por confiar en nosotros!</p>
        <p>— El equipo de <strong>asegurarte.com</strong></p>
    ";
    $mail_confirm->AltBody = "Hola {$_POST['name']},\nGracias por contactarte con Asegurarte.\nHemos recibido tu mensaje y nos pondremos en contacto a la brevedad.\nSaludos cordiales.";

    $mail_confirm->send();

    // Redirigir a gracias.html con el nombre
    header("Location: gracias.html?nombre=" . urlencode($_POST['name']));
    exit();
} catch (Exception $e) {
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}
?>
