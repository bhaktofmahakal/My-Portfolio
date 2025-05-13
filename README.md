# Portfolio Website - Vercel Deployment Guide

This is a professional portfolio website for Utsav Mishra, ready to deploy on Vercel.

## Project Structure

- **HTML/CSS/JS**: Main frontend files
- **API**: Serverless functions for the contact form
- **Images/Assets**: Portfolio images and documents
- **Certificates**: PDF certificates

## Deployment to Vercel

### Prerequisites

1. A [Vercel](https://vercel.com) account (you can sign up with GitHub)
2. [Git](https://git-scm.com/) installed on your computer
3. Basic knowledge of command line operations

### Deployment Steps

1. **Clone or Download this repository**
   ```bash
   git clone <repository-url>
   cd portfolio
   ```

2. **Install Vercel CLI (optional)**
   ```bash
   npm install -g vercel
   ```

3. **Deploy to Vercel**

   **Option 1: Using Vercel CLI**
   ```bash
   # Login to Vercel
   vercel login
   
   # Deploy to Vercel
   vercel
   
   # For production deployment
   vercel --prod
   ```

   **Option 2: Using Vercel Dashboard**
   1. Push your code to a GitHub repository
   2. Go to [Vercel Dashboard](https://vercel.com/dashboard)
   3. Click "Import Project"
   4. Select "Import Git Repository"
   5. Connect to your GitHub account and select your repository
   6. Configure your project settings
   7. Click "Deploy"

### Important Configuration

The project includes a `vercel.json` file that configures the build settings and routing for Vercel deployment. This ensures:

1. Static files (HTML, CSS, JS, images, PDFs) are served correctly
2. API routes are processed as serverless functions
3. Proper routing for all requests

## Contact Form Setup

The contact form now uses a serverless function approach:

1. The form data is sent to `/api/contact` endpoint
2. The API function forwards the request to the InfinityFree PHP backend
3. The PHP backend processes the data and stores it in the MySQL database

This approach allows you to:
1. Keep your existing database setup on InfinityFree
2. Deploy a modern frontend on Vercel with great performance
3. Continue using your existing admin panel to view messages

## Post-Deployment

1. **Test the contact form** to ensure it's working correctly
2. **Update DNS settings** if you're using a custom domain
3. **Set up monitoring** through Vercel's dashboard

## Troubleshooting

- **Contact form not working?** Check the browser console for errors. Ensure the InfinityFree backend is accessible.
- **API error messages?** Check Vercel logs in the dashboard under your project's "Functions" tab.
- **CSS/JS not loading?** Verify the paths in HTML files are correct.

## Future Improvements

- Consider moving from InfinityFree to a more integrated database solution like MongoDB Atlas or Supabase for improved performance and reliability.
- Implement serverless functions for admin functionality to completely migrate away from PHP.
