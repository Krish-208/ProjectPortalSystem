<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	
	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	session_start();
	

	$Email = $_SESSION['email'];
	$Code= mt_rand(111111,999999);

    $encodedOtp = bin2hex($Code);
	$firstPart =  substr($encodedOtp,0,3);
	$secondPart = substr($encodedOtp,3);
	$salt = '395023';
	$saltedEncodedOtp = $firstPart.$salt.$secondPart;
    setcookie('myID', $saltedEncodedOtp, time() + (60*30), "/");
	
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'your mail here';                 //SMTP username
        $mail->Password   = 'app password here';                     //SMTP password
        $mail->SMTPSecure = 'ssl';                                  //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('your mail here', 'Diwaliba Polytechnic');
        $mail->addAddress($Email);            //Add a recipient name not compulsory
        $mail->addReplyTo('no-reply@gmail.com', 'No-reply');


        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = 'Hey Your password can be reset by verifying given otp. Your OTP is: '.$Code;
        $_SESSION["email"] = $Email;
        $mail->send();
        header("Location: ../../Login/newPassword.php");
    } 
    catch (Exception $e) {
        $_SESSION['error'] = "Message could not be sent.<br><span class='detail'> Invalid mail ID or Mailer Error: " .$mail->ErrorInfo."</span>";
        header('Location: ../../Login/newPassword.php');
    }
?>
