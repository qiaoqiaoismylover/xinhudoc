<script >
$(document).ready(function(){
	
	var atype  = 'mytrash';
	var height = viewheight-$('#buttonlist{rand}').height()-90;
	var a = $('#view_{rand}').bootstable({
		tablename:'a',url:js.getapiurl('fileda'),fanye:true,params:{'atype':atype},
		method:'get',checked:true,
		bodyStyle:'height:'+height+'px;overflow:auto',
		columns:[{
			text:'类型',dataIndex:'fileext',renderer:function(v, d){
				if(!isempt(d.thumbpath))return '<img src="'+d.thumbpath+'" width="24" height="24">';
				var lxs = js.filelxext(v);
				return '<img src="/images/fileicons/'+lxs+'.gif"  height="20">';
			}
		},{
			text:'名称',dataIndex:'filename',editor:true,align:'left'
		},{
			text:'大小',dataIndex:'filesizecn',sortable:true
		},{
			text:'创建者',dataIndex:'optname',sortable:true
		},{
			text:'最后修改',dataIndex:'optdt',sortable:true
		},{
			text:'删除时间',dataIndex:'deldt',sortable:true
		},{
			text:'',dataIndex:'opt',renderer:function(v,d,oi){
				if(d.ishui=='1')return '已删';
				var s = '<a role="button" style="TEXT-DECORATION:none" onclick="fq.yulanfile(\''+d.filenum+'\',\''+d.fileext+'\')">预览</a>';
				s+='&nbsp;<a role="button" style="TEXT-DECORATION:none" onclick="fq.downfile(\''+d.filenum+'\',\''+d.fileext+'\')"><i class="icon-arrow-down"></i></a>';
				return s;
			}
		}]
	});
	
	var c = {
		reload:function(){
			a.reload();
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			var as = ['mytrash','alltrash'];
			atype  = as[lx];
			a.setparams({'atype':atype},true);
		},
		delok:function(){
			a.del({checked:true,url:js.getapiurl('fileda','delfile')});
		}
	};
	js.initbtn(c);
	
	if(useatype==0)$('#state{rand}_1').remove();
});
</script>






<div id="buttonlist{rand}">
	<table width="100%">
	<tr>
	
	
	<td  width="90%" style="padding-right:10px">
		
		
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_0" click="changlx,0" type="button">我的删除</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">管理他人删除</button></button>
		</div>
		
	</td>
	<td nowrap>
		自动保存回收站<b>{{ config('rock.recycle') }}</b>天
	</td>
	
	<td style="padding-left:10px">
		<button class="btn btn-danger" click="delok" type="button"><i class="icon-trash"></i> 彻底删除</button> 
	</td>
	</tr>
	</table>
	
</div>
<div style="height:10px;overhidden"></div>
<div id="view_{rand}"></div>