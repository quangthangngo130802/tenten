<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="white">
            <a target="_blank" href="https://sgomedia.vn/" class="logo">
                <img style="width: 80%;" src="{{ asset('backend/SGO VIET NAM (1000 x 375 px).png') }}"
                    alt="navbar brand" class="navbar-brand img-fluid" />
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
                    <a href="{{ route('dashboard') }}" class="collapsed">
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
                    <a href="{{ route('company.index') }}">
                        <i class="fas fa-cogs"></i>
                        <p>Cấu hình công ty</p>
                    </a>
                </li>

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
                                <a href="{{ route('domain.price') }}">
                                    <span class="sub-item">Tên miền</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('hosting.index') }}">
                                    <span class="sub-item">Hosting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('email.index', ['type_id' => 1]) }}">
                                    <span class="sub-item">Email Server</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('cloud.index') }}">
                                    <span class="sub-item">Cloud</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <span class="sub-item">Website</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#service_active">
                        <i class="fas fa-check-circle "></i>
                        <p>Dịch vụ được đăng ký</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="service_active">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('domain.index') }}">
                                    <span class="sub-item">Tên miền</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('service.hosting.list.hosting') }}">
                                    <span class="sub-item">Hosting</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <span class="sub-item">Email Server</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('service.cloud.list.cloud') }}">
                                    <span class="sub-item">Cloud</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#order">
                        <i class="fa fa-box"></i>
                        <p>Quản lý đơn hàng</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="order">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('order.index', ['status' => 'nopayment']) }}">
                                    <span class="sub-item">Đơn hàng chưa thanh toán</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('order.index', ['status' => 'payment']) }}">
                                    <span class="sub-item">Đơn hàng đã thanh toán</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('order.index', ['status' => 'pending']) }}">
                                    <span class="sub-item">Đơn hàng chờ cấp tài khoản</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('order.index', ['status' => 'active']) }}">
                                    <span class="sub-item">Đơn hàng đã kích hoạt</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('history.index') }}">
                        <i class="fas fa-pen-square"></i>
                        <p>Lịch sử giao dịch</p>

                    </a>
                </li> --}}
                @else
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#service_dk">
                        <i class="fas fa-pen-square"></i>
                        <p>Đăng ký dịch vụ</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="service_dk">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('customer.domain.index') }}">
                                    <span class="sub-item">Tên miền</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.hosting.index') }}">
                                    <span class="sub-item">Hosting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.email.index', ['email_type' => 1]) }}">
                                    <span class="sub-item">Email Server</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.cloud.index') }}">
                                    <span class="sub-item">Cloud</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#service_active">
                        <i class="fas fa-check-circle "></i>
                        <p>Dịch vụ được đăng ký</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="service_active">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('customer.service.list.service', ['type' => 'domain']) }}">
                                    <span class="sub-item">Tên miền</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.service.list.service', ['type' => 'hosting']) }}">
                                    <span class="sub-item">Hosting</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.service.list.service', ['type' => 'email']) }}">
                                    <span class="sub-item">Email Server</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('customer.service.list.service', ['type' => 'cloud']) }}">
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
                                <a href="{{ route('customer.order.index', ['status' => 'nopayment']) }}">
                                    <span class="sub-item">Đơn hàng chưa thanh toán</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('customer.order.index', ['status' => 'payment']) }}">
                                    <span class="sub-item">Đơn hàng đã thanh toán</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('customer.order.index', ['status' => 'active']) }}">
                                    <span class="sub-item">Đơn hàng đã kích hoạt</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('history.index') }}">
                        <i class="fa fa-eye"></i>
                        <p>Lịch sử giao dịch</p>

                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
