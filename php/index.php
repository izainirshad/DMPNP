<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XAMPP Docker - Welcome</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .header { text-align: center; color: white; margin-bottom: 3rem; }
        .header h1 { font-size: 3rem; margin-bottom: 1rem; }
        .header p { font-size: 1.2rem; opacity: 0.9; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card h2 { color: #2c3e50; margin-bottom: 1rem; }
        .card p { color: #7f8c8d; margin-bottom: 1.5rem; }
        .btn { display: inline-block; background: #3498db; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px; transition: background 0.3s; }
        .btn:hover { background: #2980b9; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #229954; }
        .btn-warning { background: #f39c12; }
        .btn-warning:hover { background: #e67e22; }
        .status { background: #ecf0f1; padding: 1rem; border-radius: 6px; margin-bottom: 2rem; }
        .status h3 { color: #2c3e50; margin-bottom: 0.5rem; }
        .status p { color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐳 XAMPP Docker</h1>
            <p>Your local development environment is ready!</p>
        </div>

        <div class="status">
            <h3>System Status</h3>
            <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?> | <strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?> | <strong>Status:</strong> ✅ Running</p>
        </div>

        <div class="cards">
            <div class="card">
                <h2>🎛️ Admin Panel</h2>
                <p>Manage PHP versions, nginx configurations, and Docker services through our web-based admin interface.</p>
                <a href="admin.php" class="btn btn-success">Open Admin Panel</a>
            </div>

            <div class="card">
                <h2>📊 PHP Info</h2>
                <p>View detailed PHP configuration, loaded extensions, and system information.</p>
                <a href="phpinfo.php" class="btn">View PHP Info</a>
                <a href="phpinfo.php?full=1" class="btn btn-warning">Full PHP Info</a>
            </div>

            <div class="card">
                <h2>🗄️ phpMyAdmin</h2>
                <p>Access your MySQL database through the phpMyAdmin web interface.</p>
                <a href="http://localhost:8081" class="btn" target="_blank">Open phpMyAdmin</a>
            </div>

            <div class="card">
                <h2>📁 File Manager</h2>
                <p>Your web files are located in the <code>/php</code> directory. Edit them directly or use your favorite IDE.</p>
                <p><strong>Document Root:</strong> <code><?php echo $_SERVER['DOCUMENT_ROOT']; ?></code></p>
            </div>

            <div class="card">
                <h2>🔧 Services</h2>
                <p>All services are running in Docker containers:</p>
                <ul style="margin-top: 1rem; color: #7f8c8d;">
                    <li>Nginx: <code>localhost:8080</code></li>
                    <li>MySQL: <code>localhost:3306</code></li>
                    <li>phpMyAdmin: <code>localhost:8081</code></li>
                </ul>
            </div>

            <div class="card">
                <h2>📚 Quick Start</h2>
                <p>Get started with your development:</p>
                <ul style="margin-top: 1rem; color: #7f8c8d;">
                    <li>Create PHP files in this directory</li>
                    <li>Use the admin panel to manage configurations</li>
                    <li>Access databases via phpMyAdmin</li>
                    <li>Multiple PHP versions available</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>