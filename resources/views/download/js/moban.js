/**
 * Created by 36980 on 2017/10/12 .
 */

    function xiugaiphone(){
        $('#myphoneform').submit();
    }
var countdown=60;
function settime() {
    if (countdown == 0) {
        $("#sendcode88").attr("disabled",false);
        $("#sendcode88").html("鍙戦€侀獙璇佺爜");
        countdown = 60;
        return;
    } else {
        $("#sendcode88").attr("disabled",true);
        $("#sendcode88").html("閲嶆柊鍙戦€?(" + countdown + ")");
        countdown--;
    }
    setTimeout(function() {
            settime() }
        ,1000)
}

function sendcode8899(){
    $("#sendcode88").attr("disabled",true);
    var phone = $('#phone').val();

    if(phone){
        $.ajax({
            url: '/index.php/login/phone_login',
            type: 'post',
            data: {phone : phone},
            dataType: 'json',
            beforeSend: function() {
            },
            complete: function() {

            },
            success: function(json) {

                if(json['code']==1){
                    settime();
                    //$("#sendcode88").html("宸插彂閫?,璇锋敞鎰忔煡鏀?");
                    return false;
                }else{
                    alert(json['msg']);
                    $("#sendcode88").attr("disabled",false);
                    return false;
                }
            }
        });
    }else{
        alert("璇疯緭鍏ユ墜鏈哄彿鐮?");
        $("#sendcode88").attr("disabled",false);
        return false;
    }

}

$(function(){
    var browser = {
        versions: function() {
            var u = navigator.userAgent, app = navigator.appVersion;
            return {
                trident: u.indexOf('Trident') > -1,
                presto: u.indexOf('Presto') > -1,
                webKit: u.indexOf('AppleWebKit') > -1,
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,
                mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/),
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,
                iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1,
                iPad: u.indexOf('iPad') > -1,
                webApp: u.indexOf('Safari') == -1
            };
        }()
    }
    if(is_weixin())
    {
        if(browser.versions.ios || browser.versions.iPhone || browser.versions.iPad){
            $('#weixin').show();
        }else{
            $('#weixin_an').show();
        }
    }
    if(isQQ())
    {
        if(browser.versions.ios || browser.versions.iPhone || browser.versions.iPad){
            $('#weixin').show();
        }else{
            $('#weixin_an').show();
        }
    }
    var os_type =  2;
    if(browser.versions.ios || browser.versions.iPhone || browser.versions.iPad){
        if(os_type == 2){
            $('#down2').show();
        }else{
            $('#down2').hide();
            $('#down_tip').show().html('璇蜂娇鐢ㄥ畨鍗撹澶囧畨瑁?');
        }
    }else if(browser.versions.android){
        if(os_type == 1){
            $('#down2').show();
        }else{
            $('#down2').hide();
            $('#down_tip').show().html('璇蜂娇鐢↖OS璁惧瀹夎');
        }
    }
})
function ajaxTj()
{
    var browser = {
        versions: function() {
            var u = navigator.userAgent, app = navigator.appVersion;
            return {
                trident: u.indexOf('Trident') > -1,
                presto: u.indexOf('Presto') > -1,
                webKit: u.indexOf('AppleWebKit') > -1,
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,
                mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/),
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,
                iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1,
                iPad: u.indexOf('iPad') > -1,
                webApp: u.indexOf('Safari') == -1
            };
        }()
    }

    var tip_val = $('#ios_tips').val();
    if(tip_val ==1){
        $('.pos_all').css('display','block');
        $('#pos_inherit').addClass('pos_inherit');

    }
    if(!is_weixin())
    {
        if(browser.versions.ios || browser.versions.iPhone || browser.versions.iPad){
            $("#down2").hide();
            $("#down_loading").show().delay(5000).fadeOut(100);
            $("#install_tips").hide().delay(5000).fadeIn(200);
        }
    }

    $.ajax({
        type: "GET",
        url:'/app/tj',
        data: {package_key:'98d0f7fc5457ddb0777534f47adb9119'},
        success: function(msg){
            //console.log(msg);
            if(msg == 1)
            {
                $('#down1').attr('href','#');
                $("#down1").attr("target", "");
                $('#down2').attr('href','');
                $("#down2").attr("target", "#");
            }
        }
    });
}
function is_weixin(){
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

$(function() {
    $('.close_ios9').click(function(){
        $('.pos_all').css('display','none');
        $('#pos_inherit').removeClass('pos_inherit');
    });

    $("#btnFk").on("click", function() {
        $('#btncontact').css('display', 'block');
        $('#btnFk').css('display', 'none');
        $("#feedback_content_box").slideDown(600);
        $("#feedback_content_box").animate({top: 0}, 600);
        $("#feedback_content_box").focus();
    });



    $("#submit").on("click", function() {
        var contact = $('#btncontact').val().trim();
        var content = $('textarea').val().trim();
        var app_key = "3c4f68eaf91d14a5b16a9129f0ffba0f";
        var app_name = "瀹氳浆鐖嗙矇";
        var url = '/feedback/add';

        if(!content || content == '鍙嶉鍐呭'){

            $("#content_tips").css({display:'block'});
            return false;
        }

        $('#toggle').css('display', 'none');
        $('#content_tips').css('display', 'none');
        $("#feedback_content_box").slideUp(600);
        $("#feedback_content_box").animate({ top: 20 }, 600);


        if( !contact || contact == 'Email/QQ/寰俊/鐢佃瘽' ){
            contact = '鍖垮悕';
        }

        $.post(url,{contact:contact,content:content,app_key:app_key,app_name:app_name},function(msg){
            if(msg){
                $('#for_tips,textarea,#btnFk,#submit').css({display:'none'})
            }else{
                $('.feedback_tip').text('鎻愪氦澶辫触锛岃 <a href="/site/contact">鍚愭Ы</a>');
            }
            $('.feedback_tip').css({display:'block'})
        },'json')
    });
})