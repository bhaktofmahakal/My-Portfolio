# Vercel PR Deployment Guide

This guide will help you set up your portfolio project for Vercel PR deployments.

## Prerequisites

1. A GitHub account
2. A Vercel account
3. Your project pushed to a GitHub repository

## Step 1: Push Your Project to GitHub

If your project is not already on GitHub:

```bash
# Initialize git repository (if not already done)
git init

# Add all files
git add .

# Commit changes
git commit -m "Initial commit"

# Add GitHub remote (replace with your repository URL)
git remote add origin https://github.com/yourusername/portfolio.git

# Push to GitHub
git push -u origin main
```

## Step 2: Connect Vercel to Your GitHub Repository

1. Go to [Vercel](https://vercel.com/) and sign in
2. Click "Add New..." and select "Project"
3. Import your GitHub repository
4. Configure your project:
   - Framework Preset: Other
   - Root Directory: ./
   - Build Command: `npm run build`
   - Output Directory: dist
5. Click "Deploy"

## Step 3: Enable PR Deployments

1. Go to your project dashboard in Vercel
2. Navigate to "Settings" > "Git"
3. Under "Preview Deployments", make sure "Deploy Preview for Pull Requests" is enabled
4. Save your changes

## Step 4: Test PR Deployments

1. Create a new branch in your repository:
   ```bash
   git checkout -b feature/test-pr-deployment
   ```

2. Make a small change to your project
3. Commit and push the change:
   ```bash
   git add .
   git commit -m "Test PR deployment"
   git push origin feature/test-pr-deployment
   ```

4. Create a Pull Request on GitHub
5. Vercel will automatically create a preview deployment for your PR
6. You'll see a "Vercel" check in your PR with a link to the preview

## Additional Configuration

Your project is already well-configured for Vercel with:

- `build-vercel.js` script that prepares your files for deployment
- `vercel.json` with proper routing and security headers

The build script will:
- Create a `dist` directory
- Copy all HTML, CSS, and JS files
- Copy necessary directories (api, image, includes)
- Skip sensitive files like `includes/config.php`

## Troubleshooting

If you encounter any issues:

1. Check the build logs in Vercel
2. Ensure your `build-vercel.js` script is correctly copying all necessary files
3. Verify your `vercel.json` configuration is correct

For more help, refer to the [Vercel documentation](https://vercel.com/docs).