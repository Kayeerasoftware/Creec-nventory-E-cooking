<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing database connection...\n";
    
    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✓ Database connection successful!\n\n";
    
    // Check if users table exists
    $tables = DB::select("SHOW TABLES LIKE 'users'");
    if (empty($tables)) {
        echo "✗ Users table does not exist!\n";
        echo "Please run: php artisan migrate\n";
        exit(1);
    }
    echo "✓ Users table exists\n\n";
    
    // Count users
    $userCount = DB::table('users')->count();
    echo "Total users in database: $userCount\n\n";
    
    if ($userCount === 0) {
        echo "✗ No users found in database!\n";
        echo "Please run: php artisan db:seed --class=ComprehensiveUserSeeder\n";
        exit(1);
    }
    
    // List all users
    $users = DB::table('users')->select('id', 'name', 'email', 'role')->get();
    echo "Users in database:\n";
    echo str_repeat('-', 80) . "\n";
    printf("%-5s %-30s %-30s %-15s\n", "ID", "Name", "Email", "Role");
    echo str_repeat('-', 80) . "\n";
    
    foreach ($users as $user) {
        printf("%-5s %-30s %-30s %-15s\n", 
            $user->id, 
            $user->name, 
            $user->email, 
            $user->role
        );
    }
    echo str_repeat('-', 80) . "\n";
    
    echo "\n✓ All checks passed! Users are loading correctly.\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
