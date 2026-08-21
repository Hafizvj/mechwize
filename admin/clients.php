<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';
require __DIR__ . '/_init.php';

require_admin();

$pdo = database();
$action = (string) ($_GET['action'] ?? 'list');
$id = (int) ($_GET['id'] ?? 0);

if (is_post()) {
    require_post_csrf();
    $postAction = (string) ($_POST['action'] ?? '');

    if ($postAction === 'delete') {
        $deleteId = (int) ($_POST['id'] ?? 0);
        $row = $pdo->prepare('SELECT logo_path FROM clients WHERE id = :id');
        $row->execute(['id' => $deleteId]);
        $client = $row->fetch();
        if ($client) {
            delete_upload($client['logo_path'] ?? null);
            $pdo->prepare('DELETE FROM clients WHERE id = :id')->execute(['id' => $deleteId]);
            set_flash('success', 'Client deleted.');
        }
        redirect(url('/admin/clients.php'));
    }

    $editId = (int) ($_POST['id'] ?? 0);
    $data = [
        'name' => trim((string) ($_POST['name'] ?? '')),
        'logo_alt' => trim((string) ($_POST['logo_alt'] ?? '')),
        'industry' => trim((string) ($_POST['industry'] ?? '')),
        'website' => trim((string) ($_POST['website'] ?? '')),
        'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_published' => isset($_POST['is_published']) ? 1 : 0,
    ];

    try {
        $logo = store_upload($_FILES['logo_path'] ?? [], 'clients', 'client');
    } catch (Throwable $exception) {
        set_flash('error', $exception->getMessage());
        redirect(url('/admin/clients.php?action=' . ($editId ? 'edit&id=' . $editId : 'new')));
    }

    if ($data['name'] === '') {
        set_flash('error', 'Client name is required.');
        redirect(url('/admin/clients.php?action=' . ($editId ? 'edit&id=' . $editId : 'new')));
    }

    if ($data['logo_alt'] === '') {
        $data['logo_alt'] = $data['name'];
    }

    if ($editId > 0) {
        $existing = $pdo->prepare('SELECT logo_path FROM clients WHERE id = :id');
        $existing->execute(['id' => $editId]);
        $current = $existing->fetch() ?: [];
        if ($logo) {
            delete_upload($current['logo_path'] ?? null);
        } else {
            $logo = $current['logo_path'] ?? null;
        }

        $statement = $pdo->prepare(
            'UPDATE clients SET name=:name, logo_path=:logo_path, logo_alt=:logo_alt, industry=:industry, website=:website,
             sort_order=:sort_order, is_featured=:is_featured, is_published=:is_published WHERE id=:id'
        );
        $statement->execute($data + ['logo_path' => $logo, 'id' => $editId]);
        set_flash('success', 'Client updated.');
    } else {
        $statement = $pdo->prepare(
            'INSERT INTO clients (name, logo_path, logo_alt, industry, website, sort_order, is_featured, is_published)
             VALUES (:name, :logo_path, :logo_alt, :industry, :website, :sort_order, :is_featured, :is_published)'
        );
        $statement->execute($data + ['logo_path' => $logo]);
        set_flash('success', 'Client created.');
    }

    redirect(url('/admin/clients.php'));
}

if ($action === 'new' || $action === 'edit') {
    $client = [
        'id' => 0,
        'name' => '',
        'logo_alt' => '',
        'industry' => '',
        'website' => '',
        'sort_order' => 0,
        'is_featured' => 0,
        'is_published' => 1,
        'logo_path' => null,
    ];

    if ($action === 'edit' && $id > 0) {
        $statement = $pdo->prepare('SELECT * FROM clients WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        if (!$row) {
            set_flash('error', 'Client not found.');
            redirect(url('/admin/clients.php'));
        }
        $client = $row;
    }

    admin_header($action === 'new' ? 'Add client' : 'Edit client');
    ?>
    <form class="contact-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <input type="hidden" name="id" value="<?= (int) $client['id']; ?>">
        <div class="form-row">
            <label for="name">Name *</label>
            <input id="name" name="name" required value="<?= e((string) $client['name']); ?>">
        </div>
        <div class="form-row two">
            <div>
                <label for="industry">Industry</label>
                <input id="industry" name="industry" value="<?= e((string) $client['industry']); ?>">
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" value="<?= (int) $client['sort_order']; ?>">
            </div>
        </div>
        <div class="form-row">
            <label for="website">Website</label>
            <input id="website" name="website" type="url" value="<?= e((string) $client['website']); ?>">
        </div>
        <div class="form-row two">
            <div>
                <label for="logo_path">Logo</label>
                <input id="logo_path" name="logo_path" type="file" accept="image/*">
            </div>
            <div>
                <label for="logo_alt">Logo alt text</label>
                <input id="logo_alt" name="logo_alt" value="<?= e((string) $client['logo_alt']); ?>">
            </div>
        </div>
        <?php if (!empty($client['logo_path'])): ?>
            <img class="thumb" src="<?= e(asset((string) $client['logo_path'])); ?>" alt="">
        <?php endif; ?>
        <div class="form-row two">
            <label><input type="checkbox" name="is_featured" <?= !empty($client['is_featured']) ? 'checked' : ''; ?>> Featured</label>
            <label><input type="checkbox" name="is_published" <?= !empty($client['is_published']) ? 'checked' : ''; ?>> Published</label>
        </div>
        <button class="button primary" type="submit">Save client</button>
        <a class="button secondary" href="<?= e(url('/admin/clients.php')); ?>">Cancel</a>
    </form>
    <?php
    admin_footer();
    exit;
}

$clients = $pdo->query('SELECT * FROM clients ORDER BY sort_order ASC, id ASC')->fetchAll();
admin_header('Clients');
?>
<p><a class="button primary" href="<?= e(url('/admin/clients.php?action=new')); ?>">Add client</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Industry</th>
                <th>Flags</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td>
                    <?php if (!empty($client['logo_path'])): ?>
                        <img class="thumb" src="<?= e(asset((string) $client['logo_path'])); ?>" alt="">
                    <?php endif; ?>
                    <strong><?= e($client['name']); ?></strong>
                </td>
                <td><?= e((string) $client['industry']); ?></td>
                <td><?= !empty($client['is_featured']) ? 'Featured · ' : ''; ?><?= !empty($client['is_published']) ? 'Published' : 'Hidden'; ?></td>
                <td>
                    <a href="<?= e(url('/admin/clients.php?action=edit&id=' . $client['id'])); ?>">Edit</a>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this client?');">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int) $client['id']; ?>">
                        <button type="submit" class="button secondary" style="padding:0.35rem 0.7rem;margin-left:0.4rem;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
