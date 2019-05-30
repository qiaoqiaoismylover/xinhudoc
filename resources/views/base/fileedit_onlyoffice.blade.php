<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>{{ $filename }}</title>
<script src="/js/jquery.1.9.1.min.js"></script>
<script type="text/javascript" src="{{ $onlyurl }}/web-apps/apps/api/documents/api.js"></script>
<script src="/js/base64-min.js"></script>
</head>
<body style="padding:0px;margin:0px;">
<div id="placeholder" style="height:100%;width:100%"></div>
<script>
var config = {
    "document": {
        "fileType": "{{ $fileext }}",
        //"key": "{{ $cnum }}{{ $filenum }}a",
        "title": "{{ $filename }}",
        "url": "{{ $url }}",
		"permissions": {
            "comment": false,
            "download": false,
            "fillForms": true,
            "print": true,
			'help':false
        }
    },
	"type": "{{ $viewtype }}",
	"documentType": "{{ $documentType }}",
	"editorConfig": {
		"mode": "edit",
		"lang":"zh-CN",
		"location":"zh-CN",
		"user":{
			"name" :"{{ $useainfo->name }}",
			"id" :"{{ $useainfo->user }}"
		},
		"help":false,
		"customization": {
            "chat": false,
			"autosave":false,
            "comments": false,	
			"compactToolbar": false, //显示简单工具栏
			"help":false,
			'about': false,
            'feedback': false,
			"logo": {
                "image": "{{ $appurl }}/images/platlogo.png",
                "imageEmbedded": "{{ $appurl }}/images/platlogo.png",
                "url": "{{ $appurl }}"
            },
			"customer": {
                "address": "{{ $useainfo->deptallname }}",
                "info": "{{ $companyinfo->name }}",
                "logo": "{{ $companyinfo->logo }}",
                "mail": "{{ $useainfo->email }}",
                "name": "{{ $useainfo->name }}",
                "www": "{{ $appurl }}"
            }
		},
		"callbackUrl": "{{ $callbackUrl }}"+jm.base64decode("{{ $callbackCan }}")+""
		//"callbackUrl": "https://api.onlyoffice.com/editors/callback"
		//"callbackUrl": "http://192.168.1.104/call.php"
		//"callbackUrl": "http://doc.rockoa.com/call.php"
	},
	"height": "100%",
	"width": "100%",
	"events": {
        "onDocumentReady":function(){
			//var obj = $('iframe')[0];
			//console.log(obj.src);
			//console.log('red');
			//docEditor.denyEditingRights('这个是个提示');
		}
    }
};
//console.log(config.editorConfig.callbackUrl);
var docEditor = new DocsAPI.DocEditor("placeholder", config);
</script>
</body>
</html>