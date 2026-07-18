<?php
$vendorDir  = __DIR__ . '/vendor';
$staticFile = $vendorDir . '/composer/autoload_static.php';

// namespace => relative path suffix (starting from vendor/)
$newEntries = [
    'Spatie\\Permission\\'          => '/spatie/laravel-permission/src',
    'Laravel\\Sanctum\\'            => '/laravel/sanctum/src',
    'Laravel\\Reverb\\'             => '/laravel/reverb/src',
    'Stevebauman\\Purify\\'         => '/stevebauman/purify/src',
    'Stripe\\'                      => '/stripe/stripe-php/lib',
    'BladeUI\\Icons\\'              => '/blade-ui-kit/blade-icons/src',
    'Owenvoke\\BladeFontawesome\\'  => '/owenvoke/blade-fontawesome/src',
    'HTMLPurifier\\'                => '/ezyang/htmlpurifier/library',
];

$static = file_get_contents($staticFile);

// Group by first letter for prefixLengthsPsr4
$byLetter = [];
foreach ($newEntries as $ns => $path) {
    $letter = $ns[0];
    $byLetter[$letter][] = $ns;
}

// Helper: escape a PHP namespace for use inside single-quoted string literal
function phpStr(string $ns): string {
    // In single-quoted PHP strings, \ must be written as \\
    return str_replace('\\', '\\\\', $ns);
}

// Build length block entries (grouped by letter)
$lengthBlock = '';
foreach ($byLetter as $letter => $nsList) {
    // Check if letter already exists in static file
    if (strpos($static, "'" . $letter . "' =>\n        array") !== false) {
        // Letter exists — inject inside its array
        foreach ($nsList as $ns) {
            $len = strlen($ns);
            $insert = "            '" . phpStr($ns) . "' => " . $len . ",\n";
            $static = preg_replace(
                "/'" . preg_quote($letter, '/') . "' =>\\s*\n\\s*array \\(/",
                "'" . $letter . "' =>\n        array (\n" . $insert,
                $static,
                1
            );
        }
    } else {
        // New letter — build block to append
        $lengthBlock .= "        '$letter' =>\n        array (\n";
        foreach ($nsList as $ns) {
            $len = strlen($ns);
            $lengthBlock .= "            '" . phpStr($ns) . "' => " . $len . ",\n";
        }
        $lengthBlock .= "        ),\n";
    }
}

// Append new letter blocks before closing ); of prefixLengthsPsr4
if ($lengthBlock !== '') {
    $static = preg_replace(
        '/(public static \$prefixLengthsPsr4 = array \()(.*?)(\n    \);)/s',
        '$1$2' . $lengthBlock . '    );',
        $static
    );
}

// Build dirsPsr4 entries
$dirsBlock = '';
foreach ($newEntries as $ns => $path) {
    $dirsBlock .= "        '" . phpStr($ns) . "' => \n        array (\n            0 => __DIR__ . '/..' . '" . $path . "',\n        ),\n";
}

// Append before closing ); of prefixDirsPsr4
$static = preg_replace(
    '/(public static \$prefixDirsPsr4 = array \()(.*?)(\n    \);)/s',
    '$1$2' . $dirsBlock . '    );',
    $static
);

file_put_contents($staticFile, $static);
echo "autoload_static.php updated\n";

// Syntax check
exec('php -l ' . escapeshellarg($staticFile) . ' 2>&1', $out, $code);
echo implode("\n", $out) . "\n";

if ($code === 0) {
    require $vendorDir . '/autoload.php';
    echo class_exists('Spatie\Permission\Models\Permission') ? "Spatie: OK\n" : "Spatie: FAIL\n";
    echo class_exists('Laravel\Sanctum\Sanctum')             ? "Sanctum: OK\n" : "Sanctum: FAIL\n";
} else {
    echo "FAIL - restoring backup\n";
}
