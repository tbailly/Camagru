<?php

Class Mail {

	private $_to 		= null;
	private $_subject 	= null;
	private $_headers 	= null;
	private $_message 	= null;
	
	/*
	** Sender and receiver are arrays directly from SQL request
	** Expect to have values 'username', 'firstname', 'lastname' and 'mail'
	*/
	private $_sender 	= null;
	private $_receiver 	= null;

	function __construct() {
		// No parameters to give during construction
    }

    function __destruct() {
    	// No special actions required during destruction
    }

    public function __invoke() {
    	$this->send();
    }

    public function __toString() {
    	return ('Class: ' . get_class($this));
    }

    public function send() {
		$this->setHeaders();
    	if ($this->_to === null || $this->_subject === null ||
    		$this->_message === null || $this->_headers === null)
    		throw new Exception('You should set the receiver, subject and message before sending your mail');
    	$result = mail($this->_to, $this->_subject, $this->_message, $this->_headers);

    	if ($result === FALSE)
    		throw new Exception('There was an error while sending e-mail, please try again in a few seconds');
    }

    /* GETTERS AND SETTERS */
    /* Message setters */
    public function setConfirmationMessage($token) {
    	if ($this->_receiver == null)
    		throw new Exception('The receiver must be set before setting the message');

		$message = '
		<html>
			<head>
				<style>
					body {
						font-family: sans-serif;
					}
					#container {
						text-align: center;
					}
					#button {
						cursor: pointer;
						text-decoration: none;
						display: inline-block;
						font-weight: 400;
						text-align: center;
						white-space: nowrap;
						vertical-align: middle;
						-webkit-user-select: none;
						-moz-user-select: none;
						-ms-user-select: none;
						user-select: none;
						border: 1px solid transparent;
						padding: .375rem .75rem;
						font-size: 1rem;
						line-height: 1.5;
						border-radius: .25rem;
						color: #fff;
						background-color: #007bff;
						border-color: #007bff;
						transition: color .15s ease-in-out,
							background-color .15s ease-in-out,
							border-color .15s ease-in-out,
							box-shadow .15s ease-in-out;
					}
					a:hover {
						color: #fff;
						background-color: #0069d9;
						border-color: #0062cc;
					}
				</style>
			</head>
			<body>
				<div id="container">
					<h1><strong>Confirm your Camagru account</strong></h1>
					<p>Hi %s ! To finalize your registration to camagru, please click on the link below.</p>
					<a id="button" href="%s?token=%s">Activate account</a>
				</div>
			</body>
		</html>
		';

		$message = sprintf(
			$message,
			$this->_receiver['firstname'],
			'http://localhost:8080/camagru/public/confirm-account.php',
			$token
		);

		$this->_message = $message;
		$this->_subject = 'Confirm your Camagru account';
    }

    public function setNotificationMessage() {
		if ($this->_receiver == null || $this->_sender == null)
    		throw new Exception('The sender and receiver must be set before setting the message');

		$message = '
		<html>
			<head>
				<style>
					body {
						font-family: sans-serif;
					}
					#container {
						text-align: center;
					}
					#button {
						cursor: pointer;
						text-decoration: none;
						display: inline-block;
						font-weight: 400;
						text-align: center;
						white-space: nowrap;
						vertical-align: middle;
						-webkit-user-select: none;
						-moz-user-select: none;
						-ms-user-select: none;
						user-select: none;
						border: 1px solid transparent;
						padding: .375rem .75rem;
						font-size: 1rem;
						line-height: 1.5;
						border-radius: .25rem;
						color: #fff;
						background-color: #007bff;
						border-color: #007bff;
						transition: color .15s ease-in-out,
							background-color .15s ease-in-out,
							border-color .15s ease-in-out,
							box-shadow .15s ease-in-out;
					}
					#button:hover {
						color: #fff;
						background-color: #0069d9;
						border-color: #0062cc;
					}
				</style>
			</head>
			<body>
				<div id="container">
					<h1><strong>You got a new comment on a picture !</strong></h1>
					<p>Hi %s ! You got a new comment by %s %s.
					<br>To connect and see it, please click on the link below.</p>
					<a id="button" href="%s">Go on Camagru</a>
				</div>
			</body>
		</html>';

		$message = sprintf(
			$message,
			$this->_receiver['firstname'],
			$this->_sender['firstname'],
			$this->_sender['lastname'],
			'http://localhost:8080/camagru/public'
		);

		$this->_message = $message;
		$this->_subject = 'You got a new Camagru comment';
    }

    public function setResetPasswordMessage($token) {
    	if ($this->_receiver == null)
    		throw new Exception('The receiver must be set before setting the message');

		$message =
		'<html>
			<head>
				<style>
					body {
						font-family: sans-serif;
					}
					#container {
						text-align: center;
					}
					#button {
						cursor: pointer;
						text-decoration: none;
						display: inline-block;
						font-weight: 400;
						text-align: center;
						white-space: nowrap;
						vertical-align: middle;
						-webkit-user-select: none;
						-moz-user-select: none;
						-ms-user-select: none;
						user-select: none;
						border: 1px solid transparent;
						padding: .375rem .75rem;
						font-size: 1rem;
						line-height: 1.5;
						border-radius: .25rem;
						color: #fff;
						background-color: #007bff;
						border-color: #007bff;
						transition: color .15s ease-in-out,
							background-color .15s ease-in-out,
							border-color .15s ease-in-out,
							box-shadow .15s ease-in-out;
					}
					#button:hover {
						color: #fff;
						background-color: #0069d9;
						border-color: #0062cc;
					}
				</style>
			</head>
			<body>
				<div id="container">
					<h1><strong>Reset your Camagru password</strong></h1>
					<p>Hi %s ! It appears that you asked for a password reset.
					<br>To confirm and save another one, please click on the link below.</p>
					<a id="button" href="%s?token=%s">Reset password</a>
				</div>
			</body>
		</html>';

		$message = sprintf(
			$message,
			$this->_receiver['firstname'],
			'http://localhost:8080/camagru/public/reset-password.php',
			$token
		);

		$this->_message = $message;
		$this->_subject = 'Reset your Camagru account password';
    }

    /* Users setters */
	public function setSender($sender) {
		try {
			$this->setUser($sender, $this->_sender);
		} catch (Exception $e) {
			throw $e;
		}
	}

	public function setReceiver($receiver) {
		try {
			$this->setUser($receiver, $this->_receiver);
			$this->_to = $receiver['mail'];
		} catch (Exception $e) {
			throw $e;
		}
	}

	private function setUser($user, &$variable) {
		if (!isset($user['username']) || !isset($user['firstname'])
			|| !isset($user['lastname']) || !isset($user['mail']))
			throw new Exception('User should have a valid firstname, lastname, username and mail');
		$variable = array(
			'username' 	=> $user['username'],
			'firstname' => $user['firstname'],
			'lastname' 	=> $user['lastname'],
			'mail' 		=> $user['mail']
		);
	}

    /* Others setters */
	private function setHeaders() {
		$this->_headers = 	'From: tbailly-@student.42.fr' . "\r\n" .
							/*'Reply-To: tbailly-@student.42.fr' . "\r\n" .*/
							/*'Return-Path: tbailly-@student.42.fr' . "\r\n" .*/
							'X-Mailer: PHP/' . phpversion() . "\r\n" .
							'X-Sender: tbailly- < tbailly-@student.42.fr >' . "\r\n" .
							'MIME-Version: 1.0' . "\r\n" . 
							'Content-type: text/html; charset=utf-8' . "\r\n" .
							'Content-Transfer-Encoding: 8bit' . "\r\n" .
							'X-Priority: 1' . "\r\n";
    }

    /* Getters */
	public function getSender(){ return ($this->_sender); }
	public function getHeaders(){ return ($this->_headers); }
	public function getReceiver(){ return ($this->_receiver); }

}