#!/bin/bash

# Modern Admin Dashboard Theme Packaging Script
# This script creates a ready-to-upload WordPress theme package

echo "🚀 Packaging Modern Admin Dashboard Theme..."

# Create theme directory
THEME_DIR="modern-admin-theme"
PACKAGE_NAME="modern-admin-theme.zip"

# Remove existing package if it exists
if [ -f "$PACKAGE_NAME" ]; then
    echo "📦 Removing existing package..."
    rm "$PACKAGE_NAME"
fi

# Remove existing theme directory if it exists
if [ -d "$THEME_DIR" ]; then
    echo "🗑️  Cleaning up existing theme directory..."
    rm -rf "$THEME_DIR"
fi

# Create theme directory
echo "📁 Creating theme directory..."
mkdir "$THEME_DIR"

# Copy theme files
echo "📋 Copying theme files..."

# Core files
cp style.css "$THEME_DIR/"
cp functions.php "$THEME_DIR/"

# Create directories
mkdir -p "$THEME_DIR/js"
mkdir -p "$THEME_DIR/css"

# Copy JavaScript
cp js/admin.js "$THEME_DIR/js/"

# Copy CSS
cp css/login.css "$THEME_DIR/css/"

# Copy documentation
cp README.md "$THEME_DIR/"
cp INSTALLATION.md "$THEME_DIR/"
cp CHANGELOG.md "$THEME_DIR/"

# Create additional theme files
echo "📝 Creating additional theme files..."

# Create index.php for security
cat > "$THEME_DIR/index.php" << 'EOF'
<?php
// Silence is golden.
EOF

# Create screenshot.png placeholder (you would add an actual screenshot)
cat > "$THEME_DIR/screenshot.txt" << 'EOF'
Screenshot placeholder
Replace this file with a 1200x900px screenshot of your admin theme
Name it screenshot.png
EOF

# Create theme.json for WordPress 5.8+ support
cat > "$THEME_DIR/theme.json" << 'EOF'
{
    "version": 2,
    "settings": {
        "appearanceTools": true,
        "useRootPaddingAwareAlignments": true,
        "color": {
            "palette": [
                {
                    "color": "#667eea",
                    "name": "Primary Blue",
                    "slug": "primary-blue"
                },
                {
                    "color": "#764ba2",
                    "name": "Secondary Purple",
                    "slug": "secondary-purple"
                },
                {
                    "color": "#f8f9fa",
                    "name": "Light Gray",
                    "slug": "light-gray"
                }
            ]
        }
    },
    "styles": {
        "color": {
            "background": "#f8f9fa"
        }
    }
}
EOF

# Create .gitignore
cat > "$THEME_DIR/.gitignore" << 'EOF'
# WordPress
wp-config.php
wp-content/uploads/
wp-content/cache/
wp-content/backup-db/
wp-content/advanced-cache.php
wp-content/wp-cache-config.php

# Logs
*.log
error_log
debug.log

# OS
.DS_Store
Thumbs.db

# IDE
.vscode/
.idea/
*.swp
*.swo

# Node
node_modules/
npm-debug.log

# Temporary files
*.tmp
*.temp
EOF

# Create composer.json for dependency management
cat > "$THEME_DIR/composer.json" << 'EOF'
{
    "name": "modern-admin/wordpress-admin-theme",
    "description": "A modern, clean, and user-friendly WordPress admin theme",
    "type": "wordpress-theme",
    "license": "GPL-2.0-or-later",
    "authors": [
        {
            "name": "Modern Admin Team",
            "email": "admin@modernadmin.com"
        }
    ],
    "keywords": [
        "wordpress",
        "admin",
        "theme",
        "dashboard",
        "modern",
        "responsive"
    ],
    "require": {
        "php": ">=7.4"
    },
    "require-dev": {
        "phpunit/phpunit": "^9.0"
    },
    "autoload": {
        "psr-4": {
            "ModernAdmin\\": "src/"
        }
    },
    "scripts": {
        "test": "phpunit",
        "lint": "phpcs --standard=WordPress"
    }
}
EOF

# Create package.json for development
cat > "$THEME_DIR/package.json" << 'EOF'
{
    "name": "modern-admin-wordpress-theme",
    "version": "1.0.0",
    "description": "A modern, clean, and user-friendly WordPress admin theme",
    "main": "style.css",
    "scripts": {
        "build": "npm run build:css && npm run build:js",
        "build:css": "postcss css/*.css --dir dist/css",
        "build:js": "webpack --mode production",
        "dev": "npm run build:css && npm run build:js --mode development --watch",
        "lint": "eslint js/*.js",
        "format": "prettier --write ."
    },
    "keywords": [
        "wordpress",
        "admin",
        "theme",
        "dashboard",
        "modern",
        "responsive"
    ],
    "author": "Modern Admin Team",
    "license": "GPL-2.0-or-later",
    "devDependencies": {
        "autoprefixer": "^10.4.0",
        "cssnano": "^5.1.0",
        "eslint": "^8.0.0",
        "postcss": "^8.4.0",
        "postcss-cli": "^9.1.0",
        "prettier": "^2.5.0",
        "webpack": "^5.65.0",
        "webpack-cli": "^4.9.0"
    }
}
EOF

# Set proper permissions
echo "🔐 Setting file permissions..."
find "$THEME_DIR" -type d -exec chmod 755 {} \;
find "$THEME_DIR" -type f -exec chmod 644 {} \;

# Create ZIP package
echo "📦 Creating ZIP package..."
zip -r "$PACKAGE_NAME" "$THEME_DIR" -x "*.DS_Store" "*/.*" "*/node_modules/*"

# Get package size
PACKAGE_SIZE=$(du -h "$PACKAGE_NAME" | cut -f1)

# Clean up theme directory
echo "🧹 Cleaning up..."
rm -rf "$THEME_DIR"

echo ""
echo "✅ Theme packaging complete!"
echo "📦 Package: $PACKAGE_NAME"
echo "📏 Size: $PACKAGE_SIZE"
echo ""
echo "🚀 Ready to upload to WordPress!"
echo ""
echo "Installation instructions:"
echo "1. Download $PACKAGE_NAME"
echo "2. Go to WordPress Admin > Appearance > Themes"
echo "3. Click 'Add New' > 'Upload Theme'"
echo "4. Select $PACKAGE_NAME and click 'Install Now'"
echo "5. Click 'Activate' to start using the theme"
echo ""
echo "📚 For detailed instructions, see INSTALLATION.md"
echo "📖 For full documentation, see README.md"