<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail_confirm = new PHPMailer(true);

try {
    $mail_confirm->isSMTP();
    $mail_confirm->Host       = 'smtp.mail.me.com';
    $mail_confirm->SMTPAuth   = true;
    $mail_confirm->Username   = 'mejido@me.com';
    $mail_confirm->Password   = 'lpio-fmws-gydo-dqad'; // App Password
    $mail_confirm->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail_confirm->Port       = 587;

    $mail_confirm->setFrom('mejido@me.com', 'Asegurarte');
    $mail_confirm->addAddress($_POST['email'], $_POST['name']);

    $mail_confirm->isHTML(true);
    $mail_confirm->Subject = 'Gracias por contactarnos';
    $mail_confirm->Body = "
<!DOCTYPE html>
<html lang='es'>
<head>
  <meta charset='UTF-8'>
  <style>
    body {
      font-family: 'Helvetica Neue', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .email-container {
      max-width: 600px;
      margin: 30px auto;
      background: #ffffff;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .email-header {
      background-color: #0077c8;
      padding: 20px;
      text-align: center;
      color: #ffffff;
    }
    .email-header img {
      max-width: 150px;
      margin-bottom: 10px;
    }
    .email-body {
      padding: 30px;
      color: #333333;
      line-height: 1.6;
    }
    .email-footer {
      background-color: #f0f0f0;
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #777777;
    }
    .email-body h2 {
      color: #0077c8;
    }
  </style>
</head>
<body>
  <div class='email-container'>
    <div class='email-header'>
      <img src='https://via.placeholder.com/150x50?text=LOGO' alt='Asegurarte Logo'>
    </div>
    <div class='email-body'>
      <h2>¡Hola " . htmlspecialchars($_POST['name']) . "!</h2>
      <p>Gracias por contactarte con <strong>Asegurarte</strong>.</p>
      <p>Hemos recibido tu mensaje y uno de nuestros asesores se pondrá en contacto con vos a la brevedad.</p>
      <p>¡Gracias por confiar en nosotros!</p>
      <p>— El equipo de <strong>Asegurarte</strong></p>
    </div>
    <div class='email-footer'>
      © 2025 Asegurarte. Todos los derechos reservados.
    </div>
  </div>
</body>
</html>
";

    $mail_confirm->AltBody = "Hola {$_POST['name']},\nGracias por contactarte con Asegurarte.\nHemos recibido tu mensaje y nos pondremos en contacto a la brevedad.\nSaludos cordiales.";

    $mail_confirm->send();
    echo "Mensaje de confirmación enviado.";
} catch (Exception $e) {
    echo "Error al enviar el mensaje de confirmación: {$mail_confirm->ErrorInfo}";
}
?>