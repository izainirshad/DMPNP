<?php
// PHP Info page for testing different PHP versions
echo "<h1>🐳 XAMPP Docker - PHP Info</h1>";
echo "<h2>Current PHP Version: " . PHP_VERSION . "</h2>";
echo "<hr>";
echo "<h3>Quick Links:</h3>";
echo "<ul>";
echo "<li><a href='admin.php'>Admin Panel</a></li>";
echo "<li><a href='index.php'>Home</a></li>";
echo "<li><a href='phpinfo.php'>PHP Info (Full)</a></li>";
echo "</ul>";
echo "<hr>";
echo "<h3>System Information:</h3>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Server Name:</strong> " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";

// Show loaded extensions
echo "<h3>Loaded Extensions:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
echo "<ul>";
foreach ($extensions as $ext) {
    echo "<li>" . $ext . "</li>";
}
echo "</ul>";

// Show environment variables
echo "<h3>Environment Variables:</h3>";
echo "<ul>";
foreach ($_ENV as $key => $value) {
    if (strpos($key, 'PHP') === 0 || strpos($key, 'DOCKER') === 0) {
        echo "<li><strong>" . $key . ":</strong> " . $value . "</li>";
    }
}
echo "</ul>";

// Show full phpinfo if requested
if (isset($_GET['full'])) {
    echo "<hr>";
    echo "<h2>Full PHP Info:</h2>";
    phpinfo();
}
?>
