const fs = require('fs');
const path = require('path');

// Function to create directory if it doesn't exist
function ensureDir(dirPath) {
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true });
    console.log(`Created directory: ${dirPath}`);
  }
}

// Function to copy file
function copyFile(src, dest) {
  try {
    fs.copyFileSync(src, dest);
    console.log(`Copied: ${src} -> ${dest}`);
  } catch (err) {
    console.error(`Error copying ${src}: ${err.message}`);
  }
}

// Function to copy directory
function copyDir(src, dest) {
  try {
    if (!fs.existsSync(src)) {
      console.log(`Directory does not exist: ${src}`);
      return;
    }

    ensureDir(dest);
    
    const entries = fs.readdirSync(src, { withFileTypes: true });
    
    for (const entry of entries) {
      const srcPath = path.join(src, entry.name);
      const destPath = path.join(dest, entry.name);
      
      try {
        if (entry.isDirectory()) {
          copyDir(srcPath, destPath);
        } else {
          // Skip config.php in includes directory
          if (src.includes('includes') && entry.name === 'config.php') {
            console.log(`Skipping sensitive file: ${srcPath}`);
            continue;
          }
          copyFile(srcPath, destPath);
        }
      } catch (err) {
        console.error(`Error processing ${srcPath}: ${err.message}`);
      }
    }
  } catch (err) {
    console.error(`Error copying directory ${src}: ${err.message}`);
  }
}

// Main build function
function build() {
  console.log('Starting build process...');
  
  try {
    // Create dist directory
    const distDir = path.resolve('./dist');
    ensureDir(distDir);
    
    // Copy HTML, CSS, and JS files
    console.log('Copying root files...');
    const rootDir = path.resolve('./');
    const rootFiles = fs.readdirSync(rootDir);
    
    for (const file of rootFiles) {
      const filePath = path.join(rootDir, file);
      try {
        const stats = fs.statSync(filePath);
        if (stats.isFile()) {
          const ext = path.extname(file).toLowerCase();
          if (['.html', '.css', '.js', '.ico'].includes(ext) || file === 'vercel.json') {
            copyFile(filePath, path.join(distDir, file));
          }
        }
      } catch (err) {
        console.error(`Error processing ${filePath}: ${err.message}`);
      }
    }
    
    // Copy directories
    const directories = ['api', 'image', 'includes'];
    for (const dir of directories) {
      console.log(`Processing directory: ${dir}`);
      const srcDir = path.join(rootDir, dir);
      const destDir = path.join(distDir, dir);
      copyDir(srcDir, destDir);
    }
    
    console.log('Build completed successfully!');
  } catch (err) {
    console.error(`Build failed: ${err.message}`);
    process.exit(1);
  }
}

// Run the build
build();