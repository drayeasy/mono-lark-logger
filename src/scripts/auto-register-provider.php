<?php

// auto-register-provider.php

$providersPath = app_path() . '/../bootstrap/providers.php';
$providerToAdd = "App\Providers\MonoLarkLoggerServiceProvider::class";


$content = file_get_contents($providersPath);


if (!str_contains($content, $providerToAdd)) {

    $content = str_replace('];', "    $providerToAdd\n];", $content);

    file_put_contents($providersPath, $content);
}
