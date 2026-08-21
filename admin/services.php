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
        $row = $pdo->prepare('SELECT hero_image, og_image FROM services WHERE id = :id');
        $row->execute(['id' => $deleteId]);
        $service = $row->fetch();
        if ($service) {
            delete_upload($service['hero_image'] ?? null);
            delete_upload($service['og_image'] ?? null);
            $pdo->prepare('DELETE FROM services WHERE id = :id')->execute(['id' => $deleteId]);
            set_flash('success', 'Service deleted.');
        }
        redirect(url('/admin/services.php'));
    }

    $title = trim((string) ($_POST['title'] ?? ''));
    $slug = trim((string) ($_POST['slug'] ?? ''));
    if ($slug === '') {
        $slug = slugify($title);
    }
    $data = [
        'title' => $title,
        'slug' => $slug,
        'category' => trim((string) ($_POST['category'] ?? '')),
        'summary' => trim((string) ($_POST['summary'] ?? '')),
        'body' => trim((string) ($_POST['body'] ?? '')),
        'seo_title' => trim((string) ($_POST['seo_title'] ?? '')),
        'seo_description' => trim((string) ($_POST['seo_description'] ?? '')),
        'seo_keywords' => trim((string) ($_POST['seo_keywords'] ?? '')),
        'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
    ];

    $features = array_values(array_filter(array_map('trim', explode("\n", (string) ($_POST['features'] ?? '')))));
    $editId = (int) ($_POST['id'] ?? 0);

    try {
        $hero = store_upload($_FILES['hero_image'] ?? [], 'services', 'service');
        $og = store_upload($_FILES['og_image'] ?? [], 'services', 'service-og');
    } catch (Throwable $exception) {
        set_flash('error', $exception->getMessage());
        redirect(url('/admin/services.php?action=' . ($editId ? 'edit&id=' . $editId : 'new')));
    }

    if ($title === '' || $data['summary'] === '' || $data['body'] === '') {
        set_flash('error', 'Title, summary and body are required.');
        redirect(url('/admin/services.php?action=' . ($editId ? 'edit&id=' . $editId : 'new')));
    }

    if ($editId > 0) {
        $existing = $pdo->prepare('SELECT hero_image, og_image FROM services WHERE id = :id');
        $existing->execute(['id' => $editId]);
        $current = $existing->fetch() ?: [];

        if ($hero) {
            delete_upload($current['hero_image'] ?? null);
        } else {
            $hero = $current['hero_image'] ?? null;
        }
        if ($og) {
            delete_upload($current['og_image'] ?? null);
        } else {
            $og = $current['og_image'] ?? null;
        }

        $statement = $pdo->prepare(
            'UPDATE services SET title=:title, slug=:slug, category=:category, summary=:summary, body=:body,
             hero_image=:hero_image, seo_title=:seo_title, seo_description=:seo_description, seo_keywords=:seo_keywords,
             og_image=:og_image, sort_order=:sort_order, is_featured=:is_featured, is_active=:is_active WHERE id=:id'
        );
        $statement->execute($data + [
            'hero_image' => $hero,
            'og_image' => $og,
            'id' => $editId,
        ]);
        $pdo->prepare('DELETE FROM service_features WHERE service_id = :id')->execute(['id' => $editId]);
        $serviceId = $editId;
        set_flash('success', 'Service updated.');
    } else {
        $statement = $pdo->prepare(
            'INSERT INTO services (title, slug, category, summary, body, hero_image, seo_title, seo_description, seo_keywords, og_image, sort_order, is_featured, is_active)
             VALUES (:title, :slug, :category, :summary, :body, :hero_image, :seo_title, :seo_description, :seo_keywords, :og_image, :sort_order, :is_featured, :is_active)'
        );
        $statement->execute($data + [
            'hero_image' => $hero,
            'og_image' => $og,
        ]);
        $serviceId = (int) $pdo->lastInsertId();
        set_flash('success', 'Service created.');
    }

    $featureStatement = $pdo->prepare('INSERT INTO service_features (service_id, feature_text, sort_order) VALUES (:service_id, :feature_text, :sort_order)');
    foreach ($features as $index => $feature) {
        $featureStatement->execute([
            'service_id' => $serviceId,
            'feature_text' => $feature,
            'sort_order' => $index + 1,
        ]);
    }

    redirect(url('/admin/services.php'));
}

if ($action === 'new' || $action === 'edit') {
    $service = [
        'id' => 0,
        'title' => '',
        'slug' => '',
        'category' => '',
        'summary' => '',
        'body' => '',
        'seo_title' => '',
        'seo_description' => '',
        'seo_keywords' => '',
        'sort_order' => 0,
        'is_featured' => 0,
        'is_active' => 1,
        'features' => [],
    ];

    if ($action === 'edit' && $id > 0) {
        $statement = $pdo->prepare('SELECT * FROM services WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        if (!$row) {
            set_flash('error', 'Service not found.');
            redirect(url('/admin/services.php'));
        }
        $service = $row;
        $service['features'] = get_service_features($id);
    }

    admin_header($action === 'new' ? 'Add service' : 'Edit service');
    ?>
    <form class="contact-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <input type="hidden" name="id" value="<?= (int) $service['id']; ?>">
        <div class="form-row two">
            <div>
                <label for="title">Title *</label>
                <input id="title" name="title" data-slug-source required value="<?= e((string) $service['title']); ?>">
            </div>
            <div>
                <label for="slug">Slug</label>
                <input id="slug" name="slug" data-slug-target value="<?= e((string) $service['slug']); ?>">
            </div>
        </div>
        <div class="form-row two">
            <div>
                <label for="category">Category</label>
                <input id="category" name="category" value="<?= e((string) $service['category']); ?>">
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" value="<?= (int) $service['sort_order']; ?>">
            </div>
        </div>
        <div class="form-row">
            <label for="summary">Summary *</label>
            <textarea id="summary" name="summary" required><?= e((string) $service['summary']); ?></textarea>
        </div>
        <div class="form-row">
            <label for="body">Body *</label>
            <textarea id="body" name="body" required><?= e((string) $service['body']); ?></textarea>
        </div>
        <div class="form-row">
            <label for="features">Features (one per line)</label>
            <textarea id="features" name="features"><?= e(implode("\n", $service['features'] ?? [])); ?></textarea>
        </div>
        <div class="form-row two">
            <div>
                <label for="hero_image">Hero image</label>
                <input id="hero_image" name="hero_image" type="file" accept="image/*">
            </div>
            <div>
                <label for="og_image">OG image</label>
                <input id="og_image" name="og_image" type="file" accept="image/*">
            </div>
        </div>
        <div class="form-row">
            <label for="seo_title">SEO title</label>
            <input id="seo_title" name="seo_title" value="<?= e((string) $service['seo_title']); ?>">
        </div>
        <div class="form-row">
            <label for="seo_description">SEO description</label>
            <textarea id="seo_description" name="seo_description"><?= e((string) $service['seo_description']); ?></textarea>
        </div>
        <div class="form-row">
            <label for="seo_keywords">SEO keywords</label>
            <input id="seo_keywords" name="seo_keywords" value="<?= e((string) $service['seo_keywords']); ?>">
        </div>
        <div class="form-row two">
            <label><input type="checkbox" name="is_featured" <?= !empty($service['is_featured']) ? 'checked' : ''; ?>> Featured</label>
            <label><input type="checkbox" name="is_active" <?= !empty($service['is_active']) ? 'checked' : ''; ?>> Active</label>
        </div>
        <button class="button primary" type="submit">Save service</button>
        <a class="button secondary" href="<?= e(url('/admin/services.php')); ?>">Cancel</a>
    </form>
    <?php
    admin_footer();
    exit;
}

$services = $pdo->query('SELECT id, title, slug, category, is_featured, is_active, sort_order FROM services ORDER BY sort_order ASC, id ASC')->fetchAll();
admin_header('Services');
?>
<p><a class="button primary" href="<?= e(url('/admin/services.php?action=new')); ?>">Add service</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Flags</th>
                <th>Order</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($services as $service): ?>
            <tr>
                <td>
                    <strong><?= e($service['title']); ?></strong><br>
                    <span class="address">/services/<?= e($service['slug']); ?></span>
                </td>
                <td><?= e((string) $service['category']); ?></td>
                <td><?= !empty($service['is_featured']) ? 'Featured · ' : ''; ?><?= !empty($service['is_active']) ? 'Active' : 'Hidden'; ?></td>
                <td><?= (int) $service['sort_order']; ?></td>
                <td>
                    <a href="<?= e(url('/admin/services.php?action=edit&id=' . $service['id'])); ?>">Edit</a>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this service?');">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int) $service['id']; ?>">
                        <button type="submit" class="button secondary" style="padding:0.35rem 0.7rem;margin-left:0.4rem;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
