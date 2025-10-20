# 👗 Velvet Vogue — E-Commerce Web Application

Velvet Vogue is a **PHP & MySQL-based e-commerce platform** built for a fashion and clothing store.  
It allows users to browse products, register and verify their accounts via OTP, manage their carts, checkout securely, and receive invoices via email.  
The project demonstrates full-stack development skills including authentication, CRUD operations, session handling, and secure email communication using PHPMailer.

---

## 🖥️ Project Overview

Velvet Vogue provides a secure and user-friendly shopping experience with features for both customers and administrators.

**Key Highlights**
- OTP-based user registration and email verification.
- Password reset functionality via email.
- Product management (add, update, delete).
- Shopping cart and checkout system.
- PDF invoice generation and email delivery.
- Admin dashboard and user management.
- Clean, modular PHP structure with session-based authentication.

---

## 🌐 Live Demo
Check out the live version of the project here: [https://velvetvogue.kesug.com/](https://velvetvogue.kesug.com/)

---

## 🚀 Features

### 👤 User Features
- Register and verify account with OTP (sent via email using PHPMailer).
- Login and maintain a secure session.
- Reset forgotten password via email.
- Browse and search products.
- Add/remove products from the shopping cart.
- Checkout and view invoices.

### 🧑‍💼 Admin Features
- Add, update, or delete products.
- Manage users (view, edit, delete).
- View and manage orders.
- Generate and send invoices to customers.

### 🔐 Security
- Passwords hashed before saving to the database.
- OTPs expire after a set time to prevent reuse.
- All forms protected against SQL injection (prepared statements recommended).
- PHP session-based authentication for secure access control.

---

## ⚙️ Tech Stack

| Category | Technology |
|-----------|-------------|
| **Frontend** | HTML, CSS, JavaScript, Bootstrap |
| **Backend** | PHP (Core PHP) |
| **Database** | MySQL |
| **Email Service** | PHPMailer (SMTP configuration) |
| **PDF Generation** | PHP/HTML-based invoice system |
| **Version Control** | Git & GitHub |

---

## 📂 Folder Structure

```
velvet-vogue/
│
├── assets/                 # Images, icons, and logos
├── css/                    # CSS files (Bootstrap & custom styles)
├── js/                     # JavaScript files
├── uploads/                # Uploaded product images
│
├── index.php               # Landing / Home page
├── login.php               # User login page
├── register.php            # Registration page
├── verification.php        # OTP verification page
├── forgot_password.php     # Password reset page
├── reset_link.php          # Reset link handler
├── reset_password.php      # New password form
│
├── addtocart.php           # Add item to cart
├── cart.php                # Shopping cart page
├── checkout.php            # Checkout and payment simulation
├── invoice.php             # Generates PDF invoice and email
│
├── product_add.php         # Admin adds new product
├── product_update.php      # Admin updates product info
├── product_delete.php      # Admin deletes a product
│
├── user_dashboard.php      # User profile/dashboard
├── user_update.php         # Edit user profile info
│
├── functions.php           # Common reusable PHP functions
├── database.php            # MySQL connection setup
│
├── PHPMailer/              # PHPMailer library for sending emails
│
└── README.md               # Project documentation
```

---

## 🛠️ Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/ShehanRanasinghe/velvet-vogue.git
cd velvet-vogue
```

### 2. Create the Database
- Open **phpMyAdmin** (or your preferred MySQL client).
- Create a new database named `velvet_vogue`.
- Import the SQL file (if provided, e.g., `velvet_vogue.sql`).

### 3. Configure Database Connection
Edit `database.php` with your local database credentials:
```php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "velvet_vogue";
$conn = mysqli_connect($host, $user, $pass, $dbname);
```

### 4. Configure PHPMailer (SMTP)
In your PHPMailer setup file or email script:
```php
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your_email@gmail.com';
$mail->Password = 'your_app_password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
```
> ⚠️ Use a **Gmail App Password** (not your regular password) if using Gmail SMTP.

### 5. Run the Application
- Place the project folder inside your **XAMPP htdocs** directory.
- Start **Apache** and **MySQL** from XAMPP.
- Visit the project in your browser:
```
http://localhost/velvet-vogue/
```

---

## 📧 Email & OTP Flow

1. User registers → system generates OTP and stores it in the database.  
2. OTP is emailed via PHPMailer.  
3. User enters OTP to verify their account.  
4. Upon success, account is marked as verified and user is logged in.  
5. The same logic applies for password reset requests.

---

## 🧾 Database Structure (Simplified)

| Column | Type | Description |
|---------|------|-------------|
| id | INT | Primary key |
| username | VARCHAR | User name |
| email | VARCHAR | User email |
| password | VARCHAR | Hashed password |
| otp_code | VARCHAR | One-time password for verification |
| otp_expiration | DATETIME | Expiration timestamp for OTP |
| is_verified | TINYINT(1) | 0 = not verified, 1 = verified |
| created_at | DATETIME | Registration time |

---

## 💡 Future Enhancements

- Add two-factor authentication (2FA) for login.
- Modernize UI/UX with responsive layouts and animations.
- Integrate live payment gateways (Stripe, PayPal, etc.).
- Implement order tracking and status updates.
- Add HTML email templates for OTPs and invoices.
- Improve security with prepared statements and CSRF tokens.

---

## 👨‍💻 Author

**Developed by:** [Shehan Ranasinghe](https://github.com/ShehanRanasinghe)  

---

## 🪪 License

This project is licensed under the **GNU General Public License v3.0 (GPL-3.0)**.
You are free to use, modify, and distribute it under the terms of the GPL-3.0 license. See [GNU GPL](https://www.gnu.org/licenses/gpl-3.0.en.html) for details.

---

### ⭐ If you found this project helpful, please give it a star on GitHub!

