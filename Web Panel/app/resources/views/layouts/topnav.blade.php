<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="#" class="b-brand text-primary">
                <!-- ========   Change your logo from here   ============ -->
                <img src="/assets/images/xlogo.png" alt="Xpanel" style="width:50px"/>
                <span class="badge bg-light-success rounded-pill ms-2 theme-version" style="font-size: 13px;"></span>
            </a>
        </div>
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="/assets/images/user/avatar-1.jpg" alt="user-image" class="user-avtar wid-45 rounded-circle" />
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">Admin</h6>
                        </div>
                        <a class="btn btn-icon btn-link-secondary avtar" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sort-outline"></use>
                            </svg>
                        </a>
                    </div>
                    <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                        <div class="pt-3">
                            <a href="{{route('setting')}}">
                                <i class="ti ti-settings"></i>
                                <span>Settings</span>
                            </a>
                            <a href="{{route('user.logout')}}">
                                <i class="ti ti-power"></i>
                                <span>Logut</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>SSH Direct + TLS</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item">
                    <a href="{{route('dashboard')}}" class="pc-link">
                        <i data-feather="airplay"></i>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="pc-item">
                    <a href="{{route('users')}}" class="pc-link">
                        <i data-feather="users"></i>
                        <span class="pc-mtext">Users</span>
                    </a>
                </li>

                <li class="pc-item">
                    <a href="{{route('online')}}" class="pc-link">
            <span class="pc-micon">
              <svg class="pc-icon">
                <use xlink:href="#custom-story"></use>
              </svg>
            </span>
                        <span class="pc-mtext">Online User</span>
                    </a>
                </li>
                <li class="pc-item pc-caption">
                    <label>Other More</label>
                    <i class="ti ti-chart-arcs"></i>
                </li>
                <li class="pc-item">
                    <a href="{{route('filtering')}}" class="pc-link">
                        <i data-feather="target"></i>
                        <span class="pc-mtext">Filtering Status</span>
                    </a>
                </li>
                    <li class="pc-item">
                    <a href="{{route('admins')}}" class="pc-link">
                        <i data-feather="users"></i>
                        <span class="pc-mtext">Managers</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
<!-- [ Header Topbar ] start -->
<header class="pc-header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
        </div>

       
    </div>
</header>
<!-- [ Header ] end -->
