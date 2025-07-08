<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}

</form>

<a href="{{ route('logout') }}" class="dropdown-item user-info-item"
    onclick= "event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="fa-solid fa-night-from-bracket"></i>
    Logout
</a>