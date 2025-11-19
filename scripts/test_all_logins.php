<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

// Bootstrap Laravel
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Security guard: require CLI execution and a matching secret key passed as first arg.
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line only.\n";
    exit(1);
}

$providedKey = $argv[1] ?? null;
$expectedKey = getenv('TEMP_SCRIPTS_KEY') ?: null;
if (empty($expectedKey)) {
    echo "TEMP_SCRIPTS_KEY is not set in environment. To run this script, add TEMP_SCRIPTS_KEY to your .env or system environment.\n";
    exit(1);
}

if ($providedKey !== $expectedKey) {
    echo "Invalid or missing key. Usage: php test_all_logins.php <key>\n";
    exit(1);
}

// Test login pour chaque type d'utilisateur
$users = [
    ['username' => 'admin', 'code_entreprise' => 'ADMIN001', 'type' => '0', 'name' => 'Administrateur'],
    ['username' => 'layka', 'code_entreprise' => 'YODI', 'type' => '1', 'name' => 'BIGNOUMBA Divassa I'],
    ['username' => 'jdoe', 'code_entreprise' => 'ENTR-2025', 'type' => '2', 'name' => 'John Doe Support']
];

foreach ($users as $userData) {
    echo "\nTesting user: {$userData['name']} (type {$userData['type']})\n";
    echo "Username: {$userData['username']}, Code: {$userData['code_entreprise']}\n";
    
    $user = App\Models\User::where('username', $userData['username'])
                          ->where('code_entreprise', $userData['code_entreprise'])
                          ->first();
    
    if (!$user) {
        echo "ERROR: User not found in database\n";
        continue;
    }
    
    echo "Found in DB: id={$user->id}, type={$user->type}, code_entreprise={$user->code_entreprise}\n";
    
    // Simulate request with these credentials
    // Use the actual passwords you provided for verification
    $passwords = [
        'admin' => 'admin123',
        'layka' => '1234567',
        'jdoe' => 'support123!'
    ];

    $request = Illuminate\Http\Request::create('/login', 'POST', [
        'username' => $userData['username'],
        'code_entreprise' => $userData['code_entreprise'],
        'password' => $passwords[$userData['username']] ?? 'password123'
    ]);

    $controller = new App\Http\Controllers\Auth\AuthenticatedSessionController();
    try {
        // Before attempting login check password hash and verification status
        $providedPassword = $request->get('password');
        $hashCheck = Illuminate\Support\Facades\Hash::check($providedPassword, $user->password);
        echo "Password hash check: " . ($hashCheck ? 'MATCH' : 'NO MATCH') . "\n";
        echo "email_verified_at: " . ($user->email_verified_at ?? 'NULL') . "\n";

        $response = $controller->store($request);
        if ($response instanceof Illuminate\Http\RedirectResponse) {
            echo "Login attempt resulted in redirect to: " . $response->getTargetUrl() . "\n";
        } else {
            echo "Unexpected response type: " . get_class($response) . "\n";
        }
    } catch (Exception $e) {
        echo "Error during login attempt: " . $e->getMessage() . "\n";
    }
    echo "----------------------------------------\n";
}