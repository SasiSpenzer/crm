@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-lg-12">
		<h2 class="label-info"></h2>
	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Ads / Members To Be Removed
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<!-- Nav tabs -->
				<ul class="nav nav-pills" style="margin-bottom: 5px;">
					<li class="active">
						<a href="#active-ads" data-toggle="tab">Active ads in expired</a>
					</li>
					<li>
						<a id="btn-without-payment" href="#without-payment" data-toggle="tab">Ads without payment</a>
					</li>
					<li>
						<a id="btn-limit-exceed" href="#limit-exceed" data-toggle="tab">Limit exceed ads</a>
					</li>
					<li>
						<a id="btn-null-ads" href="#null-ads" data-toggle="tab">Null data ads</a>
					</li>
					<li>
						<a id="btn-app-ads" href="#app-ads" data-toggle="tab">Mobile ads</a>
					</li>
					<li>
						<a id="btn-exp-upgrade-ads" href="#exp-upgrade-ads" data-toggle="tab">Expired upgrade ad</a>
					</li>
				</ul>
				<!-- Nav tabs /-->
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane fade in active" id="active-ads">
						<div class="alert" id="flash_message" style="display:none"></div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl_active_ads">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Member Expire</th>
									<th>Payment Expire</th>
									<th>Category</th>
									<th>AM</th>
									<th>Ads</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="tab-pane fade" id="without-payment">
						<div class="alert" id="flash_message" style="display:none"></div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl_without_payment">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Member Expire</th>
									<th>Payment Expire</th>
									<th>Category</th>
									<th>AM</th>
									<th>Ads</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="tab-pane fade" id="limit-exceed">
						<div class="alert" id="flash_message" style="display:none"></div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl_limit_exceed">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Member Expire</th>
									<th>Payment Expire</th>
									<th>Category</th>
									<th>AM</th>
									<th>Ads</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="tab-pane fade" id="null-ads">
						<div class="alert" id="flash_message" style="display:none"></div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl_null_ads">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Member Expire</th>
									<th>Payment Expire</th>
									<th>Category</th>
									<th>AM</th>
									<th>Ads</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="tab-pane fade" id="app-ads">
						<div class="alert" id="flash_message" style="display:none"></div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl_app_ads">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Member Expire</th>
									<th>Payment Expire</th>
									<th>Category</th>
									<th>AM</th>
									<th>Ads</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="tab-pane fade" id="exp-upgrade-ads">
						<div class="alert" id="flash_message" style="display:none"></div>
						<table width="100%" class="table table-striped table-bordered table-hover" id="tbl_exp_upgrade_ads">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Member Expire</th>
									<th>Payment Expire</th>
									<th>Category</th>
									<th>AM</th>
									<th>Ads</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>

				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<script type="text/javascript" src="{{ URL::asset('/js/app/ads/to_be_removed.js') }}"></script>

@endsection