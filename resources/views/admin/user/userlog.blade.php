@extends('layouts.admin_common')
@section('right-box')

    <script src="{{ asset('admin/lib/ueditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('admin/lib/ueditor/ueditor.all.min.js') }}"> </script>
    <style>
        table tr{
            margin-top:5px;
        }
    </style>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="height:800px;overflow-y: scroll;padding-bottom:100px;">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header">用户详情</h1>

                <table class="table table-striped table-bordered" style="width:400px;">
                    <tr>
                        <td style="width:120px;">用户编号：</td>
                        <td><input type="text" value="{{ $userinfo -> uid }}" class="form-control" disabled/></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">微信昵称：</td>
                        <td><input type="text" value="{{ $userinfo -> nickname }}" class="form-control" disabled/></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">点数</td>
                        <td><input type="text" value="{{ $userinfo -> point }}" class="form-control" disabled/></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">邀请码：</td>
                        <td><input type="text" value="{{ $userinfo -> code }}" class="form-control" disabled/></td>
                    </tr>
                    <tr>
                        <td style="width:120px;">注册时间：</td>
                        <td><input type="text" value="{{ date('Y-m-d H:i',$userinfo -> created_at) }}" class="form-control" disabled/></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <h1 class="page-header">充值记录</h1>

                <table class="table table-striped table-bordered" style="width:400px;">
                    @if($chongzhi)
                        @foreach($chongzhi as $vo)
                    <tr>
                        <td >{{ date('Y-m-d H:i:s',$vo -> created_at) }}</td>
                        <td><input type="text" value="{{ $vo -> point }}" class="form-control" disabled/></td>
                    </tr>
                        @endforeach
                    @endif
                </table>
            </div>

            <div class="col-md-12">
                <h1 class="page-header">投注记录</h1>

                <table class="table table-striped table-bordered" style="width:400px;">
                    @if($touzhu)
                        @foreach($touzhu as $vo)
                            <tr>
                                <td >{{ date('Y-m-d H:i:s',$vo -> created_at) }}</td>
                                <td><input type="text" value="{{ $vo -> point }}" class="form-control" disabled/></td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>

            <div class="col-md-12">
                <h1 class="page-header">代理记录</h1>

                <table class="table table-striped table-bordered" style="width:400px;">
                    @if($daili)
                        @foreach($daili as $vo)
                            <tr>
                                <td >{{ date('Y-m-d H:i:s',$vo -> created_at) }}</td>
                                <td><input type="text" value="{{ $vo -> point }}" class="form-control" disabled/></td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>




        </div>
    </div>


@stop