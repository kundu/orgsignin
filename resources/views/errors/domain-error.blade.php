<div class="alert alert-danger">
    <strong>Authentication Error:</strong>
    @switch($message ?? '')
        @case('Invalid email domain')
            <p>Your email domain is not authorized to access this application.</p>
            @break
        @case('Email not registered in the system')
            <p>This email is not registered in our system. Please contact your administrator.</p>
            @break
        @case('Email not verified')
            <p>Your email address has not been verified. Please verify your email first.</p>
            @break
        @default
            <p>{{ $message ?? 'An error occurred during authentication.' }}</p>
    @endswitch
    <a href="{{ route('login') }}" class="alert-link">Return to login</a>
</div> 