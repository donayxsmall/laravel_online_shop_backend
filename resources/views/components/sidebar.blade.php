<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            {{-- <a href="index.html">Stisla</a> --}}
            <img src="{{ asset('img/pet-logo.png') }}" alt="logo" width="60">
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            {{-- <a href="index.html">MENU</a> --}}
            <img src="{{ asset('img/pet-logo.png') }}" alt="logo" width="50">
        </div>
        <ul id="sidebarMenu" class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li>
                <a class="nav-link" href="{{ url('/') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>


            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Users</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ route('user.index') }}">All User</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Category</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ route('category.index') }}">All Category</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Product</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ route('product.index') }}">All Product</a>
                    </li>
                </ul>
            </li>

        </ul>
    </aside>
</div>


@push('scripts')
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const currentUrl = window.location.href;
            const menuItems = document.querySelectorAll("#sidebarMenu li");

            console.log('currentUrl : ' + JSON.stringify(menuItems));

            menuItems.forEach((item) => {
                const link = item.querySelector("a");
                if (link && link.href === currentUrl) {
                    item.classList.add("active");
                }
            });
        });
    </script> --}}


    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const currentUrl = window.location.href;

            console.log("Currenturl: " + currentUrl);

            const menuItems = document.querySelectorAll("#sidebarMenu li");

            console.log("menuiItems : " + JSON.stringify(menuItems));


            menuItems.forEach((item) => {
                console.log("item " + JSON.stringify(item));
                // const link = item.querySelector("a");
                // console.log()
                // if (link && (link.href === currentUrl || link.closest('a').href === currentUrl)) {
                //     item.classList.add("active");
                // }
            });
        });
    </script> --}}
@endpush
