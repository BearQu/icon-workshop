<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keyword" content="app图标缩放,应用图标生成工具,批量生成iOS图标,批量生成Android图标,app图标,iWatch图标,Cordova图标,启动图生成工具,LaunchImage批量生成">
    <meta name="description" content="一键快速生成iOS, Android，Web App, PhoneGap(Cordova)等移动平台不同大小(规格尺寸)的App图标和Launch Image/Splash Screen启动图，快速预览，可以直接打包发布到应用市场">
    <meta name="author" content="睿鸿游戏">
    <meta name="slogan" content="<?php echo Config::get('constants.slogan') ?>">
    <link rel="icon" href="/favicon.ico">

    <title>{{ Config::get('constants.site_name') . ' - ' . Config::get('constants.slogan') }}</title>

    <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/select2/4.0.1/css/select2.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/bootstrap-material-design/0.5.7/css/ripples.min.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/hint.css/1.3.6/hint.min.css" rel="stylesheet">

    @if(App::environment('local'))
        <link href="/css/bootstrap-material-design.min.css" rel="stylesheet">
        <link href="/css/loading-bar.min.css" rel="stylesheet">
        <link href="/bower/ng-dialog/css/ngDialog.min.css" rel="stylesheet">
        <link href="/bower/ng-dialog/css/ngDialog-theme-default.min.css" rel="stylesheet">
        <link href="/bower/angular-material/angular-material.min.css" rel="stylesheet">
        <link href="/bower/md-color-picker/dist/mdColorPicker.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="/css/main.css">
    @else
        <link rel="stylesheet" href="/css/all.css?_={{ $GLOBALS['_VER_'] }}">
    @endif

    <!--[if lt IE 9]>
    <script src="/js/lib/html5shiv.js"></script>
    <script src="/js/lib/respond.min.js"></script>
    <![endif]-->

    @yield('head')
</head>

<body ng-app="rhIcon" ng-controller="RootCtrl" ng-cloak>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container" style="position: relative">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/" style="color: white">
                <span id="navbar_logo">RH</span>
                <?php echo Config::get('constants.site_name') ?>
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li ng-class="stateCls('home')"><a href="/">首页</a></li>
                <li ng-class="stateCls('guide')">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;" role="button" aria-haspopup="true" aria-expanded="false">
                        使用教程 <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                            foreach(Config::get('constants.platforms') as $k => $v) {
                                echo '<li><a href="/guide/' . $k . '">' . $v . '</a></li>';
                            }
                        ?>
                        <li><a href="/splash/demo">启动图生成工具</a></li>
                    </ul>
                </li>
                <li ng-class="stateCls('faq')"><a href="/faq">常见问题</a></li>
                <li ng-class="stateCls('changelog')">
                    <a href="/changelog">
                        更新日志
                        <?php if (time() - $GLOBALS['_VER_'] <= 1296000) { // 15天 ?>
                            <span class="red-dot"></span>
                        <?php } ?>
                    </a>
                </li>
                <li ng-class="stateCls('splash')">
                    <a href="/splash">启动图<span class="red-dot"></span></a>
                </li>
                <li ng-class="stateCls('donate')">
                    <a href="/donate">捐赠<span class="red-dot"></span></a>
                </li>
                <li><a href="http://blog.wuruihong.com" target="_blank">博客</a></li>
                <li ng-class="stateCls('about')"><a href="/about">关于我们</a></li>
            </ul>
        </div>
        <div id="gh-star">
            <a class="github-button" href="https://github.com/zhanghuanchong/icon-workshop"
               data-size="large" data-show-count="true"
               aria-label="Star zhanghuanchong/icon-workshop on GitHub">Star</a>
        </div>
    </div>
</nav>

@yield('container')

<footer class="navbar navbar-default" style="margin-bottom: 0">
    <p>
        <a href="http://blog.wuruihong.com" target="_blank" style="color:white">博客</a>　
        <a href="https://github.com/zhanghuanchong/icon-workshop/issues/new" target="_blank" style="color:white">Github 反馈</a>　
        <a href="/about" style="color:white">留言</a>
    </p>
    <p style="margin-bottom: 0">
        Copyright &copy; since 2015　All Rights Reserved　豫ICP备14010348号-1
    </p>
</footer>

<script>
    window.showAd = <?php echo App::environment('local') ? 'false' : 'true' ?>;
</script>

<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="//cdn.bootcss.com/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="//cdn.bootcss.com/select2/4.0.1/js/select2.min.js"></script>
<script src="//cdn.bootcss.com/select2/4.0.1/js/i18n/zh-CN.js"></script>
<script src="//cdn.bootcss.com/bootstrap-material-design/0.5.7/js/ripples.min.js"></script>
<script src="//cdn.bootcss.com/angular.js/1.4.8/angular.min.js"></script>
<script src="//cdn.bootcss.com/angular.js/1.4.8/angular-animate.min.js"></script>
<script src="//cdn.bootcss.com/angular-ui-router/0.2.15/angular-ui-router.min.js"></script>
<script src="//cdn.bootcss.com/angular-ui-bootstrap/1.0.3/ui-bootstrap-tpls.min.js"></script>
<script src="//cdn.bootcss.com/lodash.js/4.0.0/lodash.min.js"></script>
<script src="//cdn.bootcss.com/js-cookie/latest/js.cookie.min.js"></script>
<!--[if IE 10]>
<script src="/js/lib/ie10-viewport-bug-workaround.js"></script>
<![endif]-->
@if(App::environment('local'))
    <script src="/js/lib/material.min.js"></script>
    <script src="/js/lib/loading-bar.min.js"></script>
    <script src="/bower/angular-aria/angular-aria.min.js"></script>
    <script src="/bower/angular-cookies/angular-cookies.min.js"></script>
    <script src="/bower/ng-dialog/js/ngDialog.min.js"></script>
    <script src="/bower/tinycolor/dist/tinycolor-min.js"></script>
    <script src="/bower/angular-material/angular-material.min.js"></script>
    <script src="/bower/md-color-picker/dist/mdColorPicker.min.js"></script>

    <script src="/js/main.js"></script>
    <script src="/js/app/constant.js"></script>
    <script src="/js/app/directive.js"></script>
    <script src="/js/app/core.service.js"></script>
    <script src="/js/app/root.ctrl.js"></script>
@else
    <script src="/js/all.js?_={{ $GLOBALS['_VER_'] }}"></script>
@endif

@yield('scripts')

<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
