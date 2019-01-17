@extends('layouts.'.getTheme())
@section('css')
    <link href="{{ asset(getThemeAssets('dataTables/datatables.min.css', true)) }}" rel="stylesheet">
    <style type="text/css">
        .import-file {
            display: none !important;
        }
    </style>
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{!! trans('order.title') !!}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="javascript:void(0);">{!! trans('order.title') !!}</a>
                </li>
                <li class="active">
                    <strong>{!! trans('order.orderList') !!}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">
            <div class="title-action">
                @if(haspermission('usercontroller.create'))
                    <a href="{{ route('order.create') }}" class="btn btn-info">
                        <i class="fa fa-plus"></i> {!! trans('common.create').trans('order.slug') !!}
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{!! trans('order.title') !!}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        @include('flash::message')

                        <div id="dataTableBuilder_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <form action="">
                                        <div class="dataTables_length" id="dataTableBuilder_length">
                                            <label>
                                                搜索:
                                                <input type="search" class="form-control input-sm search-input"
                                                       name="search"
                                                       aria-controls="dataTableBuilder" value="{{ $search or '' }}">
                                            </label>

                                            <button class="btn btn-sm btn-primary">确定</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-6">
                                    <form action="{{ route('order.import.file') }}" method="post"
                                          enctype="multipart/form-data"
                                          class="import-form col-sm-3 import-form">

                                        <input type="file" name="import_file" class="import-file"/>
                                        {{ csrf_field() }}

                                        <button class="btn btn-sm btn-success import-btn" type="button">
                                            {!! trans('order.import') !!}{!! trans('order.slug') !!}
                                        </button>
                                    </form>
                                    <form action="{{ route('order.export.file') }}" method="post">
                                        <input type="hidden" name="serch" class="input-serche-hidden">
                                        {{ csrf_field() }}
                                        <button class="btn btn-sm btn-success" type="submit">
                                            {!! trans('order.export') !!}{!! trans('order.slug') !!}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer"
                                           id="dataTableBuilder" role="grid" aria-describedby="dataTableBuilder_info">
                                        <thead>
                                        <tr role="row">
                                            <th>序号</th>
                                            <th>著作权人</th>
                                            <th>流水号</th>
                                            <th>软件名称</th>
                                            <th>交件日期</th>
                                            <th>工作日</th>
                                            <th>出证日期</th>
                                            <th>价格</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($data as $item)
                                            <tr role="row" class="odd">
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->copyright_figure }}</td>
                                                <td>{{ $item->serial_number }}</td>
                                                <td>{{ $item->software_name }}</td>
                                                <td>{{ $item->deliveried_at }}</td>
                                                <td>{{ $item->work_hours }}</td>
                                                <td>{{ $item->out_at ? $item->out_at : '暂未出证' }}</td>
                                                <td>{{ $item->price }}</td>
                                                <td>
                                                    <a href="{{ route('order.show',['id'=>encodeId($item->id)]) }}"
                                                       class="btn btn-xs btn-info tooltips">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('order.edit',['id'=>encodeId($item->id)]) }}"
                                                       class="btn btn-xs btn-outline btn-warning tooltips">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <a href="javascript:;"
                                                       delete-url="{{ route('order.destroy',['id'=>encodeId($item->id)]) }}"
                                                       class="btn btn-xs btn-outline btn-danger tooltips destroy_item">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_paginate paging_simple_numbers"
                                         id="dataTableBuilder_paginate">
                                        {!! $data->appends(['search'=>$search])->links() !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset(getThemeAssets('dataTables/datatables.min.js', true))}}"></script>
    <script src="{{asset(getThemeAssets('layer/layer.js', true))}}"></script>
    <script type="text/javascript">
        $(document).on('click', '.destroy_item', function () {
            var _item = $(this);
            var title = "{{trans('common.deleteTitle').trans('order.slug')}}？";
            layer.confirm(title, {
                btn: ['{{trans('common.yes')}}', '{{trans('common.no')}}'],
                icon: 3
            }, function (index) {
                let url = _item.attr('delete-url');
                $.ajax({
                    url: url,
                    data: {'_token': "{{ csrf_token() }}",},
                    dataType: 'json',
                    type: 'DELETE',
                    success: function (e) {
                        if (e.error_code === 0) {
                            location.reload(true);
                            return false;
                        }
                        layer.alert(e.msg, {
                            icon: 2
                        });
                    }
                });
            });
        });

        $(document).on('click', '.import-btn', function () {
            $('.import-file').click();
        });

        $(document).on('change', '.import-file', function () {
            $('.import-form').submit();
        });

        $(document).on('change', '.search-input', function () {
            $('.input-serche-hidden').val($(this).val());
        });
    </script>
@endsection