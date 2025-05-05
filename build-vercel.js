const fs = require('fs');
const path = require('path');

// Create public directory (Vercel expects this directory name)
if (!fs.existsSync('public')) {
  fs.mkdirSync('public', { recursive: true });
  console.log('Created public directory');
}

// Copy HTML files
const htmlFiles = fs.readdirSync('.').filter(file => file.endsWith('.html'));
htmlFiles.forEach(file => {
  fs.copyFileSync(file, `public/${file}`);
  console.log(`Copied ${file} to public/`);
});

// Copy CSS files
const cssFiles = fs.readdirSync('.').filter(file => file.endsWith('.css'));
cssFiles.forEach(file => {
  fs.copyFileSync(file, `public/${file}`);
  console.log(`Copied ${file} to public/`);
});

// Copy JS files
const jsFiles = fs.readdirSync('.').filter(file => file.endsWith('.js'));
jsFiles.forEach(file => {
  fs.copyFileSync(file, `public/${file}`);
  console.log(`Copied ${file} to public/`);
});

// Copy vercel.json
if (fs.existsSync('vercel.json')) {
  fs.copyFileSync('vercel.json', 'public/vercel.json');
  console.log('Copied vercel.json to public/');
}

// Copy directories
const directories = ['api', 'image', 'includes', 'certificates'];
directories.forEach(dir => {
  if (fs.existsSync(dir)) {
    // Create directory in public
    if (!fs.existsSync(`public/${dir}`)) {
      fs.mkdirSync(`public/${dir}`, { recursive: true });
    }
    
    // Copy files (excluding config.php in includes)
    const files = fs.readdirSync(dir);
    files.forEach(file => {
      const srcPath = path.join(dir, file);
      const destPath = path.join('public', dir, file);
      
      if (fs.statSync(srcPath).isDirectory()) {
        // Skip directories for now
        console.log(`Skipping subdirectory: ${srcPath}`);
      } else {
        // Skip config.php in includes directory
        if (dir === 'includes' && file === 'config.php') {
          console.log(`Skipping sensitive file: ${srcPath}`);
          return;
        }
        
        fs.copyFileSync(srcPath, destPath);
        console.log(`Copied ${srcPath} to ${destPath}`);
      }
    });
  } else {
    console.log(`Directory ${dir} does not exist, skipping`);
  }
});

console.log('Build completed successfully!');