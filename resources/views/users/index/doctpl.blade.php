<script>
$(document).ready(function(){
	var height = viewheight-$('#buttonlist{rand}').height()-90;

	var atype = 'my';
	var a = $('#view_{rand}').bootstable({
		celleditor:true,url:js.getapiurl('doctpl'),fanye:true,params:{'atype':atype},
		tablename:'c',
		method:'get',
		bodyStyle:'height:'+height+'px;overflow:auto',
		columns:[{
				text:"类型",dataIndex:"fileext",sortable:true,renderer:function(v,d){
					var lxs = js.filelxext(v);
					return '<img src="/images/fileicons/'+lxs+'.png"  height="24">';
				}
			},
			{
				text:"模版名称",dataIndex:"filename",sortable:true,editor:true,align:'left',editormsg:'请规范输入'
			},
			
			{text:"创建人",dataIndex:"optname",sortable:true},
			{text:"共享给",dataIndex:"shatename",sortable:true},
			{text:"排序号",dataIndex:"sort",sortable:true,type:'number',editor:true,editormsg:'越大越靠后'},
			{text:"状态",dataIndex:"status",sortable:true,editor:true,type:'checkbox'},
			{
				text:'',dataIndex:'caozuos',renderer:function(v,d,oi){
					var s = '<a role="button" style="TEXT-DECORATION:none" onclick="fq.yulanfile(\''+d.filenum+'\',\''+d.fileext+'\')">预览</a>';
					if(atype=='my'){
						s+='&nbsp;<a role="button" style="TEXT-DECORATION:none" onclick="fq.editfile(\''+d.filenum+'\',\''+d.fileext+'\')">编辑</a>';
						s+='&nbsp;<a role="button" onclick="caozuo{rand}('+oi+',this)" style="TEXT-DECORATION:none">共享</a>';
					}
					return s;
				}
			}
		],
		itemclick:function(){
			if(atype=='my')get('del_{rand}').disabled=false;
		},
		beforeload:function(){
			get('del_{rand}').disabled=true;
		}
	});
	
	var menuobj=false;
	caozuo{rand}=function(oi,o1){
		var d = a.getData(oi);
		c.showmenuclick();
	}
	
	var c={
		clickwin:function(o,lx){
			$.rockmodelinput({
				'title':'新增模版',
				'okbtn':'新增',
				'columns':[{
					'name' :'filename',labelText:'模版名称',type:'text',required:true,blankText:'请输入模版名称'
				},{
					name:'fileext',labelText:'模版类型',type:'select',store:[['docx','Word文档'],['xlsx','Excel表格'],['pptx','PPT放灯片']],valuefields:0,displayfields:1
				}],
				saveurl:js.getapiurl('doctpl','savetpl'),
				onsuccess:function(){
					a.reload();
				}
			});
			
		},
		showmenuclick:function(d){
			$.rockmodeuser({
				title:'共享给..',
				changetype:'deptusercheck',
				onselect:function(sna,sid){
					c.changeuserok(sna,sid,3);
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
		del:function(){
			a.del({url:js.getapiurl('doctpl','deltpl')});
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			var as = ['my','shateall'];
			a.setCans({celleditor:lx==0});
			atype  = as[lx];
			a.setparams({'atype':atype},true);
		}
	}
	
	js.initbtn(c);
});
</script>
<div id="buttonlist{rand}">
	<table width="100%">
	<tr>
		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增模版</button></td>
		
		
		<td  width="90%" style="padding-left:10px">
		
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_0" click="changlx,0" type="button">我创建的</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">共享给我</button>
		</div>	
		
		</td>
	
		<td align="right" id="tdright_{rand}" nowrap>
			<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
		</td>
	</tr>
	</table>
</div>
<div style="height:10px;overflow:hidden"></div>
<div id="view_{rand}"></div>