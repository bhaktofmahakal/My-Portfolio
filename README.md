# Utsav Mishra Portfolio

A professional portfolio website showcasing skills, projects, and contact information.

## Deployment Instructions

### Frontend Deployment (Vercel)

1. **Push your code to GitHub**
   - Create a new GitHub repository
   - Push your code to the repository

2. **Deploy on Vercel**
   - Go to [Vercel](https://vercel.com) and sign in with your GitHub account
   - Click "New Project" and select your repository
   - Keep the default settings (Framework Preset: Other)
   - Click "Deploy"

3. **Configure Domain (Optional)**
   - In the Vercel dashboard, go to your project settings
   - Click on "Domains" and add your custom domain

### Backend Deployment (PHP/MySQL)

1. **Choose a PHP Hosting Provider**
   - Recommended options: Hostinger, DigitalOcean, or any PHP/MySQL hosting service

2. **Set Up the Database**
   - Create a new MySQL database
   - Create the required tables using the following SQL:

```sql
-- Create admin_users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create messages table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'handled') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create rate_limits table
CREATE TABLE rate_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    action VARCHAR(50) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (ip_address, action)
);
```

3. **Upload Backend Files**
   - Upload the following files/folders to your hosting:
     - `process_contact.php`
     - `includes/` directory
     - `admin/` directory

4. **Configure Database Connection**
   - Update `includes/config.php` with your database credentials:
   ```php
   define('DB_HOST', 'your-database-host');
   define('DB_USER', 'your-database-username');
   define('DB_PASS', 'your-database-password');
   define('DB_NAME', 'your-database-name');
   ```

5. **Set Up Admin User**
   - Navigate to `your-backend-url.com/admin/setup.php` in your browser
   - This will create the default admin user
   - Delete `setup.php` after running it for security

6. **Update Frontend Configuration**
   - In the deployed frontend code, update the backend URL in `portfolio-advanced.js`:
   ```javascript
   const backendUrl = 'https://your-backend-url.com/process_contact.php';
   ```

## Local Development

1. **Set up a local PHP server**
   - Install XAMPP, WAMP, or MAMP
   - Place the project files in the web server directory
   - Start the Apache and MySQL services

2. **Create the database**
   - Open phpMyAdmin and create a new database
   - Import the SQL from the deployment instructions

3. **Configure database connection**
   - Update `includes/config.php` with your local database credentials

4. **Run the application**
   - Access the site at `http://localhost/portfolio`

## Security Considerations

1. **HTTPS**
   - Ensure both frontend and backend use HTTPS

2. **Admin Security**
   - Change the default admin password immediately
   - Consider implementing additional security measures like IP restrictions

3. **Regular Backups**
   - Set up regular database backups

## Maintenance

1. **Content Updates**
   - Update the HTML files to reflect new projects, skills, or information

2. **Backend Updates**
   - Regularly update your PHP version and dependencies for security

3. **Monitoring**
   - Set up monitoring for your backend to ensure it remains operational