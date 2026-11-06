<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// Function to get the client IP address
	function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	function send_email_notif_invalid($username,$ctr,$attempt_count){
		require_once('ep_custom_modules/dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
        require('ep_custom_modules/dist/handler/email_config.php');

    	$attempt_count = $attempt_count.' of 5';
        if($ctr==0){
        	$username = $username.'<span style="font-weight:normal;color:#555;"> - not registered</span>';
        	$attempt_count = 'n/a';
        }

		// $mail->setFrom('eportalmail@pricon.ph', 'Employee Portal');
		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		$mail->addAddress('jgsulit@pricon.ph');
		// $mail->addReplyTo('eportalmail@pricon.ph', 'Information System Section');
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		// $mail->addCC($email,$name);
		// $mail->addCC('lpmailom@pricon.ph');
		// $mail->addCC('rgfaustino@pricon.ph');
		// $mail->addBCC('rlatienza@pricon.ph');
		$mail->isHTML(true);
		$mail->Subject = 'Employee Portal - Invalid Login Attempt Notification';


		$body = '<div style="font-family:segoe ui, calibri;">';
		$body .= '<div style="background-color:#ffec70;font-size:2.5em;font-family:segoe ui light,segoe ui, calibri;">&nbsp;&nbsp;&nbsp;&#9888; &nbsp; Invalid Login Attempt!</div>';
		$body .= '<br>';
		$body .= '<br>Hi!';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '<br>';
		$body .= 'An invalid login attempt has been recorded by our system.';
		$body .= '<br>';
		$body .= 'Please see the details below:';
		$body .= '<br>';
		$body .= '<br>';
		$body .= 'Username:&nbsp;&nbsp;&nbsp; <strong>'.$username.'</strong>';
		$body .= '<br>';
		$body .= 'Datetime:&nbsp;&nbsp;&nbsp;&nbsp; <strong>'.date('r').'</strong>';
		$body .= '<br>';
		$body .= 'IP address:&nbsp;&nbsp;&nbsp; <strong>'.get_client_ip().'</strong>';
		$body .= '<br>';
		$body .= 'Attempts:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>'.$attempt_count.'</strong>';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '*** This is a system generated email. Please do not reply. ***';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '';
		$body .= '';
		$body .= '';
		$body .= '<span style="font-family:calibri;color:#555;font-size:.95em;">';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$body .= '</span>';
		$body .= '</div>';
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}
		// return $mail_status;
	}

	function send_email_notif_disabled($username){
		require_once('ep_custom_modules/dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
        require('ep_custom_modules/dist/handler/email_config.php');

		// $mail->setFrom('eportalmail@pricon.ph', 'Employee Portal');
		// $mail->addAddress('group-iss@pricon.ph');
		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		$mail->addAddress('jgsulit@pricon.ph');
		// $mail->addReplyTo('eportalmail@pricon.ph', 'Information System Section');
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		// $mail->addCC($email,$name);
		// $mail->addCC('lpmailom@pricon.ph');
		// $mail->addCC('rgfaustino@pricon.ph');
		// $mail->addBCC('rlatienza@pricon.ph');
		$mail->isHTML(true);
		$mail->Subject = 'Employee Portal - Disabled Account Notification';


		$body = '<div style="font-family:segoe ui, calibri;">';
		$body .= '<div style="background-color:#ff7070;font-size:2.5em;font-family:segoe ui light,segoe ui, calibri;">&nbsp;&nbsp;&nbsp;&#9888; &nbsp; Disabled Account!</div>';
		$body .= '<br>';
		$body .= '<br>Hi!';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '<br>';
		$body .= 'An account has been disabled due to several invalid login attempts.';
		$body .= '<br>';
		$body .= 'Please see the details below:';
		$body .= '<br>';
		$body .= '<br>';
		$body .= 'Username:&nbsp;&nbsp;&nbsp; <strong>'.$username.'</strong>';
		$body .= '<br>';
		$body .= 'Datetime:&nbsp;&nbsp;&nbsp;&nbsp; <strong>'.date('r').'</strong>';
		$body .= '<br>';
		$body .= 'IP address:&nbsp;&nbsp;&nbsp; <strong>'.get_client_ip().'</strong>';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '*** This is a system generated email. Please do not reply. ***';
		$body .= '<br>';
		$body .= '<br>';
		$body .= '';
		$body .= '';
		$body .= '';
		$body .= '<span style="font-family:calibri;color:#555;font-size:.95em;">';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$body .= '</span>';
		$body .= '</div>';
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}
		// return $mail_status;
	}

	function fn_save_login_logs($username,$type,$remarks){
		require_once('ep_custom_modules/dist/handler/query.php');
		write_logs::getInstance()->save_login_logs($username,$type,$remarks);
	}

	function fn_increment($username){
		// require_once('./query.php');
		require_once('ep_custom_modules/dist/handler/query.php');
	

		$msg = '';
		$remarks = 'invalid-login';
		$user_info = fn_select_user_info($username);
		$tmp_attempt_count = $user_info['attempt_count'];
		$tmp_disabled = $user_info['disabled'];
		$tmp_ctr = $user_info['ctr'];

		if( $tmp_ctr > 0 ){//has user
			//do nothing, disabled already, ignore this, already has validation in user.php which will be run first, so matic,
			//only not yet disabled account will processed here

			$tmp_attempt_count++;
			if( $tmp_attempt_count >= 5 ){
				$tmp_attempt_count = 5;
				$tmp_disabled = 1;
			}

			$result 				= '';
			$array_fields 			= array('attempt_count',
											'disabled',
											'datetime_disabled_enabled');
			$array_values 			= array($tmp_attempt_count,
											$tmp_disabled,
											date('Y-m-d H:i:s'));
			$table 	   				= 'rka_users';
			$idname 				= 'username';
			$pkid 					= $username;
			db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
			$msg = '<strong>You only have '.(5-$tmp_attempt_count).' login attempt(s) left.</strong>';
			$remarks = 'login-invalid x'.$tmp_attempt_count;
		}
		send_email_notif_invalid($username,$tmp_ctr,$tmp_attempt_count);
		fn_save_login_logs($username,7,$remarks);


		//for disabled account
		if( $tmp_ctr > 0 && $user_info['disabled'] == 0 && $tmp_disabled == 1 ){//newly disabled
			send_email_notif_disabled($username);
			$remarks = 'login-disabled';
			fn_save_login_logs($username,8,$remarks);
			$msg = '<strong>Your account has been disabled. Contact ISS local 205 to re-activate your account.</strong>';
		}

		$return = array();
		$return['attempt_count'] = $attempt_count;
		$return['disabled'] = $disabled;
		$return['ctr'] = $ctr;
		$return['msg'] = $msg;

		return $return;
	}

	function fn_select_user_info($username){
		// require_once('./query.php');
		require_once('ep_custom_modules/dist/handler/query.php');
		$return = array();
		$result 				= '';
		$array_fields 			= array('attempt_count',
										'disabled');
		$table 	   				= 'rka_users';
		$joins 	   				= '';
		$sql_where 				= 'WHERE username="'.$username.'"';
		$sql_order 				= '';

		$sql_limit 				= '';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 					= 0;
		$attempt_count 			= 0;
		$disabled 				= 0;

		while($row = mysqli_fetch_array($result)){
			$attempt_count 				= $row['attempt_count'];
			$disabled 					= $row['disabled'];
			$ctr++;
		}

		$return['username'] = $username;
		$return['attempt_count'] = $attempt_count;
		$return['disabled'] = $disabled;
		$return['ctr'] = $ctr;
		return $return;
	}


	function fn_incrementxx($username){
		// require_once('./query.php');
		require_once('ep_custom_modules/dist/handler/query.php');
		$msg = '';
		$fn_select_invalid 	= fn_select_invalid($username);
		$disabled 			= $fn_select_invalid['disabled'];
		$attempt_count 		= $fn_select_invalid['attempt_count'];
		$ctr 				= $fn_select_invalid['ctr'];
		$attempt_count++;
		if( $attempt_count >=5 ){
			$attempt_count 	= 5;
			$disabled 	= 1;
		}
		$remarks = 'login-invalid';
		if( $fn_select_invalid['ctr'] > 0 ){///has rec in db
			$result 				= '';
			$array_fields 			= array('attempt_count',
											'disabled',
											'datetime_disabled_enabled');
			$array_values 			= array($attempt_count,
											$disabled,
											date('Y-m-d H:i:s'));
			$table 	   				= 'rka_users';
			$idname 				= 'username';
			$pkid 					= $username;
			db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
			$msg = '<strong>You only have '.(5-$attempt_count).' login attempt(s) left.</strong>';
			$remarks = 'login-invalid x'.$attempt_count;
		}
		send_email_notif_invalid($username,$ctr,$attempt_count);
		fn_save_login_logs($username,7,$remarks);
		if( $fn_select_invalid['ctr'] > 0 && $disabled==1 ){///has rec in db
			if( $fn_select_invalid['attempt_count'] != $attempt_count ){
				send_email_notif_disabled($username);
				$remarks = 'login-disabled';
				fn_save_login_logs($username,8,$remarks);
			}
			$msg = '<strong>Your account has been disabled. Contact ISS local 205 to re-activate your account.</strong>';
		}
		$return = array();
		$return['attempt_count'] = $attempt_count;
		$return['disabled'] = $disabled;
		$return['ctr'] = $ctr;
		$return['msg'] = $msg;

		return $return;
	}

	function fn_reset_attemtps($username){
		require_once('ep_custom_modules/dist/handler/query.php');
		$result 				= '';
		$array_fields 			= array('attempt_count');
		$array_values 			= array(0);
		$table 	   				= 'rka_users';
		$idname 				= 'username';
		$pkid 					= $username;
		db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		$remarks = 'login';
		fn_save_login_logs($username,6,$remarks);
	}
?>