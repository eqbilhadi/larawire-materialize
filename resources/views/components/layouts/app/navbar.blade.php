<nav
    class="layout-navbar container-fluid navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a
            class="nav-item nav-link px-0 me-xl-6"
            href="javascript:void(0)">
            <i
                class="icon-base ri ri-menu-line icon-22px"></i>
        </a>
    </div>

    <div
        class="navbar-nav-right d-flex align-items-center justify-content-end"
        id="navbar-collapse"
    >
        <ul
            class="navbar-nav flex-row align-items-center ms-md-auto">
            <!-- User -->
            <li
                class="nav-item navbar-dropdown dropdown-user dropdown">
                <a
                    class="nav-link dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                >
                    <x-ui.avatar :user="auth()->user()" size="2.5" />
                </a>
                <ul
                    class="dropdown-menu dropdown-menu-end mt-3 py-2">
                    <li>
                        <a
                            class="dropdown-item"
                            href="pages-account-settings-account.html">
                            <div
                                class="d-flex align-items-center"
                            >
                                <div class="flex-shrink-0 me-2">
                                    <x-ui.avatar :user="auth()->user()" size="2.5" />
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small">{{ auth()->user()->name }}</h6>
                                    <small class="text-body-secondary">{{ auth()->user()->main_role }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a
                            class="dropdown-item"
                            href="pages-profile-user.html"
                        >
                            <i class="icon-base ri ri-user-3-line icon-22px me-3"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <livewire:layout.logout />
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
