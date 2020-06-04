<ul class="nav nav-tabs" id="accountTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'account.profile' ? 'active' : '' }}" id="profile"
           href="{{ route('account.profile' ) }}">Profile</a>
    </li>
    @if (Route::currentRouteName() == 'account.subscription')
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'account.subscription' ? 'active' : '' }}" id="subscription"
               href="{{ route('account.subscription' ) }}">Subscription</a>
        </li>
    @elseif (Route::currentRouteName() == 'subscription.invoices')
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'subscription.invoices' ? 'active' : '' }}" id="subscription"
               href="{{ route('account.subscription' ) }}">Subscription</a>
        </li>
    @elseif (Route::currentRouteName() == 'subscription.card')
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'subscription.card' ? 'active' : '' }}" id="subscription"
               href="{{ route('account.subscription' ) }}">Subscription</a>
        </li>
    @elseif (Route::currentRouteName() == 'subscription.cancel')
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'subscription.cancel' ? 'active' : '' }}" id="subscription"
               href="{{ route('account.subscription' ) }}">Subscription</a>
        </li>
    @elseif (Route::currentRouteName() == 'subscription.resume')
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'subscription.resume' ? 'active' : '' }}" id="subscription"
               href="{{ route('account.subscription' ) }}">Subscription</a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'account' ? 'active' : '' }}" id="subscription"
               href="{{ route('account.subscription' ) }}">Subscription</a>
        </li>
    @endif
</ul>