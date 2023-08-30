<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

	$excel_class = 'excel.php';
	// require_once("../../../handler/query.php");

	if(file_exists($excel_class)){
		include($excel_class);
	}
	else{
		echo 'File '.$excel_class.' does not exist';
		exit;
	}
	
	$font = array(
		'font'  => array(
		    'size'  => 10,
		    'name'  => 'Arial'
		)
	);

	$vertical_align_middle = array(
        'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        )
    );

	$border_thick = array(
	    'borders' => array(
	        'outline' => array(
	            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
	        ),
	    ),
	);

	$border_double = array(
	    'borders' => array(
	        'outline' => array(
	            'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
	        ),
	    ),
	);


	//FILL COLOR
	$fill_blue = array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'b3ecff')
		)
	);
	$fill_indigo = array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'c3d6f4')
		)
	);
	$fill_cream = array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'f2e1b5')
		)
	);
	$fill_yellow = array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'ffff99')
		)
	);
	$fill_green = array(
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array('rgb' => 'b5ffc9')
		)
	);

	$return 			= $_GET;

	$excel = new EXCEL;
	$excel->title = 'Attendance Record';
	$excel->add_page();
	$excel->set_active_sheet(0);
	$excel->getDefaultStyle()->applyFromArray($font)->applyFromArray($vertical_align_middle);
	$excel->getActiveSheet()->setAutoFilter('A3:L3');
	$excel->getActiveSheet()->freezePane('A4');
	$array_cell = array('A','B','C','D','E','F','G','H','I','J','K','L');
	$array_width = array('15','15','15','10','10','10','10','10','10','10','10','10');
	$excel->set_width($array_cell,$array_width);
	$array_row = array('1','2');
	$array_height = array(30,20);
	$excel->set_height($array_row,$array_height);
	$excel->set_font('A1',true,'','6ff298',15,'Arial Narrow');
	$excel->merge_cells('A1:L1');
	$excel->place_value('A1','Attendance Record','string','left',true);
	$excel->set_font('A2',false,'','6ff298',12,'Arial Narrow');
	$excel->merge_cells('A2:L2');
	$excel->place_value('A2', date('F d, Y', strtotime($return['date_start'])).' - '.date('F d, Y', strtotime($return['date_end'])) ,'string','left',true);
	// $excel->set_borders('A4:L4',1,1,1,1);	
	$excel->place_value('A3','Date','string','center',true);
	$excel->place_value('B3','Time In','string','center',true);
	$excel->place_value('C3','Time Out','string','center',true);
	$excel->place_value('D3','Rendered Hrs.','string','center',true);
	$excel->place_value('E3','Regular OT','string','center',true);
	$excel->place_value('F3','Sunday OT','string','center',true);
	$excel->place_value('G3','Special Holiday','string','center',true);
	$excel->place_value('H3','Holiday OT','string','center',true);
	$excel->place_value('I3','Under-time','string','center',true);
	$excel->place_value('J3','Night Diff','string','center',true);
	$excel->place_value('K3','Absent.','string','center',true);
	$excel->place_value('L3','SL/VL','string','center',true);
	$excel->set_font('A3:L3',true,'','ffff99',12,'Arial Narrow');
	$excel->set_borders('A1:L4',1,1,1,1);	

//---
	require_once("../../../handler/query.php");
	//	$return 			= $_GET; --- at the top
	$result 			= '';
	$array_fields 		= array('date',
								'time_in',
								'mode_in',
								'time_out',
								'ot1 as regular_ot',
								'(ot3 + ot3a) as sunday_ot',
								'(ot2 + ot2a + ot5 + ot5a) as special_holiday_ot',
								'(ot4 + ot4a + ot6 + ot6a) as holday_ot',
								'under_mins',
								'nightdiff',
								'absent',
								'leavecode',
								'leavenum');
	$table 	   			= 'vwDailyTFM';
	$joins 	   			= '';


	if(trim($return['emp_no'])=='undefined'||trim($return['emp_no'])==''){
		$sql_where 			= 'WHERE empno="'.$_SESSION['s_username'].'" AND (date BETWEEN "'.$return['date_start'].'" AND "'.$return['date_end'].'")';
	}
	else{
		$sql_where 			= 'WHERE empno="'.$return['emp_no'].'" AND (date BETWEEN "'.$return['date_start'].'" AND "'.$return['date_end'].'")';		
	}


	$sql_order 			= '';
	$sql_limit 			= '';
	$result 			= db_otks::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
	$query				= 0;
	$ctr 				= 4;
	$ctr_1st_row		= $ctr;
	while($row = mysqli_fetch_array($result)){
		$value_date = '';
		$value_time_in = '';
		$value_time_out = '';
		$value_rendered = '';
		$value_regular_ot = '';
		$value_sunday_ot = '';
		$value_special_ot = '';
		$value_holiday_ot = '';
		$value_undertime = '';
		$value_night_diff = '';
		$value_absent = '';
		$value_sl_vl = '';

		// $datetime1 = $row['time_in'];
		// $datetime2 = $row['time_out'];

		$date1 = $row['time_in'];
		$date2 =  $row['time_out'];
		 
		//Convert them to timestamps.
		$date1Timestamp = strtotime($date1);
		$date2Timestamp = strtotime($date2);
		 
		//Calculate the difference.
		$difference = $date2Timestamp - $date1Timestamp;

		$value_date 	                = date('M d, Y',strtotime($row['date']));
		switch($row['mode_in']){
			case 'IN':
					// $return['time_in'][$ctr]               	= date('h:i A',strtotime($row['time_in']));
					// $return['time_out'][$ctr] 	            = date('h:i A',strtotime($row['time_out']));
				
					$value_time_in               	= date('h:i A',strtotime($row['time_in']));

					// if($row['time_out']==''){
					// 	$return['time_out'][$ctr] 	            = '---';
					// }
					// else{
						$value_time_out 	            = date('h:i A',strtotime($row['time_out']));
					// }

					break;
			case 'BUS LATE':
					$value_time_in               	= date('h:i A',strtotime($row['time_in'])).'(BL)';//ff1616
					$value_time_out 	            = date('h:i A',strtotime($row['time_out']));
				break;
			case 'OB':
					$value_time_in               	= date('h:i A',strtotime($row['time_in']));
					$value_time_out 	            = date('h:i A',strtotime($row['time_out']));
				break;
			case '':
				switch($row['time_in']){
					case 'yec':
						$value_time_in               	= 'YEC';
						$value_time_out 	            = 'YEC';
						break;
					case 'VL':
						$value_time_in               	= 'VL';
						$value_time_out 	            = 'VL';
						break;
					case 'SL':
						$value_time_in               	= 'SL';
						$value_time_out 	            = 'SL';
						break;
					case 'ML':
						$value_time_in               	= 'ML';
						$value_time_out 	            = 'ML';
						break;
					case 'holiday':
						$value_time_in               	= 'HOL';
						$value_time_out 	            = 'HOL';
						break;
					case 'FL':
						$value_time_in               	= 'FL';
						$value_time_out 	            = 'FL';
						break;
					case 'do':
						$value_time_in               	= 'DO';
						$value_time_out 	            = 'DO';
						break;
					case 'BRL':
						$value_time_in               	= 'BRL';
						$value_time_out 	            = 'BRL';
						break;
					case 'BL':
						$value_time_in               	= 'BL';
						$value_time_out 	            = 'BL';
						break;
					case 'BDL':
						$value_time_in               	= 'BDL';
						$value_time_out 	            = 'BDL';
						break;
					default:
						$value_time_in               	= 'NO DATA';
						$value_time_out 	            = 'NO DATA';	
						break;
				}
				break 1;
			case 'undefined':
					$value_time_in               	= 'NO DATA';
					$value_time_out 	            = 'NO DATA';				
				break 1;
			default:
				$value_time_in               	= date('h:i A',strtotime($row['time_in']));
				$value_time_out 	            = date('h:i A',strtotime($row['time_out']));					
				break 1;
		}

		if($row['time_out']==''){
			$value_time_out				= '---';
			$value_rendered			= '---';
		}
		else{
			$value_rendered			= number_format(abs($difference/60/60),2,'.','');
		}

		$value_regular_ot             = number_format($row['regular_ot'],2,'.','');
		$value_sunday_ot 	            = number_format($row['sunday_ot'],2,'.','');
		$value_special_ot 	= number_format($row['special_holiday_ot'],2,'.','');
		$value_holiday_ot 	        = number_format($row['holday_ot'],2,'.','');
		$value_undertime 	            = number_format($row['under_mins'],2,'.','');
		$value_night_diff 	        = number_format($row['nightdiff'],2,'.','');
		$value_absent 	            = number_format($row['absent'],2,'.','');
		// $return['leave_code'][$ctr] 	        = $row['leavecode'];
		$value_sl_vl 	                = number_format($row['leavenum'],2,'.','');

		$excel->place_value('A'.$ctr,$value_date,'string','left',true);
		$excel->place_value('B'.$ctr,$value_time_in,'string','left',true);
		$excel->place_value('C'.$ctr,$value_time_out,'string','left',true);
		$excel->place_value('D'.$ctr,$value_rendered,'string','right',true);
		$excel->place_value('E'.$ctr,$value_regular_ot,'number_2_decimal_with_comma','right',true);
		$excel->place_value('F'.$ctr,$value_sunday_ot,'number_2_decimal_with_comma','right',true);
		$excel->place_value('G'.$ctr,$value_special_ot,'number_2_decimal_with_comma','right',true);
		$excel->place_value('H'.$ctr,$value_holiday_ot,'number_2_decimal_with_comma','right',true);
		$excel->place_value('I'.$ctr,$value_undertime,'number_2_decimal_with_comma','right',true);
		$excel->place_value('J'.$ctr,$value_night_diff,'number_2_decimal_with_comma','right',true);
		$excel->place_value('K'.$ctr,$value_absent,'number_2_decimal_with_comma','right',true);
		$excel->place_value('L'.$ctr,$value_sl_vl,'number_2_decimal_with_comma','right',true);

		$ctr++;
	}


	$excel->place_value('D'.$ctr,'Total','string','center',true);
	for ($i='E'; $i < 'M' ; $i++) { 
		if($ctr > $ctr_1st_row){
			// $excel->place_value($i.$ctr,'=SUM('.$i.$ctr_1st_row.':'.$i.$ctr.')','string','center',true);
			$excel->place_value($i.$ctr,'=SUM('.$i.$ctr_1st_row.':'.$i.($ctr-1).')','number_2_decimal_with_comma','right',true);
			// $excel->getActiveSheet()->setCellValue($i.$ctr,'=SUM('.$i.$ctr_1st_row.':'.$i.($ctr-1).')');
		}
	}
	$excel->set_borders('A'.$ctr_1st_row.':L'.$ctr,1,1,1,1);
	$excel->set_font('A'.$ctr.':L'.$ctr,true,'','ffff99','','');

	$filename = 'attendance_record_report';
	write_logs::getInstance()->save_logs('4','Attendance Excel '.$sql_where);
	$excel->output($filename,'2003');
?>