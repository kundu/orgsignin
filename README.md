### Laravel Organization Google Sign-In Package Guide

#### Overview

This package provides a straightforward way to integrate Google Sign-In with domain restrictions into your Laravel application. It allows only users from a specific domain to authenticate using their Google accounts.

#### Prerequisites

Ensure your environment meets the following requirements:

*   **PHP Version**: ^7.2 or ^8.2
*   **Laravel Version**: ^8.0 or ^11.0
*   **Google API Client Credentials**: Set up through Google Cloud Console

---

### Installation

#### 1\. Install the Package

To install the package, run the following command in your terminal:

```xml
composer require kundu/orgsignin
```

#### 2\. Publish Configuration and Views

After installation, publish the configuration file:

```xml
php artisan vendor:publish --tag="orgsignin-config"
```

Optionally, you can also publish the views:

```xml
php artisan vendor:publish --tag="orgsignin-views"
```

---

### Configuration

#### 1\. Environment Variables

Add the following variables to your `.env` file to configure Google Sign-In:

```xml
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
ORG_SIGNIN_ALLOWED_DOMAIN=your-allowed-domain.com,another-allowed-domain.com
ORG_SIGNIN_USER_TABLE=users
ORG_SIGNIN_EMAIL_COLUMN=email
ORG_SIGNIN_CHECK_VERIFIED=true
ORG_SIGNIN_REDIRECT_ROUTE=/home
SIGNIN_BUTTON_TEXT="Sign in with Google"
```

#### 2\. Google OAuth Credentials

Configure your Google OAuth credentials in the Google Cloud Console, making sure to set the correct redirect URI for your Laravel app.

---

### Integration

#### 1\. Include the Sign-In Button

To display the Google Sign-In button on your login view, add this line:

```xml
@include('orgsignin::components.signin-button')
```

#### 2\. Route Protection

Apply the `ValidateDomain` middleware to protect routes and allow access only to users with the specified email domain. For example:

```php
Route::middleware(['auth', ValidateDomain::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    // Additional protected routes...
});
```

---

### Authentication Flow

This package handles the authentication flow as follows:

1.  **Domain Validation**: Ensures the user’s email belongs to the allowed domain.
2.  **User Verification**: Checks if the user exists in the database.
3.  **Optional Email Verification**: Verifies the user’s email if required.

Upon successful authentication, users are redirected to the specified route (`ORG_SIGNIN_REDIRECT_ROUTE`); otherwise, an error message is displayed.

---

### Customization

You can customize the views and configuration to fit your application. The text on the sign-in button can be changed using the `SIGNIN_BUTTON_TEXT` environment variable.