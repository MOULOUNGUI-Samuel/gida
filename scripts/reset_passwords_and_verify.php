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
    echo "Invalid or missing key. Usage: php reset_passwords_and_verify.php <key>\n";
    exit(1);
}

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$updates = [
    ['username' => 'admin', 'password' => 'admin123'],
    ['username' => 'layka', 'password' => '1234567'],
    // Temporary password for support user; change after login if needed
    ['username' => 'jdoe', 'password' => 'support123!'],
];

foreach ($updates as $u) {
    $user = User::where('username', $u['username'])->first();
    if (!$user) {
        echo "User {$u['username']} not found\n";
        continue;
    }
    $user->password = Hash::make($u['password']);
    $user->email_verified_at = date('Y-m-d H:i:s');
    $user->save();
    echo "Updated {$u['username']} password and email_verified_at\n";
}

echo "Done.\n";