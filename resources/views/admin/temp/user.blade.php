@extends('admin.app')
@section('title', '用户列表')
@section('content')
	<blockquote class="layui-elem-quote news_search">
		<div class="layui-inline">
			<form class="search_form" method="get">
				<div class="layui-input-inline">
					<input type="text" value="{{ request('search') }}" name="search" placeholder="请输入关键字" class="layui-input search_input">
				</div>
				<button class="layui-btn search_btn">查询</button>
			</form>
		</div>
		{{--<div class="layui-inline">
			<a class="layui-btn linksAdd_btn" style="background-color:#5FB878">添加链接</a>
		</div>
		<div class="layui-inline">
			<a class="layui-btn layui-btn-danger batchDel">批量删除</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux">本页面刷新后除新添加的链接外所有操作无效，关闭页面所有数据重置</div>
		</div>--}}
	</blockquote>
	<div class="layui-form links_list">
	  	<table class="layui-table">
		    <colgroup>
				<col width="50">
				{{--<col width="80">--}}
				<col>
				<col width="13%">
		    </colgroup>
		    <thead>
				<tr>
					<th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose" id="allChoose"></th>
					{{--<th>序号</th>--}}
					<th>URL</th>
					<th>操作</th>
				</tr> 
		    </thead>
		    <tbody class="links_content">
				@foreach($data as $key=>$val)
					<tr>
						<td><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose" id="allChoose"></td>
						{{--<td>{{ $key }}</td>--}}
						<td><a href="javascript:;" onclick="show_html(false,'{{ $val->url }}','320px','568px')">{{ $val->url }}</a></td>
						<td>
							<button onclick="show_html(false,'{{ $val->url }}','320px','568px')" class="layui-btn layui-btn-sm">查看</button>
							<button onclick="ajax_request('{{ url('admin/temp/user') }}/{{ $val->id }}','PATCH','',function(){layui.layer.msg('操作成功');location.reload();})" class="layui-btn layui-btn-normal layui-btn-sm">提取</button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div id="page">
		{{ $data->links() }}
	</div>

	<script type="text/javascript" src="linksList.js"></script>

@endsection