@extends('layouts.admin_common')
@section('right-box')
    <style>
        #mytable tr td{
            border:1px solid #000000;
        }
    </style>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="max-height:800px;overflow: scroll;" >


        <form method="post">
            <table class="table" style="width:50%;">
                <tr>
                    <td>会员号：</td>
                    <td>
                        <input type="text" name="userid"  class="form-control" value="@if(!empty($_POST['userid'])){{ $_POST['userid'] }}@endif"/>
                    </td>

                    <td>微信名称</td>
                    <td>
                        <input type="text" name="nickname"  class="form-control" value="@if(!empty($_POST['nickname'])){{ $_POST['nickname'] }}@endif" />
                    </td>



                </tr>

                <tr>
                    <td colspan="4">
                        <button class="btn btn-default" type="submit">搜索</button>
                        <button class="btn btn-default" type="button" onclick="location.href='{{Request::getRequestUri()}}' ">重置</button>
                    </td>
                </tr>
            </table>
            {{ csrf_field() }}
        </form>
        <ol class="breadcrumb">
            <li>总用户：{{ $count }}</li>
        </ol>



        <h1 class="page-header">用户信息 <span class="badge"></span></h1>
        <div class="table-responsive"  >
            <table class="table table-striped table-hover" id="mytable">
                <thead>
                <tr>
                    <th><span class="glyphicon glyphicon-th-large"></span> <span class="visible-lg">用户ID</span></th>
                    <th><span class="glyphicon glyphicon-user"></span> <span class="visible-lg" >微信名称</span></th>
                    <th><span class="glyphicon glyphicon-signal"></span> <span class="visible-lg">充值点数</span></th>
                    <th><span class="glyphicon glyphicon-camera"></span> <span class="visible-lg">邀请码</span></th>
                    <th><span class="glyphicon glyphicon-camera"></span> <span class="visible-lg">提现验证码</span></th>

                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">注册时间</span></th>
                    <th><span class="glyphicon glyphicon-time"></span> <span class="visible-lg">操作</span></th>

                </tr>
                </thead>
                <tbody>
                @unless(!$res)
                    @foreach($res as $k => $vo)
                        <tr>
                            <td>{{ $vo -> uid}}</td>
                            <td>{{$vo -> nickname }}</td>
                            <td>{{$vo -> point }}</td>
                            <td>{{$vo -> code}}</td>
                            <td>{{$vo -> yanzhengma}}</td>
                            <td>{{ date('Y-m-d H:i',$vo -> created_at) }}</td>
                            <td><a class="duihuan" nickname="{{$vo -> nickname}}" point = "{{$vo -> point}}" openid = "{{ $vo -> openid }}" >兑换</a> <a href="{{ url('admin/userlog').'/'.$vo -> id }}">查看记录</a></td>
                        </tr>
                    @endforeach
                @endunless
                </tbody>
                @if(count($res))
                <tfoot>
                    <tr>

                        <td colspan="6">{{ $res -> links() }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    <!-- 充值 -->
    <div class="modal fade " id="duihuan_box" tabindex="-1" role="dialog"  >
        <div class="modal-dialog" role="document" style="width:900px;">
            <form action="{{ url('admin/duihuan') }}" method="post" autocomplete="off" draggable="false" id="myForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" >兑换</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table" style="margin-bottom:0px;">
                            <thead>
                            <tr> </tr>
                            </thead>
                            <tbody>
                            <input type="hidden" name="openid" id="openid" />
                            <tr>
                                <td wdith="10%">微信名:</td>
                                <td width="90%">
                                    <input type="text" value="" class="form-control" disabled id="nickname"/>
                                </td>
                            </tr>

                            <tr>
                                <td wdith="10%">兑换点数:</td>
                                <td width="90%">
                                    <input type="number" value="" name="point" id="duihuan_point" class="form-control" maxlength=""  required/>
                                </td>
                            </tr>

                            {{ csrf_field() }}
                            </tbody>
                            <tfoot>
                            <tr></tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-primary">确认</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <script>

        $('.duihuan').click(function(){
            $('#duihuan_point').val($(this).attr('point'));
            $('#nickname').val($(this).attr('nickname'));
            $('#openid').val($(this).attr('openid'));

            $('#duihuan_point').attr('max',$(this).attr('point'));
            $('#duihuan_box').modal('show');
        })
    </script>
    <script>
        $(function(){
            //数据验证
            $('#myForm').submit(function(){
                return true;
            })

            @if (session('recharge_true'))
                $('#recharge_true').modal('show')
            @endif

            @if(session('duihuan'))
            alert('兑换成功');
            @endif
            @if (session('isset'))
                alert('{{ session('isset') }}');
            @endif

            @if (session('delete_number') && session('delete_number') == 'success')
                alert('删除成功');
            @endif

            @if (session('delete_number') && session('delete_number') == 'error')
                alert('该账号挂机信息已经变动，请刷新页面后重试！');
            @endif

            //删除数据
            $('.delete_number').click(function(){
                var id  = $(this).attr('data');
                if(confirm('您确定要删除么')){
                    location.href="{{ url('manage/delete_number') }}"+'/'+id;
                }
            })




        })

        function tinggua (sh){
            $('#number_id').val($(sh).attr('number'));
            //将剩余挂机次数显示
            $('#numbertime').text($(sh).attr('data'));
            $('#stopnumber').modal('show');
        }

    </script>
@stop