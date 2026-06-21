<?php

$config = require __DIR__ . "/../config/config.php";

$db_config = $config['db'];
$dsn = "mysql:host=" . $db_config['host'] . ";port=" . $db_config['port'] . ";dbname=" . $db_config['dbname'] . ";charset=utf8mb4;";
$pdo = new PDO($dsn, $db_config['user'], $db_config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

try {
    echo "Begin migration: \n";

    $sql = "CREATE TABLE IF NOT EXISTS schema_migrations (
    version VARCHAR(255) PRIMARY KEY,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";
    $pdo->exec($sql);

    echo "Checking for pending migrations... \n";
    $sql = "SELECT version FROM schema_migrations";
    $stmt = $pdo->query($sql);
    $executed_migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $migrations = scandir(__DIR__ . '/../src/Database/migrations');
    $migrations = array_diff($migrations, ['.', '..']);
    sort($migrations);
    $pending_migrations = array_diff($migrations, $executed_migrations);
    echo "Runnning pending migrations... \n";
    foreach ($pending_migrations as $path)
    {
        $path = __DIR__ . '/../src/Database/migrations/' . $path;
        $sql = file_get_contents($path);
        $pdo->exec($sql);
        $migration = basename($path);
        $sql = "
        INSERT INTO schema_migrations(version)
        VALUES (:version)
        ";
    
        $stmt = $pdo->prepare($sql);
        
        echo "Running migration {$migration}... \n";
        $stmt->execute([
            'version' => $migration
        ]);
        echo "Migration {$migration} completed.\n";
    }
    echo "Finished Successfully! \n";

} catch (Exception $e) {
    echo $e->getMessage();
}
