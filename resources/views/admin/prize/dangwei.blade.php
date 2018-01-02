@extends('layouts.admin_common')
@section('right-box')

    <style>
        table tr{
            margin-top:5px;
        }
        .laydate_box, .laydate_box * {
            box-sizing:content-box;
        }
    </style>
    <script src="{{ asset('js/laydate/laydate.js') }}"></script>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-lg-10 col-md-offset-2 main" id="main" style="height:800px;overflow-y: scroll;padding-bottom:100px;">
        <div class="row">
                <div class="col-md-12">
                    <form method="post">
                        <table class="table" style="width:800px;" >
                            <tr>
                                <td colspan="4">
                                    <label><input type="radio" name="dangwei" value="1" @if($dangwei == 1)checked @endif/>一档 正常算法计算（稳赚）</label>
                                    <label><input type="radio" name="dangwei" value="2" @if($dangwei == 2)checked @endif/>二档 反转算法（稳赔）</label>
                                    <label><input type="radio" name="dangwei" value="3" @if($dangwei == 3)checked @endif/>三档 完全随机</label>
                                    <label><input type="radio" name="dangwei" value="3" @if($dangwei == 4)checked @endif/>四档 合不吃大小</label>
                                    <!--
                                    <label><input type="radio" name="dangwei" value="4" @if($dangwei == 4)checked @endif/>四档 开大</label>
                                    <label><input type="radio" name="dangwei" value="5" @if($dangwei == 5)checked @endif/>五档 开小</label>
                                    -->
                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td colspan="4">
                                    <label><input type="radio" name="dangwei" value="1" @if($dangwei == 1)checked @endif/>一档-正常算法计算（稳赚）</label>
                                </td>
                            </tr>
                            -->
                            <tr>
                                <td colspan="4">
                                    <button class="btn btn-default" type="submit">保存</button>
                                </td>
                            </tr>
                        </table>
                        {{ csrf_field() }}
                    </form>


                </div>


        </div>
    </div>
    <script>

    </script>


@stop