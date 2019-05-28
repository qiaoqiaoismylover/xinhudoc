<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $pagetitle or config('app.name') }}({{ trans('manage/public.menu.guanli') }})</title>
<link rel="shortcut icon" href="{{ $companyinfo->logo }}" />

<link href="{{ $style['path'] }}" rel="stylesheet">
<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-{{ $style['nav'] }} navbar-static-top ">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ route('manage', $cnum) }}">
                       <img src="{{ $companyinfo->logo }}" style="display:inline;" align="absmiddle" height="24"> {{ $companyinfo->name }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    
					 <ul class="nav navbar-nav">
						<li @if(strpos($tpl,'dept')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'dept']) }}">{{ trans('manage/public.menu.dept') }}</a></li>
						 
						<li @if(strpos($tpl,'usera')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'usera']) }}">{{ trans('manage/public.menu.usera') }}</a></li>
				 
						<li @if(strpos($tpl,'group')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'group']) }}">{{ trans('manage/public.menu.group') }}</a></li>
					
 
						 <li @if(strpos($tpl,'option')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'option']) }}">{{ trans('manage/public.menu.option') }}</a></li>
	
						
                    </ul>
					

                   
                    <ul class="nav navbar-nav navbar-right">
						<li @if($tpl=='manage/cog') class="active" @endif><a href="{{ route('manage', [$cnum,'cog']) }}">{{ trans('manage/public.menu.dwinfo') }}</a></li>
						
						
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<img style="width:18px;height:18px;border-radius:50%" src="{{ Auth::user()->face }}" align="absmiddle">
								{{ Auth::user()->name }} <span class="caret"></span>
							</a>

							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ route('usersmanage') }}">{{ trans('manage/public.menu.grhome') }}</a></li>
								<li><a href="{{ route('usersindexs', $cnum) }}">{{ trans('manage/public.menu.ushome') }}</a></li>
								<li><a href="{{ route('userscog') }}">{{ trans('users/cog.title') }}</a></li>
									
								<li><a href="{{ route('usersloginout') }}">{{ trans('base.exittext') }}</a>
								</li>
							</ul>
						</li>
                    </ul>
                </div>
            </div>
        </nav>

        <div style="min-height:480px">
        @yield('content')
		</div>
    </div>

    <script src="/res/bootstrap3.3/js/bootstrap.min.js"></script>
	<script src="/res/plugin/jquery-rockvalidate.js"></script>
	<script src="/res/plugin/jquery-rockmodel.js"></script>
	
	<script>
	var companyid = {{ $cid }},cnum='{{ $companyinfo->num }}';
	</script>
	@yield('script')
	
	<div align="center">{!! $helpstr !!}</div>
	@include('layouts.footer')
	
</body>
</html>
