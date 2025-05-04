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

For detailed backend deployment instructions, please refer to the [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) file.

Here's a quick summary:

1. **Choose a PHP Hosting Provider**
   - Options include: InfinityFree (Free), 000webhost (Free), Hostinger, NameCheap, or Bluehost

2. **Set Up the Database**
   - Create a new MySQL database on your hosting
   - Import the database schema from `database/setup.sql`

3. **Configure Database Connection**
   - Copy `includes/config.production.php` to `includes/config.php`
   - Update with your hosting database credentials:
   ```php
   define('DB_HOST', 'your-database-host');
   define('DB_USER', 'your-database-username');
   define('DB_PASS', 'your-database-password');
   define('DB_NAME', 'your-database-name');
   ```

4. **Upload Backend Files**
   - Upload the following files/folders to your hosting:
     - `process_contact_production.php` (rename to `process_contact.php` on the server)
     - `includes/` directory (with your updated config.php)
     - `admin/` directory

5. **Update Frontend Configuration**
   - Update the backend URL in `portfolio-advanced.js`:
   ```javascript
   const backendUrl = window.location.hostname === 'localhost' 
       ? 'process_contact.php' 
       : 'https://your-domain.com/process_contact.php'; // Replace with your actual domain
   ```

6. **Secure Your Admin Panel**
   - Log in to your admin panel and change the default password

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