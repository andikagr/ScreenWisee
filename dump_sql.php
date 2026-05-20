<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

DB::setDefaultConnection('pgsql');
// We need to bypass the migrations table check.
// Actually, pretend doesn't bypass the migrations table check if the migrator tries to query it.
// The easiest way is to use a local MySQL or SQLite database, but Laravel migrations are mostly DB-agnostic.
// Let's just output the contents of the migration files and I will translate them to SQL.
