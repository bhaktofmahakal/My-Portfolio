# Portfolio Admin Panel

This is the admin panel for managing contact form messages from your portfolio website.

## Features

- Secure login with password hashing
- Dashboard with message statistics
- Message management (view, mark as handled, delete)
- Reply to messages
- Dark mode support
- Mobile responsive design
- CSRF protection
- Rate limiting for security

## Setup Instructions

1. **Database Setup**
   - Create a MySQL database for your portfolio
   - Import the SQL file from `../database/setup.sql`
   - Update database credentials in `../includes/config.php` if needed

2. **Admin User Setup**
   - Navigate to `admin/setup.php` in your browser
   - This will create the default admin user
   - Default credentials:
     - Username: admin
     - Password: Admin@123
   - **Important**: Delete `setup.php` after running it for security

3. **Contact Form Setup**
   - The contact form in your portfolio is already configured to send messages to the database
   - Make sure PHP is properly configured on your server

4. **Security Recommendations**
   - Change the default admin password immediately after first login
   - Set up HTTPS for your website
   - Regularly backup your database
   - Consider implementing additional security measures like IP-based access restrictions

## Usage

1. **Login**
   - Navigate to `admin/login.php`
   - Enter your admin credentials

2. **Dashboard**
   - View message statistics
   - See the latest messages

3. **Messages**
   - View all messages with pagination
   - Search and filter messages
   - Mark messages as handled/new
   - Delete messages

4. **View Message**
   - Read the full message
   - Reply to the sender
   - Change message status
   - Delete the message

5. **Logout**
   - Click the logout button when finished

## Troubleshooting

- If you encounter database connection issues, check your database credentials in `../includes/config.php`
- If the contact form isn't working, ensure your server has PHP properly configured
- For any other issues, check the server logs for error messages