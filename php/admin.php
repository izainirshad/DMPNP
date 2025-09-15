<?php
// Admin Panel for XAMPP-like Docker Setup
session_start();

// Simple authentication (you can enhance this)
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['password']) && $_POST['password'] === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
    } else {
        showLoginForm();
        exit;
    }
}

function showLoginForm() {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XAMPP Admin - Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 50px; }
        .login-container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007cba; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        button:hover { background: #005a87; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>XAMPP Admin Panel</h2>
        <form method="POST">
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="error">Default password: admin123</div>
    </div>
</body>
</html>';
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Handle AJAX requests
if (isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'get_php_versions':
            echo json_encode(getAvailablePHPVersions());
            break;
        case 'get_nginx_configs':
            $configs = getNginxConfigs();
            echo json_encode(['success' => true, 'data' => $configs]);
            break;
        case 'save_nginx_config':
            $result = saveNginxConfig($_POST['name'], $_POST['config']);
            echo json_encode(['success' => $result, 'message' => $result ? 'Configuration saved successfully' : 'Failed to save configuration']);
            break;
        case 'delete_nginx_config':
            $result = deleteNginxConfig($_POST['name']);
            echo json_encode(['success' => $result]);
            break;
        case 'restart_services':
            $result = restartDockerServices();
            echo json_encode(['success' => $result]);
            break;
    }
    exit;
}

function getAvailablePHPVersions() {
    return [
        '7.4' => 'PHP 7.4',
        '8.0' => 'PHP 8.0', 
        '8.1' => 'PHP 8.1',
        '8.2' => 'PHP 8.2',
        '8.3' => 'PHP 8.3'
    ];
}

function getNginxConfigs() {
    $configs = [];
    // The nginx configs are now mounted at /var/www/html/nginx/
    $nginxDir = '/var/www/html/nginx/';
    
    if (is_dir($nginxDir)) {
        $files = glob($nginxDir . '*.conf');
        foreach ($files as $file) {
            $name = basename($file, '.conf');
            $configs[$name] = [
                'name' => $name,
                'content' => file_get_contents($file),
                'file' => $file
            ];
        }
    }
    
    return $configs;
}

function saveNginxConfig($name, $config) {
    // The nginx configs are now mounted at /var/www/html/nginx/
    $nginxDir = '/var/www/html/nginx/';
    
    if (!is_dir($nginxDir)) {
        mkdir($nginxDir, 0755, true);
    }
    
    $file = $nginxDir . $name . '.conf';
    return file_put_contents($file, $config) !== false;
}

function deleteNginxConfig($name) {
    $file = '/var/www/html/nginx/' . $name . '.conf';
    if (file_exists($file)) {
        return unlink($file);
    }
    return false;
}

function restartDockerServices() {
    $output = [];
    $return_var = 0;
    exec('docker-compose restart 2>&1', $output, $return_var);
    return $return_var === 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XAMPP Admin Panel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 1.5rem; }
        .logout-btn { background: #e74c3c; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; }
        .logout-btn:hover { background: #c0392b; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        .tabs { display: flex; background: white; border-radius: 8px 8px 0 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .tab { padding: 1rem 2rem; cursor: pointer; border-bottom: 3px solid transparent; transition: all 0.3s; }
        .tab.active { border-bottom-color: #3498db; background: #ecf0f1; }
        .tab:hover { background: #ecf0f1; }
        .tab-content { background: white; padding: 2rem; border-radius: 0 0 8px 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: none; }
        .tab-content.active { display: block; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; color: #2c3e50; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; }
        .form-group textarea { height: 200px; font-family: monospace; }
        .btn { background: #3498db; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; margin-right: 0.5rem; }
        .btn:hover { background: #2980b9; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .config-list { margin-top: 2rem; }
        .config-item { background: #f8f9fa; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; border-left: 4px solid #3498db; }
        .config-item h3 { margin-bottom: 0.5rem; color: #2c3e50; }
        .config-item pre { background: #2c3e50; color: #ecf0f1; padding: 1rem; border-radius: 4px; overflow-x: auto; margin-top: 0.5rem; }
        .status { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .status.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>🐳 XAMPP Docker Admin Panel</h1>
        <a href="?logout=1" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <div class="tabs">
            <div class="tab active" onclick="showTab('php')">PHP Versions</div>
            <div class="tab" onclick="showTab('nginx')">Nginx Configs</div>
            <div class="tab" onclick="showTab('services')">Docker Services</div>
        </div>

        <!-- PHP Versions Tab -->
        <div id="php-tab" class="tab-content active">
            <h2>PHP Version Management</h2>
            <div class="grid">
                <div>
                    <h3>Available PHP Versions</h3>
                    <div id="php-versions-list"></div>
                </div>
                <div>
                    <h3>Add New PHP Version</h3>
                    <form id="php-version-form">
                        <div class="form-group">
                            <label for="php-version">PHP Version:</label>
                            <select id="php-version" name="version">
                                <option value="7.4">PHP 7.4</option>
                                <option value="8.0">PHP 8.0</option>
                                <option value="8.1">PHP 8.1</option>
                                <option value="8.2">PHP 8.2</option>
                                <option value="8.3" selected>PHP 8.3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="php-name">Service Name:</label>
                            <input type="text" id="php-name" name="name" placeholder="e.g., php74, php81" required>
                        </div>
                        <button type="submit" class="btn btn-success">Add PHP Version</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Nginx Configs Tab -->
        <div id="nginx-tab" class="tab-content">
            <h2>Nginx Virtual Server Management</h2>
            <div class="grid">
                <div>
                    <h3>Create New Virtual Server</h3>
                    <form id="nginx-config-form">
                        <div class="form-group">
                            <label for="config-name">Configuration Name:</label>
                            <input type="text" id="config-name" name="name" placeholder="e.g., mysite, api" required>
                        </div>
                        <div class="form-group">
                            <label for="server-name">Server Name:</label>
                            <input type="text" id="server-name" name="server_name" placeholder="e.g., localhost, mysite.local" required>
                        </div>
                        <div class="form-group">
                            <label for="document-root">Document Root:</label>
                            <input type="text" id="document-root" name="document_root" value="/var/www/html" required>
                        </div>
                        <div class="form-group">
                            <label for="php-service">PHP Service:</label>
                            <select id="php-service" name="php_service">
                                <option value="php">php (8.3 - Default)</option>
                                <option value="php74">php74 (7.4)</option>
                                <option value="php81">php81 (8.1)</option>
                                <option value="php82">php82 (8.2)</option>
                                <option value="php83">php83 (8.3)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="custom-config">Custom Configuration (Optional):</label>
                            <textarea id="custom-config" name="custom_config" placeholder="Additional nginx directives..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Create Virtual Server</button>
                    </form>
                </div>
                <div>
                    <h3>Existing Configurations</h3>
                    <div id="nginx-configs-list"></div>
                </div>
            </div>
        </div>

        <!-- Docker Services Tab -->
        <div id="services-tab" class="tab-content">
            <h2>Docker Services Management</h2>
            <div class="status" id="service-status" style="display: none;"></div>
            <div class="grid">
                <div>
                    <h3>Service Actions</h3>
                    <button onclick="restartServices()" class="btn">Restart All Services</button>
                    <button onclick="stopServices()" class="btn btn-danger">Stop All Services</button>
                    <button onclick="startServices()" class="btn btn-success">Start All Services</button>
                </div>
                <div>
                    <h3>Service Status</h3>
                    <div id="service-info">
                        <p><strong>Nginx:</strong> <span id="nginx-status">Running on port 8080</span></p>
                        <p><strong>PHP:</strong> <span id="php-status">Running (8.3-fpm)</span></p>
                        <p><strong>MySQL:</strong> <span id="mysql-status">Running on port 3306</span></p>
                        <p><strong>phpMyAdmin:</strong> <span id="phpmyadmin-status">Running on port 8081</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
            
            // Load tab content
            if (tabName === 'nginx') {
                loadNginxConfigs();
            } else if (tabName === 'php') {
                loadPHPVersions();
            }
        }

        // Load PHP versions
        function loadPHPVersions() {
            fetch('admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=get_php_versions'
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('php-versions-list');
                container.innerHTML = '';
                Object.entries(data).forEach(([version, name]) => {
                    const div = document.createElement('div');
                    div.className = 'config-item';
                    div.innerHTML = `
                        <h3>${name}</h3>
                        <p>Version: ${version}</p>
                        <p>Status: Available</p>
                    `;
                    container.appendChild(div);
                });
            });
        }

        // Load Nginx configurations
        function loadNginxConfigs() {
            fetch('admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=get_nginx_configs'
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('nginx-configs-list');
                container.innerHTML = '';
                
                if (data.success && data.data) {
                    Object.entries(data.data).forEach(([name, config]) => {
                        const div = document.createElement('div');
                        div.className = 'config-item';
                        div.innerHTML = `
                            <h3>${name}.conf</h3>
                            <pre>${config.content}</pre>
                            <button onclick="deleteConfig('${name}')" class="btn btn-danger">Delete</button>
                        `;
                        container.appendChild(div);
                    });
                } else {
                    container.innerHTML = '<p>No configurations found or error loading configurations.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading nginx configs:', error);
                document.getElementById('nginx-configs-list').innerHTML = '<p>Error loading configurations. Check console for details.</p>';
            });
        }

        // Create Nginx configuration
        document.getElementById('nginx-config-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const configName = formData.get('name');
            const serverName = formData.get('server_name');
            const documentRoot = formData.get('document_root');
            const phpService = formData.get('php_service');
            const customConfig = formData.get('custom_config');
            
            const nginxConfig = `server {
    listen 80;
    server_name ${serverName};

    root ${documentRoot};
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \\.php$ {
        include fastcgi_params;
        fastcgi_pass ${phpService}:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_index index.php;
    }
    
    ${customConfig}
}`;

            fetch('admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=save_nginx_config&name=${configName}&config=${encodeURIComponent(nginxConfig)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatus(data.message || 'Virtual server created successfully!', 'success');
                    this.reset();
                    loadNginxConfigs();
                } else {
                    showStatus(data.message || 'Error creating virtual server', 'error');
                }
            })
            .catch(error => {
                console.error('Error saving nginx config:', error);
                showStatus('Error creating virtual server. Check console for details.', 'error');
            });
        });

        // Delete configuration
        function deleteConfig(name) {
            if (confirm('Are you sure you want to delete this configuration?')) {
                fetch('admin.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete_nginx_config&name=${name}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showStatus('Configuration deleted successfully!', 'success');
                        loadNginxConfigs();
                    } else {
                        showStatus('Error deleting configuration', 'error');
                    }
                });
            }
        }

        // Restart services
        function restartServices() {
            fetch('admin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=restart_services'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatus('Services restarted successfully!', 'success');
                } else {
                    showStatus('Error restarting services', 'error');
                }
            });
        }

        function stopServices() {
            showStatus('Stopping services...', 'info');
            // Add actual Docker stop command here
        }

        function startServices() {
            showStatus('Starting services...', 'info');
            // Add actual Docker start command here
        }

        // Show status message
        function showStatus(message, type) {
            const status = document.getElementById('service-status');
            status.textContent = message;
            status.className = `status ${type}`;
            status.style.display = 'block';
            setTimeout(() => {
                status.style.display = 'none';
            }, 3000);
        }

        // Load initial data
        loadPHPVersions();
    </script>
</body>
</html>
