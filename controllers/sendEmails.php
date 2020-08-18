<?php
require_once 'vendor/autoload.php';

/*
// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl')) 
    ->setUsername('')  // TODO mettre dans param.ini
    ->setPassword('');    // TODO mettre dans param.ini

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

function sendVerificationEmail($userEmail, $token)
{
    global $mailer;
	// TODO modifier 'http://localhost' par $_SERVER['XXX']
    $body = '<!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <title>Test mail</title>
      <style>
        .wrapper {
          padding: 20px;
          color: #444;
          font-size: 1.3em;
        }
        a {
          background: #592f80;
          text-decoration: none;
          padding: 8px 15px;
          border-radius: 5px;
          color: #fff;
        }
      </style>
    </head>

    <body>
      <div class="wrapper">
        <p>Merci de vous être inscrit sur notre site. Veuillez cliquer sur le lien ci-dessous pour confirmer votre inscription.</p>
        <p><a href="http://localhost/index.php?action=verifyUser&token=' . $token . '">Je confirme mon inscription</a></p>
      </div>
    </body>

    </html>';

    // Create a message
    $message = (new Swift_Message('Verify your email'))
        ->setFrom('')    // TODO mettre dans param.ini
        ->setTo($userEmail)
        ->setBody($body, 'text/html');

    // Send the message
    $result = $mailer->send($message);

    if ($result > 0) {
        return true;
    } else {
        return false;
    }
}
*/

function sendMail($template, $templateVars, $subject, $to, $from = NULL, $replyTo = null) {
	global $settings;
	$transport = (new Swift_SmtpTransport($settings['smtp'], $settings['port'], $settings['ssl']))
		->setUsername($settings['mail-username'])
		->setPassword($settings['mail-password']);
	$mailer = new Swift_Mailer($transport);

	if (!isset($from)) $from = $settings['from'];
	if (!isset($replyTo)) $replyTo = $settings['reply-to'];
	$content = getMailContent($template, $templateVars);
	
    $message = (new Swift_Message())
		->setSubject($subject)
        ->setFrom($from)
        ->setTo($to)
        ->setBody($content, 'text/html');

    $result = $mailer->send($message);
	return ($result > 0);
}

function getMailContent($template, $templateVars){
	global $settings;
	if (!array_key_exists('{site_name}', $templateVars))
		$templateVars['{site_name}'] = $settings['site_name'];
	if (!array_key_exists('{site_url}', $templateVars))
		$templateVars['{site_url}'] = $settings['site_url'];

	$mailContent = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/views/mail/' . $template);
	$mailContent = str_replace(
		array_keys($templateVars),
		array_values($templateVars),
		$mailContent
	);
	return $mailContent;
}
