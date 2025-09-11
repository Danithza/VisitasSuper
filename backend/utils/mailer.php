<?php
// backend/utils/mailer.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php'; // asegúrate de tener composer y vendor

function enviarCorreo($conexion, $responsable_id, $actividad, $plazo_fecha, $nombre_visita = '') {
    $stmt = $conexion->prepare("SELECT nombre, correo FROM responsables WHERE id = ?");
    $stmt->bind_param('i',$responsable_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if (!$row) return false;

    $nombre = $row['nombre'];
    $correo = $row['correo'];

    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP - ajusta con tus datos
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tu_correo@gmail.com';        // <- CAMBIAR
        $mail->Password = 'tu_contraseña_app';         // <- CAMBIAR (contraseña de aplicación)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('tu_correo@gmail.com', 'Sistema Visitas');
        $mail->addAddress($correo, $nombre);

        $mail->isHTML(true);
        $mail->Subject = 'Nueva actividad asignada';
        $mail->Body = "
            <p>Hola <b>{$nombre}</b>,</p>
            <p>Se te ha asignado la siguiente actividad en la visita: <b>{$nombre_visita}</b></p>
            <p><b>Actividad:</b> {$actividad}</p>
            <p><b>Plazo:</b> {$plazo_fecha}</p>
            <p>Por favor, atiende y actualiza el estado cuando se cumpla.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer error: " . $mail->ErrorInfo);
        return false;
    }
}
