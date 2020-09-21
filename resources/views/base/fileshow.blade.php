<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>{{ $filename }}</title>
<link rel="shortcut icon" href="{{ $companyinfo->logo }}" />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/js.js"></script>
<script>
var id = '{{ $frs->id }}',otype=js.request('otype'),callb=js.request('callb');
function initbody(){
	var url = '/api/we/docfile_sendedit/{{ $companyinfo->num }}/?id='+id+'&otype='+otype+'&ckey={{ $ckey }}&callb='+callb+'';
	$.ajax({
		type:'get',
		url:url,
		success:function(s){
			var ret = js.decode(s);
			if(ret.success){
				var da = ret.data;
				$('#msgview').html('跳转中...');
				js.location(da.url);
			}else{
				$('#msgview').html('<font color=red>'+ret.msg+'</font>');
			}	
		},
		error:function(e){
			$('#msgview').html(e.responseText);
		}
	});
}
</script>
</head>
<body style="padding:0px;margin:0px;">
<div style="margin-top:20%" align="center">
<img src="/images/mloading.gif" align="absmiddle">&nbsp;<span id="msgview">处理中...</span>
</div>

</body>
</html>
</html>