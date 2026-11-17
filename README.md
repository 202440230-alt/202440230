# SaveSpace
SaveSpace is a self-storage booking website for Puerto Princesa City, Palawan. Customers create an account, reserve a storage unit online, and track their rental; the owner confirms, cancels or completes reservations from an admin dashboard.

The project is inspired by storage rental businesses such as ExtraSpace (based in Cebu), designed as a smaller, realistic system for Palawan.

# Project Purpose
SaveSpace helps:
- Students who need temporary storage during school breaks
- Families who are moving or renovating
- Small business owners who need extra inventory space
- Travellers who need short-term luggage storage

# Features
- Sign up, log in (with Remember Me), log out, and forgot password
- Live unit availability on the home page (Available / Limited / Fully Booked)
- Online reservation: choose a size, move-in date and rental length (1, 3, 6 or 12 months); the system assigns the first free unit
- My Reservations: view status and cancel pending requests
- Profile: account summary, rental tracking, rental history, and editing your details
- Owner dashboard: total units, occupied units, pending requests, monthly income; confirm, cancel or complete reservations; change unit availability

# Pages
Public: `index.php`, `register.html`, `login.php`, `forgot.php`, `terms.html`, `privacy.html`
Customer: `home.php`, `reservations.php`, `profile.php`
Owner: `admin.php`
Handlers: `authenticate.php`, `register.php`, `reserve.php`, `cancel.php`, `update_profile.php`, `admin_action.php`, `logout.php`
Shared: `config.php` (database connection), `session.php` (login check), `message.php` (result page)

# Storage Categories
| Size | Units | Rate |
|------|-------|------|
| Small Locker | A-05, A-06, A-07 | ₱500/month |
| Medium Room | B-10, B-11, B-12 | ₱1,500/month |
| Large Room | C-19, C-20, C-21 | ₱3,000/month |

# Reservation Statuses
Pending → Confirmed → Completed, or Pending → Cancelled

# Database
MySQL database `savespace` with three tables: `users`, `units`, `reservations`. Import `database.sql` to create the tables, the nine units and the owner account.

# How to Run
1. Install XAMPP and start Apache and MySQL.
2. Copy this folder to `C:\xampp\htdocs\202440230`.
3. Import `database.sql` in phpMyAdmin.
4. Check the MySQL port in `config.php` (set to 3307).
5. Open `http://localhost/202440230/index.php`.

# Security
- Passwords hashed with `password_hash()` and checked with `password_verify()`
- Prepared statements for every query that uses user input (prevents SQL injection)
- `htmlspecialchars()` on user data shown on pages (prevents XSS)
- Sessions protect logged-in pages; admin pages check the user's role
- Validation in the browser and again in PHP

# Tools and Technologies
- HTML, CSS, a little JavaScript (menu toggles)
- PHP and MySQL (XAMPP)
- Visual Studio Code, Visual Studio, GitHub
