<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	// session_set_cookie_params(['samesite' => 'None']);
	// header('Set-Cookie: *; SameSite=None; Secure');

	// $cookieParams = session_get_cookie_params();
	// $cookieParams['samesite'] = "None";
	// session_set_cookie_params($cookieParams);

	// $cookieParams = session_get_cookie_params();
	// $cookieParams['samesite'] = "None";
	// session_set_cookie_params(
	// 	$cookieParams['lifetime'],
	// 	$cookieParams['path'].'; samesite=None',
	// 	$cookieParams['domain'],
	// 	$cookieParams['secure'],
	// 	$cookieParams['httponly']
	// );

	session_start();
	// header("Access-Control-Allow-Origin: *");
	// header('Set-Cookie: *; SameSite=None; Secure');
	if(is_ajax()){
		if(isset($_POST['action']) && !empty($_POST['action'])){
			switch($_POST['action']){
				case 'load_attendance_record'		: load_attendance_record(); 		break;
				case 'load_latest_pay_period'   	: load_latest_pay_period();     	break;
				case 'load_latest_pay_period_rapid' : load_latest_pay_period_rapid();   break;
				case 'load_latest_pay_period_rapid_all' : load_latest_pay_period_rapid_all();   break;
				case 'load_employee_info'       	: load_employee_info();         	break;
				case 'load_employee_info_rapid'    	: load_employee_info_rapid();      	break;
				case 'load_email_address'			: load_email_address();				break;
				case 'load_complaints'				: load_complaints(); 				break;
				case 'load_complaints2'				: load_complaints2(); 				break;
				case 'load_complaint'				: load_complaint(); 				break;
				case 'update_complaint'				: update_complaint();				break;
				case 'send_to_my_email'				: send_to_my_email(); 				break;
				case 'send_complaint'				: send_complaint();					break;
				case 'send_complaint_test'			: send_complaint_test();					break;
				case 'post_pay_slip_data'			: post_pay_slip_data(); 			break;
				case 'load_pay_slip_records'		: load_pay_slip_records(); 			break;
				case 'load_pay_slip_records_data'	: load_pay_slip_records_data(); 	break;
				case 'load_profile'					: load_profile();					break;
				case 'load_employees_cec'			: load_employees_cec();				break;
				case 'load_activity_logs'			: load_activity_logs();	 			break;
				case 'send_employee_cec'			: send_employee_cec();				break;
				// case 'load_list_of_users'			: load_list_of_users();			break;
				case 'load_list_of_users_rapid'		: load_list_of_users_rapid();		break;
				case 'create_new_account'			: create_new_account();				break;
				case 'select_employee_details'		: select_employee_details();		break;
				// case 'load_employee_information'	: load_employee_information();		break;
				case 'load_employee_information_rapid'	: load_employee_information_rapid();		break;
				case 'load_loan_information'		: load_loan_information();			break;
				case 'load_pay_slip_history'		: load_pay_slip_history();			break;
				case 'load_loan_payments'			: load_loan_payments(); 			break;
				case 'reset_account_password'		: reset_account_password(); 		break;

				case 'load_disabled_users'			: load_disabled_users();			break;
				case 'enable_user'					: enable_user();					break;

				case 'load_complaints_approval'		: load_complaints_approval(); 		break;
				case 'get_immediate_superior'		: get_immediate_superior(); 		break;
				case 'get_superior_email'			: get_superior_email(); 			break;

				case 'load_complaints_approval_details'		: load_complaints_approval_details(); 	break;
				case 'update_complaint_approval'			: update_complaint_approval(); 			break;
				
				case 'load_memo'							: load_memo(); 							break;
				case 'load_memo_fin'						: load_memo_fin(); 						break;

				case 'upload_memo_document'					: upload_memo_document(); 				break;
				case 'approved_memo'						: approved_memo(); 						break;
				case 'conform_memo'							: conform_memo(); 						break;			
				case 'disapproved_memo'						: disapproved_memo(); 					break;
				
				case 'load_cec'								: load_cec(); 							break;
				case 'request_cec'							: request_cec(); 						break;
				case 'approved_cec'							: approved_cec(); 						break;
				case 'disapproved_cec'						: disapproved_cec(); 					break;

				/** New Module 2023 */
				case 'load_payroll_info'					: load_payroll_info(); 					break;
				case 'create_payroll_info'					: create_payroll_info(); 				break;
				case 'read_payroll_info'					: read_payroll_info(); 					break;
				case 'update_payroll_info'					: update_payroll_info(); 				break;

			}
		}
	} 
	function is_ajax(){
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	function load_attendance_record(){
		require_once('./query.php');
		if(isset($_POST['action_child'])=='1'&&isset($_POST['emp_no'])!=''){
			$emp_no = $_POST['emp_no'];			
		}
		else{
			$emp_no = $_SESSION['s_username'];
		}

		$return 				= $_POST;
		$ctr 					= 0;
		$str 					= 0;
		$sql_where_main			= 'WHERE (A.empno="'.$emp_no.'" AND date BETWEEN "'.$return['date_start'].'" AND "'.$return['date_end'].'")';

		$late_ot_dates 		= array();
		$late_leave_dates 	= array();

		switch($return['cut_off']){
			case '1':
				$result 				= '';
				$array_fields 			= array('ot_date');
				$table 	   				= 'tbl_late_ot';
				$joins 	   				= '';
				$sql_where 				= 'WHERE empno="'.$emp_no.'" AND ot_date_paid="'.$return['year'].'-'.$return['month'].'-10" AND ot_date NOT BETWEEN "'.$return['date_start'].'" AND "'.$return['date_end'].'" AND ot_count > 0';
				$sql_order 				= 'ORDER BY ot_date ASC';
				$sql_limit 				= '';
				$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				while($row = mysqli_fetch_array($result)){
					$sql_where_main		.= ' OR (A.empno="'.$emp_no.'" AND date="'.$row['ot_date'].'")';
					$late_ot_dates[] 	= $row['ot_date'];
				}

				$result 				= '';
				$array_fields 			= array('date_of_leave');
				$table 	   				= 'tbl_leave_cutoff_date';
				$joins 	   				= '';
				$sql_where 				= 'WHERE empno="'.$emp_no.'" AND cutoff_date="'.$return['year'].'-'.$return['month'].'-10" AND date_of_leave NOT BETWEEN "'.$return['date_start'].'" AND "'.$return['date_end'].'" AND days_paid > 0';
				$sql_order 				= 'ORDER BY date_of_leave ASC';
				$sql_limit 				= '';
				$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				while($row = mysqli_fetch_array($result)){
					$sql_where_main 	.= ' OR (A.empno="'.$emp_no.'" AND date="'.$row['date_of_leave'].'")';
					$late_leave_dates[] = $row['date_of_leave'];
				}
				break;
			case '2':
				$result 				= '';
				$array_fields 			= array('ot_date');
				$table 	   				= 'tbl_late_ot';
				$joins 	   				= '';
				$sql_where 				= 'WHERE empno="'.$emp_no.'" AND ot_date_paid="'.$return['year'].'-'.$return['month'].'-20" AND ot_date NOT BETWEEN "'.$return['date_start'].'" AND "'.$return['date_end'].'" AND ot_count > 0';
				$sql_order 				= 'ORDER BY ot_date ASC';
				$sql_limit 				= '';
				$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				while($row = mysqli_fetch_array($result)){
					$sql_where_main 	.= ' OR (A.empno="'.$emp_no.'" AND date="'.$row['ot_date'].'")';
					$late_ot_dates[] 	= $row['ot_date'];
				}

				$result 				= '';
				$array_fields 			= array('date_of_leave');
				$table 	   				= 'tbl_leave_cutoff_date';
				$joins 	   				= '';
				$sql_where 				= 'WHERE empno="'.$emp_no.'" AND cutoff_date="'.$return['year'].'-'.$return['month'].'-20" AND date_of_leave NOT BETWEEN "'.$return['date_start'].'" AND "'.$return['date_end'].'" AND days_paid > 0';
				$sql_order 				= 'ORDER BY date_of_leave ASC';
				$sql_limit 				= '';
				$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
				while($row = mysqli_fetch_array($result)){
					$sql_where_main 	.= ' OR (A.empno="'.$emp_no.'" AND date="'.$row['date_of_leave'].'")';
					$late_leave_dates[] = $row['date_of_leave'];
				}
				break;				
			default:
				break;
		}

		$result 				= '';
		$array_fields 			= array('date',
										'time_in',
										'mode_in',
										'time_out',
										'mode_out',
										'ot1 as regular_ot',
										'(ot3 + ot3a) as sunday_ot',
										'(ot2 + ot2a + ot5 + ot5a) as special_holiday_ot',
										'(ot4 + ot4a + ot6 + ot6a) as holiday_ot',
										'total_undertime',
										'nightdiff as night_diff',
										'absent',
										'leavecode',
										'leavenum',
										'B.ot_count',
										'B.ot_type');
		$table 	   				= 'vw_daily_tfm AS A ';
		$joins 	   				= 'LEFT JOIN tbl_late_ot AS B ON A.empno=B.empno AND A.date=B.ot_date AND B.logdel=0';
		$sql_order 				= 'ORDER BY date DESC';
		$sql_limit 				= '';
		$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where_main,$sql_order,$sql_limit);
		$query					= 0;
		$return['total_lates'] 	= 0;
		$array_fields_child 	= array('rc_id','rc_status','rc_seen_status','rc_concern_date','rc_timekeeper_remarks');
		$table_child 	   		= 'rka_complaints';
		$joins_child 	   		= 'LEFT JOIN rka_users ON rka_complaints.rc_username=rka_users.username';
		$sql_order_child 		= 'ORDER BY rc_status ASC';
		$sql_limit_child 		= '';

		$return['total_regular_ot']                 	= 0;
		$return['total_sunday_ot']                  	= 0;
		$return['total_special_holiday_ot']         	= 0;
		$return['total_holiday_ot']                 	= 0;
		$return['total_undertime']                  	= 0;
		$return['total_night_diff']                 	= 0;
		$return['total_absent']                     	= 0;
		$return['total_sl_vl']                      	= 0;

		while($row = mysqli_fetch_array($result)){

			unset($result_child);
			$sql_where_child 				= 'WHERE rc_concern_date="'.$row['date'].'" AND rc_username="'.$emp_no.'"';
			$result_child 					= db_ep::getInstance()->select_query($array_fields_child,$table_child,$joins_child,$sql_where_child,$sql_order_child,$sql_limit_child);
			if($row_child = mysqli_fetch_array($result_child)){
				$return['rc_id'][$ctr]  							= $row_child['rc_id'];
				$return['rc_status'][$ctr]  						= $row_child['rc_status'];
				$return['rc_timekeeper_remarks'][$ctr]  			= trim($row_child['rc_timekeeper_remarks']);

				if($row_child['rc_seen_status']==1){
					$return['rc_concern_date'][$str] 				= date('M d',strtotime($row_child['rc_concern_date']));
					$str++;
				}

				$return['rc_seen_status'][$ctr]  					= $row_child['rc_seen_status'];
				$return['complaint_active_status'][$ctr]			= 1;
			}
			else{
				$return['rc_status'][$ctr]  						= $row_child['rc_status'];
				$return['rc_id'][$ctr]								= '';
				$return['complaint_active_status'][$ctr]			= 0;
			}

			$date1 = $row['time_in'];
			$date2 =  $row['time_out'];
			 
			$date1Timestamp = strtotime($date1);
			$date2Timestamp = strtotime($date2);
			 
			$difference = $date2Timestamp - $date1Timestamp;

			$return['date'][$ctr] 	                = '<span style="display:none;">'.$row['date'].'</span><span class="formatted-date">'.date('M d, Y',strtotime($row['date'])).'</span>';
			switch($row['mode_in']){
				case 'IN':						
						if($row['time_in']<'11:45:00'&&$row['time_in']>='07:31:00'){
							$return['time_in'][$ctr]               	= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'</span>';
							$return['total_lates']             		= $return['total_lates']+1;
						}
						else if($row['time_in']<'24:00:00'&&$row['time_in']>='21:31:00'){
							$return['time_in'][$ctr]               	= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'</span>';
							$return['total_lates']             		= $return['total_lates']+1;
						}
						else{
							$return['time_in'][$ctr]               	= date('h:i A',strtotime($row['time_in']));
						}
						$return['time_out'][$ctr] 	            	= date('h:i A',strtotime($row['time_out']));

						break;
				case 'BUS LATE':
						$return['time_in'][$ctr]               		= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'(<i>BL</i>)</span>';
						$return['time_out'][$ctr] 	            	= date('h:i A',strtotime($row['time_out']));
					break;
				case 'OB':
						$return['time_in'][$ctr]               		= date('h:i A',strtotime($row['time_in']));
						$return['time_out'][$ctr] 	            	= date('h:i A',strtotime($row['time_out']));
					break;
				case '':
					switch($row['time_in']){
						case 'yec':
							$return['time_in'][$ctr]               	= '<i>YEC</i>';
							$return['time_out'][$ctr] 	            = '<i>YEC</i>';
							break;
						case 'VL':
							$return['time_in'][$ctr]               	= '<i>VL</i>';
							$return['time_out'][$ctr] 	            = '<i>VL</i>';
							break;
						case 'SL':
							$return['time_in'][$ctr]               	= '<i>SL</i>';
							$return['time_out'][$ctr] 	            = '<i>SL</i>';
							break;
						case 'ML':
							$return['time_in'][$ctr]               	= '<i>ML</i>';
							$return['time_out'][$ctr] 	            = '<i>ML</i>';
							break;
						case 'holiday':
							$return['time_in'][$ctr]               	= '<i>HOL</i>';
							$return['time_out'][$ctr] 	            = '<i>HOL</i>';
							break;
						case 'FL':
							$return['time_in'][$ctr]               	= '<i>FL</i>';
							$return['time_out'][$ctr] 	            = '<i>FL</i>';
							break;
						case 'do':
							$return['time_in'][$ctr]               	= '<i>DO</i>';
							$return['time_out'][$ctr] 	            = '<i>DO</i>';
							break;
						case 'BRL':
							$return['time_in'][$ctr]               	= '<i>BRL</i>';
							$return['time_out'][$ctr] 	            = '<i>BRL</i>';
							break;
						case 'BL':
							$return['time_in'][$ctr]               	= '<i>BL</i>';
							$return['time_out'][$ctr] 	            = '<i>BL</i>';
							break;
						case 'BDL':
							$return['time_in'][$ctr]               	= '<i>BDL</i>';
							$return['time_out'][$ctr] 	            = '<i>BDL</i>';
							break;
						default:
							$return['time_in'][$ctr]               	= 'NO DATA';
							$return['time_out'][$ctr] 	            = 'NO DATA';	
							break;
					}
					break;
				case 'undefined':
						$return['time_in'][$ctr]               		= 'NO DATA';
						$return['time_out'][$ctr] 	            	= 'NO DATA';				
					break;
				default:
					$return['time_in'][$ctr]               			= date('h:i A',strtotime($row['time_in']));
					$return['time_out'][$ctr] 	            		= date('h:i A',strtotime($row['time_out']));					
					break;
			}

			if($row['time_out']==''){
				$return['time_out'][$ctr]							= '---';
				$return['time_rendered'][$ctr]						= '---';
			}
			else{
				$return['time_rendered'][$ctr]						= number_format(abs($difference/60/60),2,'.','');
			}

			$return['regular_ot'][$ctr]             				= number_format($row['regular_ot'],2,'.','');
			$return['sunday_ot'][$ctr] 	            				= number_format($row['sunday_ot'],2,'.','');
			$return['special_holiday_ot'][$ctr] 					= number_format($row['special_holiday_ot'],2,'.','');
			$return['holiday_ot'][$ctr] 	        				= number_format($row['holiday_ot'],2,'.','');

			if(in_array($row['date'], $late_ot_dates)){
				$return['undertime'][$ctr] 	           				= '0.00';
			}
			else{
				$return['undertime'][$ctr] 	            			= number_format($row['total_undertime'],2,'.','');
				$return['total_undertime']             				= $return['total_undertime']+$row['total_undertime'];
			}

			$return['night_diff'][$ctr] 	       					= number_format($row['night_diff'],2,'.','');

			if(in_array($row['date'], $late_leave_dates)){
				$return['absent'][$ctr] 	            			= '0.00';

			}
			else{
				$return['absent'][$ctr] 	            			= number_format($row['absent'],2,'.','');
				$return['total_absent']                 			= $return['total_absent']+$row['absent'];
			}

			$return['leave_code'][$ctr] 	        				= $row['leavecode'];
			$return['sl_vl'][$ctr] 	                				= number_format($row['leavenum'],2,'.','');
			

			switch($row['ot_type']){
				case '1':
					$return['regular_ot'][$ctr]     				= number_format($row['regular_ot'],2,'.','').'<i style="font-size:90%;">(Late)</i>';
					break;
				case '2': 
					$return['holiday_ot'][$ctr] 					= number_format($row['holiday_ot'],2,'.','').'<i style="font-size:90%;">(Late)</i>';
					break;
				case '3': 
					$return['sunday_ot'][$ctr] 	    				= number_format($row['sunday_ot'],2,'.','').'<i style="font-size:90%;">(Late)</i>';
					break;
				case '4':
					$return['holiday_ot'][$ctr]    					= number_format($row['holiday_ot'],2,'.','').'<i style="font-size:90%;">(Late)</i>';
					break;
				case '5':
					$return['special_holiday_ot'][$ctr]    			= number_format($row['special_holiday_ot'],2,'.','').'<i style="font-size:90%;">(Late)</i>';
					break;
				case '6':
					$return['holiday_ot'][$ctr]     				= number_format($row['holiday_ot'],2,'.','').'<i style="font-size:90%;">(Late)</i>';
					break;
				default:
					break;
			}

			$return['total_regular_ot']             				= $return['total_regular_ot']+$row['regular_ot'];
			$return['total_sunday_ot']              				= $return['total_sunday_ot']+$row['sunday_ot'];
			$return['total_special_holiday_ot']     				= $return['total_special_holiday_ot']+$row['special_holiday_ot'];
			$return['total_holiday_ot']             				= $return['total_holiday_ot']+$row['holiday_ot'];
			$return['total_night_diff']             				= $return['total_night_diff']+$row['night_diff'];
			$return['total_sl_vl']                  				= $return['total_sl_vl']+$row['leavenum'];
			$ctr++;
		}
		
		if($return['total_lates']<=0){
			$return['total_lates'] = '---';
		}
		else{
			if($return['total_lates']>1){
				$return['total_lates'] = $return['total_lates'].' tardies';
			}
			else{
				$return['total_lates'] = $return['total_lates'].' tardy';
			}
		}
		$return['total_regular_ot']                 				= number_format($return['total_regular_ot'],2,'.','');
		$return['total_sunday_ot']                  				= number_format($return['total_sunday_ot'],2,'.','');
		$return['total_special_holiday_ot']         				= number_format($return['total_special_holiday_ot'],2,'.','');
		$return['total_holiday_ot']                 				= number_format($return['total_holiday_ot'],2,'.','');
		$return['total_undertime']                  				= number_format($return['total_undertime'],2,'.','');
		$return['total_night_diff']                 				= number_format($return['total_night_diff'],2,'.','');
		$return['total_absent']                     				= number_format($return['total_absent'],2,'.','');
		$return['total_sl_vl']                      				= number_format($return['total_sl_vl'],2,'.','');
		
		$return['ctr'] = $ctr;
		$return['str'] = $str;
		echo json_encode($return);			
	}
	
	/* Original Code */
	/*function load_latest_pay_period(){
		require_once('./query.php');

		if(isset($_POST['action_child'])=='1'&&isset($_POST['emp_no'])!=''){
			$emp_no = $_POST['emp_no'];			
		}
		else{
			$emp_no = $_SESSION['s_username'];
		}

		$db_hris			= new db_hris;
		$result 			= '';
		$array_fields 		= array('Description','Pkid');
		$table 	   			= 'e_portal_cut_off_data';
		$joins				= '';
		$sql_where			= 'WHERE EndingDate >= "'.date('01/01/2019').'" AND EmpNo="'.$emp_no.'"';
		$sql_order	 		= 'ORDER BY Pkid DESC';
		$result 			= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$rka				= 0;
		$ctr 				= 0;		
		$array_fields_child	= array('rppsd_status');
		$table_child 	   	= 'rka_posted_pay_slip_data';
		$joins_child 	   	= '';
		$sql_order_child 	= '';
		$sql_limit_child 	= '';
		while($row = mssql_fetch_array($result)){
			unset($result_child);

			if($emp_no=='N110'){
				$return['Pkid'][$ctr]    		= $row['Pkid'];
				$return['Description'][$ctr] 	= $row['Description'];
				$ctr++;
			}
			else{
				$sql_where_child 	= "WHERE rppsd_fkid='".$row['Pkid']."'";
				$result_child 		= db_ep::getInstance()->select_query($array_fields_child,$table_child,$joins_child,$sql_where_child,$sql_order_child,$sql_limit_child);
				if($row_child = mysqli_fetch_array($result_child)){			
					$return['Pkid'][$ctr]    		= $row['Pkid'];
					$return['Description'][$ctr] 	= $row['Description'];
					$ctr++;
				}				
			}
		}
		
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}*/

	function load_latest_pay_period_rapid(){
		require_once('./query.php');

		if(isset($_POST['action_child'])=='1'&&isset($_POST['emp_no'])!=''){
			$emp_no = $_POST['emp_no'];
		}
		else{
			$emp_no = $_SESSION['s_username'];
		}

		$result 			= '';
		$array_fields 		= array('a.*, b.employee_no');
		$table 	   			= 'tbl_payroll_info a';
		$joins				= 'inner join tbl_payroll_info_data b on b.fkid_payroll_info = a.id';
		$sql_where			= 'WHERE b.employee_no="'.$emp_no.'" AND posted="Y"
								'; 
								// 07192024 by Nessa
								//a.date_end >= "'.date('01/01/2020').'" AND 
								// AND YEAR(a.date_cut_off)='.date('Y').' 
		// $sql_where			= 'WHERE a.date_end >= "'.date('01/01/2022').'" AND b.employee_no="'.$emp_no.'" ';
		$sql_order	 		= 'ORDER BY a.id DESC';
		$sql_limit 			= '';
		$result 			= db_rp::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 				= 0;		
		// return $result;
		while($row = mysqli_fetch_array($result)){
			$return['id'][$ctr]    			= $row['id'];
			$return['description'][$ctr]  	= $row['description'];
			$return['cut_off'][$ctr]  		= $row['cut_off'];
			$return['date_start'][$ctr]  	= date('M d -',strtotime($row['date_start']));
			$return['date_end'][$ctr]  		= date('d, Y',strtotime($row['date_end']));
			$return['date_cut_off'][$ctr]  	= date('M d, Y',strtotime($row['date_cut_off']));
			$return['md_cut_off'][$ctr]  	= date('M Y',strtotime($row['date_cut_off']));

			$ctr++;
		}
		
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	function load_latest_pay_period_rapid_all(){
		require_once('./query.php');

		if(isset($_POST['action_child'])=='1'&&isset($_POST['emp_no'])!=''){
			$emp_no = $_POST['emp_no'];
		}
		else{
			$emp_no = $_SESSION['s_username'];
		}

		$result 			= '';
		$array_fields 		= array('a.*, b.employee_no');
		$table 	   			= 'tbl_payroll_info a';
		$joins				= 'inner join tbl_payroll_info_data b on b.fkid_payroll_info = a.id';
		$sql_where			= 'WHERE posted="Y"								
								'; 
								// 07192024 by Nessa
								//a.date_end >= "'.date('01/01/2020').'" AND 
								// AND YEAR(a.date_cut_off)='.date('Y').' 
		// $sql_where			= 'WHERE a.date_end >= "'.date('01/01/2022').'" AND b.employee_no="'.$emp_no.'" ';
		$sql_order	 		= 'GROUP BY date_cut_off, description ORDER BY a.id DESC';
		$sql_limit 			= '';
		$result 			= db_rp::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 				= 0;		
		// return $result;
		while($row = mysqli_fetch_array($result)){
			$return['id'][$ctr]    			= $row['id'];
			$return['description'][$ctr]  	= $row['description'];
			$return['cut_off'][$ctr]  		= $row['cut_off'];
			$return['date_start'][$ctr]  	= date('M d -',strtotime($row['date_start']));
			$return['date_end'][$ctr]  		= date('d, Y',strtotime($row['date_end']));
			$return['date_cut_off'][$ctr]  	= date('M d, Y',strtotime($row['date_cut_off']));
			$return['md_cut_off'][$ctr]  	= date('M Y',strtotime($row['date_cut_off']));

			$ctr++;
		}
		
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}
	
	/* Original Code */
	/*function load_employee_info(){
		require_once('./query.php');

		if(isset($_POST['action_child'])=='1'&&isset($_POST['emp_no'])!=''){
			$emp_no = $_POST['emp_no'];			
		}
		else{
			$emp_no = $_SESSION['s_username'];
		}

		$return 			= $_POST;
		$db_hris			= new db_hris;
		$db_payroll			= new db_payroll;


		$result 			= '';
		$array_fields 		= array('EmpNo', 'PayType');
		$table 	   			= 'e_portal_employees';
		$joins 	   			= '';
		$sql_where 			= "WHERE EmpNo='".$emp_no."'";
		$sql_order 			= '';
		$result 			= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$ctr                = 0;
		if($row = mssql_fetch_array($result)){
			$return['PayType']	 	= $row['PayType'];
		}

		$result 			= '';
		$array_fields 		= array('StartingDate');
		$table 	   			= 'e_portal_payslip_data';
		$joins 	   			= '';
		$sql_where 			= "WHERE FKWorksheet='".$return['pay_period']."'";
		$sql_order 			= '';
		$result 			= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$ctr                = 0;
		if($row = mssql_fetch_array($result)){
			$start_date = date('01/01/Y',strtotime($row['StartingDate']));
		}


		$result 			= '';
		$array_fields 		= array('Row as row','FKWorksheet as worksheet_id','LastName','FirstName','Department','ExemptBaseCode','EmpNo','Description','BasicPay');
		$table 	   			= 'e_portal_cut_off_data';
		$joins 	   			= "";
		$sql_where 			= "WHERE EmpNo='".$emp_no."' AND EndingDate >= '".$start_date."'";
		$sql_order 			= 'ORDER BY EndingDate';
		$result 			= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$max 				= mssql_num_rows($result);
		$return['detector'] = 1;
		$return['sum_gross']= 0;
		$return['sum_tax']	= 0;
		$return['sum_prem']	= 0;
		$rka 				= 0;
		while($row = mssql_fetch_array($result)){
			$result_child		= '';
			$array_fields 		= array('Value');
			$table 	   			= 'e_portal_payslip_data';
			$joins 	   			= '';
			$sql_where 			= "WHERE FieldName='GROSS' AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
			$sql_order 			= '';
			$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
			while($row_child = mssql_fetch_array($result_child)){
				$return['sum_gross']  = $return['sum_gross']+$row_child['Value'];
				$rka++;
			}

			unset($result_child);
			unset($row_child);
			unset($array_fields);
			$result_child		= '';
			$array_fields 		= array('Value');
			$table 	   			= 'e_portal_payslip_data';
			$joins 	   			= '';
			$sql_where 			= "WHERE FieldName='WTAX' AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
			$sql_order 			= '';
			$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
			while($row_child = mssql_fetch_array($result_child)){
				$return['sum_tax']  = $return['sum_tax']+$row_child['Value'];
			}

			unset($result_child);
			unset($row_child);
			unset($array_fields);
			$result_child		= '';
			$array_fields 		= array('Value');
			$table 	   			= 'e_portal_payslip_data';
			$joins 	   			= '';
			// $sql_where 			= "WHERE (FieldName='SSSPRE' OR FieldName='MEDEMP' OR FieldName='PBGPRE') AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
			$sql_where 			= "WHERE (FieldName='SSSPRE' OR FieldName='MEDEMP' OR FieldName='PBGPRE' OR FieldName='PEMCO') AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
			$sql_order 			= '';
			$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
			while($row_child = mssql_fetch_array($result_child)){
				$return['sum_prem']  = $return['sum_prem']+$row_child['Value'];
			}

			if($return['pay_period']==$row['worksheet_id']){
				$return['EmpNo'] 			= $row['EmpNo'];
				$return['LastName'] 		= $db_payroll->detect_ene(utf8_encode($row['LastName']));
				$return['FirstName'] 		= $db_payroll->detect_ene(utf8_encode($row['FirstName']));
				$return['Department']	 	= $row['Department'];
				$return['Description'] 		= $row['Description'];
				$return['ExemptBaseCode'] 	= $row['ExemptBaseCode'];
				$return['ymdhis']			= $row['worksheet_id'].$row['row'];
				$basic_pay					= $row['BasicPay'];
				// if( $row['EmpNo'] == 'O068' ){
				// 	$return['LastName'] 		= 'Banadero';//due to null values when has special character 'enye'
				// }
				// else if ( $row['EmpNo'] == 'M074' ){
				// 	$return['LastName'] 		= 'Quinones';//due to null values when has special character 'enye'
				// }else if ( $row['EmpNo'] == 'I004' ){
				// 	$return['LastName'] 		= 'Penaflorida';//due to null values when has special character 'enye'
				// }

				unset($result_child);
				unset($row_child);
				unset($array_fields);
				$array_fields 		= array('FieldName as field_name','Value as field_value');
				$table 	   			= 'e_portal_payslip_data';
				$joins 	   			= '';
				$sql_where 			= "WHERE FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
				$sql_order 			= '';
				$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
				$ctr                = 0;
				$return['field_name']['ADVAN'] 	= 0;
				$return['field_name']['OTHER'] 	= 0;
				$return['field_name']['BONUSX'] = 0;
				$return['field_name']['RICE'] 	= 0;
				$return['field_name']['BASIC'] 	= 0;
				while($row_child = mssql_fetch_array($result_child)){
				    $field_name 						= $row_child['field_name'];
					$return['field_name'][$field_name]  = floatval(trim($row_child['field_value']));
					$ctr++;
				}
				$return['detector'] = 0;
				break;
			}
		}
		$rka 							= (24-$rka);
		$return['forecast_earnings'] 	= (($basic_pay/2)*$rka);
		$return['ctr'] 					= $ctr;

		$return['result'] = json_encode($result);
		echo json_encode($return);
	}*/

	function load_employee_info_rapid(){
		require_once('./query.php');

		if(isset($_POST['action_child'])=='1'&&isset($_POST['emp_no'])!=''){
			$emp_no = $_POST['emp_no'];			
		}
		else{
			$emp_no = $_SESSION['s_username'];
		}
		
		$pay_period = $_POST['pay_period'];		
		// return $emp_no;
		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.*, b.id as pid_id, b.*, c.personal_exemption, d.gross_income_ytd, d.forecast_earnings, d.less_ytd_tax_exempt, d.yt_tax_withheld, e.working_days');
		$table 	   			= 'tbl_payroll_info a';
		$joins 	   			= 'left join tbl_payroll_info_data b on b.fkid_payroll_info = a.id
								left join tbl_salary_report c on c.employee_no = b.employee_no
								left join tbl_annual_tax d on d.emp_no = b.employee_no
								inner join tbl_working_days e
								';
		$sql_where 			= "WHERE b.employee_no='".$emp_no."' AND a.id ='".$pay_period."' AND a.posted ='Y' ";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_rp::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr                = 0;
		// print_r(mysqli_fetch_assoc($result));
		while($row = mysqli_fetch_array($result)){
			$return['description'][$ctr]	= $row['description'];
			$return['cut_off'][$ctr]	 	= $row['cut_off'];
			$return['date_start'][$ctr]	 	= $row['date_start'];
			$return['date_end'][$ctr]	 	= $row['date_end'];
			$return['date_cut_off'][$ctr]	= $row['date_cut_off'];
			$return['posted'][$ctr]	 		= $row['posted'];
			$return['ymdhis'][$ctr]	 		= $row['id'];
			$return['sd']					= date('F d -',strtotime($row['date_start']));
			$return['ed']					= date('d, Y',strtotime($row['date_end']));
			$return['pay_period']  			= $return['sd']. ' '.$return['ed'];

			$return['employee_no'][$ctr]	 	= $row['employee_no'];
			$return['employee_name'][$ctr]	 	= $row['employee_name'];
			$return['working_days'][$ctr]	 	= $row['working_days'];
			
			if($row['description'] == 1){ // 1-daily, 2-monthly, 4-jsss
				$return['regular_pay'][$ctr]	 	= $row['regular_pay'] * $row['working_days']; 
				$return['forecast_earnings'][$ctr]	= $row['forecast_earnings']-$return['regular_pay'][$ctr];
			}else{
				$return['regular_pay'][$ctr]	 	= $row['regular_pay'];
				$return['forecast_earnings'][$ctr]	= $row['forecast_earnings']-$row['regular_pay'];
			}
			$return['thirteenth_month'][$ctr]	= $row['thirteenth_month'];
			
			$return['ot1'][$ctr]	 			= $row['ot1'];
			$return['ot2'][$ctr]	 			= $row['ot2'];
			$return['ot2a'][$ctr]	 			= $row['ot2a'];
			$return['ot3'][$ctr]	 			= $row['ot3'];
			$return['ot3a'][$ctr]	 			= $row['ot3a'];
			$return['ot4'][$ctr]	 			= $row['ot4'];
			$return['ot4a'][$ctr]	 			= $row['ot4a'];
			$return['ot5'][$ctr]	 			= $row['ot5'];
			$return['ot5a'][$ctr]	 			= $row['ot5a'];
			$return['ot6'][$ctr]	 			= $row['ot6'];
			$return['ot6a'][$ctr]	 			= $row['ot6a'];
			$return['nightdiff'][$ctr]	 		= $row['nightdiff'];
			
			/* OVERTIME/NIGHT DIFF. */
			$return['ot_nd_amnt'][$ctr]			= $row['ot1_amount']+
												$row['ot2_amount']+
												$row['ot2a_amount']+
												$row['ot3_amount']+
												$row['ot3a_amount']+
												$row['ot4_amount']+
												$row['ot4a_amount']+
												$row['ot5_amount']+
												$row['ot5a_amount']+
												$row['ot6_amount']+
												$row['ot6a_amount']+
												$row['nightdiff_amount'];
			/* SL/VL/BL/SD/SDL */
			$return['sl'][$ctr]	 				= $row['sl'];
			$return['vl'][$ctr]	 				= $row['vl'];

			$return['bl_bdl'][$ctr]	 			= $row['bl']+$row['bdl'];

			$return['shutdown'][$ctr]	 		= $row['shutdown'];

			$return['sl_vl_bl_sd_sdl_amnt'][$ctr]	= $row['sl_amount']+
													$row['vl_amount']+
													$row['bl_amount']+
													$row['bdl_amount']+
													$row['shutdown_amount'];

			$return['ecola'][$ctr]	= $row['ecola'];
			$return['salary_adj'][$ctr]	= $row['salary_adj'];
			$return['allowance'][$ctr]	= $row['allowance'];
			$return['abs_days'][$ctr]	= $row['abs_days'];
			$return['undertime'][$ctr]	= $row['undertime'];

			/* Absences + Undertime */
			$return['abs_ut_amnt'][$ctr]		= $row['abs_amnt']+$row['undertime_amnt'];
			$return['gross_pay'][$ctr]			= $row['gross_pay'];
			$return['sss_premium'][$ctr]		= $row['sss_premium'];
			$return['ph_premium'][$ctr]			= $row['ph_premium'];
			$return['pagibig'][$ctr]			= $row['pagibig'];
			// $return['sssl_ssscl_amnt'][$ctr]	= $row['sss_loan']+$row['sss_cal_loan'];
			$return['sssl_ssscl_amnt'][$ctr]	= $row['sss_loan']+$row['sss_cal_loan']+$row['sss_condonation']; // 09192024 by nessa
			// $return['pil_picl_amnt'][$ctr]		= $row['pagibig_loan']+$row['pagibig_cal_loan'];
			$return['pil_picl_amnt'][$ctr]		= $row['pagibig_loan']+$row['pagibig_cal_loan']+$row['pagibig_safe_loan'];

			$return['hipar'][$ctr]				= $row['hipar'];
			$return['adv_other_deduc'][$ctr]	= $row['adv_other_deduc'];
			
			// 05212026 by Nessa
			$return['tax_adj'][$ctr]			= $row['tax_adj'];
			$return['with_tax'][$ctr]			= $row['with_tax'];

			// var with_tax = parseFloat(data['with_tax']) + parseFloat(data['tax_adj']);

			$return['with_taxx'][$ctr] = $row['tax_adj'] + $row['with_tax'];
			
			$return['net_pay'][$ctr]			= $row['net_pay'];
			$return['other_earnings'][$ctr]		= $row['other_earnings'];

			$return['personal_exemption'][$ctr]				= $row['personal_exemption'];
			$return['ytd_gross_income'][$ctr]				= $row['gross_income_ytd']+$row['gross_pay'];

			$return['less_ytd_tax_exemption'][$ctr]			= $row['less_ytd_tax_exempt']+$row['sss_premium']+$row['ph_premium']+$row['pagibig'];
			$return['ytd_taxable_earnings_forecast'][$ctr]	= ($return['ytd_gross_income'][$ctr]+$return['forecast_earnings'][$ctr])-$return['less_ytd_tax_exemption'][$ctr];
			$return['ytd_tax_witheld'][$ctr]				= $row['yt_tax_withheld']+$row['with_tax'];

			/* Get department */
			$result_childx 			= '';
			$array_fields_childx 	= array('Department, Section, EmpNo');
			$table_childx 	   		= 'vw_emp_hris';
			$joins_childx 	   		= '';
			$sql_where_childx 		= 'WHERE EmpNo = "'.$row['employee_no'].'"';
			$sql_order_childx		= '';
			$sql_limit_childx 		= '';
			$result_childx 			= db_hris_web::getInstance()->select_query($array_fields_childx,$table_childx,$joins_childx,$sql_where_childx,$sql_order_childx,$sql_limit_childx);
			if($row_childx = mysqli_fetch_array($result_childx)){
				// $return['Department'][$ctr] 	= $row_childx['Department'].' ('.$row_childx['Section'].')';
				$return['Department'][$ctr] 	= $row_childx['Department'].' ('.$row_childx['Section'].')';

				if ($row_childx['Department'] == '-'){
					$return['Department'][$ctr] 	= $row_childx['Section'];
				}else{
					$return['Department'][$ctr] 	= $row_childx['Department'];
				}

			}

			/*$result_childxx 			= '';
			$array_fields_childxx 	= array('other_earnings', 'type_of_earning', 'emp_no');
			$table_childxx 	   		= 'tbl_taxes';
			$joins_childxx 	   		= '';
			$sql_where_childxx 		= 'WHERE emp_no = "S185"';
			$sql_order_childxx		= '';
			$sql_limit_childxx 		= '';
			$result_childxx 			= db_rp::getInstance()->select_query($array_fields_childxx,$table_childxx,$joins_childxx,$sql_where_childxx,$sql_order_childxx,$sql_limit_childxx);
			$ctr = 0;
			while($row_childxx = mysqli_fetch_array($result_childxx)){
				print_r($row_childxx);
				$oe_adj = 0;
				 for($i=0; $i< count($row_childxx) ; $i++){
				 	$oe_adj = $oe_adj + $row_childxx['other_earnings'][$i];


				 	if( $row_childxx['type_of_earning'][$i] == 1){ // bonus adj
                        // $return['b_other_earnings'][$ctr]           = $row_childxx['other_earnings'][$i]; 
                        $return['b_other_earnings'][$ctr]           = 1; 
                    }else if ( $row_childxx['type_of_earning'][$i] == 2){ // 13th adj
                        $return['t_other_earnings'][$ctr]           = 2; 
                    }else if ( $row_childxx['type_of_earning'][$i] == 3){ // sss prem adj
                        $return['s_other_earnings'][$ctr]           = 3; 
                    }else{
                        $return['other_earnings'][$ctr]           = 0; 
                    }

				 }

 			// 	$return['emp_no'][$ctr] 			= $row_childxx['emp_no'];
				// $return['other_earnings'][$ctr] 	= $$oe_adj;
				// $return['type_of_earning'][$ctr] 	= $row_childxx['type_of_earning'];

			}*/

			// return count($row_childxx);
			$ctr++;
			
		}

		$return['ctr'] 					= $ctr;

		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	function load_email_address(){
		require_once('./query.php');
		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('email');
		$table 	   			= 'rka_users';
		$joins 	   			= ' ';
		$sql_where 			= 'WHERE username = "'.$_SESSION['s_username'].'"';
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 				= 0;
		if($row = mysqli_fetch_array($result)){
			$return['email'] = $row['email'];
		}
		echo json_encode($return);				
	}

	function load_complaints(){
		require_once('./query.php');
		$curYear = date('Y'); 

		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('rka_complaints.rc_id',
										'rka_complaints.rc_concern_date',
										'rka_complaints.rc_message',
										'rka_complaints.rc_username',
										'rka_complaints.rc_adate',
										'rka_complaints.rc_status',
										'rka_complaints.rc_approval_status',
										'rka_complaints.rc_approver',
										'rka_complaints.rc_approval_remarks',
										'rka_users.name');
		$table 	   				= 'rka_complaints';
		$joins 	   				= 'LEFT JOIN rka_users ON rka_complaints.rc_username=rka_users.username';
		$sql_where 				= 'WHERE ((rc_status = 0 AND rc_approval_status = 0) OR
										 (rc_status = 1 AND  rc_approval_status = 2))';
		// $sql_order 				= 'ORDER BY rc_approval_status ASC, rc_concern_date DESC';
		$sql_order 				= 'ORDER BY rc_id DESC';

		$sql_limit 				= '';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 					= 0;
		while($row = mysqli_fetch_array($result)){
			$return['rc_id'][$ctr] 				= $row['rc_id'];
			$return['rc_concern_date'][$ctr] 	= date('F d, Y',strtotime($row['rc_concern_date']));
			$return['rc_message'][$ctr] 		= $row['rc_message'];
			$return['rc_username'][$ctr] 		= $row['rc_username'];
			$return['rc_adate'][$ctr] 			= $row['rc_adate'];
			$return['rc_approval_status'][$ctr] = $row['rc_approval_status'];
			$return['rc_approver'][$ctr] 		= $row['rc_approver'];
			$return['rc_approval_remarks'][$ctr]= $row['rc_approval_remarks'];
			$return['old_status'] 				= get_count_old_status($row['rc_approval_status']);
			$return['active_status'] 			= get_count_active_status($row['rc_approval_status']);
			$return['close_status'] 			= get_count_close_status($row['rc_approval_status']);
			// $return['closed_status'] 			= get_count_closed_status($row['rc_status']);
			$return['name'][$ctr] 				= utf8_encode($row['name']);
			$return['x_ago'][$ctr]				= time_elapsed_string($row['rc_adate']);
			$ctr++;
		}
		$return['ctr'] = $ctr;

		echo json_encode($return);			
	}

	function load_complaints2(){
		require_once('./query.php');
		$curYear = date('Y'); 

		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('rka_complaints.rc_id',
										'rka_complaints.rc_concern_date',
										'rka_complaints.rc_message',
										'rka_complaints.rc_username',
										'rka_complaints.rc_adate',
										'rka_complaints.rc_status',
										'rka_complaints.rc_approval_status',
										'rka_complaints.rc_approver',
										'rka_complaints.rc_approval_remarks',
										'rka_users.name');
		$table 	   				= 'rka_complaints';
		$joins 	   				= 'LEFT JOIN rka_users ON rka_complaints.rc_username=rka_users.username';
		$sql_where 				= 'WHERE rc_approval_status = 2 AND rc_status = 0';
		$sql_order 				= 'ORDER BY rc_approval_status ASC, rc_concern_date DESC';

		$sql_limit 				= '';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 					= 0;
		while($row = mysqli_fetch_array($result)){
			$return['rc_id'][$ctr] 				= $row['rc_id'];
			$return['rc_concern_date'][$ctr] 	= date('F d, Y',strtotime($row['rc_concern_date']));
			$return['rc_message'][$ctr] 		= $row['rc_message'];
			$return['rc_username'][$ctr] 		= $row['rc_username'];
			$return['rc_adate'][$ctr] 			= $row['rc_adate'];
			$return['rc_approval_status'][$ctr] = $row['rc_approval_status'];
			$return['rc_approver'][$ctr] 		= $row['rc_approver'];
			$return['rc_approval_remarks'][$ctr]= $row['rc_approval_remarks'];
			$return['old_status'] 				= get_count_old_status($row['rc_approval_status']);
			$return['active_status'] 			= get_count_active_status($row['rc_approval_status']);
			$return['close_status'] 			= get_count_close_status($row['rc_approval_status']);
			// $return['closed_status'] 			= get_count_closed_status($row['rc_status']);
			$return['name'][$ctr] 				= utf8_encode($row['name']);
			$return['x_ago'][$ctr]				= time_elapsed_string($row['rc_adate']);
			$ctr++;
		}
		$return['ctr'] = $ctr;

		echo json_encode($return);			
	}

	function get_count_old_status($status){
		require_once('./query.php');
		 
		$array_fields 	= array('COUNT(rc_approval_status) as old_status');
		$table 			= 'rka_complaints';
		$joins 			= '';
		$sql_where 		= "WHERE rc_approval_status = '0'";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$old_status	= 0;
		
		while($row 	= mysqli_fetch_array($result)){
			$old_status = $row['old_status'];
		}
		return $old_status;
	}

	function get_count_active_status($status){
		require_once('./query.php');
		 
		$array_fields 	= array('COUNT(rc_approval_status) as active_status');
		$table 			= 'rka_complaints';
		$joins 			= '';
		$sql_where 		= "WHERE rc_status = 0 AND rc_approval_status = 2";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$active_status	= 0;
		
		while($row 	= mysqli_fetch_array($result)){
			$active_status = $row['active_status'];
		}
		return $active_status;
	}

	function get_count_close_status($status){
		require_once('./query.php');
		 
		$array_fields 	= array('COUNT(rc_approval_status) as close_status');
		$table 			= 'rka_complaints';
		$joins 			= '';
		$sql_where 		= "WHERE rc_status = 1";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$close_status	= 0;
		
		while($row 	= mysqli_fetch_array($result)){
			$close_status = $row['close_status'];
		}
		return $close_status;
	}

	function load_complaint(){
		require_once('./query.php');
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('rka_complaints.rc_id',
										'rka_complaints.rc_concern_date',
										'rka_complaints.rc_message',
										'rka_complaints.rc_username',
										'rka_complaints.rc_timekeeper_remarks',
										'rka_complaints.rc_seen_status',
										'rka_complaints.rc_status',
										'rka_complaints.rc_adate',
										'rka_complaints.rc_approval_remarks',
										'rka_complaints.rc_approval_status',
										'rka_complaints.rc_category_type',
										'rka_complaints.rc_attachment',
										'rka_complaints.rc_attachment_filename',
										'rka_users.name',
										'rka_users.email');
		$table 	   				= 'rka_complaints';
		$joins 	   				= 'LEFT JOIN rka_users ON rka_complaints.rc_username=rka_users.username';
		$sql_where 				= 'WHERE rc_id="'.$return['rc_id'].'"';
		$sql_order 				= '';
		$sql_limit 				= '';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['rc_id'] 					= $row['rc_id'];
			$return['rc_concern_date'] 			= date('m/d/Y',strtotime($row['rc_concern_date']));
			$return['rc_message'] 				= $row['rc_message'];
			$return['rc_username'] 				= $row['rc_username'];
			$return['rc_status'] 				= $row['rc_status'];
			$return['rc_seen_status'] 			= $row['rc_seen_status'];
			$return['rc_timekeeper_remarks'] 	= $row['rc_timekeeper_remarks'];
			$return['rc_adate'] 				= $row['rc_adate'];
			$return['name'] 					= $row['name'];
			$return['email'] 					= $row['email'];
			$return['rc_approval_remarks'] 		= $row['rc_approval_remarks'];
			$return['rc_approval_status'] 		= $row['rc_approval_status'];
			$return['rc_category_type'] 		= $row['rc_category_type'];
			$return['rc_attachment'] 			= $row['rc_attachment'];
			$return['rc_attachment_filename'] 	= $row['rc_attachment_filename'];
		}

		$result 				= '';
		$array_fields 			= array('date',
									'time_in',
									'mode_in',
									'time_out',
									'mode_out',
									'ot1 as regular_ot',
									'(ot3 + ot3a) as sunday_ot',
									'(ot2 + ot2a + ot5 + ot5a) as special_holiday_ot',
									'(ot4 + ot4a + ot6 + ot6a) as holiday_ot',
									'total_undertime',
									'nightdiff as night_diff',
									'absent',
									'leavecode',
									'leavenum');
		$table 	   				= 'vw_daily_tfm';
		$joins 	   				= '';
		$sql_where 				= 'WHERE date <> "" AND empno="'.$return['rc_username'].'" AND date="'.date('Y-m-d',strtotime($return['rc_concern_date'])).'"';
		$sql_order 				= '';
		$sql_limit 				= '';
		$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$return['sd'] 				= db_otks::getInstance()->query_select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$query					= 0;
		if($row = mysqli_fetch_array($result)){

			$date1 = $row['time_in'];
			$date2 =  $row['time_out'];
			 
			$date1Timestamp = strtotime($date1);
			$date2Timestamp = strtotime($date2);
			 
			$difference = $date2Timestamp - $date1Timestamp;

			$return['date'] 	                = '<span style="display:none;">'.$row['date'].'</span><span class="formatted-date">'.date('M d, Y',strtotime($row['date'])).'</span>';
			switch($row['mode_in']){
				case 'IN':						
						if($row['time_in']<'11:45:00'&&$row['time_in']>='07:31:00'){
							$return['time_in']               	= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'</span>';
						}
						else if($row['time_in']<'24:00:00'&&$row['time_in']>='21:31:00'){
							$return['time_in']               	= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'</span>';
						}
						else{
							$return['time_in']               	= date('h:i A',strtotime($row['time_in']));
						}
						$return['time_out'] 	            = date('h:i A',strtotime($row['time_out']));

						break;
				case 'BUS LATE':
						$return['time_in']               	= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'(<i>BL</i>)</span>';
						$return['time_out'] 	            = date('h:i A',strtotime($row['time_out']));
					break;
				case 'OB':
						$return['time_in']               	= date('h:i A',strtotime($row['time_in']));
						$return['time_out'] 	            = date('h:i A',strtotime($row['time_out']));
					break;
				case '':
					switch($row['time_in']){
						case 'yec':
							$return['time_in']               	= '<i>YEC</i>';
							$return['time_out'] 	            = '<i>YEC</i>';
							break;
						case 'VL':
							$return['time_in']               	= '<i>VL</i>';
							$return['time_out'] 	            = '<i>VL</i>';
							break;
						case 'SL':
							$return['time_in']               	= '<i>SL</i>';
							$return['time_out'] 	            = '<i>SL</i>';
							break;
						case 'ML':
							$return['time_in']               	= '<i>ML</i>';
							$return['time_out'] 	            = '<i>ML</i>';
							break;
						case 'holiday':
							$return['time_in']               	= '<i>HOL</i>';
							$return['time_out'] 	            = '<i>HOL</i>';
							break;
						case 'FL':
							$return['time_in']               	= '<i>FL</i>';
							$return['time_out'] 	            = '<i>FL</i>';
							break;
						case 'do':
							$return['time_in']               	= '<i>DO</i>';
							$return['time_out'] 	            = '<i>DO</i>';
							break;
						case 'BRL':
							$return['time_in']               	= '<i>BRL</i>';
							$return['time_out'] 	            = '<i>BRL</i>';
							break;
						case 'BL':
							$return['time_in']               	= '<i>BL</i>';
							$return['time_out'] 	            = '<i>BL</i>';
							break;
						case 'BDL':
							$return['time_in']               	= '<i>BDL</i>';
							$return['time_out'] 	            = '<i>BDL</i>';
							break;
						default:
							$return['time_in']               	= 'NO DATA';
							$return['time_out'] 	            = 'NO DATA';	
							break;
					}
					break;
				case 'undefined':
						$return['time_in']               	= 'NO DATA';
						$return['time_out'] 	            = 'NO DATA';				
					break;
				default:
					$return['time_in']               	= date('h:i A',strtotime($row['time_in']));
					$return['time_out'] 	            = date('h:i A',strtotime($row['time_out']));					
					break;
			}

			if($row['time_out']==''){
				$return['time_out']				= '---';
				$return['time_rendered']			= '---';
			}
			else{
				$return['time_rendered']			= number_format(abs($difference/60/60),2,'.','');
			}

			$return['regular_ot']           = number_format($row['regular_ot'],2,'.','');
			$return['sunday_ot'] 	        = number_format($row['sunday_ot'],2,'.','');
			$return['special_holiday_ot'] 	= number_format($row['special_holiday_ot'],2,'.','');
			$return['holiday_ot'] 	        = number_format($row['holiday_ot'],2,'.','');
			$return['undertime'] 	        = number_format($row['total_undertime'],2,'.','');
			$return['night_diff'] 	        = number_format($row['night_diff'],2,'.','');
			$return['absent'] 	            = number_format($row['absent'],2,'.','');
			$return['leave_code'] 	        = $row['leavecode'];
			$return['sl_vl'] 	            = number_format($row['leavenum'],2,'.','');

			// $ctr++;
		}

		// if($return['user']!=='1'){
			$array_fields 			= array('rc_seen_status');
			$array_values 			= array('0');
			$table 	   				= 'rka_complaints';
			$idname 				= 'rc_id';
			$pkid 					= $return['rc_id'];
			db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		// }

		echo json_encode($return);	
	}

	// function load_pay_slip_records(){
	// 	require_once('./query.php');
	// 	$db_payroll			= new db_payroll;
	// 	$result 			= '';
	// 	$array_fields 		= array('Pkid','Description','StartingDate','EndingDate','Lastupdate','CutOff');
	// 	$table 	   			= 'e_portal_payslip_records';
	// 	$joins				= '';
	// 	$sql_where			= 'WHERE EndingDate >= "'.date('01/01/2018').'"';
	// 	$sql_order	 		= 'ORDER BY Pkid DESC';
	// 	$result 			= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 	$ctr 				= 0;		
	// 	$array_fields_child	= array('rppsd_status');
	// 	$table_child 	   	= 'rka_posted_pay_slip_data';
	// 	$joins_child 	   	= '';
	// 	$sql_order_child 	= '';
	// 	$sql_limit_child 	= '';	
	// 		// write_logs::getInstance()->save_logs('1',$query);
	// 	while($row = mssql_fetch_array($result)){			
	// 		$sql_where_child 	= "WHERE rppsd_fkid='".$row['Pkid']."'";
	// 		$result_child 		= db_ep::getInstance()->select_query($array_fields_child,$table_child,$joins_child,$sql_where_child,$sql_order_child,$sql_limit_child);
	// 		if($row_child = mysqli_fetch_array($result_child)){		
	// 			$return['Posted'][$ctr]			= '1';
	// 		}
	// 		else{
	// 			$return['Posted'][$ctr]			= '0';
	// 		}
	// 		$return['Pkid'][$ctr] 				= $row['Pkid'];
	// 		$return['Description'][$ctr] 		= $row['Description'];
	// 		$return['StartingDate'][$ctr]		= date('M d, Y',strtotime($row['StartingDate']));
	// 		$return['EndingDate'][$ctr] 		= date('M d, Y',strtotime($row['EndingDate']));
	// 		$return['CutOff'][$ctr] 			= date('M d, Y',strtotime($row['CutOff']));
	// 		$return['Lastupdate'][$ctr]			= date('M d, Y h:i:s A',strtotime($row['Lastupdate']));
	// 		$ctr++;
	// 	}
	// 	$return['ctr'] = $ctr;
	// 	echo json_encode($return);	
	// }

	// function load_pay_slip_records_data(){
	// 	require_once('./query.php');
	// 	$return 			= $_POST;
	// 	$db_hris			= new db_hris;
	// 	$result 			= '';
	// 	$array_fields 		= array('EmpNo','FirstName','LastName');
	// 	$table 	   			= 'e_portal_cut_off_data';
	// 	$joins				= '';
	// 	$sql_where			= 'WHERE FkWorksheet = "'.$return['pay_period'].'"';
	// 	$sql_order	 		= 'ORDER BY EmpNo ASC';
	// 	$result 			= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 	$ctr 				= 0;		
	// 	while($row = mssql_fetch_array($result)){
	// 		$return['EmpNo'][$ctr] 		= $row['EmpNo'];
	// 		$return['FirstName'][$ctr] 	= utf8_encode($row['FirstName']);
	// 		$return['LastName'][$ctr] 	= utf8_encode($row['LastName']);
	// 		$ctr++;
	// 	}
	// 	$return['ctr'] = $ctr;
	// 	echo json_encode($return);
	// }

	// function load_profile(){
	// 	require_once('./query.php');
	// 	$return 				= $_POST;
	// 	$result 				= '';
	// 	$array_fields 			= array('FirstName','LastName','DateHired','Position','EmpNo','Section','DOBirth');
	// 	$table 	   				= 'tbl_EmployeeInfo';
	// 	$joins 	   				= 'LEFT JOIN tbl_Position ON tbl_EmployeeInfo.fkPosition=tbl_Position.pkid';
	// 	$joins 	   				.= ' LEFT JOIN tbl_Section ON tbl_EmployeeInfo.fkSection=tbl_Section.pkid';
	// 	$sql_where 				= 'WHERE EmpNo="'.$_SESSION['s_username'].'"';
	// 	$sql_order 				= '';
	// 	$sql_limit 				= '';
	// 	$result 				= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	if($row = mysqli_fetch_array($result)){
	// 		$return['FirstName'] 				= utf8_encode($row['FirstName']);
	// 		$return['LastName'] 				= utf8_encode($row['LastName']);
	// 		$return['DateHired'] 				= date('F d, Y',strtotime($row['DateHired']));
	// 		$return['Position'] 				= $row['Position'];
	// 		$return['EmpNo'] 					= $row['EmpNo'];
	// 		$return['Section'] 					= $row['Section'];
	// 		$return['DOBirth'] 					= $row['DOBirth'];
	// 	}

	// 	unset($result);
	// 	$array_fields 			= array('previous_balance_VL',
	// 									'previous_balance_SL',
	// 									'credit_VL',
	// 									'credit_SL',
	// 									'current_avail_VL',
	// 									'current_avail_SL',
	// 									'total_VL_used',
	// 									'total_SL_used',
	// 									'total_VL_balance',
	// 									'total_SL_balance');
	// 	$table 	   				= 'tbl_leaves_balance';
	// 	$joins 	   				= '';
	// 	$sql_where 				= 'WHERE EmpNo="'.$_SESSION['s_username'].'"';
	// 	$sql_order 				= '';
	// 	$sql_limit 				= '';
	// 	$result 				= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	if($row = mysqli_fetch_array($result)){
	// 		$return['previous_balance_VL'] 		= number_format($row['previous_balance_VL'],1);
	// 		$return['previous_balance_SL'] 		= number_format($row['previous_balance_SL'],1);
	// 		$return['credit_VL'] 				= number_format($row['credit_VL'],1);
	// 		$return['credit_SL'] 				= number_format($row['credit_SL'],1);
	// 		$return['current_avail_VL'] 		= number_format($row['current_avail_VL'],1);
	// 		$return['current_avail_SL'] 		= number_format($row['current_avail_SL'],1);
	// 		$return['total_VL_used'] 			= number_format($row['total_VL_used'],1);
	// 		$return['total_SL_used'] 			= number_format($row['total_SL_used'],1);
	// 		$return['total_VL_balance'] 		= number_format($row['total_VL_balance'],1);
	// 		$return['total_SL_balance'] 		= number_format($row['total_SL_balance'],1);
	// 	}
		
	// 	unset($result);
	// 	$array_fields 			= array('C.leave_code',
	// 									'B.date_absent_from',
	// 									'B.no_of_days',
	// 									'B.no_of_paid');
	// 	$table 	   				= 'tbl_leave_application AS A';
	// 	$joins 	   				= 'LEFT JOIN tbl_leave_dates AS B ON A.pkid=B.fk_leave_app';
	// 	$joins 	   				.= ' LEFT JOIN tbl_leave_applied AS C ON A.leave_applied=C.pkid';
	// 	$sql_where 				= 'WHERE A.EmpNo="'.$_SESSION['s_username'].'" AND A.logdel="0" AND B.logdel="0"';
	// 	$sql_order 				= 'ORDER BY B.date_absent_from DESC';
	// 	$sql_limit 				= 'LIMIT 30';
	// 	$result 				= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	$ctr_my_leaves			= 0;
	// 	while($row = mysqli_fetch_array($result)){
	// 		$return['leave_code'][$ctr_my_leaves] 				= $row['leave_code'];
	// 		$return['date_absent_from'][$ctr_my_leaves] 		= date('M d',strtotime($row['date_absent_from']));
	// 		$return['no_of_days'][$ctr_my_leaves] 				= number_format($row['no_of_days'],2);
	// 		$return['no_of_paid'][$ctr_my_leaves] 				= number_format($row['no_of_paid'],2);
	// 		$ctr_my_leaves++;
	// 	}
	// 	$return['ctr_my_leaves'] = $ctr_my_leaves;
		
	// 	unset($result);
	// 	$result 				= '';
	// 	$array_fields 			= array('rka_complaints.rc_timekeeper_remarks',
	// 									'rka_complaints.rc_concern_date',
	// 									'rka_complaints.rc_message',
	// 									'rka_complaints.rc_status ',
	// 									'rka_complaints.rc_adate');
	// 	$table 	   				= 'rka_complaints';
	// 	$joins 	   				= 'LEFT JOIN rka_users ON rka_complaints.rc_username=rka_users.username';
	// 	$sql_where 				= 'WHERE rc_username="'.$_SESSION['s_username'].'"';
	// 	$sql_order 				= 'ORDER BY rc_concern_date DESC';
	// 	$sql_limit 				= 'LIMIT 10';
	// 	$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	// $ctr_my_complaints 				= db_ep::getInstance()->query_select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	$ctr_my_complaints		= 0;
	// 	while($row = mysqli_fetch_array($result)){
	// 		$return['rc_concern_date'][$ctr_my_complaints] 				= date('M d',strtotime($row['rc_concern_date']));
	// 		$return['rc_message'][$ctr_my_complaints] 					= $row['rc_message'];
	// 		$return['rc_timekeeper_remarks'][$ctr_my_complaints] 		= $row['rc_timekeeper_remarks'];
	// 		$return['rc_status'][$ctr_my_complaints] 					= $row['rc_status'];
	// 		$return['x_ago'][$ctr_my_complaints]						= time_elapsed_string($row['rc_adate']);
	// 		$ctr_my_complaints++;
	// 	}
	// 	$return['ctr_my_complaints'] = $ctr_my_complaints;

	// 	//-03102020
	// 	unset($result);
	// 	$result 				= '';
	// 	$array_fields 			= array('tkovertime_main.req_status','tk_overtime_details.ot_date','tk_overtime_details.filed_ot');
	// 	$table 	   				= 'tk_overtime_details';
	// 	// $joins 	   				= 'LEFT JOIN db_hris.vw_EmpInfo_Rapid ON tk_overtime_details.created_by=vw_EmpInfo_Rapid.username';
	// 	$joins 	   				= 'LEFT JOIN tkovertime_main ON tk_overtime_details.fkid_ot_main=tkovertime_main.pkid';
	// 	// $sql_where 				= 'WHERE db_hris.vw_EmpInfo_Rapid.EmpNo="'.$_SESSION['s_username'].'" AND tk_overtime_details.logdel="0"';
	// 	$sql_where 				= 'WHERE tk_overtime_details.empno ="'.$_SESSION['s_username'].'" AND tk_overtime_details.logdel="0"';
	// 	$sql_order 				= 'ORDER BY ot_date DESC';
	// 	$sql_limit 				= 'LIMIT 30';
	// 	$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	// $ctr_overtime_requests 				= db_ep::getInstance()->query_select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	$ctr_overtime_requests	= 0;
	// 	while($row = mysqli_fetch_array($result)){
	// 		$return['ot_date'][$ctr_overtime_requests] 			= date('M d',strtotime($row['ot_date']));
	// 		$return['req_status'][$ctr_overtime_requests] 		= $row['req_status'];
	// 		$return['filed_ot'][$ctr_overtime_requests] 		= $row['filed_ot'];
	// 		$ctr_overtime_requests++;
	// 	}
	// 	$return['ctr_overtime_requests'] = $ctr_overtime_requests;

	// 	//-02032021
	// 	unset($result);
	// 	$db_hris					= new db_hris;
	// 	$result 					= '';
	// 	$array_fields 				= array('EmpNo',
	// 										'LastName',
	// 										'FirstName',
	// 										'Department',
	// 										'BasicPay',
	// 										'AccountNumber',);
	// 	$table 	   					= 'e_portal_employees';
	// 	$joins 	   					= "";
	// 	$sql_where 					= "WHERE Status='1' AND EmpNo='".$_SESSION['s_username']."'";
	// 	$sql_order 					= 'ORDER BY EmpNo DESC';
	// 	$result 					= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 	$ctr 						= 0;
	// 	while($row = mssql_fetch_array($result)){
	// 		$return['EmpNo'][$ctr]				= $row['EmpNo'];
	// 		$return['BasicPay'][$ctr]			= number_format($row['BasicPay'],2,".",",");
	// 		$return['Ecola'][$ctr]				= number_format($row['Ecola'],2,".",",");
	// 		$return['AccountNumber'][$ctr]		= $row['AccountNumber'];


	// 	}


	// 	$return['ses'] = session_get_cookie_params();//isset($_SESSION)?$_SESSION:'';

	// 	echo json_encode($return);
	// }

	// function load_employees_cec(){
	// 	require_once('./query.php');	

	// 	$return 					= $_POST;
	// 	$db_hris					= new db_hris;
	// 	$db_payroll					= new db_payroll;

	// 	$result 					= '';
	// 	$array_fields 				= array('Row as row','FKWorksheet as worksheet_id','Description','Ecola');
	// 	$table 	   					= 'e_portal_cut_off_data';
	// 	$joins 	   					= "";
	// 	$sql_where 					= "WHERE EmpNo='".strtoupper($return['cec_employee_number'])."' AND StartingDate BETWEEN '".'1/1/'.$return['cec_year']."' AND '".'12/31/'.$return['cec_year']."'";
	// 	$sql_order 					= 'ORDER BY EndingDate';
	// 	$result 					= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 	$return['sd'] 				= $db_hris->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order);	
	// 	$max 						= mssql_num_rows($result);
	// 	$return['sum_sl']			= 0;
	// 	$return['sum_vl']			= 0;
	// 	$return['sum_bonus']		= 0;
	// 	$return['sum_13th_month']	= 0;
	// 	while($row = mssql_fetch_array($result)){
	// 		$return['Ecola'] = $row['Ecola'];
	// 		if (strpos(strtolower($row['Description']),'sick')!==false){
	// 			unset($result_child);
	// 			unset($row_child);
	// 			unset($array_fields);
	// 			$result_child		= '';
	// 			$array_fields 		= array('Value');
	// 			$table 	   			= 'e_portal_payslip_data';
	// 			$joins 	   			= '';
	// 			$sql_where 			= "WHERE FieldName='LCVX' AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
	// 			$sql_order 			= '';
	// 			$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 			while($row_child = mssql_fetch_array($result_child)){
	// 				$return['sum_sl']  = $return['sum_sl']+$row_child['Value'];
	// 			}
	// 		}

	// 		if (strpos(strtolower($row['Description']),'vacation')!==false){
	// 			unset($result_child);
	// 			unset($row_child);
	// 			unset($array_fields);
	// 			$result_child		= '';
	// 			$array_fields 		= array('Value');
	// 			$table 	   			= 'e_portal_payslip_data';
	// 			$joins 	   			= '';
	// 			$sql_where 			= "WHERE FieldName='LCVX' AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
	// 			$sql_order 			= '';
	// 			$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 			while($row_child = mssql_fetch_array($result_child)){
	// 				$return['sum_vl']  = $return['sum_vl']+$row_child['Value'];
	// 			}
	// 		}

	// 		unset($result_child);
	// 		unset($row_child);
	// 		unset($array_fields);
	// 		$result_child		= '';
	// 		$array_fields 		= array('Value');
	// 		$table 	   			= 'e_portal_payslip_data';
	// 		$joins 	   			= '';
	// 		$sql_where 			= "WHERE FieldName='BONUSX' AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
	// 		$sql_order 			= '';
	// 		$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 		while($row_child = mssql_fetch_array($result_child)){
	// 			$return['sum_bonus']  = $return['sum_bonus']+$row_child['Value'];
	// 		}

	// 		unset($result_child);
	// 		unset($row_child);
	// 		unset($array_fields);
	// 		$result_child		= '';
	// 		$array_fields 		= array('Value');
	// 		$table 	   			= 'e_portal_payslip_data';
	// 		$joins 	   			= '';
	// 		$sql_where 			= "WHERE FieldName='MO13' AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
	// 		$sql_order 			= '';
	// 		$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 		while($row_child = mssql_fetch_array($result_child)){
	// 			$return['sum_13th_month']  = $return['sum_13th_month']+($row_child['Value']);
	// 		}

	// 		unset($result_child);
	// 		unset($row_child);
	// 		unset($array_fields);
	// 		$result_child		= '';
	// 		$array_fields 		= array('Value');
	// 		$table 	   			= 'e_portal_payslip_data';
	// 		$joins 	   			= '';
	// 		$sql_where 			= "WHERE FieldName='MO13P' AND FKWorksheet='".$row['worksheet_id']."' AND Row='".$row['row']."'";
	// 		$sql_order 			= '';
	// 		$result_child 		= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
	// 		while($row_child = mssql_fetch_array($result_child)){
	// 			$return['sum_13th_month']  = $return['sum_13th_month']+($row_child['Value']-$return['sum_13th_month']);
	// 		}
	// 	}

	// 	$result 				= '';
	// 	$array_fields 			= array('FirstName','LastName','DateHired','Position','SalaryType');
	// 	$table 	   				= 'tbl_EmployeeInfo';
	// 	$joins 	   				= 'LEFT JOIN tbl_Position ON tbl_EmployeeInfo.fkPosition=tbl_Position.pkid';
	// 	$sql_where 				= 'WHERE EmpNo="'.$return['cec_employee_number'].'"';
	// 	$sql_order 				= '';
	// 	$sql_limit 				= '';
	// 	$result 				= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	// 	if($row = mysqli_fetch_array($result)){
	// 		$return['FirstName'] 				= utf8_encode($row['FirstName']);
	// 		$return['LastName'] 				= utf8_encode($row['LastName']);
	// 		$return['DateHired'] 				= date('F d, Y',strtotime($row['DateHired']));
	// 		$return['Position'] 				= $row['Position'];
	// 		$return['SalaryType'] 				= $row['SalaryType'];
	// 	}

	// 	$return['sum_sl']			= number_format($return['sum_sl'], 2);
	// 	$return['sum_vl']			= number_format($return['sum_vl'], 2);
	// 	$return['sum_bonus']		= number_format($return['sum_bonus'], 2);
	// 	$return['sum_13th_month']	= number_format($return['sum_13th_month'], 2);

	// 	echo json_encode($return);		
	// }

	function load_activity_logs(){
		require_once('./query.php');
		$tl_type_arr = array('','fetch data','add data','update data','report gen','send email','login','invalid login','disabled account');
		$return 				= $_POST;
		$date_now 				= date('Y-m-d H:i:s');
		// $date_less_one			= date("Y-m-d H:i:s", strtotime('-5 minutes', time()));
		$date_less_one			= date("Y-m-d", strtotime('-1 days', time()));
		$result 				= '';
		$array_fields 			= array('tl_system_name','tl_type','tl_adate','tl_employee_number','tl_page','tl_ip_address','tl_query');
		$table 	   				= 't_logs';
		$joins 	   				= '';
		$sql_where 				= 'WHERE tl_adate >= "'.$date_less_one.'"';
		$sql_order 				= 'GROUP BY DATE_FORMAT(tl_adate, "%Y-%m-%d %H:%i"), tl_employee_number, tl_page, tl_ip_address ORDER BY tl_id DESC';
		$sql_limit 				= '';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 					= 0;
		$return['test']			= db_ep::getInstance()->query_select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		while($row = mysqli_fetch_array($result)){
			$tl_system_name = isset( $row['tl_system_name'] )?$row['tl_system_name']:'';
			$tl_system_name = trim($tl_system_name);
			if( !$tl_system_name ){
				$tl_system_name = 'transact';
			}
			$return['tl_adate'][$ctr]			= date('Y_m_d_H_i_s',strtotime($row['tl_adate']));
			$return['formatted_tl_adate'][$ctr]	= date('M d, Y h:i A',strtotime($row['tl_adate']));
			$return['tl_type'][$ctr]			= $tl_type_arr[ isset($row['tl_type'])?$row['tl_type']:1 ];
			$return['tl_system_name'][$ctr]		= $tl_system_name;
			$return['tl_page'][$ctr]			= $row['tl_page'];
			$return['tl_ip_address'][$ctr]		= $row['tl_ip_address'];
			$return['tl_query'][$ctr]			= $row['tl_query'];
			$return['tl_employee_number'][$ctr]	= isset($row['tl_employee_number'])?$row['tl_employee_number']:'---';
			$ctr++;
		}
		$return['ctr'] = $ctr;

		$date_less_one			= date("Y-m-d H:i:s", strtotime('-5 minutes', time()));

		$result 				= '';
		$array_fields 			= array('DISTINCT(tl_employee_number), tl_type');
		$table 	   				= 't_logs';
		$joins 	   				= '';
		$sql_where 				= 'WHERE tl_adate >= "'.$date_less_one.'"';
		$sql_order 				= '';
		$sql_limit 				= '';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$cts 					= 0;
		while($row = mysqli_fetch_array($result)){
			if( $row['tl_employee_number'] && ($row['tl_type']!=7&&$row['tl_type']!=8) ){
				$return['active_users_emp_no'][$cts]	= $row['tl_employee_number'];
				$cts++;
			}
		}
		$return['cts'] = $cts;
		echo json_encode($return);
	}

	function load_disabled_users(){
		require_once('./query.php');
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('username',
											'name',
											'datetime_disabled_enabled'
											);
		$table 	   					= 'rka_users';
		$joins 	   					= "";
		$sql_where 					= "WHERE disabled=1";
		$sql_order 					= 'ORDER BY username DESC';
		$sql_limit 					= '';
		$result 					= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 						= 0;
		while($row = mysqli_fetch_array($result)){
			$return['username'][$ctr]			= $row['username'];
			$return['name'][$ctr]				= $row['name'];
			$return['datetime_disabled_enabled'][$ctr]	= $row['datetime_disabled_enabled'];
			$ctr++;
		}
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	function enable_user(){
		require_once('./query.php');
		// header('Content-type: text/plain');
		$return 				= $_POST;

		$date_now 				= date('Y-m-d H:i:s');
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('attempt_count',
										'disabled',
										'datetime_disabled_enabled'
									);
		$array_values 			= array(0,
										0,
										$date_now
									);
		$table 	   				= 'rka_users';

		$idname 				= 'username';
		$pkid 					= $return['query_username'];
		$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);

		$return['result'] = $result;
		echo json_encode($return);
	}

	/* Original Code */
	/*function load_list_of_users(){
		require_once('./query.php');
		$db_hris					= new db_hris;
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('EmpNo',
											'LastName',
											'FirstName',
											'Department',
											'BasicPay',
											'AccountNumber',);
		$table 	   					= 'e_portal_employees';
		$joins 	   					= "";
		// $sql_where 					= "WHERE Status='1'";//- 4212021
		$sql_where 					= "WHERE Status='1' AND EmpNo NOT LIKE 'T0%'";
		$sql_order 					= 'ORDER BY EmpNo DESC';
		$result 					= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$ctr 						= 0;
		while($row = mssql_fetch_array($result)){
			$return['EmpNo'][$ctr]				= $row['EmpNo'];
			$return['FirstName'][$ctr]			= utf8_encode($row['FirstName']);
			$return['LastName'][$ctr]			= utf8_encode($row['LastName']);
			$return['Department'][$ctr]			= $row['Department'];
			$return['BasicPay'][$ctr]			= number_format($row['BasicPay'],2,".",",");
			$return['Ecola'][$ctr]				= number_format($row['Ecola'],2,".",",");
			$return['AccountNumber'][$ctr]		= $row['AccountNumber'];

			$result_child 			= '';
			$array_fields_child 	= array('id');
			$table_child 	   		= 'rka_users';
			$joins_child 	   		= '';
			$sql_where_child 		= 'WHERE username = "'.$row['EmpNo'].'"';
			$sql_order_child 		= '';
			$sql_limit_child 		= '';
			$result_child 			= db_ep::getInstance()->select_query($array_fields_child,$table_child,$joins_child,$sql_where_child,$sql_order_child,$sql_limit_child);
			if($row_child = mysqli_fetch_array($result_child)){
				$return['active_account'][$ctr] = '1';
			}
			else{
				$return['active_account'][$ctr] = '0';
			}
			$ctr++;
		}
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}*/

	function load_list_of_users_rapid(){
		require_once('./query.php');
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('EmpNo',
											'lastname',
											'firstname',
											'Department');
		$table 	   					= 'vw_emp_hris';
		$joins 	   					= "";
		$sql_where 					= "WHERE EmpStatus ='1'";
		$sql_order 					= 'ORDER BY EmpNo DESC';
		$sql_limit 					= '';
		$result 					= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 						= 0;
		while($row = mysqli_fetch_array($result)){
			$return['EmpNo'][$ctr]				= $row['EmpNo'];
			$return['FirstName'][$ctr]			= utf8_encode($row['firstname']);
			$return['LastName'][$ctr]			= utf8_encode($row['lastname']);
			$return['Department'][$ctr]			= $row['Department'];

			$result_child 			= '';
			$array_fields_child 	= array('id');
			$table_child 	   		= 'rka_users';
			$joins_child 	   		= '';
			$sql_where_child 		= 'WHERE username = "'.$row['EmpNo'].'"';
			$sql_order_child 		= '';
			$sql_limit_child 		= '';
			$result_child 			= db_ep::getInstance()->select_query($array_fields_child,$table_child,$joins_child,$sql_where_child,$sql_order_child,$sql_limit_child);
			if($row_child = mysqli_fetch_array($result_child)){
				$return['active_account'][$ctr] = '1';
			}
			else{
				$return['active_account'][$ctr] = '0';
			}

			$result_childx 			= '';
			$array_fields_childx 	= array('a.account_no, b.basic_salary');
			$table_childx 	   		= 'tbl_account_no a';
			$joins_childx 	   		= 'inner join tbl_salary_report b on b.employee_no = a.emp_no';
			$sql_where_childx 		= 'WHERE a.emp_no = "'.$row['EmpNo'].'"';
			$sql_order_childx		= '';
			$sql_limit_childx 		= '';
			$result_childx 			= db_rp::getInstance()->select_query($array_fields_childx,$table_childx,$joins_childx,$sql_where_childx,$sql_order_childx,$sql_limit_childx);
			if($row_childx = mysqli_fetch_array($result_childx)){
				$return['account_no'][$ctr] 	= $row_childx['account_no'];
				$return['basic_salary'][$ctr] 	= number_format($row_childx['basic_salary'],2,".",",");
			}

			$ctr++;
		}
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	/* Original Code */
	/*function load_employee_information(){
		require_once('./query.php');
		$db_hris					= new db_hris;
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('EmpNo',
											'LastName',
											'FirstName',
											'BasicPay',
											'Ecola',
											'AccountNumber',
											'SSN',
											'TIN',
											'PhilHealth',
											'Department',
											'Paymode',
											'Division',
											'Section',
											'Position',
											'Allowance',
											'PayType',
											'DateHired',
											'HiringStatus',
											'Salarytype',
											'Hdmf',
											'ExemptBaseCode');
		$table 	   					= 'e_portal_employees';
		$joins 	   					= "";
		$sql_where 					= "WHERE EmpNo='".$return['emp_no']."'";
		$sql_order 					= '';
		$result 					= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$return['sd'] 					= $db_hris->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order);
		if($row = mssql_fetch_array($result)){
			$return['EmpNo']				= $row['EmpNo'];
			$return['FirstName']			= utf8_encode($row['FirstName']);
			$return['LastName']				= utf8_encode($row['LastName']);
			$return['BasicPay']				= number_format($row['BasicPay'],2,".",",");
			$return['Ecola']				= number_format($row['Ecola'],2,".",",");
			$return['AccountNumber']		= $row['AccountNumber'];
			$return['SSN']					= $row['SSN'];
			$return['TIN']					= $row['TIN'];
			$return['PhilHealth']			= $row['PhilHealth'];
			$return['Department']			= $row['Department'];
			$return['Paymode']				= $row['Paymode'];
			$return['Division']				= $row['Division'];
			$return['Section']				= $row['Section'];
			$return['Position']				= $row['Position'];
			$return['Allowance']			= number_format($row['Allowance'],2,".",",");
			$return['PayType']				= $row['PayType'];
			$return['DateHired']			= $row['DateHired'];
			$return['ExemptBaseCode']		= $row['ExemptBaseCode'];

			// switch ($row['HiringStatus']){
			// 	case '1':
			// 		$return['HiringStatus']	= 'Resigned';
			// 		break;
			// 	case '2':
			// 		$return['HiringStatus']	= 'Resigned';
			// 		break;
			// 	case '3':
			// 		$return['HiringStatus']	= 'Resigned';
			// 		break;
			// 	case '4':
			// 		$return['HiringStatus']	= '';
			// 		break;
			// }

			$return['Salarytype']			= $row['Salarytype'];
			$return['Hdmf']					= $row['Hdmf'];
		}
		echo json_encode($return);
	}*/

	function load_employee_information_rapid(){
		require_once('./query.php');
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('a.EmpNo',
											'a.LastName',
											'a.FirstName',
											'a.DateHired',
											'a.SSN',
											'a.TIN',
											'a.PhilHealth',
											'a.PagIbig',
											'b.Division',
											'c.Department',
											'd.Section',
											'e.Position'
										);
		$table 	   					= 'vw_EmployeeInfo a';
		$joins 	   					= "inner join tbl_Division b on b.pkid = a.fkDivision
										inner join tbl_Department c on c.pkid = a.fkDepartment
										inner join tbl_Section d on d.pkid = a.fkSection
										inner join tbl_Position e on e.pkid = a.fkPosition
										";
		$sql_where 					= "WHERE a.EmpNo='".$return['emp_no']."'";
		$sql_order 					= '';
		$sql_limit 					= '';
		$result 					= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['EmpNo']				= $row['EmpNo'];
			$return['FirstName']			= utf8_encode($row['FirstName']);
			$return['LastName']				= utf8_encode($row['LastName']);
			$return['DateHired']			= $row['DateHired'];
			$return['SSN']					= $row['SSN'];
			$return['TIN']					= $row['TIN'];
			$return['PagIbig']				= $row['PagIbig'];
			$return['PhilHealth']			= $row['PhilHealth'];
			$return['Department']			= $row['Department'];
			$return['Division']				= $row['Division'];
			$return['Section']				= $row['Section'];
			$return['Position']				= $row['Position'];


			$result_child 			= '';
			$array_fields_child 	= array('a.*, b.account_no');
			$table_child 	   		= 'tbl_salary_report a';
			$joins_child 	   		= 'inner join tbl_account_no b on b.emp_no = a.employee_no';
			$sql_where_child 		= 'WHERE a.employee_no = "'.$row['EmpNo'].'"';
			$sql_order_child		= '';
			$sql_limit_child 		= '';
			$result_child 			= db_rp::getInstance()->select_query($array_fields_child,$table_child,$joins_child,$sql_where_child,$sql_order_child,$sql_limit_child);
			if($row_child = mysqli_fetch_array($result_child)){
				$return['basic_salary'] 		= number_format($row_child['basic_salary'],2,".",",");
				$return['personal_exemption'] 	= $row_child['personal_exemption'];
				$return['ecola'] 				= $row_child['ecola'];
				$return['allowance'] 			= $row_child['allowance'];
				$return['account_no'] 			= $row_child['account_no'];
			}

		}
		echo json_encode($return);
	}

	function load_loan_information(){
		require_once('./query.php');
		$db_payroll					= new db_payroll;
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('LoanDescription',
											'PrincipalAmount',
											'CurrentBalance',
											'InterestRate',
											'MonthsToPay',
											'StartDate',
											'Amortization',
											'FreqDescription',
											'pkid');
		$table 	   					= 'e_portal_loans';
		$joins 	   					= "";
		$sql_where 					= "WHERE EmpNo='".$return['emp_no']."'";
		$sql_order 					= 'ORDER BY StartDate DESC';
		$result 					= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$ctr 						= 0;
		while($row = mssql_fetch_array($result)){
			$return['pkid'][$ctr]					= $row['pkid'];
			$return['LoanDescription'][$ctr]		= $row['LoanDescription'];
			$return['PrincipalAmount'][$ctr]		= number_format($row['PrincipalAmount'],2,".",",");
			$return['CurrentBalance'][$ctr]			= number_format($row['CurrentBalance'],2,".",",");
			$return['InterestRate'][$ctr]			= number_format($row['InterestRate'],2,".",",");
			// $return['SocioEcoAllow'][$ctr]			= number_format($row['SocioEcoAllow'],2,".",",");
			$return['MonthsToPay'][$ctr]			= $row['MonthsToPay'];
			$return['StartDate'][$ctr]				= date('M d, Y',strtotime($row['StartDate']));
			$return['Amortization'][$ctr]			= number_format($row['Amortization'],2,".",",");
			$return['FreqDescription'][$ctr]		= $row['FreqDescription'];
			$ctr++;
		}
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	function load_pay_slip_history(){
		require_once('./query.php');
		$return 			= $_POST;
		$db_hris			= new db_hris;
		$result 			= '';
		$array_fields 		= array('Description','StartingDate','EndingDate','CutOff','Posted','Lastupdate');
		$table 	   			= 'e_portal_cut_off_data';
		$joins				= '';
		$sql_where			= 'WHERE EmpNo="'.$return['emp_no'].'" AND Description<>null';
		$sql_order	 		= 'ORDER BY Pkid DESC';
		$result 			= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$rka				= 0;
		$ctr 				= 0;		
		while($row = mssql_fetch_array($result)){
			$return['Description'][$ctr]    = $row['Description'];
			$return['StartingDate'][$ctr] 	= date('M d, Y',strtotime($row['StartingDate']));
			$return['EndingDate'][$ctr] 	= date('M d, Y',strtotime($row['EndingDate']));
			$return['CutOff'][$ctr] 		= date('M d, Y',strtotime($row['CutOff']));
			$return['Posted'][$ctr] 		= $row['Posted'];
			$return['Lastupdate'][$ctr] 	= date('M d, Y H:i A',strtotime($row['Lastupdate']));
			$ctr++;
		}		
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	function load_loan_payments(){
		require_once('./query.php');
		$return 			= $_POST;
		$db_payroll			= new db_payroll;
		$result 			= '';
		$array_fields 		= array('SchedofPayment','DatePaid','Principal','Interest','Balance','Paid');
		$table 	   			= 'LoanSchedule';
		$joins				= '';
		$sql_where			= 'WHERE FkLoans="'.$return['loan_id'].'"';
		$sql_order	 		= 'ORDER BY SchedofPayment DESC';
		$result 			= $db_payroll->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		$return['sd'] 		= $db_payroll->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order);
		$rka				= 0;
		$ctr 				= 0;
		while($row = mssql_fetch_array($result)){
			if($row['DatePaid']==null){
				$return['DatePaid'][$ctr] 		= '';
			}
			else{
				$return['DatePaid'][$ctr] 		= date('M d, Y',strtotime($row['DatePaid']));
			}
			$rka = $ctr-1;
			if($row['Paid']==1&&$return['Paid'][$rka]==0){
				$return['CurrentBalance'] 		= number_format($row['Balance'],2,".",",");
			}

			$return['SchedofPayment'][$ctr] = date('M d, Y',strtotime($row['SchedofPayment']));
			// $return['DatePaid'][$ctr] 		= date('M d, Y',strtotime($row['DatePaid']));
			$return['Principal'][$ctr] 		= number_format($row['Principal'],2,".",",");
			$return['Interest'][$ctr] 		= number_format($row['Interest'],2,".",",");
			$return['Balance'][$ctr] 		= number_format($row['Balance'],2,".",",");
			$return['Paid'][$ctr] 			= $row['Paid'];
			$ctr++;
		}		
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	function update_complaint(){
		require_once('./query.php');
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('rc_timekeeper_remarks',
										'rc_seen_status',
										'rc_status');
		$array_values 			= array($return['my-remarks-text'],
										'1',
										$return['opt_complaint_options']);
		$table 	   				= 'rka_complaints';
		$idname 				= 'rc_id';
		$pkid 					= $return['rc_id'];
		db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		echo json_encode($return);
	}

	function update_complaint_approval(){
		require_once('./query.php');
		$lastupdate = date("Y-m-d H:i:s");
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('rc_approval_remarks',
										'rc_approval_status',
										'rc_approval_date');
		$array_values 			= array($return['my-remarks-text'],
										$return['opt_complaint_app_options'],
										$lastupdate);
		$table 	   				= 'rka_complaints';
		$idname 				= 'rc_id';
		$pkid 					= $return['rc_id'];

		if ($return['opt_complaint_app_options'] == '3'){
			if ($return['my-remarks-text'] == '' || $return['my-remarks-text'] == null){
				$return['msg'] = 'no_remarks';
			}else{
				$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
				if($row = mysqli_fetch_array($result)){
					$return['mail_status'] = send_email_complaint_approval($return['opt_complaint_app_options'], $return['my-remarks-text'], $row['rc_id']);
				}
			}
		}else if ($return['opt_complaint_app_options'] == '2'){
				$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
				if($row = mysqli_fetch_array($result)){
					$return['mail_status'] = send_email_complaint_approval($return['opt_complaint_app_options'], $return['my-remarks-text'], $row['rc_id']);
				}

		}else{
			$return['msg1'] = 'no_status';
		}



		echo json_encode($return);
	}

	function post_pay_slip_data(){
		require_once('./query.php');
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('rppsd_fkid',
										'rppsd_status',
										'rppsd_username');
		$array_values 			= array($return['pay_period'],
										'1',
										$_SESSION['s_username']);
		$table 	   				= 'rka_posted_pay_slip_data';
		db_ep::getInstance()->insert_query($table,$array_fields,$array_values);
		echo json_encode($return);		
	}

	function send_to_my_email(){
		require_once('./query.php');
		$return				= $_POST; 
		$result				= "";
		$table      	 	= "rka_sent_emails";
		$array_fields		= array('rse_recipient','rse_employee_no');
		$array_values 		= array($return['recipient-name'],$_SESSION['s_username']);
		db_ep::getInstance()->insert_query($table,$array_fields,$array_values);
		while($x==0){
        	$filename = $_SERVER['DOCUMENT_ROOT']."/e-portal/ep_custom_modules/dist/generated_reports/pdf_report/".$_SESSION['s_username'].$return['ymdhis'].".pdf";
			if (file_exists($filename)){
				send_email_with_payslip($return['recipient-name'],$_SESSION['s_username'].$return['ymdhis'],$return['pay_period']);
				$x = 1;
			}
		}
		echo json_encode($return);		
	}
	
	function send_email_with_payslip($recipient,$file_name,$pay_period){
		require_once ('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('name','email','username');
		$table 	   			= 'rka_users';
		$joins 	   			= ' ';
		$sql_where 			= 'WHERE username = "'.$_SESSION['s_username'].'"';
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 				= 0;
		if($row = mysqli_fetch_array($result)){
			$name 		= $row['name'];
			$email 		= $row['email'];
			$username 	= $row['username'];
		}

        require_once('./email_config.php');                      	// TCP port to connect to

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		$mail->addAddress($recipient);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');

		$mail->addAttachment("/var/www/e-portal/ep_custom_modules/dist/generated_reports/pdf_report/".$file_name.".pdf", $pay_period.".pdf");
		$mail->isHTML(true);

		$mail->addBCC('jgsulit@pricon.ph');
		$mail->addBCC('nsnambayan@pricon.ph');
		$mail->Subject = 'Employee Portal - Pay Slip';
		
		$body = '<div>';
		$body .= '	<span style="font-family:arial;">This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span style="font-family:arial;">If you are not the intended recipient of this email and feels that you have received this in error, please contact <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '	<span style="font-family:arial;font-weight:bold;">Details</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Employee:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$username.' - '.$name.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Email Address:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$email.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Pay Period:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$pay_period.'</td>';
		$body .= '		</tr>';
		$body .= '	</table>';
		$body .= '</div>';
		$body .= "<br><br>Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $return .=  'Message could not be sent.';
		    $return .= 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
		    $return .= 'Message has been sent';
		}
		return $return;
	}

	function send_complaint(){
		require_once('./query.php');
		$return				= $_POST;
		$result				= "";
		$table      	 	= "rka_complaints";
		$array_fields		= array('rc_concern_date','rc_message','rc_username','rc_approver','rc_approval_status');
		// $array_fields		= array('rc_concern_date','rc_message','rc_username','rc_approver','rc_approval_status', 'rc_category_type', 'rc_attachment');
		$array_values 		= array(date('Y-m-d',
										strtotime($return['concern-date'])),
										$return['message-text'],
										$_SESSION['s_username'],
										$return['name_superior'],
										1
									);
		$as_target			= 'rc_id';
		$as_name			= 'rc_id';
		$result = db_ep::getInstance()->insert_query_return_id($table,$array_fields,$array_values,$as_target,$as_name);
		if($row = mysqli_fetch_array($result)){
			$return['sent_message_status'] = send_email_complaint(date('F d, Y',strtotime($return['concern-date'])),$return['message-text'],$row['rc_id']);
		}

		echo json_encode($return);		
	}

	function send_complaint_test(){
		require_once('./query.php');
		$return				= $_POST;
		$result				= "";
		$return['error'] 	= false;
		$date_today =  date('Y-m-d');
		if(!($date_today >=  $return['date_start'] && $date_today <= $return['date_end'])){
			$return['error'] = true;
			echo json_encode($return);
			exit();

		}
		$attachement_file = isset($_FILES['concern_attachment'])?$_FILES['concern_attachment']:'';
		$date 			= date("Ymdhis");
		
	    if( $attachement_file ){
			$filename = $attachement_file['name'];
			$complain_attachment_file_name = '_complaint_'.$date.'_'.$filename;
	    }
		$table      	 	= "rka_complaints";
		// $array_fields		= array('rc_concern_date','rc_message','rc_username','rc_approver','rc_approval_status');
		$array_fields		= array('rc_concern_date','rc_message','rc_username','rc_approver','rc_approval_status', 'rc_category_type', 'rc_attachment', 'rc_attachment_filename');
		$array_values 		= array(date('Y-m-d',
										strtotime($return['concern-date'])),
										$return['message-text'],
										$_SESSION['s_username'],
										$return['name_superior'],
										1,
										$return['concern_type'],
										$complain_attachment_file_name,
										$filename
									);
		$as_target			= 'rc_id';
		$as_name			= 'rc_id';
		$result = db_ep::getInstance()->insert_query_return_id($table,$array_fields,$array_values,$as_target,$as_name);
		if($row = mysqli_fetch_array($result)){
			$return['sent_message_status'] = send_email_complaint(date('F d, Y',strtotime($return['concern-date'])),$return['message-text'],$row['rc_id']);
		}

		if($result){
			move_uploaded_file(
				$attachement_file['tmp_name'],
				'../uploads/complaint_attachments/' .$complain_attachment_file_name
			);
		}
		echo json_encode($return);		
	}
	
	function send_email_complaint($concern_date,$message,$rc_id){
		
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.rc_id', 'a.rc_message', 'a.rc_concern_date', 'a.rc_username as emp_username', 'b.name as emp_name', 'b.email as emp_email', 'a.rc_approver as approver_username', 'c.name as approver_name', 'c.email as approver_email','a.rc_approval_remarks','a.rc_approval_date');
		$table 	   			= 'rka_complaints a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.rc_username
								INNER JOIN rka_users c on c.username = a.rc_approver';
		$sql_where 			= "WHERE a.rc_username = '".$_SESSION['s_username']."' AND a.rc_id = '".$rc_id."'";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$rc_message 		= $row['rc_message'];
			$rc_concern_date 	= $row['rc_concern_date'];


			$approver_username 	= $row['approver_username'];
			$approver_name 		= $row['approver_name'];
			$approver_email 	= $row['approver_email'];
			$rc_approval_remarks= $row['rc_approval_remarks'];
			$rc_approval_date	= $row['rc_approval_date'];
		}


		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		// $mail->addAddress('jgserrac@pricon.ph');
		$mail->addAddress($approver_email);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		// $mail->addCC($emp_email,$name); // Old 06-29-2023
		$mail->addCC($emp_email,$approver_name);
		$mail->addBCC('jgsulit@pricon.ph');
		$mail->isHTML(true);
		$mail->Subject = '['.$emp_username.'] Employee Portal - Complaint Notification';
		
		$body = '<div>';
		$body .= '	<span style="font-family:arial;">This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span style="font-family:arial;">If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '	<span style="font-family:arial;font-weight:bold;">Details</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Control No.:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.str_pad($rc_id, 11, '0', STR_PAD_LEFT).'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Complainant:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$emp_username.' - '.$emp_name.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Email Address:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$emp_email.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Date:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$concern_date.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Message:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$message.'</td>';
		$body .= '		</tr>';
		$body .= '	</table>';
		$body .= '</div>';
		$body .= "<br><br>Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	function send_email_complaint_approval($approval_stat,$message,$rc_id){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.rc_id', 'a.rc_message', 'a.rc_concern_date', 'a.rc_username as emp_username', 'b.name as emp_name', 'b.email as emp_email', 'a.rc_approver as approver_username', 'c.name as approver_name', 'c.email as approver_email','a.rc_approval_remarks','a.rc_approval_date');
		$table 	   			= 'rka_complaints a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.rc_username
								INNER JOIN rka_users c on c.username = a.rc_approver';
		$sql_where 			= "WHERE a.rc_approver = '".$_SESSION['s_username']."' and rc_id = '".$rc_id."'";
		// $sql_where 			= "WHERE a.rc_approver = '".$_SESSION['s_username']."' ";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$rc_message 		= $row['rc_message'];
			$rc_concern_date 	= $row['rc_concern_date'];


			$approver_username 	= $row['approver_username'];
			$approver_name 		= $row['approver_name'];
			$approver_email 	= $row['approver_email'];
			$rc_approval_remarks= $row['rc_approval_remarks'];
			$rc_approval_date	= $row['rc_approval_date'];
		}

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		
		if($approval_stat == '2'){
			// $mail->addAddress('jgsulit@pricon.ph');
			$mail->addAddress('jgserrac@pricon.ph');
		}else{
			$mail->addAddress($emp_email);
		}

		// $mail->addAddress('jgserrac@pricon.ph');
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addCC($approver_email);
		$mail->addBCC('jgsulit@pricon.ph');
		$mail->isHTML(true);

		if ($approval_stat == '2'){
			$approval_lbl = 'Approved';
		}else if ($approval_stat == '3'){
			$approval_lbl = 'Disapproved';
		}else{
			$approval_lbl = '---';
		}
		$mail->Subject = '['.$emp_username.'] Employee Portal - '.$approval_lbl.' Complaint';
		
		$body = '<div>';
		$body .= '	<span style="font-family:arial;">This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span style="font-family:arial;">If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '	<span style="font-family:arial;font-weight:bold;">Details</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Control No.:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.str_pad($rc_id, 11, '0', STR_PAD_LEFT).'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Complainant:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$emp_username.' - '.$emp_name.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Email Address:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$emp_email.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Concern Date:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$rc_concern_date.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Message:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$rc_message.'</td>';
		$body .= '		</tr><br>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Superior:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$approver_name.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Approval Remarks:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$rc_approval_remarks.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Approval Date:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$rc_approval_date.'</td>';
		$body .= '		</tr>';
		$body .= '	</table>';
		$body .= '</div>';
		$body .= "<br><br>Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	function send_employee_cec(){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');

		$return = $_POST;
        require_once('./email_config.php');                      	// TCP port to connect to

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');

		$recipients = explode(";",$return['cec_recipients']);

		$ctr = 0;
		while($recipients[$ctr]!=''){
			$mail->addAddress(trim($recipients[$ctr]));
			$ctr++;
		}

		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		// $mail->addCC($email,$name);
		$mail->addBCC('jgsulit@pricon.ph');
		$mail->addBCC('nsnambayan@pricon.ph');
		$mail->isHTML(true);

		$mail->Subject = 'Employee Portal - Generated C.E.C.(Payroll Data)';
		
		$body = '<div>';
		$body .= '	<span style="font-family:arial;">This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span style="font-family:arial;">If you are not the intended recipient of this email and feels that you have received this in error, please contact <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '	<span style="font-family:arial;font-weight:bold;">C.E.C Details</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Name:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$return['cec_name'].'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Employee Number:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.strtoupper($return['cec_employee_number']).'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Date Hired:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$return['cec_date_hired'].'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Year:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.$return['cec_year'].'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">SL:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.number_format(str_replace(',','',$return['cec_sl']),2).'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">VL:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.number_format(str_replace(',','',$return['cec_vl']),2).'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Bonus:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.number_format(str_replace(',','',$return['cec_bonus']),2).'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">13th Month Pay:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.number_format(str_replace(',','',$return['cec_13th_month_pay']),2).'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;text-align:right;font-size:17px;">Ecola:</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">'.number_format(str_replace(',','',$return['cec_ecola']),2).'</td>';
		$body .= '		</tr>';
		$body .= '	</table>';
		$body .= '</div>';
		$body .= "<br><br>Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;
		$return = "";
		if(!$mail->send()) {
		    $return .=  'Message could not be sent.';
		    $return .= 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
		    $return .= 'Message has been sent';
		}
		echo json_encode($return);	
	}

	function time_elapsed_string($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	function create_new_account(){
		require '../../../libraries/phpass/PasswordHash.php';
		require_once('./query.php');
		// header('Content-type: text/plain');


		$return 				= $_POST;
		$t_hasher 				= new PasswordHash(10, FALSE);


		$encrypted_password		= $t_hasher->HashPassword($return['txt_password']);
		$date_now 				= date('Y-m-d H:i:s');
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('name',
										'username',
										'email',
										'password',
										'registerDate',
										'params',
										'requireReset'
									);
		$array_values 			= array($return['txt_account_name'],
										$return['txt_login_name'],
										$return['txt_email'],
										$encrypted_password,
										$date_now,
										'{"admin_style":"","admin_language":"","language":"","editor":"","helpsite":"","timezone":""}',
										$return['ckb_require_pw_reset']
									);
		$table 	   				= 'rka_users';
		$as_target 				= 'id';
		$as_name 				= 'id';
		$result = db_ep::getInstance()->insert_query_return_id($table,$array_fields,$array_values,$as_target,$as_name);

		$mail_status = '0';
		if($row = mysqli_fetch_array($result)){
			$array_fields 			= array('user_id',
											'group_id');
			$array_values 			= array($row['id'],
											'2');
			$table 	   				= 'rka_user_usergroup_map';
			$result = db_ep::getInstance()->insert_query($table,$array_fields,$array_values);
			if( $return['txt_email'] != ""){
				$mail_status = send_email_new_account($return['txt_email'], $return['txt_account_name'], $return['txt_login_name'], $return['txt_password']);
			}
		}

		$return['mail_status'] = $mail_status;
		echo json_encode($return);
	}

	function select_employee_details(){
		require_once('./query.php');
		$return 			= $_POST;
		$result 			= '';
		// $array_fields 		= array('email_add');
		// $table 	   			= 'tbl_EmployeeInfo';
		// $joins 	   			= 'LEFT JOIN db_acl.tbl_useraccnt ON tbl_EmployeeInfo.pkid = tbl_useraccnt.fkemployee';
		// $sql_where 			= 'WHERE EmpNo = "'.$return['en'].'"';
		$array_fields 		= array('email');
		$table 	   			= 'rka_users';
		$joins 	   			= '';
		$sql_where 			= 'WHERE username = "'.$return['en'].'"';
		$sql_order 			= '';
		$sql_limit 			= '';
		// $result 			= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['email_add'] = $row['email'];
		}
		echo json_encode($return);
	}

	function send_email_new_account($email_add, $account_name, $login_name, $password){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');


		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		$mail->addAddress($email_add);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addBCC('jgsulit@pricon.ph');
		$mail->addBCC('cpagtalunan@pricon.ph');
		$mail->isHTML(true);
		$mail->Subject = 'Employee Portal - New Account Notification';
		
		$body = '<div>';
		$body .= '	<h2 style="font-family:arial;font-weight:bold;color:#1F4788;margin-bottom:-20px;">Pricon Microelectronics, Inc.</h2>';
		$body .= '	<h3 style="font-family:arial;color:#317589;margin-top:-20px;">Employee Portal</h3>';
		$body .= '	<span style="font-family:arial;">This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span style="font-family:arial;">If you are not the intended recipient of this email and feels that you have received this in error, please contact <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '	<span style="font-family:arial;font-weight:bold;">Account Details</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;font-size:17px;">Employee Name</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">: '.$account_name.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;font-size:17px;">Login Name</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">: '.$login_name.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;font-size:17px;">Password</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">: '.$password.'</td>';
		$body .= '		</tr>';
		$body .= '	</table>';
		$body .= '</div>';
		$body .= '<br><a href="http://192.168.3.235/e-portal/" style="font-family:arial;">Employee Portal Link</a>';
		$body .= "<span style='font-family:arial;>'<br><br>Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.</span>";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	function reset_account_password(){
		require '../../../libraries/phpass/PasswordHash.php';
		require_once('./query.php');

		$return 				= $_POST;
		$t_hasher 				= new PasswordHash(10, FALSE);


		$encrypted_password		= $t_hasher->HashPassword($return['txt_password_reset_pw']);
		$date_now 				= date('Y-m-d H:i:s');
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('email',
										'password',
										'requireReset'
									);
		$array_values 			= array($return['txt_email_reset_pw'],
										$encrypted_password,
										$return['ckb_require_pw_reset_reset_pw']
									);
		$table 	   				= 'rka_users';

		$idname 				= 'username';
		$pkid 					= $return['txt_login_name_reset_pw'];
		db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);

		$mail_status = '0';
		if( $return['txt_email_reset_pw'] != "" ){
			$mail_status = send_email_reset_password($return['txt_email_reset_pw'], $return['txt_account_name_reset_pw'], $return['txt_login_name_reset_pw'], $return['txt_password_reset_pw']);
		}
		
		$return['mail_status'] = $mail_status;
		echo json_encode($return);
	}

	function send_email_reset_password($email_add, $account_name, $login_name, $password){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		$mail->addAddress($email_add);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addBCC('jgsulit@pricon.ph');
		$mail->addBCC('cpagtalunan@pricon.ph');
		$mail->isHTML(true);
		$mail->Subject = 'Employee Portal - Password Reset Notification';

		$body = '<div>';
		$body .= '	<h2 style="font-family:arial;font-weight:bold;color:#1F4788;margin-bottom:-20px;">Pricon Microelectronics, Inc.</h2>';
		$body .= '	<h3 style="font-family:arial;color:#317589;margin-top:-20px;">Employee Portal</h3>';
		$body .= '	<span style="font-family:arial;">This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span style="font-family:arial;">If you are not the intended recipient of this email and feels that you have received this in error, please file a ticket thru RapidX ISS Service Request.</span><br><br>';
		$body .= '	<span style="font-family:arial;font-weight:bold;">Account Details</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;font-size:17px;">Employee Name</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">: '.$account_name.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;font-size:17px;">Login Name</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">: '.$login_name.'</td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td style="font-family:arial;font-size:17px;">Password</td>';
		$body .= '			<td style="font-family:arial;padding-left:5px;">: '.$password.'</td>';
		$body .= '		</tr>';
		$body .= '	</table>';
		$body .= '</div>';
		$body .= '<br><a href="http://192.168.3.235/e-portal/" style="font-family:arial;">Employee Portal Link</a>';
		$body .= "<span style='font-family:arial;>'<br><br>Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.</span>";
		$mail->Body    = $body;
		
		if(!$mail->send()) {
			$mail_status 	=  '0';
			$mail_status .= "Mailer Error: " . $mail->ErrorInfo ."\n";
		} else {
			$mail_status 	=  '1';
		}

		return $mail_status;
	}

	function get_immediate_superior(){
		require_once('./query.php');
		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('*');
		$table 	   			= 'vw_supervisors';
		$joins 	   			= '';
		// $sql_where 			= 'WHERE emp_name LIKE "%'.$return['pattern_superior'].'%"';
		$sql_where 			= 'WHERE emp_name LIKE "%'.$return['pattern_superior'].'%" AND EmpNo <> "'.$_SESSION['s_username'].'"';
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if (!$result) {
			echo mysql_error();
		}
		
		$ctr = 0;
		
		while($row = mysqli_fetch_array($result)){
			$return['pkid'][$ctr] 				= $row['pkid'];
			$return['emp_name'][$ctr] 			= $row['emp_name'];
			$return['email_add'][$ctr] 			= $row['email_add'];
			$return['EmpNo'][$ctr] 				= $row['EmpNo'];
			$return['username'][$ctr] 			= $row['username'];
			$ctr++;
		}
		
		$return['ctr']	  = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	function get_superior_email(){
		require_once('./query.php');
		$return 			= $_POST;
		$superior_name 		= $_POST['superior_name'];
		$result 			= '';
		$array_fields 		= array('*');
		$table 	   			= 'vw_supervisors';
		$joins 	   			= '';
		$sql_where 			= 'WHERE emp_name="'.$superior_name.'"';
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if (!$result) {
			echo mysql_error();
		}
		
		$ctr = 0;
		
		while($row = mysqli_fetch_array($result)){
			$return['EmpNo'][$ctr] 			= $row['EmpNo'];
			$ctr++;
		}
		
		$return['ctr']	  = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	function load_complaints_approval(){
		require_once('./query.php');
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('rka_complaints.rc_id',
										'rka_complaints.rc_concern_date',
										'rka_complaints.rc_message',
										'rka_complaints.rc_username',
										'rka_complaints.rc_adate',
										'rka_complaints.rc_approval_status',
										'rka_complaints.rc_approver',
										'users.name',
										'approver.name as app_name'
									);
		$table 	   				= 'rka_complaints';
		$joins 	   				= 'LEFT JOIN rka_users users ON rka_complaints.rc_username=users.username 
								   LEFT JOIN rka_users approver ON rka_complaints.rc_approver=approver.username';
		$sql_where 				= 'WHERE rc_approval_status <> 0 AND rc_approver = "'.$return['approver'].'"';
		// $sql_where 				= 'WHERE rc_approval_status <> 0 AND rc_approver = "Q121"';
		// $sql_order 				= 'ORDER BY rc_approval_status, rc_concern_date ASC';
		$sql_order 				= 'ORDER BY rc_id DESC';

		$sql_limit 				= '';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 					= 0;
		while($row = mysqli_fetch_array($result)){
			$return['rc_id'][$ctr] 				= $row['rc_id'];
			$return['rc_concern_date'][$ctr] 	= date('F d, Y',strtotime($row['rc_concern_date']));
			$return['rc_message'][$ctr] 		= $row['rc_message'];
			$return['rc_username'][$ctr] 		= $row['rc_username'];
			$return['rc_adate'][$ctr] 			= $row['rc_adate'];
			$return['rc_approval_status'][$ctr] = $row['rc_approval_status'];
			$return['rc_approver'][$ctr] 		= $row['rc_approver'];
			$return['name'][$ctr] 				= utf8_encode($row['name']);
			$return['app_name'][$ctr] 			= utf8_encode($row['app_name']);
			$return['x_ago'][$ctr]				= time_elapsed_string($row['rc_adate']);
			$return['count_status'] 			= get_count_pending_status($row['rc_approver']);
			$return['count_close_status'] 		= get_count_closed_status($row['rc_approver']);
			$ctr++;
		}
		$return['ctr'] = $ctr;

		echo json_encode($return);			
	}

	function get_count_pending_status($app){
		require_once('./query.php');
		 
		$array_fields 	= array('COUNT(rc_approval_status) as count_status');
		$table 			= 'rka_complaints';
		$joins 			= '';
		$sql_where 		= "WHERE rc_approver = '$app' AND rc_approval_status = '1'";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$count_status	= 0;
		
		while($row 	= mysqli_fetch_array($result)){
			$count_status = $row['count_status'];
		}
		return $count_status;
	}

	function get_count_closed_status($app){
		require_once('./query.php');
		 
		$array_fields 	= array('COUNT(rc_approval_status) as count_close_status');
		$table 			= 'rka_complaints';
		$joins 			= '';
		// $sql_where 		= "WHERE rc_approver = '$app' AND (rc_approval_status = '2' OR rc_approval_status = '3') AND rc_status = '1'";
		$sql_where 		= "WHERE rc_approver = '$app' AND (rc_approval_status = '2' OR rc_approval_status = '3')";
		$sql_order 		= '';
		$sql_limit 		= '';
		$result 		= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$count_close_status	= 0;
		
		while($row 	= mysqli_fetch_array($result)){
			$count_close_status = $row['count_close_status'];
		}
		return $count_close_status;
	}

	function load_complaints_approval_details(){
		require_once('./query.php');
		$return 				= $_POST;
		$result 				= '';
		$ctr 				= 0;
		$array_fields 			= array('rka_complaints.rc_id',
										'rka_complaints.rc_concern_date',
										'rka_complaints.rc_message',
										'rka_complaints.rc_username',
										'rka_complaints.rc_timekeeper_remarks',
										'rka_complaints.rc_seen_status',
										'rka_complaints.rc_status',
										'rka_complaints.rc_adate',
										'rka_complaints.rc_approval_status',
										'rka_complaints.rc_approval_remarks',
										'rka_complaints.rc_category_type',
										'rka_complaints.rc_attachment',
										'rka_complaints.rc_attachment_filename',
										'rka_users.name',
										'rka_users.email');
		$table 	   				= 'rka_complaints';
		$joins 	   				= 'LEFT JOIN rka_users ON rka_complaints.rc_username=rka_users.username';
		$sql_where 				= 'WHERE rc_id="'.$return['rc_id'].'"';
		$sql_order 				= '';
		$sql_limit 				= '';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['rc_id'] 					= $row['rc_id'];
			$return['rc_concern_date'] 			= date('F d, Y',strtotime($row['rc_concern_date']));
			$return['rc_message'] 				= $row['rc_message'];
			$return['rc_username'] 				= $row['rc_username'];
			$return['rc_status'] 				= $row['rc_status'];
			$return['rc_seen_status'] 			= $row['rc_seen_status'];
			$return['rc_timekeeper_remarks'] 	= $row['rc_timekeeper_remarks'];
			$return['rc_adate'] 				= $row['rc_adate'];
			$return['name'] 					= $row['name'];
			$return['email'] 					= $row['email'];
			$return['rc_approval_status'] 		= $row['rc_approval_status'];
			$return['rc_approval_remarks'] 		= $row['rc_approval_remarks'];

			$return['rc_category_type'] 		= $row['rc_category_type'];
			$return['rc_attachment'] 			= $row['rc_attachment'];
			$return['rc_attachment_filename'] 	= $row['rc_attachment_filename'];
		}

		$result 				= '';
		$array_fields 			= array('date',
									'time_in',
									'mode_in',
									'time_out',
									'mode_out',
									'ot1 as regular_ot',
									'(ot3 + ot3a) as sunday_ot',
									'(ot2 + ot2a + ot5 + ot5a) as special_holiday_ot',
									'(ot4 + ot4a + ot6 + ot6a) as holiday_ot',
									'total_undertime',
									'nightdiff as night_diff',
									'absent',
									'leavecode',
									'leavenum');
		$table 	   				= 'vw_daily_tfm';
		$joins 	   				= '';
		$sql_where 				= 'WHERE date <> "" AND empno="'.$return['rc_username'].'" AND date="'.date('Y-m-d',strtotime($return['rc_concern_date'])).'"';
		$sql_order 				= '';
		$sql_limit 				= '';
		$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$return['sd'] 				= db_otks::getInstance()->query_select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$query					= 0;
		if($row = mysqli_fetch_array($result)){

			$date1 = $row['time_in'];
			$date2 =  $row['time_out'];
			 
			$date1Timestamp = strtotime($date1);
			$date2Timestamp = strtotime($date2);
			 
			$difference = $date2Timestamp - $date1Timestamp;

			$return['date'] 	                = '<span style="display:none;">'.$row['date'].'</span><span class="formatted-date">'.date('M d, Y',strtotime($row['date'])).'</span>';
			switch($row['mode_in']){
				case 'IN':						
						if($row['time_in']<'11:45:00'&&$row['time_in']>='07:31:00'){
							$return['time_in']               	= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'</span>';
						}
						else if($row['time_in']<'24:00:00'&&$row['time_in']>='21:31:00'){
							$return['time_in']               	= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'</span>';
						}
						else{
							$return['time_in']               	= date('h:i A',strtotime($row['time_in']));
						}
						$return['time_out'] 	            = date('h:i A',strtotime($row['time_out']));

						break;
				case 'BUS LATE':
						$return['time_in']               	= '<span style="color:#ff1616;">'.date('h:i A',strtotime($row['time_in'])).'(<i>BL</i>)</span>';
						$return['time_out'] 	            = date('h:i A',strtotime($row['time_out']));
					break;
				case 'OB':
						$return['time_in']               	= date('h:i A',strtotime($row['time_in']));
						$return['time_out'] 	            = date('h:i A',strtotime($row['time_out']));
					break;
				case '':
					switch($row['time_in']){
						case 'yec':
							$return['time_in']               	= '<i>YEC</i>';
							$return['time_out'] 	            = '<i>YEC</i>';
							break;
						case 'VL':
							$return['time_in']               	= '<i>VL</i>';
							$return['time_out'] 	            = '<i>VL</i>';
							break;
						case 'SL':
							$return['time_in']               	= '<i>SL</i>';
							$return['time_out'] 	            = '<i>SL</i>';
							break;
						case 'ML':
							$return['time_in']               	= '<i>ML</i>';
							$return['time_out'] 	            = '<i>ML</i>';
							break;
						case 'holiday':
							$return['time_in']               	= '<i>HOL</i>';
							$return['time_out'] 	            = '<i>HOL</i>';
							break;
						case 'FL':
							$return['time_in']               	= '<i>FL</i>';
							$return['time_out'] 	            = '<i>FL</i>';
							break;
						case 'do':
							$return['time_in']               	= '<i>DO</i>';
							$return['time_out'] 	            = '<i>DO</i>';
							break;
						case 'BRL':
							$return['time_in']               	= '<i>BRL</i>';
							$return['time_out'] 	            = '<i>BRL</i>';
							break;
						case 'BL':
							$return['time_in']               	= '<i>BL</i>';
							$return['time_out'] 	            = '<i>BL</i>';
							break;
						case 'BDL':
							$return['time_in']               	= '<i>BDL</i>';
							$return['time_out'] 	            = '<i>BDL</i>';
							break;
						default:
							$return['time_in']               	= 'NO DATA';
							$return['time_out'] 	            = 'NO DATA';	
							break;
					}
					break;
				case 'undefined':
						$return['time_in']               	= 'NO DATA';
						$return['time_out'] 	            = 'NO DATA';				
					break;
				default:
					$return['time_in']               	= date('h:i A',strtotime($row['time_in']));
					$return['time_out'] 	            = date('h:i A',strtotime($row['time_out']));					
					break;
			}

			if($row['time_out']==''){
				$return['time_out']				= '---';
				$return['time_rendered']			= '---';
			}
			else{
				$return['time_rendered']			= number_format(abs($difference/60/60),2,'.','');
			}

			$return['regular_ot']           = number_format($row['regular_ot'],2,'.','');
			$return['sunday_ot'] 	        = number_format($row['sunday_ot'],2,'.','');
			$return['special_holiday_ot'] 	= number_format($row['special_holiday_ot'],2,'.','');
			$return['holiday_ot'] 	        = number_format($row['holiday_ot'],2,'.','');
			$return['undertime'] 	        = number_format($row['total_undertime'],2,'.','');
			$return['night_diff'] 	        = number_format($row['night_diff'],2,'.','');
			$return['absent'] 	            = number_format($row['absent'],2,'.','');
			$return['leave_code'] 	        = $row['leavecode'];
			$return['sl_vl'] 	            = number_format($row['leavenum'],2,'.','');

			$ctr++;
		}

		if($return['user']!=='1'){
			$array_fields 			= array('rc_seen_status');
			$array_values 			= array('0');
			$table 	   				= 'rka_complaints';
			$idname 				= 'rc_id';
			$pkid 					= $return['rc_id'];
			db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		}

		echo json_encode($return);	
	}

	/*MEMO*/

	function load_memo(){
		require_once('./query.php');
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('a.id',
											'a.status',
											'a.title',
											'a.memo_file_name',
											'a.date',
											'a.username',
											'a.approver',
											'b.name',
											'a.approved_remarks',
											'a.approved_date',
											'a.disapproved_remarks',
											'a.disapproved_date'
											);
		$table 	   					= 'rka_memo a';
		$joins 	   					= "INNER JOIN rka_users b on b.username = a.approver";
		$sql_where 					= "WHERE a.logdel=0 AND 
										(a.username = '".$_SESSION['s_username']."' OR   
										a.approver = '".$_SESSION['s_username']."' AND (a.status = '0' OR a.status ='3')) ";
		$sql_order 					= 'ORDER BY a.id ASC';
		$sql_limit 					= '';
		$result 					= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 						= 0;
		while($row = mysqli_fetch_array($result)){
			$return['id'][$ctr]					= $row['id'];
			$return['status'][$ctr]				= $row['status'];
			$return['title'][$ctr]				= $row['title'];
			$return['memo_file_name'][$ctr]		= $row['memo_file_name'];
			$return['date'][$ctr]				= $row['date'];
			$return['approver'][$ctr]			= $row['approver'];
			$return['approver_name'][$ctr]		= $row['name'];
			$return['approved_remarks'][$ctr]	= $row['approved_remarks'];
			$return['approved_date'][$ctr]		= $row['approved_date'];
			$return['disapproved_remarks'][$ctr]	= $row['disapproved_remarks'];
			$return['disapproved_date'][$ctr]		= $row['disapproved_date'];
			$ctr++;
		}
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	function load_memo_fin(){
		require_once('./query.php');
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('a.id',
											'a.status',
											'a.title',
											'a.memo_file_name',
											'a.date',
											'a.username',
											'a.approver',
											'b.name',
											'a.approved_remarks',
											'a.approved_date',
											'a.disapproved_remarks',
											'a.disapproved_date'
											);
		$table 	   					= 'rka_memo a';
		$joins 	   					= "INNER JOIN rka_users b on b.username = a.approver";
		$sql_where 					= "WHERE a.logdel=0 AND (a.status = '1' OR a.status = '3')";
		$sql_order 					= 'ORDER BY a.id ASC';
		$sql_limit 					= '';
		$result 					= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 						= 0;
		while($row = mysqli_fetch_array($result)){
			$return['id'][$ctr]					= $row['id'];
			$return['status'][$ctr]				= $row['status'];
			$return['title'][$ctr]				= $row['title'];
			$return['memo_file_name'][$ctr]		= $row['memo_file_name'];
			$return['date'][$ctr]				= $row['date'];
			$return['approver'][$ctr]			= $row['approver'];
			$return['approver_name'][$ctr]		= $row['name'];
			$return['approved_remarks'][$ctr]	= $row['approved_remarks'];
			$return['approved_date'][$ctr]		= $row['approved_date'];
			$return['disapproved_remarks'][$ctr]	= $row['disapproved_remarks'];
			$return['disapproved_date'][$ctr]		= $row['disapproved_date'];
			$ctr++;
		}
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	function upload_memo_document(){
		require_once('./query.php');

		$return			= $_POST;
		// $id_mcn 		= $_POST['id_mcn'];
		$date 			= date("Y-m-d");

		$memo_file = isset($_FILES['memo_file'])?$_FILES['memo_file']:'';

		$txt_add_attachment_file_name = date('Ymd His');
	    if( $memo_file ){
			$path = $memo_file['name'];
			$txt_add_attachment_file_name = '_memo_'.$date.'.'.pathinfo($path, PATHINFO_EXTENSION);
	    }

		$result			= '';
		$table			= 'rka_memo';
		$array_fields 	= array('status, title, memo_file_name, date, username, approver');
		$array_values 	= array('0',
									$return['memo_title'], 
									$txt_add_attachment_file_name,
									$return['memo_datetime'],
									$_SESSION['s_username'],
									$return['memo_approver']);
		
		$result 		= db_ep::getInstance()->insert_query_id($table,$array_fields,$array_values);		

		// $return['result'] = json_encode($result);

		if(is_numeric($result)){
		    if( $memo_file ){
			    if ( 0 < $memo_file['error'] ) {
			    }
			    else {
					move_uploaded_file(
					    $memo_file['tmp_name'],
					    '../uploads/' .$result.$txt_add_attachment_file_name
					);

					$return['sent_message_memo'] = send_email_memo_approval($result);
			    }
			}
		}

		echo json_encode($return);
	}

	function send_email_memo_approval($id){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.id', 
									'a.title', 
									'a.date', 
									'a.username as emp_username', 
									'b.name as emp_name', 
									'b.email as emp_email', 
									'a.approver as approver_username', 
									'c.name as approver_name', 
									'c.email as approver_email');
		$table 	   			= 'rka_memo a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.username
								INNER JOIN rka_users c on c.username = a.approver';
		$sql_where 			= "WHERE a.username = '".$_SESSION['s_username']."' ";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$title 				= $row['title'];
			$date 				= $row['date'];

			$approver_username 	= $row['approver_username'];
			$approver_name 		= $row['approver_name'];
			$approver_email 	= $row['approver_email'];
		}


		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		$mail->addAddress($approver_email);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addCC($emp_email);
		$mail->addBCC('jgsulit@pricon.ph');
		$mail->isHTML(true);
		$mail->Subject = 'Employee Portal - MEMO For Approval';
		
		$body = '<div>';
		$body .= '	<span>Good day!, <br> You have new Memo for your approval. <br> Please login to your e-portal account to see the attached file. Thank you!</span><br><br>';
		$body .= '	<span>Details:</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td>Requestor: <b>'.$emp_name.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Request Date: <b>'.$date.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Memo Title: <b>'.$title.'</b> </td>';
		$body .= '		</tr>';
		$body .= '	</table>';

		$body .= '	<br><span>This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span>If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '</div>';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	function approved_memo(){
		require_once('./query.php');
		$lastupdate = date("Y-m-d H:i:s");

		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('status',
										'approved_remarks',
										'approved_date');
		$array_values 			= array('1',
										$return['memo_approved_remarks'],
										$lastupdate);
		$table 	   				= 'rka_memo';
		$idname 				= 'id';
		$pkid 					= $return['memo_app_id'];


		$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		if($row = mysqli_fetch_array($result)){
			$return['mail_status'] = send_email_approved_memo($return['memo_app_id']);
		}

		echo json_encode($return);
	}

	function send_email_approved_memo($id){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.id', 
									'a.title', 
									'a.date', 
									'a.username as emp_username', 
									'b.name as emp_name', 
									'b.email as emp_email', 
									'a.approver as approver_username', 
									'c.name as approver_name', 
									'c.email as approver_email',
									'a.approved_remarks',
									'a.approved_date');
		$table 	   			= 'rka_memo a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.username
								INNER JOIN rka_users c on c.username = a.approver';
		$sql_where 			= "WHERE a.approver = '".$_SESSION['s_username']."' AND a.id = '".$id."'";
		// $sql_where 			= "WHERE a.approver = 'Q121' AND a.id = '1'";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$title 				= $row['title'];
			$date 				= $row['date'];

			$approver_username 	= $row['approver_username'];
			$approver_name 		= $row['approver_name'];
			$approver_email 	= $row['approver_email'];
			$approved_remarks   = $row['approved_remarks'];
			$approved_date	    = $row['approved_date'];
		}

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		
		$mail->addAddress($emp_email);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addCC('jgsulit@pricon.ph');
		// $mail->addCC('jgserrac@pricon.ph');
		$mail->isHTML(true);

		$mail->Subject = 'Employee Portal - Approved MEMO';
		
		$body = '<div>';
		$body .= '	<span>Good day! <br> Please be informed that your Memo has been approved. <br> Please login to your e-portal account to see the attached file. Thank you!</span><br><br>';
		$body .= '	<span>Details:</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td>Requestor: <b>'.$emp_name.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Request Date: <b>'.$date.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Memo Title: <b>'.$title.'</b> </td> <br>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Remarks: <b>'.$approved_remarks.'</b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Date: <b>'.$approved_date.'</b> </td>';
		$body .= '		</tr>';
		$body .= '	</table>';

		$body .= '	<br><span>This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span>If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '</div>';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	function conform_memo(){
		require_once('./query.php');
		$lastupdate = date("Y-m-d H:i:s");

		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('status',
										'conform_remarks',
										'conform_by',
										'conform_date');
		$array_values 			= array('3',
										$return['memo_conform_remarks'],
										$_SESSION['s_username'],
										$lastupdate);
		$table 	   				= 'rka_memo';
		$idname 				= 'id';
		$pkid 					= $return['memo_con_id'];


		$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		if($row = mysqli_fetch_array($result)){
			$return['mail_status'] = send_email_conform_memo($return['memo_con_id']);
		}

		echo json_encode($return);
	}
	
	function send_email_conform_memo($id){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.id', 
									'a.title', 
									'a.date', 
									'a.username as emp_username', 
									'b.name as emp_name', 
									'b.email as emp_email', 
									'a.approver as approver_username', 
									'c.name as approver_name', 
									'c.email as approver_email',
									'a.conform_remarks',
									'a.conform_date');
		$table 	   			= 'rka_memo a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.username
								INNER JOIN rka_users c on c.username = a.approver';
		$sql_where 			= "WHERE a.id = '".$id."'";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$title 				= $row['title'];
			$date 				= $row['date'];

			$approver_username 	= $row['approver_username'];
			$approver_name 		= $row['approver_name'];
			$approver_email 	= $row['approver_email'];
			$conform_remarks   	= $row['conform_remarks'];
			$conform_date	    = $row['conform_date'];
		}

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		
		$mail->addAddress($emp_email);
		// $mail->addAddress('jgsulit@pricon.ph');
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addCC($approver_username); 
		// $mail->addCC('jgsulit@pricon.ph');
		$mail->addCC('jgsulit@pricon.ph');
		$mail->isHTML(true);

		$mail->Subject = 'Employee Portal - Conformed MEMO';
		
		$body = '<div>';
		$body .= '	<span>Good day! <br> Please be informed that your Memo has been conformed by Finance. <br> Please login to your e-portal account to see the attached file. Thank you!</span><br><br>';
		$body .= '	<span>Details:</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td>Requestor: <b>'.$emp_name.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Request Date: <b>'.$date.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Memo Title: <b>'.$title.'</b> </td> <br>';
		$body .= '		</tr>';
		// $body .= '		<tr>';
		// $body .= '			<td>Approval Remarks: <b>'.$approved_remarks.'</b> </td>';
		// $body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Date: <b>'.$conform_date.'</b> </td>';
		$body .= '		</tr>';
		$body .= '	</table>';

		$body .= '	<br><span>This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span>If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '</div>';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	function disapproved_memo(){
		require_once('./query.php');
		$lastupdate = date("Y-m-d H:i:s");

		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('status',
										'disapproved_remarks',
										'disapproved_date');
		$array_values 			= array('2',
										$return['memo_disapproved_remarks'],
										$lastupdate);
		$table 	   				= 'rka_memo';
		$idname 				= 'id';
		$pkid 					= $return['memo_dis_id'];


		$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		if($row = mysqli_fetch_array($result)){
			$return['mail_status'] = send_email_disapproved_memo($return['memo_dis_id']);
		}

		echo json_encode($return);
	}
	
	function send_email_disapproved_memo($id){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.id', 
									'a.title', 
									'a.date', 
									'a.username as emp_username', 
									'b.name as emp_name', 
									'b.email as emp_email', 
									'a.approver as approver_username', 
									'c.name as approver_name', 
									'c.email as approver_email',
									'a.disapproved_remarks',
									'a.disapproved_date');
		$table 	   			= 'rka_memo a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.username
								INNER JOIN rka_users c on c.username = a.approver';
		$sql_where 			= "WHERE a.approver = '".$_SESSION['s_username']."' AND a.id = '".$id."'";
		// $sql_where 			= "WHERE a.approver = 'Q121' AND a.id = '2'";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$title 				= $row['title'];
			$date 				= $row['date'];

			$approver_username 	= $row['approver_username'];
			$approver_name 		= $row['approver_name'];
			$approver_email 	= $row['approver_email'];
			$disapproved_remarks= $row['disapproved_remarks'];
			$disapproved_date	= $row['disapproved_date'];
		}

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		
		$mail->addAddress($emp_email);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addCC('jgsulit@pricon.ph');
		$mail->isHTML(true);

		$mail->Subject = 'Employee Portal - Disapproved MEMO';
		
		$body = '<div>';
		$body .= '	<span>Good day! <br> Please be informed that your Memo has been disapproved. <br> Please login to your e-portal account to see the attached file. Thank you!</span><br><br>';
		$body .= '	<span>Details:</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td>Requestor: <b>'.$emp_name.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Request Date: <b>'.$date.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Memo Title: <b>'.$title.'</b> </td> <br>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Remarks: <b>'.$disapproved_remarks.'</b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Date: <b>'.$disapproved_date.'</b> </td>';
		$body .= '		</tr>';
		$body .= '	</table>';

		$body .= '	<br><span>This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span>If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '</div>';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	/* CEC*/

	function load_cec(){
		require_once('./query.php');
		$return 					= $_POST;
		$result 					= '';
		$array_fields 				= array('a.id',
											'a.status',
											'a.purpose',
											'a.date',
											'a.username',
											'a.approver1',
											'b.name as approver1_name',
											'c.name as emp_name',
											'a.approved1_remarks',
											'a.approved1_date',
											'a.disapproved1_remarks',
											'a.disapproved1_date'
											);
		$table 	   					= 'rka_cec a';
		$joins 	   					= "INNER JOIN rka_users b on b.username = a.approver1
										INNER JOIN rka_users c on c.username = a.username";
		$sql_where 					= "WHERE a.logdel=0 AND 
										(a.username = '".$_SESSION['s_username']."' OR   
										a.approver1 = '".$_SESSION['s_username']."' AND a.status = '0') ";
		$sql_order 					= 'ORDER BY a.status, a.id ASC';
		$sql_limit 					= '';
		$result 					= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr 						= 0;
		while($row = mysqli_fetch_array($result)){
			$return['id'][$ctr]					= $row['id'];
			$return['status'][$ctr]				= $row['status'];
			$return['username'][$ctr]			= $row['username'];
			$return['purpose'][$ctr]			= $row['purpose'];
			$return['date'][$ctr]				= $row['date'];
			$return['approver1'][$ctr]			= $row['approver1'];
			$return['approver1_name'][$ctr]		= $row['approver1_name'];
			$return['emp_name'][$ctr]			= $row['emp_name'];
			$return['approved1_remarks'][$ctr]	= $row['approved1_remarks'];
			$return['approved1_date'][$ctr]		= $row['approved1_date'];
			$return['disapproved1_remarks'][$ctr]	= $row['disapproved1_remarks'];
			$return['disapproved1_date'][$ctr]		= $row['disapproved1_date'];
			$ctr++;
		}
		$return['ctr'] = $ctr;
		echo json_encode($return);
	}

	function request_cec(){
		require_once('./query.php');
		$return				= $_POST;
		$result				= "";
		$table      	 	= "rka_cec";
		$array_fields		= array('status','purpose', 'date', 'username','approver1');
		$array_values 		= array(0,
										$return['cec_purpose'],
										$return['cec_datetime'],
										$_SESSION['s_username'],
										Q121);
		$as_target			= 'id';
		$as_name			= 'id';
		$result = db_ep::getInstance()->insert_query_return_id($table,$array_fields,$array_values,$as_target,$as_name);
		if($row = mysqli_fetch_array($result)){
			$return['sent_message_status'] = send_email_cec_request($row['id']);
		}
		echo json_encode($return);		
	}
	
	function send_email_cec_request($id){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.id', 
									'a.purpose', 
									'a.date', 
									'a.username as emp_username', 
									'b.name as emp_name', 
									'b.email as emp_email', 
									'a.approver1 as approver1_username', 
									'c.name as approver1_name', 
									'c.email as approver1_email',
									'a.approved1_remarks',
									'a.approved1_date');
		$table 	   			= 'rka_cec a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.username
								INNER JOIN rka_users c on c.username = a.approver1';
		$sql_where 			= "WHERE a.username = '".$_SESSION['s_username']."'";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$purpose 			= $row['purpose'];
			$date 				= $row['date'];

			$approver1_username 	= $row['approver1_username'];
			$approver1_name 		= $row['approver1_name'];
			$approver1_email 		= $row['approver1_email'];
			$approved1_remarks 		= $row['approved1_remarks'];
			$approved1_date			= $row['approved1_date'];
		}


		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		// $mail->addAddress('jgserrac@pricon.ph');
		$mail->addAddress($approver1_email);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addCC($emp_email);
		$mail->addBCC('jgsulit@pricon.ph');
		$mail->isHTML(true);
		$mail->Subject = '['.$emp_username.'] Employee Portal - CEC Request';
		
		$body = '<div>';
		$body .= '	<span>Good day! <br> You have new CEC request for your approval.</span><br><br>';
		$body .= '	<span>Details:</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td>Requestor: <b>'.$emp_name.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Request Date: <b>'.$date.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Purpose: <b>'.$purpose.'</b> </td> <br>';
		$body .= '		</tr>';
		$body .= '	</table>';

		$body .= '	<br><span>This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span>If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '</div>';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	function approved_cec(){
		require_once('./query.php');
		$lastupdate = date("Y-m-d H:i:s");

		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('status',
										'approved1_remarks',
										'approved1_date');
		$array_values 			= array('1',
										$return['cec_approved_remarks'],
										$lastupdate);
		$table 	   				= 'rka_cec';
		$idname 				= 'id';
		$pkid 					= $return['cec_app_id'];


		$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		if($row = mysqli_fetch_array($result)){
			$return['mail_status'] = send_email_approved_cec($return['cec_app_id']);
		}

		echo json_encode($return);
	}

	function send_email_approved_cec($id){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.id', 
									'a.purpose', 
									'a.date', 
									'a.username as emp_username', 
									'b.name as emp_name', 
									'b.email as emp_email', 
									'a.approver1 as approver1_username', 
									'c.name as approver1_name', 
									'c.email as approver1_email',
									'a.approved1_remarks',
									'a.approved1_date');
		$table 	   			= 'rka_cec a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.username
								INNER JOIN rka_users c on c.username = a.approver1';
		$sql_where 			= "WHERE a.approver1 = '".$_SESSION['s_username']."' AND a.id = '".$id."'";
		// $sql_where 			= "WHERE a.approver1 = 'Q121' AND a.id = '".$id."'";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$purpose 			= $row['purpose'];
			$date 				= $row['date'];

			$approver1_username = $row['approver1_username'];
			$approver1_name 	= $row['approver1_name'];
			$approver1_email 	= $row['approver1_email'];
			$approved1_remarks  = $row['approved1_remarks'];
			$approved1_date	    = $row['approved1_date'];
		}

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		
		$mail->addAddress($emp_email);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addCC('jgsulit@pricon.ph');
		// $mail->addCC('jgserrac@pricon.ph');
		$mail->isHTML(true);

		$mail->Subject = 'Employee Portal - Approved CEC';
		
		$body = '<div>';
		$body .= '	<span>Good day! <br> Please be informed that your CEC has been approved. Thank you!</span><br><br>';
		$body .= '	<span>Details:</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td>Requestor: <b>'.$emp_name.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Request Date: <b>'.$date.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Purpose: <b>'.$purpose.'</b> </td> <br>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Remarks: <b>'.$approved1_remarks.'</b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Date: <b>'.$approved1_date.'</b> </td>';
		$body .= '		</tr>';
		$body .= '	</table>';

		$body .= '	<br><span>This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span>If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '</div>';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	function disapproved_cec(){
		require_once('./query.php');
		$lastupdate = date("Y-m-d H:i:s");

		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('status',
										'disapproved1_remarks',
										'disapproved1_date');
		$array_values 			= array('2',
										$return['cec_disapproved_remarks'],
										$lastupdate);
		$table 	   				= 'rka_cec';
		$idname 				= 'id';
		$pkid 					= $return['cec_dis_id'];


		$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
		if($row = mysqli_fetch_array($result)){
			$return['mail_status'] = send_email_disapproved_cec($return['cec_dis_id']);
		}

		echo json_encode($return);
	}

	function send_email_disapproved_cec($id){
		require_once('../../dist/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		require_once('./query.php');
        require_once('./email_config.php');

		$return 			= $_POST;
		$result 			= '';
		$array_fields 		= array('a.id', 
									'a.purpose', 
									'a.date', 
									'a.username as emp_username', 
									'b.name as emp_name', 
									'b.email as emp_email', 
									'a.approver1 as approver1_username', 
									'c.name as approver1_name', 
									'c.email as approver1_email',
									'a.disapproved1_remarks',
									'a.disapproved1_date');
		$table 	   			= 'rka_cec a';
		$joins 	   			= 'INNER JOIN rka_users b on b.username = a.username
								INNER JOIN rka_users c on c.username = a.approver1';
		$sql_where 			= "WHERE a.approver1 = '".$_SESSION['s_username']."' AND a.id = '".$id."'";
		// $sql_where 			= "WHERE a.approver1 = 'Q121' AND a.id = '".$id."'";
		$sql_order 			= '';
		$sql_limit 			= '';
		$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$emp_username 		= $row['emp_username'];
			$emp_name 			= $row['emp_name'];
			$emp_email 			= $row['emp_email'];
			$purpose 			= $row['purpose'];
			$date 				= $row['date'];

			$approver1_username = $row['approver1_username'];
			$approver1_name 	= $row['approver1_name'];
			$approver1_email 	= $row['approver1_email'];
			$disapproved1_remarks  = $row['disapproved1_remarks'];
			$disapproved1_date	    = $row['disapproved1_date'];
		}

		$mail->setFrom('issinfoservice@pricon.ph', 'Employee Portal');
		
		$mail->addAddress($emp_email);
		$mail->addReplyTo('issinfoservice@pricon.ph', 'Information System Section');
		$mail->addCC('jgsulit@pricon.ph');
		// $mail->addCC('jgserrac@pricon.ph');
		$mail->isHTML(true);

		$mail->Subject = 'Employee Portal - Dispproved CEC';
		
		$body = '<div>';
		$body .= '	<span>Good day! <br> Please be informed that your CEC has been disapproved. Thank you!</span><br><br>';
		$body .= '	<span>Details:</span><br>';
		$body .= '	<table>';
		$body .= '		<tr>';
		$body .= '			<td>Requestor: <b>'.$emp_name.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Request Date: <b>'.$date.' </b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Purpose: <b>'.$purpose.'</b> </td> <br>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Remarks: <b>'.$disapproved1_remarks.'</b> </td>';
		$body .= '		</tr>';
		$body .= '		<tr>';
		$body .= '			<td>Approval Date: <b>'.$disapproved1_date.'</b> </td>';
		$body .= '		</tr>';
		$body .= '	</table>';

		$body .= '	<br><span>This is a system generated email, please do not reply.</span><br>';
		$body .= '	<span>If you are not the intended recipient of this email and feels that you have received this in error, please send ticket to <a href="mailto:issinfoservice@pricon.ph">servicerequest@pricon.ph</a>.</span><br><br>';
		$body .= '</div>';
		$body .= "Notice of Disclaimer:<br>This message (including any attachments) contains confidential information intended for a specific individual and purpose, and is protected by law. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.";
		$mail->Body    = $body;

		if(!$mail->send()) {
		    $mail_status 	=  '0';
		} else {
		    $mail_status 	=  '1';
		}

		return $mail_status;
	}

	/* New Function for Payroll Info 2023 -Migz */
	function load_payroll_info(){
		try{
			require_once('./query.php');
			$result 			= '';
			$array_fields 		= array('*');
			$table 	   			= 'rka_payroll_info';
			$joins				= '';
			$sql_where			= '';
			// $sql_where			= 'WHERE EndingDate >= "'.date('01/01/2018').'"';
			$sql_order	 		= 'ORDER BY id DESC';
			$sql_limit 			= "";
			$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			$ctr 				= 0;		
			// write_logs::getInstance()->save_logs('1',$query);
			while($row = mysqli_fetch_array($result)){			
				$return['Pkid'][$ctr] 				= $row['id'];
				if($row['description']==1){
					$return['Description'][$ctr] ='Daily';
				}else if($row['description']==2){
					$return['Description'][$ctr] ='Monthly';
				}else if($row['description']==4){
					$return['Description'][$ctr] ='JS/SS';
				}
				if($row['cut_off']==1){
					$return['CutOff'][$ctr] ='1st';
				}else if($row['cut_off']==2){
					$return['CutOff'][$ctr] ='2nd';
				}
				$return['StartingDate'][$ctr]		= date('M d, Y',strtotime($row['date_start']));
				$return['EndingDate'][$ctr] 		= date('M d, Y',strtotime($row['date_end']));
				$return['CutOffDate'][$ctr] 		= date('M d, Y',strtotime($row['date_cut_off']));
				if($row['posted']=='Y'){
					$return['Posted'][$ctr] ='Yes';
				}else if($row['posted']=='N'){
					$return['Posted'][$ctr] ='No';
				}
				$return['Lastupdate'][$ctr]			= date('M d, Y h:i:s A',strtotime($row['updated_at']));
				$ctr++;
			}
			$return['ctr'] = $ctr;
			echo json_encode($return);
		}catch(Exception $err){
			$error['error_message'] =  $err;
			$error['has_error'] =  'Exception Error';
			echo json_encode($error);
		}

	}
	function create_payroll_info(){
		try{
			require_once('./query.php');
			$request = $_POST;
			$table = "rka_payroll_info";
			$array_fields = array('created_by',"description","cut_off","date_start","date_end","date_cut_off","posted","created_at","updated_at");
			$array_values = array(	'mclegaspi',$request["slct_description"],$request["slct_cut_off"],$request["dt_start_date"],
									$request["dt_end_date"],$request["dt_cut_off_date"],$request["slct_posted"],date('Y-m-d H:m:s'),date('Y-m-d H:m:s')
							);
			$result = db_ep::getInstance()->insert_query($table,$array_fields,$array_values);
			$return['result'] = 'success';
			echo json_encode($return);
		}catch(Exception $err){
			$error['error_message'] =  $err;
			$error['has_error'] =  'Exception Error';
			echo json_encode($error);
		}
	}
	function update_payroll_info(){
		try{
			require_once('./query.php');
			$request 	= $_POST;
			$pkid 		= $request['data_id'];
			$idname 	= 'id';
			$table = "rka_payroll_info";

			$array_fields = array('created_by',"description","cut_off","date_start","date_end","date_cut_off","posted","updated_at",);
			$array_values = array(	'mclegaspi',$request["slct_description"],$request["slct_cut_off"],$request["dt_start_date"],
									$request["dt_end_date"],$request["dt_cut_off_date"],$request["slct_posted"],date('Y-m-d H:m:s'),
							);
			$result = db_ep::getInstance()->update_query($table,$array_fields,$array_values,$pkid,$idname);
			$return['result'] = 'success';
			echo json_encode($return);
		}catch(Exception $err){
			$error['error_message'] =  $err;
			$error['has_error'] =  'Exception Error';
			echo json_encode($error);
		}
	}
	function read_payroll_info(){
		try{
			require_once('./query.php');
			$request 			= $_POST;
			$result 			= '';
			$pkid 				= $request['pkid'];
			$array_fields 		= array('*');
			$table 	   			= 'rka_payroll_info';
			$joins				= '';
			$sql_where			= 'WHERE id = "'.$pkid.'"';
			$sql_order	 		= 'ORDER BY id DESC';
			$sql_limit 			= "";
			$result 			= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
			$ctr 				= 0;		
			// write_logs::getInstance()->save_logs('1',$query);
			while($row = mysqli_fetch_array($result)){			
				$return['Pkid'][$ctr] 				= $row['id'];
				$return['Description'][$ctr] 		= $row['description'];
				$return['CutOff'][$ctr] 			= $row['cut_off'];
				$return['StartingDate'][$ctr]		= $row['date_start'];
				$return['EndingDate'][$ctr] 		= $row['date_end'];
				$return['CutOffDate'][$ctr] 		= $row['date_cut_off'];
				$return['Posted'][$ctr] 			= $row['posted'];
				$return['Lastupdate'][$ctr]			= date('M d, Y h:i:s A',strtotime($row['updated_at']));
				$ctr++;
			}
			$return['ctr'] = $ctr;
		
			echo json_encode($return);
		}catch(Exception $err){
			$error['error_message'] =  $err;
			$error['has_error'] =  'Exception Error';
			echo json_encode($error);
		}
	}

	function load_profile(){
		require_once('./query.php');
		$return 				= $_POST;
		$result 				= '';
		$array_fields 			= array('FirstName','LastName','DateHired','Position','EmpNo','Section','DOBirth');
		$table 	   				= 'tbl_EmployeeInfo';
		$joins 	   				= 'LEFT JOIN tbl_Position ON tbl_EmployeeInfo.fkPosition=tbl_Position.pkid';
		$joins 	   				.= ' LEFT JOIN tbl_Section ON tbl_EmployeeInfo.fkSection=tbl_Section.pkid';
		$sql_where 				= 'WHERE EmpNo="'.$_SESSION['s_username'].'"';
		$sql_order 				= '';
		$sql_limit 				= '';
		$result 				= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['FirstName'] 				= utf8_encode($row['FirstName']);
			$return['LastName'] 				= utf8_encode($row['LastName']);
			$return['DateHired'] 				= date('F d, Y',strtotime($row['DateHired']));
			$return['Position'] 				= $row['Position'];
			$return['EmpNo'] 					= $row['EmpNo'];

			//09072024 by Nessa
			$string_to_encrypt  				= $row['EmpNo'];
            $password           				= "passwordsa";
            $encrypted_string   				= openssl_encrypt($string_to_encrypt,"AES-128-ECB",$password);
            $return['EN'] 						= $encrypted_string;

			$return['Section'] 					= $row['Section'];
			$return['DOBirth'] 					= $row['DOBirth'];
		}

		unset($result);
		$array_fields 			= array('previous_balance_VL',
										'previous_balance_SL',
										'credit_VL',
										'credit_SL',
										'current_avail_VL',
										'current_avail_SL',
										'total_VL_used',
										'total_SL_used',
										'total_VL_balance',
										'total_SL_balance');
		$table 	   				= 'tbl_leaves_balance';
		$joins 	   				= '';
		$sql_where 				= 'WHERE EmpNo="'.$_SESSION['s_username'].'"';
		$sql_order 				= '';
		$sql_limit 				= '';
		$result 				= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		if($row = mysqli_fetch_array($result)){
			$return['previous_balance_VL'] 		= number_format($row['previous_balance_VL'],1); //02262025 by NESSA DELETE (int)
			$return['previous_balance_SL'] 		= number_format($row['previous_balance_SL'],1);
			$return['credit_VL'] 				= number_format($row['credit_VL'],1);
			$return['credit_SL'] 				= number_format($row['credit_SL'],1);
			$return['current_avail_VL'] 		= number_format($row['current_avail_VL'],1);
			$return['current_avail_SL'] 		= number_format($row['current_avail_SL'],1);
			$return['total_VL_used'] 			= number_format($row['total_VL_used'],1);
			$return['total_SL_used'] 			= number_format($row['total_SL_used'],1);
			$return['total_VL_balance'] 		= number_format($row['total_VL_balance'],1);
			$return['total_SL_balance'] 		= number_format($row['total_SL_balance'],1);
		}
		
		unset($result);
		$array_fields 			= array('C.leave_code',
										'B.date_absent_from',
										'B.no_of_days',
										'B.no_of_paid');
		$table 	   				= 'tbl_leave_application AS A';
		$joins 	   				= 'LEFT JOIN tbl_leave_dates AS B ON A.pkid=B.fk_leave_app';
		$joins 	   				.= ' LEFT JOIN tbl_leave_applied AS C ON A.leave_applied=C.pkid';
		$sql_where 				= 'WHERE A.EmpNo="'.$_SESSION['s_username'].'" AND A.logdel="0" AND B.logdel="0"';
		$sql_order 				= 'ORDER BY B.date_absent_from DESC';
		$sql_limit 				= 'LIMIT 30';
		$result 				= db_hris_web::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr_my_leaves			= 0;
		while($row = mysqli_fetch_array($result)){
			$return['leave_code'][$ctr_my_leaves] 				= $row['leave_code'];
			$return['date_absent_from'][$ctr_my_leaves] 		= date('M d',strtotime($row['date_absent_from']));
			$return['no_of_days'][$ctr_my_leaves] 				= $row['no_of_days'];
			$return['no_of_paid'][$ctr_my_leaves] 				= $row['no_of_paid'];
			$ctr_my_leaves++;
		}
		$return['ctr_my_leaves'] = $ctr_my_leaves;
		
		unset($result);
		$result 				= '';
		$array_fields 			= array('rka_complaints.rc_timekeeper_remarks',
										'rka_complaints.rc_concern_date',
										'rka_complaints.rc_message',
										'rka_complaints.rc_status ',
										'rka_complaints.rc_adate');
		$table 	   				= 'rka_complaints';
		$joins 	   				= 'LEFT JOIN rka_users ON rka_complaints.rc_username=rka_users.username';
		$sql_where 				= 'WHERE rc_username="'.$_SESSION['s_username'].'"';
		$sql_order 				= 'ORDER BY rc_concern_date DESC';
		$sql_limit 				= 'LIMIT 10';
		$result 				= db_ep::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr_my_complaints		= 0;
		while($row = mysqli_fetch_array($result)){
			$return['rc_concern_date'][$ctr_my_complaints] 				= date('M d',strtotime($row['rc_concern_date']));
			$return['rc_message'][$ctr_my_complaints] 					= $row['rc_message'];
			$return['rc_timekeeper_remarks'][$ctr_my_complaints] 		= $row['rc_timekeeper_remarks'];
			$return['rc_status'][$ctr_my_complaints] 					= $row['rc_status'];
			$return['x_ago'][$ctr_my_complaints]						= time_elapsed_string($row['rc_adate']);
			$ctr_my_complaints++;
		}
		$return['ctr_my_complaints'] = $ctr_my_complaints;

		//-03102020
		unset($result);
		$result 				= '';
		$array_fields 			= array('tkovertime_main.req_status','tk_overtime_details.ot_date','tk_overtime_details.filed_ot');
		$table 	   				= 'tk_overtime_details';
		$joins 	   				= 'LEFT JOIN tkovertime_main ON tk_overtime_details.fkid_ot_main=tkovertime_main.pkid';
		$sql_where 				= 'WHERE tk_overtime_details.empno ="'.$_SESSION['s_username'].'" AND tk_overtime_details.logdel="0"';
		$sql_order 				= 'ORDER BY ot_date DESC';
		$sql_limit 				= 'LIMIT 30';
		$result 				= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		// $ctr_overtime_requests 				= db_ep::getInstance()->query_select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
		$ctr_overtime_requests	= 0;
		while($row = mysqli_fetch_array($result)){
			$return['ot_date'][$ctr_overtime_requests] 			= date('M d',strtotime($row['ot_date']));
			$return['req_status'][$ctr_overtime_requests] 		= $row['req_status'];
			$return['filed_ot'][$ctr_overtime_requests] 		= $row['filed_ot'];
			$ctr_overtime_requests++;
		}
		$return['ctr_overtime_requests'] = $ctr_overtime_requests;

		/* Original Code */
		// unset($result);
		// $db_hris					= new db_hris;
		// $result 					= '';
		// $array_fields 				= array('EmpNo',
		// 									'LastName',
		// 									'FirstName',
		// 									'Department',
		// 									'BasicPay',
		// 									'AccountNumber',);
		// $table 	   					= 'e_portal_employees';
		// $joins 	   					= "";
		// $sql_where 					= "WHERE Status='1' AND EmpNo='".$_SESSION['s_username']."'";
		// $sql_order 					= 'ORDER BY EmpNo DESC';
		// $result 					= $db_hris->select_query($array_fields,$table,$joins,$sql_where,$sql_order);
		// $ctr 						= 0;
		// while($row = mssql_fetch_array($result)){
		// 	$return['EmpNo'][$ctr]				= $row['EmpNo'];
		// 	$return['BasicPay'][$ctr]			= number_format($row['BasicPay'],2,".",",");
		// 	$return['Ecola'][$ctr]				= number_format($row['Ecola'],2,".",",");
		// 	$return['AccountNumber'][$ctr]		= $row['AccountNumber'];
		// }

		$result_child 			= '';
		$array_fields_child 	= array('account_no');
		$table_child 	   		= 'tbl_account_no';
		$joins_child 	   		= '';
		$sql_where_child 		= 'WHERE emp_no= "'.$_SESSION['s_username'].'"';
		$sql_order_child		= '';
		$sql_limit_child 		= '';
		$result_child 			= db_rp::getInstance()->select_query($array_fields_child,$table_child,$joins_child,$sql_where_child,$sql_order_child,$sql_limit_child);
		if($row_child = mysqli_fetch_array($result_child)){
			$return['account_no'] 			= $row_child['account_no'];
		}

		$return['ses'] = session_get_cookie_params();//isset($_SESSION)?$_SESSION:'';

		echo json_encode($return);
	}



	
?>





