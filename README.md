# 🐳 XAMPP Docker - Web Admin Panel

A modern, web-based admin panel for managing your XAMPP-like Docker development environment with multiple PHP versions and nginx virtual servers.

## 🚀 Quick Start

1. **Start the services:**
   ```bash
   docker-compose up -d
   ```

2. **Access the admin panel:**
   - Main Dashboard: http://localhost:8080
   - Admin Panel: http://localhost:8080/admin.php
   - phpMyAdmin: http://localhost:8081

3. **Default login:**
   - Admin Panel Password: `admin123`

## 🎛️ Features

### Admin Panel (`/admin.php`)
- **PHP Version Management**: Switch between PHP 7.4, 8.0, 8.1, 8.2, and 8.3
- **Nginx Virtual Servers**: Create and manage multiple virtual server configurations
- **Docker Service Management**: Start, stop, and restart services
- **Real-time Configuration**: No need to manually edit config files

### Available Services
- **Nginx**: Web server (port 8080)
- **PHP-FPM**: Multiple versions available
- **MySQL**: Database server (port 3306)
- **phpMyAdmin**: Database management (port 8081)

## 📁 Project Structure

```
xamp/
├── docker-compose.yaml    # Docker services configuration
├── nginx/                 # Nginx configuration files
│   ├── default.conf      # Default nginx config
│   └── sample.conf       # Sample virtual server config
├── php/                  # Web files directory
│   ├── index.php         # Main dashboard
│   ├── admin.php         # Admin panel
│   └── phpinfo.php       # PHP information page
└── README.md             # This file
```

## 🔧 Usage

### Creating Virtual Servers

1. Go to the Admin Panel → Nginx Configs tab
2. Fill in the form:
   - **Configuration Name**: Unique name for your config
   - **Server Name**: Domain name (e.g., `mysite.local`)
   - **Document Root**: Path to your files (default: `/var/www/html`)
   - **PHP Service**: Choose which PHP version to use
   - **Custom Configuration**: Additional nginx directives (optional)

3. Click "Create Virtual Server"

### Managing PHP Versions

The system comes with multiple PHP versions pre-configured:
- `php` (8.3) - Default
- `php74` (7.4)
- `php81` (8.1)
- `php82` (8.2)
- `php83` (8.3)

Each virtual server can use a different PHP version by selecting the appropriate service in the admin panel.

### Database Access

- **MySQL**: `localhost:3306`
  - Username: `root`
  - Password: `root`
  - Database: `xampp_db`
  - User: `xampp_user` / Password: `xampp_pass`

- **phpMyAdmin**: http://localhost:8081

## 🛠️ Development

### Adding New PHP Files
Place your PHP files in the `php/` directory. They will be accessible at:
- http://localhost:8080/yourfile.php

### Custom Nginx Configurations
Create `.conf` files in the `nginx/` directory or use the admin panel to generate them automatically.

### Docker Commands
```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# Restart services
docker-compose restart

# View logs
docker-compose logs -f

# Rebuild containers
docker-compose up -d --build
```

## 🔒 Security Notes

- Change the default admin password in `admin.php`
- The admin panel is accessible to anyone who can reach your server
- Consider adding authentication for production use
- Database credentials are set in `docker-compose.yaml`

## 🐛 Troubleshooting

### Port Conflicts
If you get port conflicts, modify the ports in `docker-compose.yaml`:
```yaml
ports:
  - "8080:80"  # Change 8080 to another port
```

### Permission Issues
If you have file permission issues:
```bash
sudo chown -R $USER:$USER ./php
chmod -R 755 ./php
```

### Container Issues
If containers fail to start:
```bash
docker-compose down
docker system prune -f
docker-compose up -d
```

## 📝 License

This project is open source and available under the MIT License.

## 🤝 Contributing

Feel free to submit issues and enhancement requests!

---

**Happy coding! 🚀**
