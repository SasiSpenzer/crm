<div class="modal fade" id="salesModal" tabindex="-1" role="dialog" aria-labelledby="salesModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h5 class="modal-title" id="salesModalLabel">Add-on Sales</h5>
			</div>
			<div class="modal-body">
				<form role="form">
					<table width="100%" class="table table-striped table-bordered table-hover" id="users-table">
						<thead>
							<tr>
								<th>Type
								<select class="form-control" id="sales_type">
									@if(count(Config::get('sales.type')))>0)
									@foreach(Config::get('sales.type') as $type)
									<option value="{{$type}}">{{$type}}</option>
									@endforeach
									@else
									<option value="1">--</option>
									@endif
								</select>
							</th>
							<th>Qty<input class="form-control"  id ="sales_qty"></th>
							<th>Value<input class="form-control"  id ="sales_value"></th>
						</tr>
					</thead>
				</table>
				<div class="form-group">
					<textarea class="form-control" rows="2" id="sales_comments" placeholder="Enter Comment"></textarea>
				</div>
				<button type="submit" class="btn btn-default btn-primary" id="sales_submit" style="float: right;">Save </button>
				<div style="clear: both;"></div>
			</form>
		</div>
		<div class="modal-footer">
		</div>
	</div>
</div>
</div>