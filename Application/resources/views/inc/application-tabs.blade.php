<ul class="nav nav-tabs">
    <li class="@yield('tab-pending-active') applicationTabs">
        <a href="/applications" style="text-align: center">
            Pending Applications
        </a>
    </li>
    <li class="@yield('tab-accepted-active') applicationTabs">
        <a href="/applications/{{$hr_id}}/accepted" style="text-align: center">
            Processing Applications
        </a>
    </li>
</ul>