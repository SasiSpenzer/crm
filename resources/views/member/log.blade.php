<div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalLabel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick="closeLogModal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h5 class="modal-title" id="logModalLabel">Log Activity</h5>
			</div>
			<div class="modal-body">

				<form role="form" id="log_form">
					<table width="100%" class="table table-striped table-bordered table-hover" id="users-table">
						<thead>
							<tr>
								<!--th><input type="checkbox" id="Call">Call</th>
								<th><input type="checkbox" id="Email">Email</th>
								<th><input type="checkbox" id="Note">Note</th>
								<th><input type="checkbox" id="Meeting">Meeting</th>
								<th><input type="checkbox" id="Invoiced">Invoiced</th>
								<th><input type="checkbox" id="Payment">Payment</th>
								<th><input type="checkbox" id="Reminder">Reminder</th-->
                                <th>
                                    <input class="form-control" disabled readonly name="memship_uid" type="hidden" id="memship_uid"/>
                                    <input type="radio" id="Call" name="log_type" value="Call">
                                    <label for="Call">Call</label>
                                </th>
                                <th>
                                    <input type="radio" id="Email" name="log_type" value="Email">
                                    <label for="Email">Email</label>
                                </th>
                                <th>
                                    <input type="radio" id="Note" name="log_type" value="Note">
                                    <label for="Note">Note</label>
                                </th>
                                <th>
                                    <input type="radio" id="Meeting" name="log_type" value="Meeting">
                                    <label for="Meeting">Meeting</label>
                                </th>
                                <th>
                                    <input type="radio" id="Invoiced" name="log_type" value="Invoiced">
                                    <label for="Invoiced">Invoiced</label>
                                </th>
                                <th>
                                    <input type="radio" id="Payment" name="log_type" value="Payment">
                                    <label for="Payment">Payment</label>
                                </th>
                                <th>
                                    <input type="radio" id="Reminder" name="log_type" value="Reminder">
                                    <label for="Reminder">Reminder</label>
                                </th>
							</tr>
						</thead>
					</table>
					<div id="div-datetimepicker" class="hidden">
						<div class="form-group">
			                <div class='input-group date' id='datetimepicker'>
			                    <input name="reminder" id="txt-reminder" type='text' class="form-control" />
			                    <span class="input-group-addon">
			                        <span class="glyphicon glyphicon-calendar"></span>
			                    </span>
			                </div>
			            </div>
					</div>
					<div class="form-group">
						<textarea class="form-control" rows="2" id="log_comments" placeholder="Enter Comment"></textarea>
					</div>
					<button type="submit" class="btn btn-default btn-primary" id="log_submit" style="float: right;">Save </button>
					<div style="clear: both;"></div>
				</form>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<style>
	#logModal {
		z-index: 1051 !important;
	}
</style>
<script>
	function closeLogModal(){
		$("#logModal").modal('toggle');
        $('body').css('overflow', 'hidden');
		$('#membershipModal').css('overflow-y', 'auto');
	}
</script>