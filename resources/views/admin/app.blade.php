<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    @section('meta')
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="_token" content="{{ csrf_token() }}"/>
    @show
    @section('style')
    <link rel="stylesheet" href="{{ asset('layui-admin/layui/css/layui.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('layui-admin/css/font_eolqem241z66flxr.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('layui-admin/css/news.css') }}" media="all" />
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @show
    @section('script')
    <script type="text/javascript" src="{{ asset('layui-admin/layui/layui.all.js') }}"></script>
        <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    @show

</head>
<body class="childrenBody">

    @yield('content')


</body>

<script>


    function show_html(title,href,w,h){

        layui.use(['layer', 'form'], function(){
            var layer = layui.layer
                ,form = layui.form;
            w=w||'100%';
            h=h||'100%';
            var index = layer.open({
                title : title,
                type : 2,
                area: [w,h],
                content : href,
                success : function(layero, index){
                    /*layer.tips('点击此处返回', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });*/
                }
            })

        });

        //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
        /*/!*$(window).resize(function(){
            layui.layer.full(index);
        })
        layui.layer.full(index);*!/*/
    }

    function ajax_request(url,method,data,callback,error_callback){
        if(method == 'GET' || method == 'POST'){
            var temp_method = method;
        }else{
            var temp_method = "POST";
            if(!data){
                var data = {_method:method};
            }



        }

        $.ajax({
            type: temp_method,
            url:url,
            data:data,
            dataType:"text",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            error:function(data){
                if(error_callback){
                    error_callback(data);
                }else{
                    layui.layer.close(index);
                    for(var tmp in data.responseJSON.errors){

                        layui.layer.msg(data.responseJSON.errors[tmp][0], {icon: 2});



                    }
                }
                return false;

            },
            success:function(data){

                if(callback){
                    callback(data);
                }else{
                    layui.layer.close(index);
                    if(data.code == 200){
                        layui.layer.msg(data.msg);
                        layui.layer.closeAll("iframe");
                        //刷新父页面
                        parent.location.reload();
                    }
                }


            }
        });
    }


</script>

</html>
