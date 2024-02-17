<li class="nav-item nav-dropdown">
    <a href="#messages" class="nav-link nav-dropdown-toggle">
        <i class="fa fa-envelope"></i> Messages <i class="fa fa-caret-left"></i>
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a href="{{ route('message.inbox') }}" class="nav-link {{ Route::currentRouteName() == 'message.inbox' ? 'active' : '' }}">
                <i class="fa fa-inbox ml-3"></i> Inbox
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('message.create') }}" class="nav-link {{ Route::currentRouteName() == 'message.create' ? 'active' : '' }}">
                <i class="fa fa-pencil ml-3"></i> Create
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('message.sent') }}" class="nav-link {{ Route::currentRouteName() == 'message.sent' ? 'active' : '' }}">
                <i class="fa fa-paper-plane ml-3"></i> Sent
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('message.deleted') }}" class="nav-link {{ Route::currentRouteName() == 'message.deleted' ? 'active' : '' }}">
                <i class="fa fa-trash ml-3"></i> Deleted
            </a>
        </li>
    </ul>
</li>
