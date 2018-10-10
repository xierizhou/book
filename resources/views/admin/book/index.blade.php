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
		{{--<div class="layui-inline">
			<a class="layui-btn layui-btn-danger batchDel">批量删除</a>
		</div>--}}
		{{--<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux">本页面刷新后除新添加的链接外所有操作无效，关闭页面所有数据重置</div>
		</div>--}}
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
				<col width="80">
				<col width="80">
				<col width="100">
				<col>
		    </colgroup>
		    <thead>
				<tr>
					{{--<th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose" id="allChoose"></th>--}}
					<th>序号</th>
					<th>书名</th>
					<th>分类</th>
					<th>封面图</th>
					<th>作者</th>
					<th>最新章节</th>
					<th>阅读数</th>
					<th>收藏数</th>
					<th>状态</th>
					<th>操作</th>
				</tr> 
		    </thead>
		    <tbody class="links_content">
				@foreach($data as $key=>$item)
					<tr>
						{{--<th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose" id="allChoose"></th>--}}
						<td>{{ $key+1 }}</td>
						<td>{{ $item->title }}</td>
						<td>{{ $item->cate->name }}</td>
						<td><img width="30px" src="{{ $item->cover }}"></td>
						<td>{{ $item->author }}</td>
						<td>{{ $item->new_chapter }}</td>
						<td>{{ $item->reading_volume }}</td>
						<td>{{ $item->collect_volume }}</td>
						<td>{{ $item->status }}</td>
						<td>
							<button onclick="" class="layui-btn layui-btn-sm">章节</button>
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