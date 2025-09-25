<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/images/icon/logopt.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light" style="color: gold;">IndoPinetMart</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="img-circle elevation-2"
                        alt="User Image" style="width:35px; height:35px; object-fit:cover;">
                @else
                    <img src="{{ Avatar::create(Auth::user()->name)->setBackground('#1B0047FF')->setForeground('#FFFFFF')->toBase64() }}"
                        class="img-circle elevation-2" alt="User Avatar"
                        style="width:35px; height:35px; object-fit:cover;">
                @endif
            </div>
            <div class="info ms-2">
                <a href="#" class="d-block" style="color: gold; font-weight: bold;">
                    {{ Auth::user()->name }}
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            @php
                $menus = auth()
                    ->user()
                    ->role->menus()
                    ->whereNull('parent_id')
                    ->with([
                        'children' => function ($q) {
                            $q->whereHas('roles', function ($q2) {
                                $q2->where('roles.id', auth()->user()->role_id);
                            })->orderBy('id', 'asc');
                        },
                    ])
                    ->orderBy('id', 'asc')
                    ->get();
            @endphp
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @foreach ($menus as $menu)
                    @php
                        $hasChildren = $menu->children->count() > 0;
                    @endphp

                    <li class="nav-item {{ $hasChildren ? 'has-treeview' : '' }}">
                        <a href="{{ $hasChildren ? '#' : url($menu->route) }}" class="nav-link"
                            @if (!$hasChildren) data-widget="iframe"
                       data-type="iframe"
                       data-title="{{ $menu->name }}" @endif>
                            <i class="nav-icon {{ $menu->icon ?? 'fas fa-circle' }}"></i>
                            <p>
                                {{ $menu->name }}
                                @if ($hasChildren)
                                    <i class="right fas fa-angle-left"></i>
                                @endif
                            </p>
                        </a>

                        @if ($hasChildren)
                            <ul class="nav nav-treeview">
                                @foreach ($menu->children as $child)
                                    <li class="nav-item">
                                        <a href="{{ url($child->route) }}" class="nav-link" data-widget="iframe"
                                            data-type="iframe" data-title="{{ $child->name }}">
                                            <i class="{{ $child->icon ?? 'far fa-circle' }} nav-icon"></i>
                                            <p>{{ $child->name }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
