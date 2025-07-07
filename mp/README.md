# My Playground

A comprehensive web development playground featuring various learning tools, management systems, and utilities built with PHP, HTML, CSS, and JavaScript.

## 🚀 Features

### Learning Modules
- **Card Slideshow**: Interactive word visualization with image cards
- **Vocabulary Manager**: Modern vocabulary learning tool with search, sorting, and statistics
- **Word Visualization**: English and Korean word display tools

### Management Tools
- **CRUD Demo**: Complete Create, Read, Update, Delete operations
- **My Health**: Personal health information tracking
- **User Management**: Secure login system with session management

### Utility Tools
- **News Search**: Real-time news article search
- **Family Tour**: Interactive Gyeongju tour guide
- **Box Breathing**: Relaxation and breathing exercise tool

## 🏗️ Architecture

### Unified Layout System
The application uses a centralized `Layout` class (`system/includes/components/Layout.php`) that provides:
- Consistent header and footer across all pages
- Dark mode toggle with localStorage persistence
- Responsive navigation
- Unified styling with Tailwind CSS and ShadCN components

### File Structure
```
my_www/
├── index.php                    # Main homepage
├── system/
│   ├── includes/
│   │   ├── components/
│   │   │   ├── Layout.php      # Unified layout system
│   │   │   ├── Form.php        # Form components
│   │   │   └── ...
│   │   ├── config.php          # Configuration loader
│   │   └── Database.php        # Database abstraction
│   └── auth/                   # Authentication system
├── modules/
│   ├── learning/               # Educational tools
│   │   ├── voca/              # Vocabulary management
│   │   └── card/              # Card slideshow
│   ├── management/            # Data management tools
│   └── tools/                 # Utility applications
├── config/
│   └── credentials/           # Secure credential storage
└── resources/                 # Static assets
```

## 🎨 Design System

### Modern UI/UX
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Dark Mode**: System-wide theme toggle with smooth transitions
- **Component Library**: ShadCN-inspired components for consistency
- **Interactive Elements**: Hover effects, animations, and micro-interactions

### Color Scheme
- **Light Theme**: Clean whites and grays with blue accents
- **Dark Theme**: Deep grays with blue highlights
- **Gradient Backgrounds**: Subtle gradients for visual appeal

## 🔧 Setup & Installation

### Prerequisites
- PHP 8.0 or higher
- SQLite (included) or MySQL
- Web server (Apache/Nginx) or PHP built-in server

### Quick Start
1. Clone the repository
2. Navigate to the project directory
3. Start the development server:
   ```bash
   php -S localhost:8080
   ```
4. Open `http://localhost:8080` in your browser

### Configuration
1. Copy `config/credentials/development.example.php` to `config/credentials/development.php`
2. Update database credentials and API keys
3. Ensure the `config/credentials/` directory is excluded from version control

## 📱 Pages Overview

### Main Pages
- **Home** (`index.php`): Central hub with all module links
- **Vocabulary** (`modules/learning/voca/voca.html`): Modern vocabulary manager
- **Card Slideshow** (`modules/learning/card/slideshow.php`): Interactive word cards

### Management Pages
- **CRUD Demo** (`modules/management/crud/data_list.php`): Database operations
- **My Health** (`modules/management/myhealth/health_list.php`): Health tracking

### Tool Pages
- **News Search** (`modules/tools/news/search_news_form.php`): News exploration
- **Family Tour** (`modules/tools/tour/familytour.html`): Tour guide
- **Box Breathing** (`modules/tools/box/boxbreathe.php`): Relaxation tool

## 🔒 Security Features

### Credential Management
- Secure credential storage in `config/credentials/`
- Environment-specific configuration files
- Git-ignored sensitive data

### Authentication
- Session-based user authentication
- Secure login/logout system
- Protected private sections

## 🛠️ Development

### Adding New Pages
1. Create your page content
2. Use the Layout class for consistent styling:
   ```php
   require_once './system/includes/components/Layout.php';
   
   $content = 'Your page content here';
   
   $layout = new Layout([
       'pageTitle' => 'Your Page Title',
       'additionalCSS' => 'Custom styles if needed'
   ]);
   
   $layout->render($content);
   ```

### Database Operations
Use the Database class for all database interactions:
```php
require_once './system/includes/Database.php';
$db = new Database();
$result = $db->query("SELECT * FROM your_table");
```

## 📊 Testing

### Test Setup
- Run `./start_test.sh` for automated testing
- Check `TEST_GUIDE.md` for detailed testing procedures
- Review `FINAL_TEST_SUMMARY.md` for test results

## 🤝 Contributing

1. Follow the established code structure
2. Use the Layout class for new pages
3. Maintain consistent styling with Tailwind CSS
4. Test thoroughly before submitting changes

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For issues and questions:
1. Check the documentation in the `docs/` directory
2. Review the test guides for troubleshooting
3. Examine the configuration files for setup issues

---

**My Playground** - A comprehensive web development learning environment 