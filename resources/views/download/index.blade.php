<!DOCTYPE html>
<html>
<head>
    <title>官方APP下载</title>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" name="viewport" />

    <meta content="" name="description" />
    <!-- CSS Global Compulsory-->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />`
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/css.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
<!--<link rel="stylesheet" th:href="@{${application.approot}+'/front./css/bootstrap.min.css'}">-->

    <!--[if lt IE 10]>
    <div class="browser_notice">
        您的浏览器版本较低，无法正确显示网站中的页面和功能，建议您使用 Chrome、Safari 或升级到 Internet Explorer 10 以上版本的浏览器。</div>
    <![endif]-->
</head><body>


<script type="text/javascript" src="{{ asset('js/jquery-1.7.2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

<!--App自定义的css-->
<style type="text/css">
    html,body{height: 100%;padding: 0;margin: 0;}
    body{
        background: url({{ asset('img/login/bg.png') }});
        background-size:100% 100%;
    }
    .mui-content{background: none;}
    .title {text-align: center;margin-top: 10%;}
    .title img{width: 90%;}
    .yaoqingma_bg{position: absolute;left: 0;bottom: 8%;
        width: 100%;text-align: center;height:70%;
        background: url({{ asset('img/login/ipt.png') }}) no-repeat ;
        background-size:100% auto;z-index: 10;}
    .yaoqingma{position: absolute;left: 0;bottom:0;width:100%;height: 35%;}
    .yaoqingma div{margin-bottom: 1rem}
    .yaoqingma input{width: 50%;text-align: center;font-size: 0.75rem;height: 1.5rem;line-height: 1.5rem;padding: 0;}
    .yaoqingma span{display:inline-block;width:50%;height:2rem;line-height: 2rem;border-radius:0.25rem; font-size: 0.75rem;background: #F2F2F2;
    }

</style>

<div class="title">
    <img src="{{ asset('img/login/title.png') }}"/>
</div>
<div class="yaoqingma_bg">
    <div class="yaoqingma">
        <div><input type="text" id="yaoqingma" readonly="readonly" value="{{ $code }}"style="height:2.5rem;line-height:2.5rem;" /></div>
        <div><span onclick="download_android()" style="height:2.5rem;line-height:2.5rem;">安卓版下载</span></div>
        <div><span onclick="download_ios()" style="height:2.5rem;line-height:2.5rem;" >苹果版版下载</span></div>
    </div>
    <!--<div class="yaoqingma"></div> -->
</div>



<script language="javascript">
    function download_android(){
        //下载安卓
        window.location.href="{{ url('downLoadandroid') }}";
    }

    function download_ios(){
        //下载安卓
        window.location.href="{{ url('downLoadios') }}";
    }

    function isWeiXin(){
        var ua = navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i)=="micromessenger") {
            return true;
        } else {
            return false;
        }
    }
    function isQQ(){
        var ua = navigator.userAgent;
        if (ua.match(/QQ\//i) == "QQ/") {
            return true;
        } else {
            return false;
        }
//        if ( ua.indexOf("QQBrowser")!==-1) {
//            return true;
//        } else {
//            return false;
//        }
    }
    window.onload =(function(){
//        $("#ase").click(function(){
//            gotoopenurl('/Index/down/id/1871.html');
//            setTimeout(function(){  //使用  setTimeout（）方法设定定时2000<a href="https://www.baidu.com/s?wd=%E6%AF%AB%E7%A7%92&tn=44039180_cpr&fenlei=mv6quAkxTZn0IZRqIHckPjm4nH00T1YLn1R1PW6dPH6YPAnsPW6v0ZwV5Hcvrjm3rH6sPfKWUMw85HfYnjn4nH6sgvPsT6KdThsqpZwYTjCEQLGCpyw9Uz4Bmy-bIi4WUvYETgN-TLwGUv3EnHf4PWmsrHRdnWckPWnkrHmkrf" target="_blank" class="baidu-highlight">毫秒</a>
//                gotoopenurl('/Appipa/downloading/id/1871.html');
//            },2000);
//        });
//   $("#ase").trigger("click");
        //  gotoopenurl('/Index/down/id/1871.html');
    });
    function gotoopenurl(url){
        if(isWeiXin()||isQQ()){
            alert("请点击微信右上角按钮，然后在弹出的菜单中，点击在浏览器中打开，即可安装");
        }else{
            window.location.href = url;

        }
    }
</script>

<link rel="stylesheet" href="{{ asset('css/app.css') }}" />
<div id="weixin" style="display: none">
    <div class="click_opacity"></div>
    <div class="to_btn">
        <span class="span1"><img src="{{ asset('img/page/click_btn.png') }}" /></span>
        <span class="span2"><em>1</em> 点击右上角<img src="{{ asset('img/page/menu.png') }}" />打开菜单</span>
        <span class="span2"><em>2</em> 选择<img src="{{ asset('img/page/safari.png') }}" />用Safari打开下载</span>
    </div>
</div>
<div id="weixin_an" style="display: none">
    <div class="click_opacity"></div>
    <div class="to_btn">
        <span class="span1"><img src="{{ asset('img/page/click_btn.png') }}" /></span>
        <span class="span2"><em>1</em> 点击右上角<img src="{{ asset('img/page/menu_android.png') }}" />打开菜单</span>
        <span class="span2 android_open"><em>2</em> 选择<img src="{{ asset('img/page/android.png') }}" /></span>
    </div>
</div>
<script src="{{ asset('js/slick.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/waypoints.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.themepunch.revolution.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/moban.js') }}" type="text/javascript"></script>



<script language="javascript">
    $(document).ready(function() {
        $('#reportClick').click(function () {
            $('#reportApp').modal('show');
        });
    });
</script></body></html>