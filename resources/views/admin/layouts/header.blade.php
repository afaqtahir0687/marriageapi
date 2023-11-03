<div class="page-header">
    <nav class="navbar navbar-expand-lg d-flex justify-content-between">
        <div class="" id="navbarNav">
        <ul class="navbar-nav" id="leftNav">
            <li class="nav-item">
                <a class="nav-link" id="sidebar-toggle" href="{{ url()->previous() }}"><i data-feather="arrow-left"></i></a>
            </li>
        </ul>
        </div>
        <div class="logo">
            <a class="navbar-brand" href=""></a>
        </div>
        <div class="" id="headerNav">
            <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link search-dropdown" href="#" id="searchDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i data-feather="search"></i></a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg search-drop-menu" aria-labelledby="searchDropDown">
                    <form>
                        <input class="form-control" type="text" placeholder="Type something.." aria-label="Search">
                    </form>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link notifications-dropdown" href="#" id="notificationsDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false">0</a>
                <div class="dropdown-menu dropdown-menu-end notif-drop-menu" aria-labelledby="notificationsDropDown">
                    <h6 class="dropdown-header">Notifications</h6>
                    <a href="#">
                        <div class="header-notif">
                            <div class="notif-text">
                                <p class="bold-notif-text">Not Found</p>
                            </div>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link profile-dropdown" href="#" id="profileDropDown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img src="{{ url('assets/images/avatars/profile-image.png') }}" alt=""></a>
                <div class="dropdown-menu dropdown-menu-end profile-drop-menu" aria-labelledby="profileDropDown">
                <a class="dropdown-item" href="{{ url('profile') }}"><i data-feather="user"></i>Profile</a>
                <a class="dropdown-item" href="#"><i data-feather="settings"></i>Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ url('logout') }}"><i data-feather="log-out"></i>Logout</a>
                </div>
            </li>
            </ul>
        </div>
    </nav>
</div>