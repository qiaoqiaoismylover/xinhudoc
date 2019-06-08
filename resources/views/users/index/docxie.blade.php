<script>
$(document).ready(function(){
	var height = viewheight-$('#buttonlist{rand}').height()-90;

	var atype = 'my',mtplarr=[];
	var a = $('#view_{rand}').bootstable({
		celleditor:false,url:js.getapiurl('docxie'),fanye:true,params:{'atype':atype},
		tablename:'d',
		method:'get',
		bodyStyle:'height:'+height+'px;overflow:auto',
		columns:[{
				text:"类型",dataIndex:"fileext",sortable:true,renderer:function(v,d){
					var lxs = js.filelxext(v);
					return '<img src="/images/fileicons/'+lxs+'.png"  height="24">';
				}
			},
			{
				text:"名称",dataIndex:"filename",sortable:true,editor:true,align:'left',editormsg:'请规范输入',renderer:function(v,d){
					var ss=' <span class="label label-success label-sm">协作</span>';
					if(!d.xiebool)ss=' <span class="label label-default">只读</span>';
					return ''+v+''+ss+'';
				}
			},
			{text:"分类",dataIndex:"fenlei",sortable:true,editor:true},
			{text:"协作人",dataIndex:"xiename"},
			{text:"可查看人员",dataIndex:"recename"},
			{text:"创建人",dataIndex:"optname",sortable:true},
			{text:"修改时间",dataIndex:"optdt",sortable:true},
			{text:"最后修改",dataIndex:"editname",sortable:true},
			{text:"说明",dataIndex:"explian",editor:true,type:'textarea'},
			{text:"状态",dataIndex:"status",sortable:true,editor:true,type:'checkbox'},
			{
				text:'',dataIndex:'caozuos',renderer:function(v,d,oi){
					var s = '<a role="button" style="TEXT-DECORATION:none" onclick="fq.yulanfile(\''+d.filenum+'\',\''+d.fileext+'\')">预览</a>';
					if(d.xiebool){
						s+='&nbsp;<a role="button" style="TEXT-DECORATION:none" onclick="fq.editfile(\''+d.filenum+'\',\''+d.fileext+'\',\'calleditdocxie\')">编辑</a>';
					}
					s+='&nbsp;<a role="button" style="TEXT-DECORATION:none" onclick="fq.downfile(\''+d.filenum+'\',\''+d.fileext+'\')"><i class="icon-arrow-down"></i></a>';
					return s;
				}
			}
		],
		itemclick:function(){
			if(atype=='mycj'){
				get('del_{rand}').disabled=false;
				get('xuanbtn_{rand}').disabled=false;
			}
		},
		beforeload:function(){
			get('del_{rand}').disabled=true;
			get('xuanbtn_{rand}').disabled=true;
		},
		load:function(d1){
			mtplarr = d1.mtplarr;
		}
	});
	
	var menuobj=false;
	caozuo{rand}=function(oi,o1){
		var d = a.getData(oi);
		c.showmenuclick();
	}
	
	var c={
		clickwin:function(o,lx){
			var store = ['<optgroup label="新建office文件">',['docx','Word文档'],['xlsx','Excel表格'],['pptx','PPT放灯片'],'</optgroup>'];
			if(mtplarr.length>0){
				store.push('<optgroup label="模版中选择">')
				for(var i=0;i<mtplarr.length;i++){
					store.push([mtplarr[i].id, mtplarr[i].filename]);
				}
				store.push('</optgroup>');
			}
			$.rockmodelinput({
				'title':'新增文档协作',
				'okbtn':'新增',
				'columns':[{
					'name' :'filename',labelText:'名称',type:'text',required:true,blankText:'请输入名称'
				},{
					name:'fenlei',labelText:'分类',blankText:'文档分为一类'
				},{
					name:'fileext',labelText:'文档类型',type:'select',store:store,valuefields:0,displayfields:1
				},{
					name:'explian',labelText:'说明',blankText:'说明这个文档是什么的',type:'textarea'
				}],
				saveurl:js.getapiurl('docxie','save'),
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
			a.del({url:js.getapiurl('docxie','docxie')});
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			var as = ['my','receid','mycj'];
			a.setCans({celleditor:lx==2});
			atype  = as[lx];
			a.setparams({'atype':atype},true);
		},
		xuanopt:function(o1,lx){
			if(lx==0 || lx==1){
				var na='协作人';
				if(lx==1)na='可查看人员';
				$.rockmodeuser({
					title:'选择'+na+'',
					changetype:'deptusercheck',
					onselect:function(sna,sid){
						c.gongxiang(sna,sid,lx,'处理');
					}
				});
			}
		},
		gongxiang:function(sna,sid,lx,stx){
			js.loading(''+stx+'中...');
			js.ajax(js.getapiurl('docxie','optedit'),{sna:sna,sid:sid,lx:lx,id:a.changeid},function(ret){
				js.msgok(''+stx+'成功');
				a.reload();
			},'post');
		}
	}
	
	js.initbtn(c);
});
</script>
<div id="buttonlist{rand}">
	<table width="100%">
	<tr>
		<td style="padding-right:10px;" id="tdleft_{rand}" nowrap><button id="addbtn_{rand}" class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增文档</button></td>
		
		
		<td  width="90%" style="padding-left:10px">
		
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_0" click="changlx,0" type="button">需我协作</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">我可查看</button>
		<button class="btn btn-default" id="state{rand}_2" click="changlx,2" type="button">我创建</button>
		</div>	
		
		</td>
		<td  style="padding-right:10px">
		<div class="dropdown">
		<button data-toggle="dropdown" role="button" class="btn btn-default" id="xuanbtn_{rand}" disabled type="button">选中操作 <span class="caret"></span></button>
		<ul class="dropdown-menu" >
			<li role="button" click="xuanopt,0"><a>协作人</a></li>	
			<li role="button" click="xuanopt,1"><a>可查看人员</a></li>
			<li role="separator" class="divider"></li>
			<li role="button" click="xuanopt,2"><a>复制</a></li>
		</ul>
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