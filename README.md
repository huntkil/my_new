# My Playground - PHP Web Application

[![PHP Version](https://img.shields.io/badge/PHP-8.0+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![GitHub](https://img.shields.io/badge/GitHub-huntkil%2Fmy__new-brightgreen.svg)](https://github.com/huntkil/my_new)

A comprehensive PHP web application featuring learning modules, management tools, and utility functions. Built with modern PHP practices, secure credential management, and responsive design.

## 🌟 Features

### 📚 Learning Modules
- **Card Slideshow**: Interactive word learning with animal images
- **Vocabulary Management**: CRUD operations for word lists
- **Word Visualization**: English/Korean word display systems
- **Word Rolls**: SNS-style word presentation

### 🗂️ Management Modules
- **CRUD Demo**: MVC pattern-based data management
- **Health Tracking**: Personal health record management
- **User Authentication**: Secure login/logout system

### 🛠️ Tools Modules
- **News Search**: Real-time news API integration
- **Box Breathing**: Guided breathing exercise tool
- **Family Tour**: Travel planning with interactive maps

## 🚀 Quick Start

### Prerequisites
- PHP 8.0 or higher
- Web server (Apache/Nginx) or PHP built-in server
- SQLite (for local development) or MySQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/huntkil/my_new.git
   cd my_new
   ```

2. **Set up credentials**
   ```bash
   cd config/credentials
   cp sample.php development.php
   # Edit development.php with your actual credentials
   ```

3. **Start the development server**
   ```bash
   php -S localhost:8080
   ```

4. **Access the application**
   ```
   http://localhost:8080
   ```

## 📁 Project Structure

```
my_new/
├── 📁 config/                 # Configuration files
│   ├── 📁 credentials/        # Secure credential management
│   └── 📁 deploy/            # Deployment scripts
├── 📁 modules/               # Application modules
│   ├── 📁 learning/          # Learning tools
│   ├── 📁 management/        # Data management
│   └── 📁 tools/             # Utility tools
├── 📁 system/                # Core system files
│   ├── 📁 admin/             # Admin functions
│   ├── 📁 auth/              # Authentication
│   └── 📁 includes/          # Core includes
├── 📁 resources/             # Static resources
└── 📄 README.md              # This file
```

## 🔐 Security Features

- **Credential Management**: Secure separation of sensitive data
- **Session Security**: Enhanced session handling with security measures
- **Input Validation**: Comprehensive input sanitization
- **SQL Injection Prevention**: Prepared statements throughout
- **XSS Protection**: Output encoding and validation

## 🛠️ Development

### Environment Setup
The application automatically detects the environment:
- **Development**: `localhost`, `127.0.0.1`, port `8080`
- **Production**: All other environments

### Database Configuration
- **Development**: SQLite (automatic setup)
- **Production**: MySQL/MariaDB

### API Integration
- **News API**: Real-time news data
- **OpenAI API**: AI-powered features (optional)

## 📖 Documentation

- [Setup Guide](README_SETUP.md) - Detailed installation instructions
- [Module Guide](docs/guides/MODULE_GUIDE.md) - Module-specific documentation
- [Testing Report](TESTING_REPORT.md) - Comprehensive testing results

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [NewsAPI.org](https://newsapi.org/) for news data
- [OpenAI](https://openai.com/) for AI capabilities
- [Tailwind CSS](https://tailwindcss.com/) for styling
- [Lucide Icons](https://lucide.dev/) for beautiful icons

## 📞 Support

For support and questions:
- Create an [issue](https://github.com/huntkil/my_new/issues)
- Check the [documentation](docs/)
- Review the [setup guide](README_SETUP.md)

---

**Made with ❤️ by [huntkil](https://github.com/huntkil)**

[![GitHub stars](https://img.shields.io/github/stars/huntkil/my_new?style=social)](https://github.com/huntkil/my_new/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/huntkil/my_new?style=social)](https://github.com/huntkil/my_new/network/members)
[![GitHub issues](https://img.shields.io/github/issues/huntkil/my_new)](https://github.com/huntkil/my_new/issues) 