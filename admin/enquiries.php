<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';
require __DIR__ . '/_init.php';

require_admin();

$pdo = database();

if (is_post()) {
    require_post_csrf();
    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);

    if ($action === 'status' && $id > 0) {
        $status = (string) ($_POST['status'] ?? 'read');
        if (!in_array($status, ['new', 'read', 'closed'], true)) {
            $status = 'read';
        }
        $pdo->prepare('UPDATE enquiries SET status = :status WHERE id = :id')->execute([
            'status' => $status,
            'id' => $id,
        ]);
        set_flash('success', 'Enquiry updated.');
    }

    if ($action === 'delete' && $id > 0) {
        $pdo->prepare('DELETE FROM enquiries WHERE id = :id')->execute(['id' => $id]);
        set_flash('success', 'Enquiry deleted.');
    }

    redirect(url('/admin/enquiries.php'));
}

$rows = $pdo->query('SELECT * FROM enquiries ORDER BY id DESC')->fetchAll();
admin_header('Enquiries');
?>
<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Contact</th>
                <th>Interest</th>
                <th>Message</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td>
                    <strong><?= e($row['name']); ?></strong><br>
                    <?= e($row['email']); ?><br>
                    <?= e((string) $row['phone']); ?><br>
                    <span class="address"><?= e((string) $row['company']); ?> · <?= e((string) $row['project_location']); ?></span><br>
                    <span class="address"><?= e($row['created_at']); ?></span>
                </td>
                <td><?= e((string) $row['service_interest']); ?></td>
                <td><?= e(truncate((string) $row['message'], 180)); ?></td>
                <td><?= e($row['status']); ?></td>
                <td>
                    <form method="post" style="display:grid;gap:0.4rem;">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                        <input type="hidden" name="id" value="<?= (int) $row['id']; ?>">
                        <input type="hidden" name="action" value="status">
                        <select name="status">
                            <?php foreach (['new', 'read', 'closed'] as $status): ?>
                                <option value="<?= $status; ?>" <?= $row['status'] === $status ? 'selected' : ''; ?>><?= $status; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="button secondary" type="submit">Update</button>
                    </form>
                    <form method="post" onsubmit="return confirm('Delete enquiry?');" style="margin-top:0.4rem;">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                        <input type="hidden" name="id" value="<?= (int) $row['id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button class="button secondary" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$rows): ?>
            <tr><td colspan="5">No enquiries yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
