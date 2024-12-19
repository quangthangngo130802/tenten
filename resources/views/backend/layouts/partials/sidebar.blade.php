<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="white">
            <a target="_blank" style="width: 90%;" href="https://sgomedia.vn/" class="logo">
                <img src="{{ asset('backend/SGO VIET NAM (1000 x 375 px).png') }}" alt="navbar brand"
                    class="navbar-brand img-fluid" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item active">
                    <a href="" class="collapsed">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>
                @if (Auth::user()->role_id == 1)
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}">
                            <i class="fas fa-sign"></i>
                            <p>Quản lý tài khoản</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('client.index') }}">
                            <i class="fas fa-sign"></i>
                            <p>Quản lý khách hàng</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#service">
                            <i class="fas fa-pen-square"></i>
                            <p>Quản lý dịch vụ</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="service">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="">
                                        <span class="sub-item">Tên miền</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="sub-item">Hosting</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="sub-item">Email Server</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="sub-item">Cloud</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#order">
                            <i class="fas fa-pen-square"></i>
                            <p>Quản lý đơn hàng</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="order">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('order.index', ['status' => 'payment']) }}">
                                        <span class="sub-item">Đơn hàng đã thanh toán</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('order.index', ['status' => 'nopayment']) }}">
                                        <span class="sub-item">Đơn hàng chưa thanh toán</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('order.index', ['status' => 'unactive']) }}">
                                        <span class="sub-item">Đơn hàng chờ kích hoạt</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#product">
                            <i class="fas fa-pen-square"></i>
                            <p>Sản phẩm</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="product">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="">
                                        <span class="sub-item">Danh sách sản phẩm</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="sub-item">Danh mục sản phẩm</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="sub-item">Xuất xứ</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        <span class="sub-item">Nhiên liệu</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
