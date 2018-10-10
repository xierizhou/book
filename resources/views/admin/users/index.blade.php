@extends('admin.app')
@section('title', '用户列表')
@section('content')
	<blockquote class="layui-elem-quote news_search">
		<div class="layui-inline">
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="请输入关键字" class="layui-input search_input">
		    </div>
		    <a class="layui-btn search_btn">查询</a>
		</div>
		<div class="layui-inline">
			<a class="layui-btn linksAdd_btn" style="background-color:#5FB878">添加</a>
		</div>
		<div class="layui-inline">
			<a class="layui-btn layui-btn-danger batchDel">批量删除</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux">本页面刷新后除新添加的链接外所有操作无效，关闭页面所有数据重置</div>
		</div>
	</blockquote>
	<div class="layui-form links_list">
	  	<table class="layui-table">
		    <colgroup>
				<col width="50">
				<col>
				<col>
				<col>
				<col>
				<col>
				<col>
				<col>
				<col>
		    </colgroup>
		    <thead>
				<tr>
					{{--<th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose" id="allChoose"></th>--}}
					<th>序号</th>
					<th>账号</th>
					<th>昵称</th>
					<th>头像</th>
					<th>签名</th>
					<th>位置</th>
					<th>星座</th>
					<th>粉丝数量</th>
					<th>操作</th>
				</tr> 
		    </thead>
		    <tbody class="links_content">
				@foreach($data as $key=>$item)
					<tr>
						{{--<th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose" id="allChoose"></th>--}}
						<td>{{ $key }}</td>
						<td>{{ $item->dy_number }}</td>
						<td>{{ $item->nickname }}</td>
						<td><img width="30px" src="{{ $item->avatar }}"></td>
						<td>{{ $item->short_introduce }}</td>
						<td>{{ $item->position }}</td>
						<td>{{ $item->constellation }}</td>
						<td>{{ $item->fans_count }}</td>
						<td>
							<button onclick="" class="layui-btn layui-btn-sm">视频</button>
							<button onclick="ajax_request('{{ url('admin/user') }}/{{ $item->id }}','PATCH','',function(res){$('body').append(res),layer.msg('操作成功')})" class="layui-btn layui-btn-normal layui-btn-sm">更新</button>
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