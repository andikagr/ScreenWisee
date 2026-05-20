<?php
$host = 'aws-1-ap-northeast-1.pooler.supabase.com';
$dsn = "pgsql:host=$host;port=6543;dbname=postgres;sslmode=require";
try {
    $pdo = new PDO($dsn, 'postgres.amcjpmystwoioeymrxdr', 'AndTenjer@13', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt1 = $pdo->prepare("UPDATE users SET name = 'guru1' WHERE email = 'guru1@screenwise.com'");
    $stmt1->execute();
    $stmt2 = $pdo->prepare("UPDATE users SET name = 'guru2' WHERE email = 'guru2@screenwise.com'");
    $stmt2->execute();
    echo "Nama guru berhasil dikembalikan ke guru1 dan guru2!\n";
} catch (PDOException $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}
