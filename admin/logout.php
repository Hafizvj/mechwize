<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

logout_admin();
set_flash('success', 'You have been signed out.');
redirect(url('/admin/login.php'));
