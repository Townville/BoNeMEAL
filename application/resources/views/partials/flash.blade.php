@if (session('message'))
<div class="flashmsg">
	<div class="alert alert-box alert-info">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>{{ trans('app.notice') }}</strong><br>
		{{ session('message') }}

			@if (session('messageDetails'))
			<br /><br />
			<p>
				{{ trans('app.details') }}:<br />
				{{ session('messageDetails') }}
			</p>
		@endif
	</div>
</div>
@endif