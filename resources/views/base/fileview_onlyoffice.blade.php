<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>{{ $filename }}</title>
<script type="text/javascript" src="{{ $onlyurl }}/web-apps/apps/api/documents/api.js"></script>

</head>
<body style="padding:0px;margin:0px;">
<div id="placeholder" style="height:100%;width:100%"></div>
<script>
var config = {
    "document": {
        "fileType": "{{ $fileext }}",
        "key": "",
        "title": "{{ $filename }}",
        "url": "{{ $url }}",
		"permissions": {
            "comment": false,
            "download": false,
            "edit": false,
            "fillForms": false,
            "print": false,
            "review": false,
			'help':false
        }
    },
	"type": "{{ $viewtype }}",
	"documentType": "{{ $documentType }}",
	"editorConfig": {
		"mode": "view",
		"lang":"zh-CN",
		"customization": {
            "chat": false,
            "comments": false,
			'plugins':false,
			'about':false,
			'feedback':false,
			"help": false,
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
		}
	},
	"height": "100%",
	"width": "100%"
};
var docEditor = new DocsAPI.DocEditor("placeholder", config);
</script>
</body>
</html>