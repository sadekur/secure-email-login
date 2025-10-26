=== Password Less Login ===
Contributors: sadekur
Plugin URI: https://profiles.wordpress.org/sadekur/
Tags: passwordless login, email authentication, OTP login, secure login, easy login
Author URI: https://profiles.wordpress.org/sadekur/
Requires at least: 5.9
Tested up to: 6.8
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A powerful and easy-to-use WordPress plugin for passwordless and OTP-based login.

== Description ==
**Password Less Login** is a passwordless and OTP-based login system for WordPress.  
Every user — both existing and new — must verify their identity using a **One-Time Password (OTP)** sent to their email before being logged in.

This ensures that no one can access an account without confirming ownership of the email address, providing a secure, passwordless authentication process.

### How It Works
1. The user enters their email address.
2. The plugin sends a **6-digit OTP** to that email.
3. The user enters the OTP:
   * If the email exists → the user is securely logged in.
   * If the email is new → the user provides a username, verifies the OTP, and a new account is created automatically.
4. The OTP is valid for **10 minutes** and expires after use.

> **Note:** The plugin never logs in users without OTP verification.

---

### Key Features

* **OTP-Based Authentication for All Users** – Both existing and new users must verify the OTP before login.
* **Passwordless Login** – Securely log in using only your email and OTP.
* **Auto User Registration** – New users can register instantly after OTP verification.
* **Temporary OTP (10 Minutes)** – Each OTP expires after 10 minutes and can only be used once.
* **Rate Limiting** – Prevents brute-force or spam OTP requests (maximum 5 per 15 minutes per email).
* **Nonce Verification** – Protects REST API endpoints from unauthorized access.
* **Secure Email Handling** – Emails are hashed when stored in transients to protect user data.
* **Streamlined User Experience** – Clean, minimal login flow with conditional fields for existing vs. new users.

---

### Why Choose Password Less Login?

* No passwords to remember or reset.
* OTP verification ensures true ownership of email.
* Protects against brute-force attacks.
* Simple setup – works with the native WordPress login page.
* Modern and user-friendly design.
* Reduces “Forgot Password” support requests.

---

== Installation ==

**Automatic Installation**

1. Go to your WordPress dashboard → **Plugins → Add New**.
2. Search for **Password Less Login**.
3. Click **Install Now** and then **Activate**.

**Manual Installation**

1. Download the plugin from WordPress.org.
2. Upload the `password-less-login` folder to `/wp-content/plugins/`.
3. Activate the plugin through the **Plugins** menu.

---

== Usage ==

1. Go to your WordPress login page.
2. Enter your email address and click “Send OTP”.
3. Check your email for the OTP.
4. Enter the OTP in the login form:
   - If your account exists, you’ll be logged in.
   - If not, you’ll be prompted to provide a username before registration and login.
5. You’ll be redirected to your dashboard after successful verification.

---

== Frequently Asked Questions ==

**Q: Does this plugin log in users automatically when they submit their email?**  
A: No. Users are only logged in **after successful OTP verification**. Email submission only sends the OTP.

**Q: What is OTP?**  
A: OTP (One-Time Password) is a 6-digit temporary code valid for 10 minutes.

**Q: How many times can a user request OTP?**  
A: Users can request up to 5 OTPs every 15 minutes per email to prevent abuse.

**Q: Is the OTP stored securely?**  
A: Yes. OTPs are stored temporarily and securely using hashed transient keys.

**Q: Can I customize the OTP email message?**  
A: Yes, you can modify the email template in the plugin settings page.

---

== Screenshots ==

1. Login screen with email input.
2. OTP verification form for existing users.
3. Registration form (email, username, OTP) for new users.
4. Admin settings page for customizing OTP email templates.

---

== Changelog ==

= 1.0.1 =
* Added OTP verification for both existing and new users.
* Added nonce verification for REST API requests.
* Added rate limiting (5 OTP requests per 15 minutes).
* Enhanced email and OTP sanitization.
* Improved overall security and error handling.

= 1.0.0 =
* Initial release with passwordless email login, OTP verification, and auto-registration.

---

== Upgrade Notice ==

= 1.0.1 =
Critical security update — existing users now require OTP verification before login.  
Please update immediately for improved protection and reliability.

---

== License ==
This plugin is released under the GPL license. You are free to use and modify it.

For support, contact: [sadekur0rahman@gmail.com](mailto:sadekur0rahman@gmail.com)
