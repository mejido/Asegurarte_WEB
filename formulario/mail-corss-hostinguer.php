<?php
// Configurar CORS solo si es necesario (para formularios clásicos no haría falta, pero lo dejamos bien)
header("Access-Control-Allow-Origin: https://www.asegurarte.com");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Solo aceptar POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    exit('Método no permitido');
}

// Sanitizar datos
$nombre = isset($_POST['nombre']) ? strip_tags(trim($_POST['nombre'])) : '';
$email  = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$mensaje = isset($_POST['mensaje']) ? strip_tags(trim($_POST['mensaje'])) : '';

// Validaciones
if (empty($nombre) || empty($email) || empty($mensaje) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Datos inválidos');
}

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com'; // Asegurate que sea el SMTP correcto
    $mail->SMTPAuth = true;
    $mail->Username = 'bajatuscostos@asegurarte.com';
    $mail->Password = 'CONTRASEÑA_DEL_CORREO';
    $mail->SMTPSecure = 'tls'; // O 'ssl' según el servidor
    $mail->Port = 587; // O 465

    // Remitente y Destinatario
    $mail->setFrom('bajatuscostos@asegurarte.com', 'Asegurarte');
    $mail->addAddress('bajatuscostos@asegurarte.com'); // Donde llegan los datos
    $mail->addReplyTo($email, $nombre); // Opcional: responde al cliente

    // Email
    $mail->isHTML(true);
    $mail->Subject = "Nuevo mensaje de formulario";
    $mail->Body = "
        <h2>Nuevo mensaje recibido</h2>
        <p><strong>Nombre:</strong> {$nombre}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Mensaje:</strong><br>{$mensaje}</p>
    ";

    $mail->send();
    echo 'Mensaje enviado correctamente';
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
}
?>
