<div class="sidebar">
    <div class="sidebar-header">
        <h3>Rental PS</h3>
    </div>
    <ul class="sidebar-menu">
        <li class="{{ Request::routeIs('home.admin') ? 'active' : '' }}">
            <a href="{{ route('home.admin') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        </li>
        <li class="{{ Request::routeIs('service.page') ? 'active' : '' }}">
            <a href="{{ route('service.page') }}"><i class="fas fa-gamepad me-2"></i> Layanan</a>
        </li>
        <li class="{{ Request::routeIs('booking.admin.page') ? 'active' : '' }}">
            <a href="{{ route('booking.admin.page') }}"><i class="fas fa-calendar-check me-2"></i> Booking</a>
        </li>
        <li>
            <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </li>
    </ul>
</div>