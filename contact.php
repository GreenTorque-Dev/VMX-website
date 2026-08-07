<?php 

	/* ==========================  Define variables ========================== */

	#Your e-mail address
	define("__TO__", "support@greencloud.live");

	#Message subject
	define("__SUBJECT__", "VMX Website Contact Form:");

	#Success message
	define('__SUCCESS_MESSAGE__', "Your message has been sent. Thank you!");

	#Error message 
	define('__ERROR_MESSAGE__', "Error, your message could not be sent.");

	#Message when one or more fields are empty
	define('__MESSAGE_EMPTY_FIELDS__', "Please fill out all required fields.");

	/* ========================  End Define variables ======================== */

	//Send mail function
	function send_mail($to,$subject,$message,$headers){
		if(@mail($to,$subject,$message,$headers)){
			echo json_encode(array('info' => 'success', 'msg' => __SUCCESS_MESSAGE__));
		} else {
			echo json_encode(array('info' => 'error', 'msg' => __ERROR_MESSAGE__));
		}
	}

	//Check e-mail validation
	function check_email($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
	}

	//Get post data
	if(isset($_POST['first_name']) and isset($_POST['last_name']) and isset($_POST['email']) and isset($_POST['message'])){
		$first_name = trim($_POST['first_name']);
		$last_name  = trim($_POST['last_name']);
		$email      = trim($_POST['email']);
		$company    = isset($_POST['company']) ? trim($_POST['company']) : '';
		$message    = trim($_POST['message']);

		if($first_name == '') {
			echo json_encode(array('info' => 'error', 'msg' => "Please enter your first name."));
			exit();
		} else if($last_name == '') {
			echo json_encode(array('info' => 'error', 'msg' => "Please enter your last name."));
			exit();
		} else if($email == '' or check_email($email) == false){
			echo json_encode(array('info' => 'error', 'msg' => "Please enter a valid e-mail."));
			exit();
		} else if($message == ''){
			echo json_encode(array('info' => 'error', 'msg' => "Please enter your message."));
			exit();
		} else {
			//Send Mail
			$to = __TO__;
			$fullName = $first_name . ' ' . $last_name;
			$subject = __SUBJECT__ . ' ' . $fullName;
			
			$email_content = '
			<html>
			<head>
			  <title>Mail from '. htmlspecialchars($fullName) .'</title>
			</head>
			<body>
			  <table style="width: 500px; font-family: arial, sans-serif; font-size: 14px; border-collapse: collapse; border: 1px solid #ddd;" border="1" cellpadding="8">
				<tr style="background-color: #f2f2f2; height: 32px;">
				  <th colspan="2" align="left">Contact Inquiry Details</th>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; font-weight: bold;">First Name:</th>
				  <td align="left">'. htmlspecialchars($first_name) .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="font-weight: bold;">Last Name:</th>
				  <td align="left">'. htmlspecialchars($last_name) .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="font-weight: bold;">E-mail:</th>
				  <td align="left"><a href="mailto:'. htmlspecialchars($email) .'">'. htmlspecialchars($email) .'</a></td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="font-weight: bold;">Company:</th>
				  <td align="left">'. ($company !== '' ? htmlspecialchars($company) : 'N/A') .'</td>
				</tr>
				<tr>
				  <th align="right" valign="top" style="font-weight: bold;">Message:</th>
				  <td align="left" style="line-height: 1.5; white-space: pre-wrap;">'. htmlspecialchars($message) .'</td>
				</tr>
			  </table>
			</body>
			</html>
			';

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: ' . $email . "\r\n";
			$headers .= 'Reply-To: ' . $email . "\r\n";

			send_mail($to,$subject,$email_content,$headers);
		}
	} else {
		echo json_encode(array('info' => 'error', 'msg' => __MESSAGE_EMPTY_FIELDS__));
	}
 ?>