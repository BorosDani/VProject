<?php
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));
define('BASE_URL', '');

date_default_timezone_set('Europe/Budapest');

function base_url($path = '') 
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $base = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    
    $base = str_replace('/pages/', '/', $base);
    $base = str_replace('/emailsend/', '/', $base);
    $base = str_replace('/includes/', '/', $base);
    
    return $protocol . "://" . $host . $base . ltrim($path, '/');
}

class Database 
{
    private $username   = "villamme_dodo";
    private $password   = ".m]8+y!w~~oVp2=l";
    private $host       = "localhost";
    private $dbname     = "villamme_villammelo";
    private $charset    = "utf8mb4";
    
    protected $pdo = null;

    public function __construct() 
    {
        try 
        {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            $this->pdo->exec("SET NAMES utf8mb4");
        } 
        catch (PDOException $e) 
        {
            error_log("Database error: " . $e->getMessage());
            die("Adatbázis kapcsolati hiba. Próbáld később!");
        }
    }

    public function get() 
    {
        return $this->pdo;
    }

    public function prepare($sql) 
    {
        return $this->pdo->prepare($sql);
    }

    public function query($sql, $params = []) 
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function lastInsertId() 
    {
        return $this->pdo->lastInsertId();
    }
    
    public function fetchAll($sql, $params = []) 
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function fetchOne($sql, $params = []) 
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function rowCount($sql, $params = []) 
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
}
?>