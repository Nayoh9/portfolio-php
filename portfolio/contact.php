    <?php
    include "includes/functions.php";
    include "header.php";

    use PHPMailer\PHPMailer\PHPMailer;

    $not_ok_contact = $template_url . "accueil";
    $ok_contact = $template_url . "accueil";

    $error = false;

    if (empty($_POST["fname"])) {
        $error = "error_firstname";
    }

    if (empty($_POST["lname"])) {
        $error = "error_lastname";
    }

    if (empty($_POST["email"])) {
        $error = "error_email";
    }

    if (empty($_POST["phone"])) {
        $error = "error_phone_number";
    }

    if (empty($_POST["message"])) {
        $error = "error_message";
    }

    if (!empty($error)) {
        header("location:$template" . "accueil?error=$error");
        die();
    }

    $firstname = htmlspecialchars($_POST["fname"]);
    $lastname = htmlspecialchars($_POST["lname"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $message = htmlspecialchars($_POST["message"]);
    !empty($_POST["service"]) && $service = htmlspecialchars($_POST["service"]);

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = $brevo_host;
        $mail->Port = $brevo_port;
        $mail->Username = $brevo_username;
        $mail->Password = $brevo_password;
        $mail->setFrom($email);
        // mail du destinataire 
        $mail->addAddress("y.andre90000@gmail.com");

        $mail->Subject = "Demande client pour Pixeven";
        $mail->Body = $message . "<br/>" . "<br/>" . "Demande pour du " . $service . " <br/> " . "email :" . $email . " <br/> " . "téléphone :"  . $phone;

        $mail->send();
        echo 'envoyé';

        header("location: $ok_contact?success=message_sent");
    } catch (\Throwable $e) {
        $error = "email_sending_error";
        header("location: $not_ok_contact?error=$error");
        die();
    }
