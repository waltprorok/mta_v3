<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle {{ Route::currentRouteName() == 'message.inbox' ? 'active' : '' }}">
        <i class="fa fa-envelope"></i> Messages <i class="fa fa-caret-left"></i>
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a href="{{ route('message.inbox') }}" class="nav-link">
                <i class="fa fa-inbox"></i> Inbox
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('message.create') }}" class="nav-link">
                <i class="fa fa-pencil"></i> Create
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('message.sent') }}" class="nav-link">
                <i class="fa fa-paper-plane"></i> Sent
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('message.deleted') }}" class="nav-link">
                <i class="fa fa-trash"></i> Deleted
            </a>
        </li>
    </ul>
</li>
