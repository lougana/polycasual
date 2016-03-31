<?php

/**
 * @Mailer
 *
 */
$json = array();
$subject_message = "You Got a Lead";
$constructioner_message = 'Message Sent.';
$failure_message = 'Message Fail.';
$recipient = 'sales@brandster.com';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["username"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["useremail"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["description"]);

    // Check that data was sent to the mailer.
    if (empty($name) OR empty($message) OR ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        $json['type'] = "error";
        $json['message'] = 'Oops! There was a problem with your submission. Please complete the form and try again.';
        echo json_encode($json);
        exit;
    }

    // Set the recipient email address.
    // FIXME: Update this to your desired email address.
    // Set the email subject.
    $subject = $subject_message;

    // Build the email content.
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        // http_response_code(200);
        $json['type'] = "success";
        $json['message'] = $constructioner_message;
        echo json_encode($json);
        die();
    } else {
        // Set a 500 (internal server error) response code.
        // http_response_code(500);
        $json['type'] = "error";
        $json['message'] = $failure_message;
        echo json_encode($json);
        die();
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    // http_response_code(403);
    echo
    $json['type'] = "error";
    $json['message'] = $failure_message;
    echo json_encode($json);
    die();
}
