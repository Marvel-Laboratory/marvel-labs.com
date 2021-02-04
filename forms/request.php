<?php

if ( count( $_POST ) == 0 || isset( $_POST['visa'] ) ) {
	die( 'go away, a****e!' );
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'includes/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/src/SMTP.php';

$errors = array();
$data   = array();

$name    = ( isset( $_POST['name'] ) && ! empty( $_POST['name'] ) ) ? filter_var( $_POST['name'],
	FILTER_SANITIZE_STRING ) : null;
$email   = ( isset( $_POST['email'] ) && ! empty( $_POST['email'] ) && filter_var( $_POST['email'],
		FILTER_VALIDATE_EMAIL ) ) ? $_POST['email'] : null;
$subject = ( isset( $_POST['subject'] ) && ! empty( $_POST['subject'] ) ) ? filter_var( $_POST['subject'],
	FILTER_SANITIZE_STRING ) : null;
$message = ( isset( $_POST['message'] ) && ! empty( $_POST['message'] ) ) ? filter_var( $_POST['message'],
	FILTER_SANITIZE_STRING ) : null;


if ( empty( $name ) ) {
	$errors['name'] = 'Name is required.';
}

if ( empty( $email ) ) {
	$errors['email'] = 'Email is required.';
}

if ( empty( $subject ) ) {
	$errors['subject'] = 'Subject is required.';
}

if ( empty( $message ) ) {
	$errors['message'] = 'Message is required.';
}

// return a response ===========================================================

// if there are any errors in our errors array, return a success boolean of false
if ( ! empty( $errors ) ) {

	// if there are items in our errors array, return those errors
	$data['success'] = false;
	$data['errors']  = $errors;

	$errorMessages = '<div class="alert alert-danger" role="alert">';

	foreach ( $errors as $error ) {
		$errorMessages .= $error . '<br>';
	}
	$errorMessages       .= '</div>';
	$data['errors_html'] = $errorMessages;
} else {

	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer( true );

	$from = 'smtp@marvel-labs.com';
	$to   = 'info@marvel-labs.com';

	//Server settings
//	$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	$mail->isSMTP();                                            // Send using SMTP
	$mail->Host       = 'smtp-relay.sendinblue.com';                    // Set the SMTP server to send through
	$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	$mail->Username   = 'fawaz.qamar@gmail.com';                     // SMTP username
	$mail->Password   = 'hWMkAx39bsUSPJTB';                               // SMTP password
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = 587;

	//Recipients
	$mail->setFrom( $from, 'SMTP Marvel-Lab' );
	$mail->addAddress( $to, 'Michael Drews' );     // Add a recipient
	$mail->addReplyTo( $to, 'Reply-to' );

	// Content
	$mail->CharSet  = 'UTF-8';
	$mail->Encoding = 'base64';

	$subject = 'Contact from Marvel-Labs';

	$htmlMessage = '<html><body>';
	$htmlMessage .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
	$htmlMessage .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . strip_tags( $name ) . "</td></tr>";
	$htmlMessage .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags( $email ) . "</td></tr>";
	$htmlMessage .= "<tr style='background: #eee;'><td><strong>Subject:</strong> </td><td>" . strip_tags( $subject ) . "</td></tr>";
	$htmlMessage .= "<tr><td><strong>Message:</strong> </td><td>" . $message . "</td></tr>";
	$htmlMessage .= "</table>";
	$htmlMessage .= "</body></html>";
	$mail->isHTML( true );
	$mail->Subject = $subject;
	$mail->Body    = $htmlMessage;
	$mail->AltBody = 'Email: ' . $email . ' >> Message: ' . $message;


	if ( $mail->send() ) {

		$data['success'] = true;
		$data['message'] = 'Success!';

	} else {
		$data['success'] = false;
		$data['errors']  = 'There is some problem. Please try again.';

		$errorMessages       = '<div class="alert alert-danger" role="alert">';
		$errorMessages       .= 'There is some problem. Please try again.';
		$errorMessages       .= '</div>';
		$data['errors_html'] = $errorMessages;
	}

}

// return all our data to an AJAX call
if ( count( $_POST ) > 0 ) {
	echo json_encode( $data );
	die();
}
