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
        $images = $pdo->prepare('SELECT image_path FROM project_images WHERE project_id = :id');
        $images->execute(['id' => $deleteId]);
        foreach ($images->fetchAll() as $image) {
            delete_upload($image['image_path'] ?? null);
        }
        $cover = $pdo->prepare('SELECT cover_image FROM projects WHERE id = :id');
        $cover->execute(['id' => $deleteId]);
        $project = $cover->fetch();
        if ($project) {
            delete_upload($project['cover_image'] ?? null);
            $pdo->prepare('DELETE FROM projects WHERE id = :id')->execute(['id' => $deleteId]);
            set_flash('success', 'Project deleted.');
        }
        redirect(url('/admin/projects.php'));
    }

    if ($postAction === 'delete_image') {
        $imageId = (int) ($_POST['image_id'] ?? 0);
        $statement = $pdo->prepare('SELECT * FROM project_images WHERE id = :id');
        $statement->execute(['id' => $imageId]);
        $image = $statement->fetch();
        if ($image) {
            delete_upload($image['image_path'] ?? null);
            $pdo->prepare('DELETE FROM project_images WHERE id = :id')->execute(['id' => $imageId]);
            set_flash('success', 'Image removed.');
            redirect(url('/admin/projects.php?action=edit&id=' . (int) $image['project_id']));
        }
        redirect(url('/admin/projects.php'));
    }

    $title = trim((string) ($_POST['title'] ?? ''));
    $slug = trim((string) ($_POST['slug'] ?? ''));
    if ($slug === '') {
        $slug = slugify($title);
    }

    $data = [
        'title' => $title,
        'slug' => $slug,
        'location' => trim((string) ($_POST['location'] ?? '')),
        'year' => trim((string) ($_POST['year'] ?? '')),
        'category' => trim((string) ($_POST['category'] ?? '')),
        'summary' => trim((string) ($_POST['summary'] ?? '')),
        'body' => trim((string) ($_POST['body'] ?? '')),
        'seo_title' => trim((string) ($_POST['seo_title'] ?? '')),
        'seo_description' => trim((string) ($_POST['seo_description'] ?? '')),
        'seo_keywords' => trim((string) ($_POST['seo_keywords'] ?? '')),
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_published' => isset($_POST['is_published']) ? 1 : 0,
    ];

    $editId = (int) ($_POST['id'] ?? 0);

    try {
        $cover = store_upload($_FILES['cover_image'] ?? [], 'projects', 'project');
    } catch (Throwable $exception) {
        set_flash('error', $exception->getMessage());
        redirect(url('/admin/projects.php?action=' . ($editId ? 'edit&id=' . $editId : 'new')));
    }

    if ($title === '' || $data['summary'] === '' || $data['body'] === '') {
        set_flash('error', 'Title, summary and body are required.');
        redirect(url('/admin/projects.php?action=' . ($editId ? 'edit&id=' . $editId : 'new')));
    }

    if ($editId > 0) {
        $existing = $pdo->prepare('SELECT cover_image FROM projects WHERE id = :id');
        $existing->execute(['id' => $editId]);
        $current = $existing->fetch() ?: [];
        if ($cover) {
            delete_upload($current['cover_image'] ?? null);
        } else {
            $cover = $current['cover_image'] ?? null;
        }

        $statement = $pdo->prepare(
            'UPDATE projects SET title=:title, slug=:slug, location=:location, year=:year, category=:category,
             summary=:summary, body=:body, cover_image=:cover_image, seo_title=:seo_title, seo_description=:seo_description,
             seo_keywords=:seo_keywords, is_featured=:is_featured, is_published=:is_published WHERE id=:id'
        );
        $statement->execute($data + ['cover_image' => $cover, 'id' => $editId]);
        $projectId = $editId;
        set_flash('success', 'Project updated.');
    } else {
        $statement = $pdo->prepare(
            'INSERT INTO projects (title, slug, location, year, category, summary, body, cover_image, seo_title, seo_description, seo_keywords, is_featured, is_published)
             VALUES (:title, :slug, :location, :year, :category, :summary, :body, :cover_image, :seo_title, :seo_description, :seo_keywords, :is_featured, :is_published)'
        );
        $statement->execute($data + ['cover_image' => $cover]);
        $projectId = (int) $pdo->lastInsertId();
        set_flash('success', 'Project created.');
    }

    if (!empty($_FILES['gallery']['name']) && is_array($_FILES['gallery']['name'])) {
        $count = count($_FILES['gallery']['name']);
        $insert = $pdo->prepare('INSERT INTO project_images (project_id, image_path, alt_text, sort_order) VALUES (:project_id, :image_path, :alt_text, :sort_order)');
        for ($i = 0; $i < $count; $i++) {
            if (($_FILES['gallery']['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $file = [
                'name' => $_FILES['gallery']['name'][$i],
                'type' => $_FILES['gallery']['type'][$i],
                'tmp_name' => $_FILES['gallery']['tmp_name'][$i],
                'error' => $_FILES['gallery']['error'][$i],
                'size' => $_FILES['gallery']['size'][$i],
            ];
            try {
                $path = store_upload($file, 'projects', 'gallery');
                if ($path) {
                    $insert->execute([
                        'project_id' => $projectId,
                        'image_path' => $path,
                        'alt_text' => $title,
                        'sort_order' => $i + 1,
                    ]);
                }
            } catch (Throwable $exception) {
                error_log('Gallery upload failed: ' . $exception->getMessage());
            }
        }
    }

    redirect(url('/admin/projects.php'));
}

if ($action === 'new' || $action === 'edit') {
    $project = [
        'id' => 0,
        'title' => '',
        'slug' => '',
        'location' => '',
        'year' => date('Y'),
        'category' => '',
        'summary' => '',
        'body' => '',
        'seo_title' => '',
        'seo_description' => '',
        'seo_keywords' => '',
        'is_featured' => 0,
        'is_published' => 1,
        'images' => [],
    ];

    if ($action === 'edit' && $id > 0) {
        $statement = $pdo->prepare('SELECT * FROM projects WHERE id = :id');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();
        if (!$row) {
            set_flash('error', 'Project not found.');
            redirect(url('/admin/projects.php'));
        }
        $project = $row;
        $project['images'] = get_project_images($id);
    }

    admin_header($action === 'new' ? 'Add project' : 'Edit project');
    ?>
    <form class="contact-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <input type="hidden" name="id" value="<?= (int) $project['id']; ?>">
        <div class="form-row two">
            <div>
                <label for="title">Title *</label>
                <input id="title" name="title" data-slug-source required value="<?= e((string) $project['title']); ?>">
            </div>
            <div>
                <label for="slug">Slug</label>
                <input id="slug" name="slug" data-slug-target value="<?= e((string) $project['slug']); ?>">
            </div>
        </div>
        <div class="form-row two">
            <div>
                <label for="location">Location</label>
                <input id="location" name="location" value="<?= e((string) $project['location']); ?>">
            </div>
            <div>
                <label for="year">Year</label>
                <input id="year" name="year" value="<?= e((string) $project['year']); ?>">
            </div>
        </div>
        <div class="form-row">
            <label for="category">Category</label>
            <input id="category" name="category" value="<?= e((string) $project['category']); ?>">
        </div>
        <div class="form-row">
            <label for="summary">Summary *</label>
            <textarea id="summary" name="summary" required><?= e((string) $project['summary']); ?></textarea>
        </div>
        <div class="form-row">
            <label for="body">Body *</label>
            <textarea id="body" name="body" required><?= e((string) $project['body']); ?></textarea>
        </div>
        <div class="form-row">
            <label for="cover_image">Cover image</label>
            <input id="cover_image" name="cover_image" type="file" accept="image/*">
        </div>
        <div class="form-row">
            <label for="gallery">Gallery images</label>
            <input id="gallery" name="gallery[]" type="file" accept="image/*" multiple>
        </div>
        <?php if (!empty($project['images'])): ?>
            <div class="card-grid two">
                <?php foreach ($project['images'] as $image): ?>
                    <div class="admin-card">
                        <img class="thumb" src="<?= e(asset((string) $image['image_path'])); ?>" alt="">
                        <form method="post" onsubmit="return confirm('Remove image?');">
                            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                            <input type="hidden" name="action" value="delete_image">
                            <input type="hidden" name="image_id" value="<?= (int) $image['id']; ?>">
                            <button class="button secondary" type="submit">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="form-row">
            <label for="seo_title">SEO title</label>
            <input id="seo_title" name="seo_title" value="<?= e((string) $project['seo_title']); ?>">
        </div>
        <div class="form-row">
            <label for="seo_description">SEO description</label>
            <textarea id="seo_description" name="seo_description"><?= e((string) $project['seo_description']); ?></textarea>
        </div>
        <div class="form-row">
            <label for="seo_keywords">SEO keywords</label>
            <input id="seo_keywords" name="seo_keywords" value="<?= e((string) $project['seo_keywords']); ?>">
        </div>
        <div class="form-row two">
            <label><input type="checkbox" name="is_featured" <?= !empty($project['is_featured']) ? 'checked' : ''; ?>> Featured</label>
            <label><input type="checkbox" name="is_published" <?= !empty($project['is_published']) ? 'checked' : ''; ?>> Published</label>
        </div>
        <button class="button primary" type="submit">Save project</button>
        <a class="button secondary" href="<?= e(url('/admin/projects.php')); ?>">Cancel</a>
    </form>
    <?php
    admin_footer();
    exit;
}

$projects = $pdo->query('SELECT id, title, slug, category, location, year, is_featured, is_published FROM projects ORDER BY year DESC, id DESC')->fetchAll();
admin_header('Projects');
?>
<p><a class="button primary" href="<?= e(url('/admin/projects.php?action=new')); ?>">Add project</a></p>
<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Project</th>
                <th>Category</th>
                <th>Location</th>
                <th>Flags</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($projects as $project): ?>
            <tr>
                <td>
                    <strong><?= e($project['title']); ?></strong><br>
                    <span class="address">/projects/<?= e($project['slug']); ?></span>
                </td>
                <td><?= e((string) $project['category']); ?></td>
                <td><?= e((string) $project['location']); ?> · <?= e((string) $project['year']); ?></td>
                <td><?= !empty($project['is_featured']) ? 'Featured · ' : ''; ?><?= !empty($project['is_published']) ? 'Published' : 'Draft'; ?></td>
                <td>
                    <a href="<?= e(url('/admin/projects.php?action=edit&id=' . $project['id'])); ?>">Edit</a>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Delete this project?');">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= (int) $project['id']; ?>">
                        <button type="submit" class="button secondary" style="padding:0.35rem 0.7rem;margin-left:0.4rem;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
