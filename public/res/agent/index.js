var tabsarr={},nowtabs,opentabs=[];
function initbody(){
	objcont = $('#content_allmainview');
	objtabs = $('#tabs_title');
	if(show_key!='')jm.setJmstr(jm.base64decode(show_key));
	resizewh();
	$(window).resize(resizewh);
	clickhome();
	document.ondragover=function(e){e.preventDefault();};
	document.ondrop=function(e){e.preventDefault();};
	try{
		history.pushState(null, null, document.URL);
		window.addEventListener('popstate', function (){
			history.pushState(null, null, document.URL);
		});
	}catch(e){}
	
	$('body').keydown(function(e){
		var code	= e.keyCode;
		if(code==27){
			if(get('xpbg_bodydds')){
				$('#rockModal').modal('hide');
			}else{
				closenowtabs();
			}
			return false;
		}
	});
	
	$(document).ajaxSuccess(function(ev, xhr, setobj) {
		if(setobj.url.indexOf('/api/we')==0){
			var cont = xhr.responseText;
			var dar  = js.decode(cont);
			if(dar && dar.code==401){
				js.msg();
				js.alert('登录已失效：'+dar.msg+'，请从新登录','', function(){
					js.loading('退出重新登录...');
					js.location('/users/loginout');
				});
			}
		}
	});
}

js.getapiurl=function(m1,a1){
	if(a1)m1+='_'+a1+'';
	return '/api/we/'+m1+'/'+cnum+'';
}

js.initbtn = function(obj){
	var o = $("[click]"),i,o1,cl;
	for(i=0; i<o.length; i++){
		o1	= $(o[i]);
		cl	= o1.attr('clickadd');
		if(cl!='true'){
			o1.click(function(eo){
				var cls = $(this).attr('click'),bo;
				if(typeof(cls)=='string'){
					cls=cls.split(',');
					bo =obj[cls[0]](this, cls[1], cls[2], eo);
				}
				if(typeof(bo)=='boolean')return bo;
			});
		}
	}
	o.attr('clickadd','true');
}

function resizewh(){
	var _lw = 0;
	if(get('indexmenu')){
		_lw = $('#indexmenu').width()+3;
	}
	var w = winWb()-_lw;
	var h = winHb(),_ht=objtabs.height();
	viewwidth  = w; 
	viewheight = h-_ht;
	
	$('#indexcontent').css({width:''+viewwidth+'px',height:''+(viewheight)+'px'});
	var nh = winHb();
	$('#indexmenu').css({height:''+nh+'px'});
	$('#indexsplit').css({height:''+nh+'px'});
}
function clickhome(){
	var ad = {num:'home',url:'home',icons:'folder-close-alt',name:'文件中心',hideclose:true};
	addtabs(ad);
	return false;
}

var coloebool = false;
function closetabs(num){
	tabsarr[num] = false;
	$('#content_'+num+'').remove();
	$('#tabs_'+num+'').remove();
	if(num == nowtabs.num){
		var now ='home',i,noux;
		for(i=opentabs.length-1;i>=0;i--){
			noux= opentabs[i];
			if(get('content_'+noux+'')){
				now = noux;
				break;
			}
		}
		changetabs(now);
	}
	coloebool = true;
	setTimeout('coloebool=false',10);
}
function changetabs(num,lx){
	if(coloebool)return;
	if(!lx)lx=0;
	$("div[temp='content']").hide();
	$("[temp='tabs']").removeClass();
	var bo = false;
	if(get('content_'+num+'')){
		$('#content_'+num+'').show();
		$('#tabs_'+num+'').addClass('active');
		nowtabs = tabsarr[num];
		if(typeof(nowtabs.onshow)=='function')nowtabs.onshow();
		bo = true;
	}
	opentabs.push(num);
	return bo;
}
function closenowtabs(){
	var nu=nowtabs.num;
	if(nu=='home')return;
	closetabs(nu);
}
function zuijing(){
	var ad = {num:'time',url:'time',icons:'time',name:'最近'};
	addtabs(ad);
}

/**
*	添加选择卡
*/
function addtabs(a){
	var url = a.url,
		num	= a.num;
	if(isempt(url))return false;

	if(nowtabs && nowtabs.id && !a.id)a.id=nowtabs.id;
	nowtabs = a;
	if(changetabs(num))return true;
	
	var s = '<li temp="tabs" role="button" onclick="changetabs(\''+num+'\',1)" id="tabs_'+num+'" class="active"><a style="TEXT-DECORATION:none;"><font>';
	if(a.icons)s+='<i class="icon-'+a.icons+'"></i>  ';
	s+=a.name+'</font>';
	if(!a.hideclose)s+='&nbsp;<span onclick="closetabs(\''+num+'\')" class="icon-remove"></span>';
	s+='</a></li>';
	objtabs.append(s);
	
	
	var bgs = '<div id="mainloaddiv" style="width:'+viewwidth+'px;height:'+viewheight+'px;overflow:hidden;background:#000000;color:white;filter:Alpha(opacity=20);opacity:0.2;z-index:3;position:absolute;left:0px;line-height:'+viewheight+'px;top:0px;" align="center"><img src="/images/mloading.gif"  align="absmiddle">&nbsp;加载中...</div>';
	$('#indexcontent').append(bgs);
	a.urlpath = url+'.php';
	var rand = js.getrand();
	objcont.append('<div temp="content" id="content_'+num+'"></div>');
	$.ajax({
		url:'/users/indexhome?surl='+jm.base64encode(url)+'',
		type:'get',
		success: function(da){
			$('#mainloaddiv').remove();
			var s = da;
				s = s.replace(/\{rand\}/gi, rand);
			var obja = $('#content_'+num+'');
			obja.html(s);
		},
		error:function(e){
			$('#mainloaddiv').remove();
			var s = 'Error('+e.status+','+e.statusText+'):加载出错喽,'+url+'';
			$('#content_'+num+'').html(s);
		}
	});
	tabsarr[num] = a;
	return false;
}