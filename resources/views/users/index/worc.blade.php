<script>
$(document).ready(function(){
	var height = viewheight-$('#buttonlist{rand}').height()-90;

	var a = $('#viewworc_{rand}').bootstable({
		celleditor:true,url:js.getapiurl('docfile','getworcdata'),fanye:true,
		tablename:'a',
		method:'get',
		bodyStyle:'height:'+height+'px;overflow:auto',
		columns:[
			{
				text:"名称",dataIndex:"name",sortable:true,editor:true,align:'left',editormsg:'请规范输入'
			},
			{text:"限制上传类型",dataIndex:"uptype",editor:true,editorattr:'placeholder="为空不限制"'},	
			{text:"可查看人员",dataIndex:"recename"},{text:"管理人员",dataIndex:"guanname"},
			{text:"可上传人员",dataIndex:"upuser"},
			{text:"空间大小(字节)",dataIndex:"size",sortable:true,editor:useatype==1,editorattr:'placeholder="0不限制"',type:'number'},
			{
				text:"空间大小",dataIndex:"sizee",renderer:function(v,d){
					var str='无限制';
					if(d.size)str=js.formatsize(d.size);
					return str;
				}
			},
			{text:"排序号",dataIndex:"sort",sortable:true,type:'number',editor:true,editormsg:'越大越靠后'},
			{text:"已使用大小",dataIndex:"sizeu",sortable:true,renderer:function(v,d){
				var str='&nbsp;';
				if(v>0)str=js.formatsize(v);
				if(d.size>0){
					var dx=Math.ceil(100*v/d.size);
					if(dx>100)dx=100;
					str+='<div class="progress" style="margin:0;width:120px;"><div class="progress-bar progress-bar-success" style="width:'+dx+'%;color:#000000;">'+dx+'%</div></div>';
				}
				return str;
			}},
			{text:"创建人",dataIndex:"optname",sortable:true},
			{
				text:'',dataIndex:'caozuos',renderer:function(v,d,oi){
					return '<a role="button" onclick="caozuo{rand}('+oi+',this)" style="TEXT-DECORATION:none">操作<i class="icon-angle-down"></i></a>';
				}
			}
		],
		load:function(d){
			fq.show(d);
		}
	});
	
	var menuobj=false;
	caozuo{rand}=function(oi,o1){
		var d = a.getData(oi);
		if(!menuobj)menuobj=$.rockmenu({
			data:[],
			itemsclick:function(d){c.showmenuclick(d);},
			width:150
		});
		var da = [{name:'修改可查看人员',lx:0},{name:'修改管理人员',lx:1},{name:'修改可上传人员',lx:2}];
		var off=$(o1).offset();
		menuobj.setData(da);
		setTimeout(function(){menuobj.showAt(off.left,off.top+20)},10);
	}
	
	var c={
		clickwin:function(o,lx){
			js.prompt('新增分区','请输入分区名称', function(jg,txt){
				if(jg=='yes' && txt)c.createok(txt);
			});
		},
		showmenuclick:function(d){
			$.rockmodeuser({
				title:d.name,
				changetype:'deptusercheck',
				onselect:function(sna,sid){
					c.changeuserok(sna,sid,d.lx);
				}
			});
		},
		changeuserok:function(sna,sid,lx){
			var d=a.changedata;
			js.loading('修改中...');
			js.ajax(js.getapiurl('docfile','editworc'),{sna:sna,sid:sid,id:d.id,lx:lx},function(ret){
				js.msgok('修改成功');
				a.reload();
			},'post');
		},
		createok:function(mc){
			js.loading('分区['+mc+']创建中...');
			js.ajax(js.getapiurl('docfile','createworc'),{name:mc},function(ret){
				js.msgok('创建成功');
				a.reload();
			},'post');
		},
		del:function(){
			a.del({url:js.getapiurl('docfile','delworc')});
		},
		tongji:function(){
			js.loading('重新统计创建中...');
			js.ajax(js.getapiurl('docfile','worctongji'),{},function(ret){
				js.msgok('统计完成');
				a.reload();
			},'get');
		}
	}
	
	js.initbtn(c);
});
</script>
<div id="buttonlist{rand}">
	<table width="100%">
	<tr>
		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增分区</button></td>
		
		<!--
		<td>
			<input class="form-control" style="width:200px" id="key_{rand}" placeholder="名称/关键词">
		</td>
		<td style="padding-left:10px">
			<button class="btn btn-default" click="searchbtn" type="button">搜索</button>
		</td>
		-->
		<td  width="90%" style="padding-left:10px"><div id="changatype{rand}" class="btn-group"></div></td>
	
		<td align="right" id="tdright_{rand}" nowrap>
			<button class="btn btn-default" click="tongji" type="button">重新统计已使用大小</button>&nbsp;
			<button class="btn btn-danger" click="del" type="button"><i class="icon-trash"></i> 删除</button>
		</td>
	</tr>
	</table>
</div>
<div style="height:10px;overflow:hidden"></div>
<div id="viewworc_{rand}"></div>