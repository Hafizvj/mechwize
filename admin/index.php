<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';
require __DIR__ . '/_init.php';

require_admin();

$pdo = database();
$counts = [
    'services' => (int) $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn(),
    'projects' => (int) $pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
    'clients' => (int) $pdo->query('SELECT COUNT(*) FROM clients')->fetchColumn(),
    'enquiries' => (int) $pdo->query('SELECT COUNT(*) FROM enquiries')->fetchColumn(),
];

$latest = $pdo->query('SELECT id, name, email, service_interest, status, created_at FROM enquiries ORDER BY id DESC LIMIT 8')->fetchAll();

admin_header('Dashboard');
?>
<div class="stats-grid">
    <div class="admin-card"><strong><?= $counts['services']; ?></strong><div class="address">Services</div></div>
    <div class="admin-card"><strong><?= $counts['projects']; ?></strong><div class="address">Projects</div></div>
    <div class="admin-card"><strong><?= $counts['clients']; ?></strong><div class="address">Clients</div></div>
    <div class="admin-card"><strong><?= $counts['enquiries']; ?></strong><div class="address">Enquiries</div></div>
</div>
<div class="admin-card">
    <h2 style="font-size:1.4rem;">Latest enquiries</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Interest</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($latest as $row): ?>
            <tr>
                <td><?= e($row['name']); ?></td>
                <td><?= e($row['email']); ?></td>
                <td><?= e((string) $row['service_interest']); ?></td>
                <td><?= e($row['status']); ?></td>
                <td><?= e($row['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$latest): ?>
            <tr><td colspan="5">No enquiries yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
