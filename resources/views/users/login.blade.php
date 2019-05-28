<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ config('app.name') }}</title>
<link href="{{ $bootstyle }}" rel="stylesheet">
</head>
<body class="well" style="border-radius:0;border:0">
   
<div class="container" align="center" style="margin-top:5%">
	<div style="max-width:500px" align="left">
		<div align="center"><h3>{{ config('app.name') }}</h3></div>
		<div class="panel panel-default">
			
			<div align="center">
				<img id="myface" style="margin:20px;width:100px;height:100px;border-radius:50%" src="/images/logo.png">
			</div>
			
			
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="" name="myform">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<div class="form-group" inputname="user">
						<label for="user" class="col-md-3 col-sm-3 control-label">{{ trans('users/login.user') }}</label>

						<div class="col-md-7 col-sm-7">
							<input id="user" maxlength="100" data-fields="{{ trans('users/login.user') }}" placeholder="{{ trans('users/login.user_msg') }}" onkeyup="if(event.keyCode==13)getpassobj().focus()" class="form-control" name="user" required autofocus>
						</div>
					</div>
					
					
					
					<div class="form-group" inputname="pass">
						<label for="pass" class="col-md-3  col-sm-3 control-label">{{ trans('users/login.pass') }}</label>

						<div class="col-md-5 col-sm-5">
							<input name="pass" id="pass" type="hidden">
							<input maxlength="30" onblur="get('pass').value=this.value" data-fields="{{ trans('users/login.pass') }}" placeholder="{{ trans('users/login.pass_msg') }}" onkeyup="if(event.keyCode==13)submitadd()" onkeydown="get('pass').value=this.value" type="password" class="form-control" required>
						</div>
						<div class="col-md-3 col-sm-3" style="line-height:34px"><a href="{{ route('usersfind') }}">{{ trans('users/login.wjpass') }}</a></div>
					</div>

				   

					<div class="form-group">
						<div class="col-md-8  col-sm-offset-3 col-md-offset-3">
							<input type="button" name="submitbtn" onclick="submitadd()"  value="{{ trans('base.logintext') }}" class="btn btn-primary" />
							&nbsp;<span id="msgview">
							@if(config('app.openreg'))
							{{ trans('users/login.myzhanh') }}
							<a href="{{ route('usersreg') }}">{{ trans('users/login.regs') }}</a>
							@endif
							</span>
						  
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
		
	@include('layouts.footer')
</div>	

<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
<script src="/js/base64-min.js"></script>
<script src="/res/plugin/jquery-rockvalidate.js"></script>
<script>
function submitadd(){
	js.setoption('userszhang', get('user').value);
	js.setoption('userspass', get('pass').value);
	$.rockvalidate({
		url:'{{ route('apilogincheck') }}',
		submitmsg:'{{ trans('base.logintext') }}',
		submitparams:{'device':device},
		onsubmitsuccess:function(ret){
			js.setoption(TOKENKEY, ret.data.token);
			js.setoption('face', ret.data.face);
			get('myface').src = ret.data.face;
			js.savecookie('bootstyle', ret.data.bootstyle);
			js.setmsg('{{ trans('users/login.loginsucc') }}','green');
			var burk = '{{ route('usersindex') }}',burl=js.request('backurl');
			if(burl){
				if(burl=='reim'){
					burk = '/reim/index.html';
				}else if(burl=='back'){
					js.back();
					return;
				}else{
					burk = jm.base64decode(burl);
				}
			}
			if(burk)js.location(burk);
		}
	});
}

function initbody(){
	var face = js.getoption('face');
	if(face)get('myface').src = face;
	
	get('user').value=js.getoption('userszhang');
	get('pass').value=js.getoption('userspass');
	getpassobj().val(get('pass').value);
}

function getpassobj(){
	return $('input[type=password]');
}
</script>	
	
</body>
</html>
