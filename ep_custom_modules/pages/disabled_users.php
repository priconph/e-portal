<html>
<head>
	<?php
  		require_once('../dist/includes.php');
	?>
	<!-- <link rel="stylesheet" type="text/css" href="../dist/sticky_table/sticky-table.min.css"> -->
	<!-- </script><script type="text/javascript" src="../dist/sticky_table/sticky-table.min.js"></script> -->
	<style type="text/css">
		thead tr, tfoot tr{
			font-size:70%;
			text-align: center;
			vertical-align: middle;
		}
		#t_loan_information tbody, #t_pay_slip_history tbody, #t_loan_payments tbody{
			font-size: 80%;
			cursor: pointer;
		}
		.wrapper-sticky{
			max-height: 165px !important;
			overflow-x: hidden;
		}
	</style>
</head>
<body>
	<div class="col-sm-12" style="padding:0;margin-top:0">
		<div class="card">
		  <div class="card-header">
		  	<i class="fas fa-user-slash text-danger"></i> Disabled Users
		  </div>
			<div id="cont_list_of_users" class="card-body" style="margin-left:3px;width:99%;overflow-x:scroll;">
				<table id="t_list_of_users" class="table table-striped compact table-hover table-bordered nowrap" style="width:100%;">
			        <thead style="width:100% !important;">
			            <tr style="text-align:center;">
			                <th>E. N.</th>
			                <th>Name</th>
			                <th>Datetime disabled</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tbody style="font-size:80%;cursor:pointer;width:100%">
			        </tbody>
			    </table>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="../dist/js/disabled_users.js?1"></script>
	<script type="text/javascript">
		$('#cont_loading').fadeIn();
	</script>
</body>
</html>