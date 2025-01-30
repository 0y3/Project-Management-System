<!-- Main sidebar -->
{{-- {{dd($sidebarMenus)}} --}}
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="#"><img src="{{ asset('images/default.jpg') }}" width="38" height="38" class="rounded-circle profileImage" alt=""></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            {{-- <i class="icon-pin font-size-sm"></i> &nbsp;Santa Ana, CA --}}Active
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i>
                </li>
                 <li class="nav-item">
                    <a href="{{ route('task.index') }}" class="nav-link active">
                        <i class="icon-home4"></i>
                        <span>
                            My Task
                            {{-- <span class="d-block font-weight-normal opacity-50">No active orders</span> --}}
                        </span>
                    </a>
                </li>
                @if ($sidebarMenus)
                @foreach($sidebarMenus as $sidebarMenu)
                @if (array_intersect(Auth::user()->roles()->pluck('name')->toArray(), array_column($sidebarMenu['roles'], "name")))
                <li class="nav-item {{ ( !empty($sidebarMenu['children']) )&&( count($sidebarMenu['children']) > 0 ) ?'nav-item-submenu' :''  }}">
                    <a href="{{ empty($sidebarMenu['children']) ?($sidebarMenu['route'] != '#') ?route($sidebarMenu['route']) :$sidebarMenu['route'] :'#' }}" class="nav-link"><i class=" icon-menu3"></i> <span>{{ $sidebarMenu['name'] }}</span></a>
                    @if (!empty($sidebarMenu['children']))
                    @if (count($sidebarMenu['children']) > 0)
                    <ul class="nav nav-group-sub" data-submenu-title="#">
                        @foreach ($sidebarMenu['children'] as $children)
                        @if (array_intersect(Auth::user()->roles()->pluck('name')->toArray(), array_column($children['roles'], "name")))
                        <li class="nav-item"><a href="{{ route($children['route']) }}" class="nav-link">{{ $children['name'] }}</a></li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                    @endif
                </li>
                @endif
                @endforeach
                @endif
            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /main sidebar -->
