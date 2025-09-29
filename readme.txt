=== Secure Email Login ===
Contributors        : sadekur
Plugin URI          : [https://profiles.wordpress.org/sadekur/](https://profiles.wordpress.org/sadekur/)
Tags                : passwordless login, email authentication, OTP login, secure login, WordPress security, user verification, easy login, email OTP, two-factor authentication, login security
Author URI          : [https://profiles.wordpress.org/sadekur/](https://profiles.wordpress.org/sadekur/)
Requires at least   : 5.0
Tested up to        : 6.8
Stable Tag          : 1.0.0
Requires PHP        : 7.2
License             : GPLv2 or later
License URI         : [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

== Secure Email Login ==

== Description ==
**Secure Email Login** is a passwordless login solution for WordPress. With this plugin, users can securely log in or register using only their email address and a one-time password (OTP).

If a user already has an account, they are securely logged in using their email and redirected to their dashboard.

If a new user provides a valid email that does not exist in the system, the plugin prompts them to enter their **email, username, and OTP**. Once verified, their account is created instantly and they are logged in.

The OTP is valid for 10 minutes, ensuring both convenience and strong security.

> **Note**: This plugin requires PHP 7.2 or higher.

### Key Features

* **Passwordless Login**: Users log in without needing a password, just their email.
* **OTP-Based Authentication**: One-Time Passwords (OTP) are sent to the user’s email and are valid for 10 minutes.
* **Auto User Registration**: New users can create an account simply by entering their email, username, and OTP—no extra steps required.
* **Secure and Reliable**: Eliminates the risks of stolen or weak passwords by replacing them with a temporary OTP.
* **Streamlined User Experience**: Fast, simple, and user-friendly login and registration flow.
* **Automatic Dashboard Redirect**: Users are redirected to their WordPress dashboard after login or registration.

### Video Tutorial

Learn how to set up and use Secure Email Login by watching our step-by-step [video tutorial](https://wordpress.org/plugins/secure-email-login/#tutorial).

If you like the plugin, please leave a positive review on [WordPress.org](https://wordpress.org/plugins/secure-email-login/#reviews)!

---

### Why Choose Secure Email Login?

* **No More Passwords**: No need to remember or reset passwords.
* **Stronger Security**: OTPs reduce the risk of brute force and credential theft.
* **Faster Registration**: New users can create an account in seconds.
* **Better User Experience**: Clean, modern login flow with minimal fields.
* **Lower Support Requests**: Fewer “forgot password” issues to handle.
* **Future-Proof**: Stay ahead with secure, passwordless authentication.

== Installation ==

**Automatic Installation**

1. Go to your WordPress dashboard, navigate to the **Plugins** section.
2. Click **Add New** and search for **Secure Email Login**.
3. Click **Install Now** and then **Activate** the plugin.

**Manual Installation**

1. Download the plugin from WordPress.org.
2. Upload the `secure-email-login` folder to your `/wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.

---

== Usage ==

1. Navigate to the WordPress login page.
2. Enter your email address.

   * If you already have an account, simply enter your email to log in.
   * If you are a new user, provide your email, username, and OTP to create an account and log in.
3. After entering the valid OTP, you’ll be redirected to your WordPress dashboard.

---

== Frequently Asked Questions ==

**Q: What is OTP?**
A: OTP stands for One-Time Password, a unique code valid for 10 minutes that verifies user identity.

**Q: Can I customize the OTP email template?**
A: Yes, the email template can be customized in the plugin settings page.

**Q: What happens if the OTP expires?**
A: The user can simply re-enter their email to generate a new OTP.

**Q: Does this work with all WordPress user roles?**
A: Yes, all roles can log in or register using this method.

---

== Screenshots ==

1. Updated login screen with email field.
2. OTP input and new user registration form.
3. Admin settings page for customizing OTP email templates.

---

== Changelog ==

= 1.0.0 =

* Initial release with passwordless email login, OTP verification, and auto-registration.

---

== Upgrade Notice ==

= 1.0.1 =

* Minor bug fixes and performance improvements. Update recommended for best experience.

---

== License ==
This plugin is released under the GPL license. You are free to use and modify it.

For support, contact: [sadekur0rahman@gmail.com](mailto:sadekur0rahman@gmail.com).
