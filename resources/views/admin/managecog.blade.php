@extends('admin.public')

@section('content')

<div class="container" align="center">
	<div align="left" style="max-width:800px">
		<div>
			<h3>{{ trans('admin/public.menu.platcog') }}</h3>
			<div>{{ trans('admin/platcog.platcogdesc') }}{!! $helpstr !!}</div>

			<hr class="head-hr" />
		</div>
		<div>
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">{{ trans('admin/platcog.infotit') }}</a></li>
				<li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">{{ trans('admin/platcog.logotit') }}</a></li>
				<li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">{{ trans('admin/platcog.smstit') }}</a></li>
				
				<li role="presentation"><a href="#tab6" aria-controls="tab6" role="tab" data-toggle="tab">{{ trans('admin/platcog.filetit') }}</a></li>
				<li role="presentation"><a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab">{{ trans('admin/platcog.guantit') }}</a></li>
				
			
			</ul>
			<form name="myform" class="form-horizontal" style="padding:20px">
			<div class="tab-content">
				
				<div role="tabpanel" class="tab-pane active" id="tab1">
					
					<div class="form-group">
						<label for="input_name" class="col-sm-4 control-label"> {{ trans('admin/platcog.name') }}(APP_NAME)</label>
						<div class="col-sm-8">
						  <input class="form-control" placeholder="{{ trans('admin/platcog.name_msg') }}" value="{{ env('APP_NAME') }}" onblur="this.value=strreplace(this.value)" id="input_name" name="APP_NAME">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_nameadmin" class="col-sm-4 control-label"> {{ trans('admin/platcog.nameadmin') }}(APP_NAMEADMIN)</label>
						<div class="col-sm-8">
						  <input class="form-control" placeholder="{{ trans('admin/platcog.nameadmin_msg') }}" onblur="this.value=strreplace(this.value)" value="{{ env('APP_NAMEADMIN') }}"  id="input_nameadmin" name="APP_NAMEADMIN">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_url" class="col-sm-4 control-label"> {{ trans('admin/platcog.url') }}(APP_URL)</label>
						<div class="col-sm-8">
						  <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.url_msg') }}" value="{{ env('APP_URL') }}"  id="input_url" name="APP_URL">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_appurly" class="col-sm-4 control-label"> {{ trans('admin/platcog.appurly') }}(APP_URLY)</label>
						<div class="col-sm-8">
						  <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.appurly_msg') }}" value="{{ env('APP_URLY') }}"  id="input_appurly" name="APP_URLY">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_urllocal" class="col-sm-4 control-label"> {{ trans('admin/platcog.urllocal') }}(APP_URLLOCAL)</label>
						<div class="col-sm-8">
						  <input class="form-control" placeholder="{{ trans('admin/platcog.urllocal_msg') }}" onblur="this.value=strreplace(this.value)" value="{{ env('APP_URLLOCAL') }}"  id="input_urllocal" name="APP_URLLOCAL">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_debug" class="col-sm-4 control-label"> {{ trans('admin/platcog.debug') }}(APP_DEBUG)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_debug" name="APP_DEBUG">
						  <option value="">{{ trans('admin/platcog.debug_false') }}</option>
						  <option @if(config('app.debug'))selected @endif value="true">{{ trans('admin/platcog.debug_true') }}</option>
						  </select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_openreg" class="col-sm-4 control-label"> {{ trans('admin/platcog.openreg') }}(APP_OPENREG)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_openreg" name="APP_OPENREG">
						  <option value="false">{{ trans('admin/platcog.openreg_false') }}</option>
						  <option @if(config('app.openreg'))selected @endif value="">{{ trans('admin/platcog.openreg_true') }}</option>
						  </select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_randkey" class="col-sm-4 control-label"> {{ trans('admin/platcog.randkey') }}(ROCK_RANDKEY)</label>
						<div class="col-sm-8">
						  
						  
							<div class="input-group">
							  <input class="form-control" placeholder="{{ trans('admin/platcog.randkey_msg') }}" onblur="this.value=strreplace(this.value)" value="{{ $randkey }}"  id="input_randkey" name="ROCK_RANDKEY">
							  <span class="input-group-btn">
								<button class="btn btn-default" onclick="reaterandkey()" type="button">{{ trans('base.createtext') }}</button>
							  </span>
							</div>
						  
						</div>
					</div>
					
				</div>
				<div role="tabpanel" class="tab-pane" id="tab2">
					
					<div class="col-sm-offset-4">
						<input name="APP_LOGO" type="hidden" value="{{ env('APP_LOGO') }}">
						<div><img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ config('app.logo') }}" id="faceimg" width="100"></div>
						<div style="margin-top:5px"><input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}..."></div>
						
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="tab3">
					<div class="form-group">
						<label for="input_smsprovider" class="col-sm-4 control-label"> {{ trans('admin/platcog.smsprovider') }}(ROCK_SMSPROVIDER)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_smsprovider" name="ROCK_SMSPROVIDER">
						  <option value="">{{ trans('admin/platcog.smsprovider_xinhu') }}(smsxinhu)</option>
						  <option value="smsali" @if(config('rocksms.provider')=='smsali')selected @endif>{{ trans('admin/platcog.smsprovider_ali') }}(smsali)</option>
						  </select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_yzmlogin" class="col-sm-4 control-label"> {{ trans('admin/platcog.yzmlogin') }}(ROCK_YZMLOGIN)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_yzmlogin" name="ROCK_YZMLOGIN">
						  <option value="">{{ trans('admin/platcog.yzmlogin_false') }}</option>
						  <option @if(config('rocksms.yzmlogin'))selected @endif value="true">{{ trans('admin/platcog.yzmlogin_true') }}</option>
						  </select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_origin" class="col-sm-4 control-label"> {{ trans('admin/platcog.origin') }}(ALLOW_ORIGIN)</label>
						<div class="col-sm-8">
						   <textarea rows="3" class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.origin_msg') }}" id="input_origin" name="ALLOW_ORIGIN">{{ env('ALLOW_ORIGIN') }}</textarea>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_whiteip" class="col-sm-4 control-label"> {{ trans('admin/platcog.whiteip') }}(ACCESS_WHITEIP)</label>
						<div class="col-sm-8">
						   <textarea rows="3" class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.whiteip_msg') }}" id="input_whiteip" name="ACCESS_WHITEIP">{{ env('ACCESS_WHITEIP') }}</textarea>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_blackip" class="col-sm-4 control-label"> {{ trans('admin/platcog.blackip') }}(ACCESS_BLACKIP)</label>
						<div class="col-sm-8">
						   <textarea rows="3" class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.blackip_msg') }}" id="input_blackip" name="ACCESS_BLACKIP">{{ env('ACCESS_BLACKIP') }}</textarea>
						</div>
					</div>
					
				</div>
				
				<div role="tabpanel" class="tab-pane" id="tab4">
					
				</div>
				
				<div role="tabpanel" class="tab-pane" id="tab6">
					<div class="form-group">
						<label for="input_officeview" class="col-sm-4 control-label"> {{ trans('admin/platcog.officeview') }}(ROCK_OFFICEVIEW)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_officeview" name="ROCK_OFFICEVIEW">
						  <option value="">{{ trans('admin/platcog.officeview_microsoft') }}(microsoft)</option>
						  <option value="rockdoc" @if(env('ROCK_OFFICEVIEW')=='rockdoc')selected @endif>{{ trans('admin/platcog.officeview_rockdoc') }}(rockdoc)</option>
						  <option value="rockoffice" @if(env('ROCK_OFFICEVIEW')=='rockoffice')selected @endif>{{ trans('admin/platcog.officeview_rockoffice') }}(rockoffice)</option>
						  <option value="onlyoffice" @if(env('ROCK_OFFICEVIEW')=='onlyoffice')selected @endif>{{ trans('admin/platcog.officeview_onlyoffice') }}(onlyoffice)</option>
						  <option value="mingdao" @if(env('ROCK_OFFICEVIEW')=='mingdao')selected @endif>{{ trans('admin/platcog.officeview_mingdao') }}(mingdao)</option>
						  
						  </select>
						  <div>{{ trans('admin/platcog.officeview_help') }}{!! c('help')->show('rockview') !!}</div>
						</div>
					</div>
					<div class="form-group">
						<label for="input_officeedit" class="col-sm-4 control-label"> {{ trans('admin/platcog.officeedit') }}(ROCK_OFFICEDIT)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_officeview" name="ROCK_OFFICEDIT">
						  <option value="">{{ trans('admin/platcog.officeview_onlyoffice') }}(onlyoffice)</option>
						   <option value="rockoffice" @if(env('ROCK_OFFICEDIT')=='rockoffice')selected @endif>{{ trans('admin/platcog.officeview_rockoffice') }}(rockoffice)</option>
						   <option value="rockdoc" @if(env('ROCK_OFFICEDIT')=='rockdoc')selected @endif>{{ trans('admin/platcog.officeview_rockdoc') }}(rockdoc)</option>  
						  </select>
						</div>
					</div>
					<div class="form-group">
						<label for="input_onlyoffice" class="col-sm-4 control-label"> {{ trans('admin/platcog.onlyoffice') }}(ROCK_ONLYOFFICE)</label>
						<div class="col-sm-8">
						   <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.onlyoffice_msg') }}" value="{{ env('ROCK_ONLYOFFICE') }}"  id="input_onlyoffice" name="ROCK_ONLYOFFICE">
						   <div>{{ trans('admin/platcog.onlyoffice_help') }}{!! c('help')->show('onlyoffice') !!}</div>
						</div>
					</div>
				</div>
				
				<div role="tabpanel" class="tab-pane" id="tab5">
					
					<div class="form-group">
						<label for="input_urly" class="col-sm-4 control-label"> {{ trans('admin/platcog.urly') }}(ROCK_URLY)</label>
						<div class="col-sm-8">
						   <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.urly_msg') }}" value="{{ env('ROCK_URLY') }}"  id="input_urly" name="ROCK_URLY">
						</div>
					</div>
					<div class="form-group">
						<label for="input_xinhukey" class="col-sm-4 control-label"> {{ trans('admin/platcog.xinhukey') }}(ROCK_XINHUKEY)</label>
						<div class="col-sm-8">
						   <input class="form-control" placeholder="{{ trans('admin/platcog.xinhukey_msg') }}" onblur="this.value=strreplace(this.value)" value="{{ $xinhukey }}"  id="input_xinhukey" name="ROCK_XINHUKEY">
						   <div>{{ trans('admin/platcog.xinhukey_help') }} {!! c('help')->show('xhkey') !!}</div>
						</div>
					</div>
				</div>
				
				<div style="margin-top:20px" class="form-group">
					<div class="col-sm-offset-4">
					 <button class="btn btn-success" name="submitbtn" onclick="submitadd()" type="button">{{ trans('base.savetext') }}</button>
					</div>
				</div>	
			</div>
			</form>
		</div >
	</div>
</div>
@endsection

@section('script')
<script src="/base/upfilejs?cfrom=admin"></script>
<script>
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('adminmanagesave','cog') }}',
		submitmsg:'{{ trans('base.savetext') }}',
		autoback:false,
		onsubmitsuccess:function(){
			//form('submitbtn').disabled=false;
			
		}
	});
}
function xuantuan(){
	if(typeof(upbtn)=='undefined')upbtn = $.rockupfile({
		'uptype':'image',
		'updir' :'logo',
		onsuccess:function(ret){
			get('faceimg').src = ret.viewpats;
			form('APP_LOGO').value = ret.viewpats;
		}
	});
	upbtn.changefile();
}
function reaterandkey(){
	$.get('/base/randkey', function(ret){
		form('ROCK_RANDKEY').value=ret;
	});
}
</script>
@endsection