<!--sidebar start-->
<aside>
	<div id="sidebar"  class="nav-collapse ">
		<!-- sidebar menu start-->
		<ul class="sidebar-menu" id="nav-accordion">
			
			@foreach ($menu as $m)
				@if (empty($m->child))
				<li>
					<a href="{{ $m->url }}" class="@if ($m->active) active @endif">
						<i class="{{ $m->icon }}"></i>
						<span>{{ $m->name }}</span>
					</a>
				</li>
				@else
				<li class="sub-menu">
					<a href="javascript:;" class="@if ($m->active) active @endif">
						<i class="{{ $m->icon }}"></i>
						<span>{{ $m->name }}</span>
						@if ($m->active)
						<span class="dcjq-icon"></span>
						@endif
					</a>
					<ul class="sub">
						@foreach ($m->child as $mc)
						<li class="@if ($mc->active) active @endif"><a  href="{{ $mc->url }}">{{ $mc->name}}</a></li>
						@endforeach
					</ul>
				</li>
				@endif
			@endforeach

		</ul>
		<!-- sidebar menu end-->
	</div>
</aside>
<!--sidebar end-->

