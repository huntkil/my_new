#!/bin/bash

echo "🎮 My Playground - Test Environment Setup"
echo "=========================================="

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.0 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "✅ PHP Version: $PHP_VERSION"

# Run setup test
echo ""
echo "🧪 Running setup test..."
php test_setup.php

echo ""
echo "🚀 Starting PHP development server..."
echo "📱 Server will be available at: http://localhost:8080"
echo "🎯 Test page: http://localhost:8080/test.html"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Start PHP server
php -S localhost:8080 