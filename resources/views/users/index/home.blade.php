<script>
$(document).ready(function(){
	
	var height = viewheight-$('#buttonlist{rand}').height()-90,officeview,officeedit;
	
	var a = $('#view_{rand}').bootstable({
		celleditor:false,checked:true,url:js.getapiurl('docfile'),fanye:true,
		method:'get',
		tablename:'b',
		bodyStyle:'height:'+height+'px;overflow:auto',
		columns:[{
			text:'',dataIndex:'fileext',renderer:function(v,d){
				if(!isempt(d.thumbpath))return '<img src="'+d.thumbpath+'" width="24" height="24">';
				if(d.type=='0'){
					var lxs = js.filelxext(v);
					return '<img src="/images/fileicons/'+lxs+'.gif"  height="20">';
				}else{
					return '<img src="/images/folder.png" height="24">';
				}
			}
		},{
			text:'名称',dataIndex:'filename',sortable:true,editor:true,align:'left',editorbefore:function(d){
				if(isguan=='0'){
					return false;
				}else{
					return true;
				}
			}
		},{
			text:'大小',dataIndex:'filesize',sortable:true,renderer:function(v,d){
				if(d.type=='1'){
					v='&nbsp;';
					if(d.downshu>0)v='<font color=#aaaaaa>有子目录</font>';
				}
				if(d.type=='0')v=js.formatsize(v);
				return v;
			}
		},{
			text:'创建者',dataIndex:'optname'
		},{
			text:'最后修改',dataIndex:'optdt',sortable:true,renderer:function(v,d){
				if(d.type=='1')v='&nbsp;';
				return v;
			}
		},{
			text:'共享给',dataIndex:'shatename'
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true,editorbefore:function(d){
				if(isguan=='0'){
					return false;
				}else{
					return true;
				}
			}
		},{
			text:'',dataIndex:'opt',renderer:function(v,d,oi){
				if(d.ishui=='1')return '已删';
				if(d.type=='1'){
					return '<a role="button" style="TEXT-DECORATION:none" onclick="fq.openfolder('+d.fqid+','+d.id+')">打开</a>';
				}else{
					var s = '<a role="button" style="TEXT-DECORATION:none" onclick="fq.yulanfile(\''+d.filenum+'\',\''+d.fileext+'\')">预览</a>';
					if(officelx.indexOf(','+d.fileext+',')>-1 && isguan=='1')s+='&nbsp;<a role="button" style="TEXT-DECORATION:none" onclick="fq.editfile(\''+d.filenum+'\',\''+d.fileext+'\',\'calleditword\')">编辑</a>';
					s+='&nbsp;<a role="button" style="TEXT-DECORATION:none" onclick="fq.downfile(\''+d.filenum+'\',\''+d.fileext+'\')"><i class="icon-arrow-down"></i></a>';
					return s;
				}
			}
		}],
		load:function(d){
			fq.show(d);
			officeview=d.officeview;
			officeedit=d.officeedit;
		},
		itemdblclick:function(d){
			if(d.type=='1'){
				fq.openfolder(d.fqid,d.id);
			}else{
				fq.yulanfile(d.filenum,d.fileext);
			}
		}
	});
	
	var officelx = ',doc,docx,xls,xlsx,ppt,pptx,';
	
	fqarr=[];
	allfq='0';
	fqid = '0';
	folderid='0';
	
	var isup,isguan='0',uptype='';
	
	fq = {
		show:function(da){
			allfq = da.allfq;
			fqarr = da.fqarr;
			var s='',i,len=fqarr.length;
			for(i=0;i<len;i++){
				s+='<li temp="fq" onclick="fq.changefenqu('+i+', true)" role="button"><a style="TEXT-DECORATION:none;padding-left:24px">'+fqarr[i].name+'</a></li>';
			}
			$('li[temp="fq"]').remove();
			$('#showfqdiv').append(s);
			
			
			var arr = da.lujarr;
			if(arr){
				var s = '';
				for(var i=0;i<arr.length;i++){
					if(i>0)s+=' / ';
					s+='<a onclick="fq.openfolder('+arr[i].fqid+', '+arr[i].folderid+')" style="TEXT-DECORATION:none" role="button">'+arr[i].name+'</a>';
				}
				$('#showlujing').html(s);
			}
		},
		changefenqu:function(oi, bo){
			var d = fqarr[oi];
			if(nowtabs.num!='home')changetabs('home');
			fqid = ''+d.id+'';
			var sne  = '所有分区';
			isup 	 = d.isup;
			isguan 	 = d.isguan;
			uptype 	 = d.uptype;
			
			if(fqid>0){
				sne = ''+d.name+'';
			}
			var qswt = '';
			if(isguan=='0'){
				if(isup=='1'){
					qswt = '<span class="label label-info">仅上传</span> ';
				}else{
					qswt = '<span class="label label-default">只读</span> ';
				}
			}else{
				if(isup=='1'){
					qswt = '<span class="label label-success">可管理上传</span> ';
				}else{
					qswt = '<span class="label label-info">仅管理</span> ';
				}
			}
			$('#toolbar_center').html(''+qswt+'<span id="showlujing"><a style="TEXT-DECORATION:none" role="button" onclick="fq.changefenqu('+oi+', true)">'+sne+'</a></span>');
			
			if(isup=='1'){
				get('folderbtn').disabled=false;
				get('upbtn').disabled=false;
				get('delbtn').disabled=false;
				get('xuanbtn').disabled=false;
			}else{
				get('folderbtn').disabled=true;
				get('upbtn').disabled=true;
				get('delbtn').disabled=true;
				get('xuanbtn').disabled=true;
			}
			if(isguan=='1'){
				get('delbtn').disabled=false;
				get('xuanbtn').disabled=false;
				a.setCans({'celleditor':true,'checked':true});
			}else{
				get('delbtn').disabled=true;
				get('xuanbtn').disabled=true;
				a.setCans({'celleditor':false,'checked':false});
			}
			if(bo)this.search(fqid,'0');
		},
		showall:function(sou){
			if(!sou)this.key='';
			if(nowtabs.num!='home')changetabs('home');
			var sne  = '<span class="label label-default">只读</span> <span id="showlujing"><a style="TEXT-DECORATION:none" role="button">所有分区</a></span>';
			$('#toolbar_center').html(sne);
			get('folderbtn').disabled=true;
			get('upbtn').disabled=true;
			get('xuanbtn').disabled=true;
			get('delbtn').disabled=true;
			a.setCans({'celleditor':false,'checked':false});
			this.search('0','0');
		},
		guanlifq:function(){
			addtabs({num:'worc',url:'worc',icons:'',name:'分区管理'});
		},
		key:'',
		search:function(id1,id2){
			fqid=id1;
			folderid = id2;
			a.setparams({'fqid':''+id1+'','folderid':''+id2+'','key':jm.base64encode(this.key)}, true);
		},
		openfolder:function(id1,id2){
			var oi = -1;
			var i,len=fqarr.length;
			for(i=0;i<len;i++){
				if(fqarr[i].id==id1)oi=i;
			}
			if(oi==-1){
				js.msg('msg','无效操作');
				return;
			}
			this.key='';
			this.changefenqu(oi, false);
			this.search(id1,id2);
		},
		yulanfile:function(fnum,fext){
			if(js.isimg(fext)){
				js.loading('预览初始化中...');
				js.ajax(js.getapiurl('docfile','getfile'),{'filenum':fnum}, function(ret){
					var d2 = ret.data;
					$.imgview({'url':d2.filepath,'downbool':false});
				},'get',function(msg){
					js.msgerror(msg);
				});
			}else{
				var url = this.getshowurl('view', fnum);
				window.open(url);
			}
		},
		getshowurl:function(lx, fnum){
			var ckey = jm.encrypt(''+cnum+'_'+adminid+'');
			return '/file'+lx+'/'+ckey+'/'+jm.base64encode(fnum)+'';
		},
		editfile:function(fnum, fext, callb){
			if(!callb)callb='';
			var url = this.getshowurl('show', fnum);
			url+='?otype=0';
			if(callb)url+='&callb='+callb+'';
			window.open(url);
		},
		downfile:function(fnum, fext){
			var url = this.getshowurl('down', fnum);
			js.location(url);
		},
		xuanopt:function(lx){
			c.xuanopt(lx);
		},
		openshate:function(){
			addtabs({num:'share',url:'share',icons:'share',name:'共享的文件'});
		},
		docxie:function(lx){
			
			if(lx==0){
				addtabs({num:'doctpl',url:'doctpl',icons:'building',name:'文档模版'});
			}else if(lx==1){
				addtabs({num:'docxie',url:'docxie',icons:'file-alt',name:'文档协作'});
			}else{
				js.msgok('暂无此功能待完善');
			}
		}
	};
	
	var c = {
		createfolder:function(){
			js.prompt('创建文件夹','请输入文件夹名称', function(jg,txt){
				if(jg=='yes' && txt){
					c.createfolders(txt);
				}
			});
		},
		createfolders:function(mc){
			js.loading('创建中...');
			js.ajax(js.getapiurl('docfile','createfolder'),{'fqid':fqid,'folderid':folderid,'name':mc}, function(ret){
				js.msgok('文件夹创建成功');
				a.reload();
			},'post');
		},
		del:function(){
			a.del({url:js.getapiurl('docfile','delfile')});
		},
		uploadfile:function(){
			var html = '<div id="upfileviewqy" style="padding:10px"><form name="form_inputfiles"><input multiple type="file" value="选择文件..." onchange="fq.upobj.change(this)" id="inputfiles">&nbsp;或者将文件拖动到这里空白区域里</form></div><div id="showuplist" style="margin:10px"></div>';
			this.clobj = $.rockmodel({
				title:'上传文件',
				width:'550px',
				bodyheight:'280px',
				body:html,
				bodypadding:'0px',
				type:1,
				okbtn:'开始上传',
				onok:function(){
					if(!fq.upobj.filebool()){
						js.msg('msg','没有可上传的文件');
						return false;
					}
					this.btndisabled(true);
					fq.upobj.start();
					return false;
				}
			});
			if(!fq.upobj)fq.upobj=$.rockupfile({
				autoup:false, //不自动上传
				fileview:'showuplist',
				dropview:'rockModal_body',//拖动到此区域上传
				allsuccess:function(arr,ids){
					c.upsuccess(arr,ids);
				},
				inputfile:'inputfiles'
			});
			fq.upobj.setuptype(uptype);
			
		},
		upsuccess:function(uparr,ids){
			if(uparr.length>0){
				js.modeclose();
				js.loading('上传完成添加数据中...');
				js.ajax(js.getapiurl('docfile','savefile'),{'fqid':fqid,'folderid':folderid,'ids':ids}, function(ret){
					js.msgok('上传完成添加数据成功');
					a.reload();
				},'post');
			}else{
				this.clobj.btndisabled(false);//让按钮可以在点
			}
		},
		xuanopt:function(lx){
			var s = a.getchecked();
			if(s==''){
				js.msgerror('没有选中记录');
				return;
			}
			if(lx==0){
				$.rockmodeuser({
					title:'选中的文件共享给...',
					changetype:'deptusercheck',
					onselect:function(sna,sid){
						if(sna)c.gongxiang(sna,sid,s,'共享');
					}
				});
			}
			if(lx==1){
				c.gongxiang('','',s,'取消共享');
			}
			if(lx==2){
				$.selectdata({
					title:'选择移动的分区/目录',
					url:js.getapiurl('docfile','getfenqu'),
					checked:false,
					onselect:function(sda,sna, sid){
						js.loading('移动中...');
						js.ajax(js.getapiurl('docfile','movefile'),{'fqid':sda.fqid,'folderid':sda.folderid,'ids':s},function(ret){
							js.msgok('移动成功');
							a.reload();
						},'post');
					}
				});
			}
		},
		gongxiang:function(sna,sid,fid,stx){
			js.loading(''+stx+'中...');
			js.ajax(js.getapiurl('docfile','shate'),{sna:sna,sid:sid,fid:fid},function(ret){
				js.msgok(''+stx+'成功');
				a.reload();
			},'post');
		},
		keysearch:function(s){
			if(fq.key==s)return;
			fq.key=s;
			fq.showall(true);
		}
	};
	
	$('#menukeyword').keyup(function(e){
		if(e.keyCode==13){
			c.keysearch(this.value);
		}
	});
	
	fq.showall();

	$('#fqguanbtn').click(function(){
		fq.guanlifq();
		return false;
	});
	js.initbtn(c);
	//$('[data-toggle="tooltip"]').tooltip();
});
</script>
<div id="buttonlist{rand}">

<table width="100%" style="background:none">
	<tr>
	<td >
		<button class="btn btn-primary" id="upbtn" click="uploadfile" disabled type="button"><i class="icon-cloud-upload"></i> 上传文件</button>
	</td>
	<td  width="90%" style="padding-left:10px"><div id="toolbar_center"></div></td>
	<td  style="padding-right:10px">
		<div class="dropdown">
		<button data-toggle="dropdown" role="button" class="btn btn-default" id="xuanbtn" disabled type="button">选中操作 <span class="caret"></span></button>
		<ul class="dropdown-menu" >
			<li role="button" onclick="fq.xuanopt(0)"><a>共享</a></li>	
			<li role="button" onclick="fq.xuanopt(1)"><a>取消共享</a></li>
			<li role="separator" class="divider"></li>
			<li role="button" onclick="fq.xuanopt(2)"><a>移动</a></li>
		</ul>
		</div>
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" id="folderbtn" click="createfolder" disabled type="button"><i class="icon-folder-close-alt"></i> 创建文件夹</button>&nbsp; 
		<button class="btn btn-danger" id="delbtn" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button>
		<!--
		&nbsp; <button class="btn btn-default" title="列表" type="button"><i class="icon-align-justify"></i></button>&nbsp; 
		<button class="btn btn-default" title="缩略图" type="button"><i class="icon-th"></i></button>-->
	</td>
</tr>
</table>
</div>

<div style="height:10px;overhidden"></div>
<div id="view_{rand}"></div>