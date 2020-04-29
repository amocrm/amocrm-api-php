<?php

use AmoCRM\Exceptions\AmoCRMApiException;

function printError(AmoCRMApiException $e): void
{
    $errorTitle = $e->getTitle();
    $code = $e->getCode();
    $debugInfo = var_export($e->getLastRequestInfo(), true);

    $error = <<<EOF
Error: $errorTitle
Code: $code
Debug: $debugInfo
EOF;

    echo '<pre>' . $error . '</pre>';
}
