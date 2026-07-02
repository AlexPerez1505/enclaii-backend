<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=endocare', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tables = ['users', 'password_reset_tokens', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs'];

$pdo->exec("SET FOREIGN_KEY_CHECKS=0");

foreach ($tables as $t) {
    try { $pdo->exec("CREATE TABLE IF NOT EXISTS `$t` (id int) ENGINE=InnoDB"); } catch(Exception $e) {}
    try { $pdo->exec("ALTER TABLE `$t` DISCARD TABLESPACE"); echo "DISCARD $t OK\n"; } catch(Exception $e) { echo "DISCARD $t skip: ".$e->getMessage()."\n"; }
    try { $pdo->exec("DROP TABLE IF EXISTS `$t`"); echo "DROP $t OK\n"; } catch(Exception $e) { echo "DROP $t fail: ".$e->getMessage()."\n"; }
}

$pdo->exec("SET FOREIGN_KEY_CHECKS=1");
echo "\nRecreando tablas...\n";

// users
try {
    $pdo->exec("CREATE TABLE `users` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL,
      `email_verified_at` timestamp NULL DEFAULT NULL,
      `password` varchar(255) NOT NULL,
      `remember_token` varchar(100) DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `users_email_unique` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "users OK\n";
} catch(Exception $e) { echo "users: ".$e->getMessage()."\n"; }

// password_reset_tokens
try {
    $pdo->exec("CREATE TABLE `password_reset_tokens` (
      `email` varchar(255) NOT NULL,
      `token` varchar(255) NOT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "password_reset_tokens OK\n";
} catch(Exception $e) { echo "password_reset_tokens: ".$e->getMessage()."\n"; }

// cache
try {
    $pdo->exec("CREATE TABLE `cache` (
      `key` varchar(255) NOT NULL,
      `value` mediumtext NOT NULL,
      `expiration` int NOT NULL,
      PRIMARY KEY (`key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "cache OK\n";
} catch(Exception $e) { echo "cache: ".$e->getMessage()."\n"; }

// cache_locks
try {
    $pdo->exec("CREATE TABLE `cache_locks` (
      `key` varchar(255) NOT NULL,
      `owner` varchar(255) NOT NULL,
      `expiration` int NOT NULL,
      PRIMARY KEY (`key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "cache_locks OK\n";
} catch(Exception $e) { echo "cache_locks: ".$e->getMessage()."\n"; }

// jobs
try {
    $pdo->exec("CREATE TABLE `jobs` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `queue` varchar(255) NOT NULL,
      `payload` longtext NOT NULL,
      `attempts` tinyint unsigned NOT NULL,
      `reserved_at` int unsigned DEFAULT NULL,
      `available_at` int unsigned NOT NULL,
      `created_at` int unsigned NOT NULL,
      PRIMARY KEY (`id`),
      KEY `jobs_queue_index` (`queue`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "jobs OK\n";
} catch(Exception $e) { echo "jobs: ".$e->getMessage()."\n"; }

// job_batches
try {
    $pdo->exec("CREATE TABLE `job_batches` (
      `id` varchar(255) NOT NULL,
      `name` varchar(255) NOT NULL,
      `total_jobs` int NOT NULL,
      `pending_jobs` int NOT NULL,
      `failed_jobs` int NOT NULL,
      `failed_job_ids` longtext NOT NULL,
      `options` mediumtext DEFAULT NULL,
      `cancelled_at` int DEFAULT NULL,
      `created_at` int NOT NULL,
      `finished_at` int DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "job_batches OK\n";
} catch(Exception $e) { echo "job_batches: ".$e->getMessage()."\n"; }

// failed_jobs
try {
    $pdo->exec("CREATE TABLE `failed_jobs` (
      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
      `uuid` varchar(255) NOT NULL,
      `connection` text NOT NULL,
      `queue` text NOT NULL,
      `payload` longtext NOT NULL,
      `exception` longtext NOT NULL,
      `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "failed_jobs OK\n";
} catch(Exception $e) { echo "failed_jobs: ".$e->getMessage()."\n"; }

echo "\nListo.\n";
