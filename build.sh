#!/bin/bash

# Exit on error
set -e

echo "Starting build process..."

# Create a build directory if it doesn't exist
mkdir -p dist

# Copy HTML files
echo "Copying HTML files..."
cp *.html dist/

# Copy CSS files
echo "Copying CSS files..."
cp *.css dist/

# Copy JavaScript files
echo "Copying JavaScript files..."
cp *.js dist/

# Copy API directory
echo "Copying API directory..."
mkdir -p dist/api
cp -r api/* dist/api/

# Copy images directory
echo "Copying images directory..."
if [ -d "image" ]; then
  mkdir -p dist/image
  cp -r image/* dist/image/
fi

# Copy includes directory (excluding sensitive files)
echo "Copying includes directory..."
if [ -d "includes" ]; then
  mkdir -p dist/includes
  # Copy only necessary files, exclude config with sensitive data
  find includes -type f -not -name "config.php" -exec cp {} dist/includes/ \;
fi

# Copy any other necessary files
echo "Copying other files..."
if [ -f "favicon.ico" ]; then
  cp favicon.ico dist/
fi

# Copy vercel.json to the dist directory
cp vercel.json dist/

echo "Build completed successfully!"