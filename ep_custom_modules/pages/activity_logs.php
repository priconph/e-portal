<html>
<head>
	<?php
  		require_once('../dist/includes.php');
	?>
	<style type="text/css">
		thead tr, tfoot tr{
			font-size:70%;
			text-align: center;
			vertical-align: middle;
		}
	</style>
</head>
<body>
	<div class="col-sm-12" style="padding:0;margin-top:0">
		<div class="card">
			<div class="card-header">
			    <div class="btn-group float-left">
					<button type="button" class="btn btn-outline-light text-dark btn-sm" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
						Active user/s for the last 5 minutes:
						<span class="badge badge-success" id="active_users">0</span>
					</button>
					<div class="dropdown-menu dropdown-menu-right" style="width:50px !important;">
						<div class="row">
							<div class="col-auto small" style="padding-left:20px;" id="cont_active_users">
							</div>
						</div>
					</div>
					<span id="cont_loading" style="display: none;">
						&nbsp;<i class="fa fa-spinner fa-spin" style="color:green;"></i>
					</span>
				</div>
			</div>
			<div id="cont_activity_logs" class="card-body" style="width:100%;overflow-x:scroll;">
				<table id="t_activity_logs" class="table table-striped table-hover table-bordered nowrap" style="width:100% !important;">
			        <thead>
			            <tr style="text-align:center;">
			                <th>Date</th>
			                <th>Type</th>
			                <th>Activity</th>
			                <th>Page</th>
			                <th>Query</th>
			                <th>E. N.</th>
			                <th>IP Address</th>
			            </tr>
			        </thead>
			        <tbody style="font-size:80%;">
			        </tbody>
			    </table>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="../dist/js/activity_logs.js?2"></script>
	<script type="text/javascript">
		$('#cont_loading').fadeIn();
        load_activity_logs('initial');
	</script>
</body>
</html>