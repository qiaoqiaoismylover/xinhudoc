/**
*	上传文件的js文件
*	主页：http://www.rockoa.com/
*	软件：信呼文件管理平台
*	作者：雨中磐石(rainrock)
*	时间：2019-05-20
*/

(function ($) {
	maxupgloble = {{ $maxup }};
	upurlgloble = '{{ $upurl }}';
	function rockupfile(opts){
		var me 		= this;
		var opts	= js.apply({
			inputfile:'',
			initpdbool:false,
			initremove:true,
			thumbnail:'',
			uptoken:'upfile',
			updir:'',
			uptype:'*',
			upbadata:[],
			maxsize:0,
			onchange:function(){},
			onprogress:function(){},
			onsuccess:function(){},
			quality:1,
			xu:0,
			fileallarr:[],
			autoup:true,
			onerror:false,
			fileidinput:'fileid',
			onabort:function(){},
			allsuccess:function(){}
		},opts);
		this._init=function(){
			for(var a in opts)this[a]=opts[a];
			
			if(maxupgloble==0)$.getJSON(''+upurlgloble+'/base/getmaxup',function(res){
				try{
				if(res.code==200){
					var maxup = parseFloat(res.data.maxup);
					me.maxsize= maxup;
					maxupgloble = maxup;
				}}catch(e){}
			});
			if(maxupgloble>0)this.maxsize= maxupgloble;
			if(this.fileview)$('#'+this.fileview+'').click(function(){me.rexushow();});
			if(this.dropview && get(this.dropview)){
				document.ondragover=function(e){e.preventDefault();};
				document.ondrop=function(e){e.preventDefault();};
				get(this.dropview).addEventListener('drop', function(e) {
					var files = e.dataTransfer;
					me.change(files);
				}, false);
			}
			
			if(!this.autoup)return;
			if(this.initremove){
				if(!this.inputfile)this.inputfile='upfileinput'+parseInt(Math.random()*9999)+'';
				$('#'+this.inputfile+'').parent().remove();
				var s='<form style="display:none;height:0px;width:0px" name="form_'+this.inputfile+'"><input type="file" id="'+this.inputfile+'"></form>';
				$('body').append(s);
			}
			$('#'+this.inputfile+'').change(function(){
				me.change(this);
			});
		};
		this.reset=function(){
			var fids = 'form_'+this.inputfile+'';
			if(document[fids])document[fids].reset();
		};
		this.setparams=function(ars){
			this.oparams = js.apply({uptype:this.uptype}, ars);
			this.uptype=this.oparams.uptype;
		};
		this.setuptype=function(lx){
			this.uptype = lx;
		},
		this.changefile=function(ars){
			if(this.upbool)return;
			this.setparams(ars);
			get(this.inputfile).click();
		};
		this.clear=function(){
			this.fileallarr = [];
			this.filearr	= {};
			this.xu 		= 0;
			$('#'+this.fileview+'').html('');
		};
		this.change=function(o1){
			if(!o1.files){
				js.msg('msg','当前浏览器不支持上传1');
				return;
			}
			this.fileswait = o1.files;
			this.changes(0);
		};
		this.changes=function(oi){
			
			if(oi>=this.fileswait.length){
				this.rexushow();
				setTimeout(function(){me.reset();},500);
				return false;
			}
			
			var f = this.fileswait[oi];
			if(!f || f.name=='/'){
				this.changes(oi+1);
				return;
			}
			var a = {filename:f.name,filesize:f.size,filesizecn:js.formatsize(f.size)};
			if(a.filesize<=0){
				js.msg('msg',''+f.name+'不存在');
				this.changes(oi+1);
				return;
			}
			if(this.isfields(a)){
				this.changes(oi+1);
				return;
			}
			
			if(this.maxsize>0 && f.size>this.maxsize*1024*1024){
				js.msg('msg','文件不能超过'+this.maxsize+'MB,当前文件'+a.filesizecn+'');
				this.changes(oi+1);
				return;
			}
			var filename = f.name;
			var fileext	 = filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
			if(!this.uptype)this.uptype='*';
			if(this.uptype=='image')this.uptype='jpg,gif,png,bmp,jpeg';
			if(this.uptype=='word')this.uptype='doc,docx,pdf,xls,xlsx,ppt,pptx,txt';
			if(this.uptype!='*'){
				var upss=','+this.uptype+',';
				if(upss.indexOf(','+fileext+',')<0){
					js.msg('msg','禁止文件类型,请选择'+this.uptype+'');
					this.changes(oi+1);
					return;
				}
			}
			
			a.fileext	 = fileext;
			a.isimg		 = js.isimg(fileext);
			if(a.isimg)a.imgviewurl = this.getimgview(oi);
			a.xu		 = this.xu;
			a.f 		 = f;
			for(var i in this.oparams)a[i]=this.oparams[i];
			this.filearr = a;
			var zc=this.fileallarr.push(a);
			
			//如果是图片压缩一下超过1M
			if(f.size>1024*1024 && a.isimg && this.quality<1){
				this.compressimg(a.imgviewurl,f,function(nf){
					a.filesize 	 = nf.size;
					a.filesizecn = js.formatsize(nf.size);
					me.fileallarr[zc-1].f = nf;
					me.nnonchagn(a, nf, zc);
					me.changes(oi+1);
				});
			}else{
				this.nnonchagn(a, f, zc);
				this.changes(oi+1);
			}
		};
		this.rexushow=function(){
			if(!this.fileview)return;
			var o	= $('#'+this.fileview+'').find('label');
			for(var i=0;i<o.length;i++){
				o[i].innerHTML=''+(i+1)+'. ';
			}
		};
		this.nnonchagn=function(a,f,zc){
			this.xu++;
			this.onchange(a);
			if(!this.autoup){
				var s='<div style="padding:5px;font-size:14px;border-bottom:1px #dddddd solid"><label></label><img src="'+upurlgloble+'/images/fileicons/'+js.filelxext(a.fileext)+'.gif" width="16px" align="absmiddle" > <font>'+a.filename+'</font>('+a.filesizecn+')&nbsp;<span style="color:#ff6600" id="'+this.fileview+'_'+a.xu+'">&nbsp;<a oi="'+(zc-1)+'" id="gm'+this.fileview+'_'+a.xu+'" href="javascript:;">改名</a>&nbsp;<a onclick="$(this).parent().parent().remove()" href="javascript:;">×</a></span></div>';
				$('#'+this.fileview+'').append(s);
				$('#gm'+this.fileview+'_'+a.xu+'').click(function(){
					me.s_gaiming(this);
				});
				return;
			}
			this._startup(f);
		};
		this.s_gaiming=function(o1){
			var o,oi,one,fa,o2;
			o  = $(o1);
			oi = parseFloat($(o1).attr('oi'));
			fa = this.fileallarr[oi];
			o2 = o.parent().parent().find('font');
			one= o2.html().replace('.'+fa.fileext+'','');
			if(get('confirm_main') || get('rockModal') || !js.prompt){
				var nr = prompt('新文件名', one);
				if(nr){
					var newfie = nr+'.'+fa.fileext;
					o2.html(newfie);
					me.fileallarr[oi].filename=newfie;
				}
			}else{
				js.prompt('修改文件名','新文件名', function(jg,nr){
					if(jg=='yes' && nr){
						var newfie = nr+'.'+fa.fileext;
						o2.html(newfie);
						me.fileallarr[oi].filename=newfie;
					}
				}, one);
			}
		};
		this.compressimg=function(path,fobj,call){
			var img = new Image();
            img.src = path;
			if(!call)call=function(){};
			img.onload = function(){
				var that = this;
                var w = that.width,
                    h = that.height,
                    scale = w / h;
                var quality = me.quality;//压缩图片质量
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                var anw = document.createAttribute("width");
                anw.nodeValue = w;
                var anh = document.createAttribute("height");
                anh.nodeValue = h;
                canvas.setAttributeNode(anw);
                canvas.setAttributeNode(anh);
                ctx.drawImage(that, 0, 0, w, h);
				var base64 = canvas.toDataURL(fobj.type, quality);
				var nfobj  = me.base64toblob(base64);
				call(nfobj);
			}
		};
		this.base64toblob=function(urlData){
			var arr = urlData.split(','), mime = arr[0].match(/:(.*?);/)[1],
				bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
			while(n--){
				u8arr[n] = bstr.charCodeAt(n);
			}
			return new Blob([u8arr], {type:mime});
		};
		this.getimgview=function(oi){
			try{
				return URL.createObjectURL(this.fileswait.item(oi));
			}catch(e){return false;}
		};
		this.isfields=function(a){
			var bo = false,i,d=this.fileallarr;
			for(i=0;i<d.length;i++){
				if(this.fileviewxu(d[i].xu) && d[i].filename==a.filename && d[i].filesize==a.filesize){
					return true;
				}
			}
			return bo;
		};
		this.sendbase64=function(nr,ocs){
			this.filearr=js.apply({filename:'截图.png',filesize:0,filesizecn:'',isimg:true,fileext:'png'}, ocs);
			this._startup(false, nr);
		};
		//多文件时开始上传
		this.start=function(){
			this.upbadata = [];
			return this.startss(0);
		};
		//判断是否有文件需要上传
		this.filebool=function(){
			var bo=false,i,len=this.fileallarr.length,f;
			for(i=0;i<len;i++){
				f = this.fileallarr[i];
				if(f && this.fileviewxu(f.xu) && !f.id){
					bo=true;
					break;
				}
			}
			return bo;
		};
		this.startss=function(oi){
			if(oi>=this.xu){
				var ids='';
				var a = this.fileallarr;
				for(var i=0;i<a.length;i++)if(a[i] && a[i].id)ids+=','+a[i].id+'';
				if(ids!='')ids=ids.substr(1);
				try{if(form(this.fileidinput))form(this.fileidinput).value=ids;}catch(e){};
				this.fileallarr=[];
				this.allsuccess(this.upbadata, ids);
				return false;
			}
			this.nowoi = oi;
			var f=this.fileallarr[oi];
			if(!f || !this.fileviewxu(f.xu)){
				return this.startss(this.nowoi+1);
			}
			this.filearr = f;
			this.onsuccessa=function(dst){
				if(dst.id){
					this.fileallarr[this.nowoi].id=dst.id;
					this.fileallarr[this.nowoi].filepath=dst.filepath;
				}else{
					js.msg('msg', str);
					this.fileallarr[this.nowoi] = false;
					this.fileviewxu(this.nowoi, '<font color=red>失败1</font>');
				}
				this.startss(this.nowoi+1);
			}
			this.onprogressa=function(f,bil){
				var str = ''+bil+'%';
				if(bil>=100)str='<font color=green>上传完成</font>';
				this.fileviewxu(this.nowoi, str);
			}
			this.onerror=function(st1){
				this.fileallarr[this.nowoi] = false;
				js.msgerror(st1);
				this.fileviewxu(this.nowoi, '<font color=red>失败0</font>');
				this.startss(this.nowoi+1);
			}
			this._startup(f.f);
			return true;
		};
		this.fileviewxu=function(oi,st){
			if(typeof(st)=='string')$('#'+this.fileview+'_'+oi+'').html(st);
			return get(''+this.fileview+'_'+oi+'');
		};
		
		this._initfile=function(f){
			var a 	= this.filearr,d={'filesize':a.filesize,'fileext':a.fileext};
			if(!a.isimg)d.filename=jm.base64encode(a.filename);
			var url = js.apiurl('upload','initfile', d);
			$.getJSON(url, function(ret){
				if(ret.success){
					var bstr = ret.data;
					me.upbool= false;
					me.onsuccess(a,bstr);
				}else{
					me._startup(f,false,true);
				}
			});
		};
		this._startup=function(fs, nr, bos){
			this.upbool = true;
			//if(this.initpdbool && fs && !bos){this._initfile(fs);return;}
			try{var xhr = new XMLHttpRequest();}catch(e){js.msg('msg','当前浏览器不支持2');return;}
			var url = ''+upurlgloble+'/api/upfile?uptoken='+this.uptoken+'';
			if(this.thumbnail)url+='&thumbnail='+this.thumbnail+'';
			if(this.updir)url+='&updir='+this.updir+'';
			if(typeof(cnum)=='string')url+='&cnum='+cnum+'';
			xhr.open('POST', url, true); 
			xhr.onreadystatechange = function(){me._statechange(this);};
			xhr.upload.addEventListener("progress", function(evt){me._onprogress(evt, this);}, false);  
			xhr.addEventListener("load", function(){me._onsuccess(this);}, false);  
			xhr.addEventListener("error", function(){me._error(false,this);}, false); 
			if(nr)fs = this.base64toblob(nr);
			var fd = new FormData();  
			fd.append('file', fs, this.filearr.filename);
			xhr.send(fd);
			this.xhr = xhr;
		};
		this.onsuccessa=function(){
			
		};
		this._onsuccess=function(o){
			this.upbool = false;
			var bstr 	= o.response;
			if(o.status!=200 || !bstr){
				this._error(bstr);
			}else{
				var ret = js.decode(bstr);
				if(!ret.success){
					this._error(ret.msg);
				}else{
					this.upbadata.push(ret.data);
					this.onsuccessa(ret.data,this.filearr,o);
					this.onsuccess(ret.data,this.filearr,o);
				}
			}
		};
		this._error=function(ts,xr){
			this.upbool = false;
			if(!ts)ts='无返回内部错误';
			if(!this.onerror && js.msgerror){
				js.msgerror(ts);
			}else{
				this.onerror(ts);
			}
		};
		this._statechange=function(o){
			
		};
		this.onprogressa=function(){
			
		};
		this._onprogress=function(evt){
			var loaded 	= evt.loaded;  
			var tot 	= evt.total;  
			var per 	= (100*loaded/tot).toFixed(1);
			this.onprogressa(this.filearr,per, evt);
			this.onprogress(this.filearr,per, evt);
		};
		this.abort=function(){
			this.xhr.abort();
			this.upbool = false;
			this.onabort();
		};
		this._init();
	}
	
	
	$.rockupfile = function(options){
		var cls  = new rockupfile(options,false);
		return cls;
	}
	
})(jQuery); 