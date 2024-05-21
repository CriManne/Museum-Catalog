<?php

declare(strict_types=1);

return
    array_merge(
        require(APP_PATH . '/config/routes/api/routes.php'),
        require(APP_PATH . '/config/routes/pages/routes.php')
    );
