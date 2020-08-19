<?php

require_once 'vendor/autoload.php';

function sendMail($template, $templateVars, $subject, $to, $from = NULL, $replyTo = NULL) {
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
