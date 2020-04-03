<!-- Sidebar user panel (optional) -->
<div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
        <img src="{{ asset('theme/admin-lte/dist/img/avatar.png') }}" class="img-circle elevation-2" alt="User Image">
    </div>
    <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->formatName() }}</a>
    </div>
</div>

<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link @isset($page) {{ ($page == 'dashboard') ? 'active' : '' }} @endisset">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Tableau de bord
                    {{--<span class="right badge badge-danger">New</span>--}}
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('engagement.index') }}" class="nav-link @isset($page) {{ ($page == 'engagement') ? 'active' : '' }} @endisset">
                <i class="nav-icon fas fa-clipboard-check"></i>
                <p>
                    Engagements
                    {{--<span class="right badge badge-danger">New</span>--}}
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('echelon.index') }}" class="nav-link @isset($page) {{ ($page == 'echelon') ? 'active' : '' }} @endisset">
                <i class="nav-icon fas fa-money-bill-alt"></i>
                <p>
                    Paiements
                    {{--<span class="right badge badge-danger">New</span>--}}
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('provider.index') }}" class="nav-link @isset($page) {{ ($page == 'provider') ? 'active' : '' }} @endisset">
                <i class="nav-icon fas fa-shipping-fast"></i>
                <p>
                    Fournisseurs
                    {{--<span class="right badge badge-danger">New</span>--}}
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('engagement.search') }}" class="nav-link @isset($page) {{ ($page == 'engagement.search') ? 'active' : '' }} @endisset">
                <i class="nav-icon fas fa-search"></i>
                <p>
                    Recherche Multi-critères
                    {{--<span class="right badge badge-danger">New</span>--}}
                </p>
            </a>
        </li>

        @can('isAdmin')
        <li class="nav-header">ADMINISTRATION</li>
        <!-- Users -->
        <li class="nav-item has-treeview @isset($page) {{ ($page == 'user') ? 'menu-open' : '' }} @endisset">
            <a href="#" class="nav-link @isset($page) {{ ($page == 'user') ? 'active' : '' }} @endisset">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    Utilisateurs
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link @isset($sub_page) {{ ($sub_page == 'user.list') ? 'active' : '' }} @endisset">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Liste</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.create') }}" class="nav-link @isset($sub_page) {{ ($sub_page == 'user.create') ? 'active' : '' }} @endisset">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Ajouter</p>
                    </a>
                </li>
            </ul>
        </li>
        @endcan
    </ul>
</nav>
<!-- /.sidebar-menu -->