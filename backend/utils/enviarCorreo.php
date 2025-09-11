<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP (Gmail)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'TU_CORREO@gmail.com';   // 👉 tu correo Gmail
    $mail->Password = 'TU_CONTRASEÑA_APP';    // 👉 contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Destinatario
    $mail->setFrom('TU_CORREO@gmail.com', 'VisitasSuper');
    $mail->addAddress('DESTINATARIO@gmail.com');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de correo';
    $mail->Body    = '¡Este es un correo de prueba enviado desde <b>VisitasSuper</b>!';

    $mail->send();
    echo 'Correo enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
}
