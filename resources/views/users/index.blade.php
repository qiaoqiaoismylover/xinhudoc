<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ $companyinfo->name }}</title>
<link rel="shortcut icon" href="{{ $companyinfo->logo }}" />
<link href="{{ $style['path'] }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/res/fontawesome/css/font-awesome.min.css">
<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
<script src="/js/base64-min.js"></script>
</head>
<body style="overflow:hidden;padding:0">


<table style="width:100%;background:none" cellspacing="0" cellpadding="0">
<tr valign="top">
<td>
	<div class="well" id="indexmenu" style="border-radius:0;border:0;;margin:0;padding:0px;width:200px">
	<div style="padding:10px">
		<div align="center" style="font-size:20px;margin-top:5px">
			<img src="{{ $companyinfo->logo }}" style="display:inline;" align="absmiddle" height="40" width="40"> {{ $companyinfo->shortname }}
		</div>
		<div style="padding-top:15px" align="center">
			<input type="text" id="menukeyword" class="form-control" placeholder="搜文件名/文件夹">
		</div>
		
		<div style="padding:5px 0px">
		<ul class="nav nav-pills nav-stacked" id="showfqdiv">
			<li role="button" onclick="fq.showall()"><a style="TEXT-DECORATION:none"><i class="icon-folder-close-alt"></i> 文件分区 <span data-toggle="tooltip" title="分区管理" id="fqguanbtn" style="float:right"><i class="icon-cog"></i></span></a></li>

		</ul>
		</div>
	
		<div style="overflow:hidden;height:1px;background:rgba(0,0,0,0.1)">&nbsp;</div>
		
		<div style="padding:5px 0px">
		<ul class="nav nav-pills nav-stacked">
			<li role="button" onclick="fq.openshate()"><a style="TEXT-DECORATION:none"><i class="icon-share"></i> 共享的文件</a></li>
		  
			<li role="button" onclick="fq.docxie(0)"><a style="TEXT-DECORATION:none"><i class="icon-building"></i> 文档模版</a></li>
			<li role="button" onclick="fq.docxie(1)"><a style="TEXT-DECORATION:none"><i class="icon-file-alt"></i> 文档协作</a></li>
		</ul>
		</div>
		
		<!--
		<div style="overflow:hidden;height:1px;background:rgba(0,0,0,0.1)">&nbsp;</div>
		<div style="padding:5px 0px">
		<ul class="nav nav-pills nav-stacked">
			<li role="button"><a style="TEXT-DECORATION:none"><i class="icon-check"></i> 汇报</a></li>
			
			<li role="button" onclick="fq.docxie(2)"><a style="TEXT-DECORATION:none;padding-left:24px">汇报管理</a></li>
			<li role="button" onclick="fq.docxie(2)"><a style="TEXT-DECORATION:none;padding-left:24px">汇报给我</a></li>

		</ul>
		</div>
		-->
		
	</div>
	</div>
</td>

<td>
	<div id="indexsplit" style="overflow:hidden;width:3px;background:rgba(0,0,0,0.1)"></div>
</td>

<td width="100%">

	<ul class="nav nav-tabs" id="tabs_title">
		<li style="float:right">
			<a style="TEXT-DECORATION:none;border:none" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				<img style="width:18px;height:18px;border-radius:50%" src="{{ Auth::user()->face }}" align="absmiddle">
				{{ $useainfo->name }}<span class="caret"></span>
			</a>
			<ul class="dropdown-menu" style="min-width:auto" role="menu">
				@if($useatype>0)
				<li><a target="_blank" href="{{ route('manage', $companyinfo->num) }}">{{ trans('manage/public.menu.unitgl') }}</a></li>
				@endif
				<li><a target="_blank" href="{{ route('userscog') }}">{{ trans('users/cog.title') }}</a></li>
				<li><a target="_blank" href="{{ route('usersmanage') }}">{{ trans('manage/public.menu.grhome') }}</a></li>
			
				<li><a href="javascript:;" onclick="js.reload()">{{ trans('base.reloadtext') }}</a></li>	
				<li><a href="javascript:;" onclick="exitlogin()">{{ trans('base.exittext') }}</a></li>
			</ul>	
		</li>
	</ul>

	<div id="indexcontent" style="overflow:auto;position:relative;">
		<div id="content_allmainview" style="margin:10px;"></div>
	</div>

</td>
</tr>
</table>


<script>
var cnum = '{{ $companyinfo->num }}',
	adminid={{ $useainfo->id }},
	useatype={{ $useatype }},
	show_key='{{ $show_key }}';
function exitlogin(){
	var url = '{{ route('usersloginout') }}';
	
	js.confirm('{{ trans('users/index.exitmsg') }}',function(lx){
		if(lx=='yes'){
			js.loading('{{ trans('base.exittext') }}...');
			js.location(url);
		}
	});
}
</script>	

<script src="/res/bootstrap3.3/js/bootstrap.min.js"></script>
<script src="/res/plugin/jquery-rockmodel.js"></script>
<script src="/res/agent/index.js"></script>
<script src="/res/plugin/jquery-changeuser.js"></script>
<script src="/res/plugin/jquery-imgview.js"></script>
<script src="/res/bootstrapplugin/jquery-bootstable.js"></script>
<script src="/base/upfilejs"></script>
<link href="/res/plugin/jquery-rockmenu.css" rel="stylesheet">
<script src="/res/plugin/jquery-rockmenu.js"></script>
<script src="/res/perfectscrollbar/jquery.mousewheel.js"></script>


</body>
</html>