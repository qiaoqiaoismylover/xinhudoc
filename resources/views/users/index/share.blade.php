<script >
$(document).ready(function(){
	
	var height = viewheight-$('#buttonlist{rand}').height()-90;
	var a = $('#view_{rand}').bootstable({
		tablename:'a',url:js.getapiurl('docfile'),fanye:true,params:{'atype':'shateall'},
		method:'get',
		tablename:'b',
		bodyStyle:'height:'+height+'px;overflow:auto',
		columns:[{
			text:'类型',dataIndex:'fileext',renderer:function(v, d){
				if(!isempt(d.thumbpath))return '<img src="'+d.thumbpath+'" width="24" height="24">';
				if(d.type=='0'){
					var lxs = js.filelxext(v);
					return '<img src="/images/fileicons/'+lxs+'.gif"  height="20">';
				}else{
					return '<img src="/images/folder.png" height="24">';
				}
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
			text:'共享给',dataIndex:'shatename'
		},{
			text:'共享人',dataIndex:'shateren',sortable:true
		},{
			text:'',dataIndex:'opt',renderer:function(v,d,oi){
				if(d.ishui=='1')return '已删';
				var s = '<a role="button" style="TEXT-DECORATION:none" onclick="fq.yulanfile(\''+d.filenum+'\',\''+d.fileext+'\')">预览</a>';
				s+='&nbsp;<a role="button" style="TEXT-DECORATION:none" onclick="fq.downfile(\''+d.filenum+'\',\''+d.fileext+'\')"><i class="icon-arrow-down"></i></a>';
				return s;
			}
		}]
	});
	showvies{rand}=function(oi,lx){
		var d=a.getData(oi);
		if(lx==1){
			js.downshow(d.fileid)
		}else{
			js.yulanfile(d.fileid,d.fileext,d.filepath,d.filename);
		}
	}
	var c = {
		reload:function(){
			a.reload();
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			var as = ['shateall','shatewfx'];
			a.setparams({'atype':as[lx]},true);
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:jm.base64encode(s)},true);
		}
	};
	js.initbtn(c);

});
</script>






<div id="buttonlist{rand}">
	<table width="100%">
	<tr>
	
	<td  width="90%" style="padding-right:10px">
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_0" click="changlx,0" type="button">共享给我的</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">我共享的</button>
		</div>	
	</td>
	
	
	<td align="right" nowrap>
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="文件名/创建者">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	</tr>
	</table>
	
</div>
<div style="height:10px;overhidden"></div>
<div id="view_{rand}"></div>