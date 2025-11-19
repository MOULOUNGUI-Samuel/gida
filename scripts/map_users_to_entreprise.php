<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

// Bootstrap Laravel
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line only.\n";
    exit(1);
}

$providedKey = $argv[1] ?? null;
$expectedKey = getenv('TEMP_SCRIPTS_KEY') ?: null;
if (empty($expectedKey)) {
    echo "TEMP_SCRIPTS_KEY is not set in environment.\n";
    exit(1);
}
if ($providedKey !== $expectedKey) {
    echo "Invalid or missing key. Usage: php map_users_to_entreprise.php <key>\n";
    exit(1);
}

use App\Models\User;
use App\Models\Entreprise;

$users = User::whereNull('entreprise_id')->whereNotNull('code_entreprise')->get();

foreach ($users as $u) {
    $code = $u->code_entreprise;
    $entreprise = Entreprise::where('code', $code)->orWhere('nom', $code)->first();
    if ($entreprise) {
        $u->entreprise_id = $entreprise->id;
        $u->save();
        echo "Mapped user {$u->username} to entreprise id {$entreprise->id} ({$entreprise->nom})\n";
    } else {
        echo "No entreprise found for user {$u->username} with code '{$code}'\n";
    }
}

echo "Done.\n";
