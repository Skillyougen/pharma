<?php
define('DB_HOST', 'fdb1032.runhosting.com');
define('DB_NAME', '4763391_wpressb8458c2a');
define('DB_USER', '4763391_wpressb8458c2a');
define('DB_PASS', '67120Fof@ck');
define('DB_CHARSET', 'utf8mb4');

function getPDO(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:2rem;background:#fdf0f0;border:2px solid #db1d14;margin:2rem;border-radius:8px;"><h2 style="color:#db1d14">❌ Erreur de connexion BD</h2><p>'.htmlspecialchars($e->getMessage()).'</p><p>Vérifiez <code>config/database.php</code></p></div>');
        }
    }
    return $pdo;
}
