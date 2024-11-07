**Laravel Organization Google Sign-In Package Installation and Configuration Guide**

**Prerequisites**

Before proceeding with the installation and configuration of the Laravel Organization Google Sign-In package, ensure that your environment meets the following requirements:

* PHP version: ^8.1 or ^8.2
* Laravel version: ^10.0 or ^11.0
* Google API Client credentials

**Installation**

To install the package, execute the following command in your terminal:
```bash
composer require kundu/orgsignin -W
```
**Publishing Configuration and Views**

After installation, publish the configuration file using the following command:
```bash
php artisan vendor:publish --tag="orgsignin-config"
```
Optionally, publish the views with the following command:
```bash
php artisan vendor:publish --tag="orgsignin-views"
```
**Environment Variables**

Add the following environment variables to your `.env` file:

* `GOOGLE_CLIENT_ID`
* `GOOGLE_CLIENT_SECRET`
* `ORG_SIGNIN_ALLOWED_DOMAIN`
* `ORG_SIGNIN_USER_TABLE`
* `ORG_SIGNIN_EMAIL_COLUMN`
* `ORG_SIGNIN_CHECK_VERIFIED`
* `ORG_SIGNIN_REDIRECT_ROUTE`

**Google OAuth Credentials Configuration**

Configure your Google OAuth credentials in the Google Cloud Console, ensuring that you have the correct redirect URI.

**Integration with Laravel Application**

In your Laravel application, include the sign-in button in your login view using the following code:
```php
@include('orgsignin::components.signin-button')
```
**Route Protection**

Protect your routes by applying the `ValidateDomain` middleware to ensure that only users with the allowed domain can access them.

**Authentication Flow**

The package handles the authentication flow by:

1. Validating the user's email domain
2. Checking if the user exists in your database
3. Verifying the email if required

Upon successful authentication, users are redirected to the specified route; otherwise, they receive an error message.

**Customization**

Customize the views and configuration as needed to fit your application's requirements.

**Support and Licensing**

If you encounter any issues or have security concerns, contact the package author. The package is licensed under the MIT License, allowing for free use and modification.