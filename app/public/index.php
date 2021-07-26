<?php

use App\Kernel;
echo '<pre>';
    print_r(phpinfo());
echo '</pre>';
exit;
require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
