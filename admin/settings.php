<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';
require __DIR__ . '/_init.php';

require_admin();

$fields = [
    'site_name',
    'tagline',
    'site_url',
    'phone_primary',
    'phone_secondary',
    'whatsapp',
    'email_primary',
    'email_sales',
    'address',
    'map_url',
    'working_hours',
    'social_linkedin',
    'social_instagram',
    'social_facebook',
    'default_meta_title',
    'default_meta_description',
    'default_og_image',
    'google_site_verification',
];

if (is_post()) {
    require_post_csrf();
    $payload = [];
    foreach ($fields as $field) {
        $payload[$field] = trim((string) ($_POST[$field] ?? ''));
    }
    update_site_settings($payload);
    set_flash('success', 'Contact and SEO settings updated.');
    redirect(url('/admin/settings.php'));
}

$settings = site_settings();
admin_header('Contact & SEO Settings');
?>
<form class="contact-form" method="post">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
    <h2 style="font-size:1.3rem;">Contact details</h2>
    <div class="form-row two">
        <div>
            <label for="phone_primary">Primary phone</label>
            <input id="phone_primary" name="phone_primary" value="<?= e((string) $settings['phone_primary']); ?>">
        </div>
        <div>
            <label for="phone_secondary">Secondary phone</label>
            <input id="phone_secondary" name="phone_secondary" value="<?= e((string) $settings['phone_secondary']); ?>">
        </div>
    </div>
    <div class="form-row two">
        <div>
            <label for="whatsapp">WhatsApp</label>
            <input id="whatsapp" name="whatsapp" value="<?= e((string) $settings['whatsapp']); ?>">
        </div>
        <div>
            <label for="working_hours">Working hours</label>
            <input id="working_hours" name="working_hours" value="<?= e((string) $settings['working_hours']); ?>">
        </div>
    </div>
    <div class="form-row two">
        <div>
            <label for="email_primary">Primary email</label>
            <input id="email_primary" name="email_primary" type="email" value="<?= e((string) $settings['email_primary']); ?>">
        </div>
        <div>
            <label for="email_sales">Sales email</label>
            <input id="email_sales" name="email_sales" type="email" value="<?= e((string) $settings['email_sales']); ?>">
        </div>
    </div>
    <div class="form-row">
        <label for="address">Address</label>
        <textarea id="address" name="address"><?= e((string) $settings['address']); ?></textarea>
    </div>
    <div class="form-row">
        <label for="map_url">Google Maps URL</label>
        <input id="map_url" name="map_url" value="<?= e((string) $settings['map_url']); ?>">
    </div>
    <h2 style="font-size:1.3rem;margin-top:1rem;">Brand & SEO defaults</h2>
    <div class="form-row two">
        <div>
            <label for="site_name">Site name</label>
            <input id="site_name" name="site_name" value="<?= e((string) $settings['site_name']); ?>">
        </div>
        <div>
            <label for="site_url">Canonical site URL</label>
            <input id="site_url" name="site_url" value="<?= e((string) $settings['site_url']); ?>">
        </div>
    </div>
    <div class="form-row">
        <label for="tagline">Tagline</label>
        <input id="tagline" name="tagline" value="<?= e((string) $settings['tagline']); ?>">
    </div>
    <div class="form-row">
        <label for="default_meta_title">Default meta title</label>
        <input id="default_meta_title" name="default_meta_title" value="<?= e((string) $settings['default_meta_title']); ?>">
    </div>
    <div class="form-row">
        <label for="default_meta_description">Default meta description</label>
        <textarea id="default_meta_description" name="default_meta_description"><?= e((string) $settings['default_meta_description']); ?></textarea>
    </div>
    <div class="form-row two">
        <div>
            <label for="default_og_image">Default OG image path</label>
            <input id="default_og_image" name="default_og_image" value="<?= e((string) $settings['default_og_image']); ?>">
        </div>
        <div>
            <label for="google_site_verification">Google site verification</label>
            <input id="google_site_verification" name="google_site_verification" value="<?= e((string) $settings['google_site_verification']); ?>">
        </div>
    </div>
    <div class="form-row">
        <label for="social_linkedin">LinkedIn URL</label>
        <input id="social_linkedin" name="social_linkedin" value="<?= e((string) $settings['social_linkedin']); ?>">
    </div>
    <div class="form-row two">
        <div>
            <label for="social_instagram">Instagram URL</label>
            <input id="social_instagram" name="social_instagram" value="<?= e((string) $settings['social_instagram']); ?>">
        </div>
        <div>
            <label for="social_facebook">Facebook URL</label>
            <input id="social_facebook" name="social_facebook" value="<?= e((string) $settings['social_facebook']); ?>">
        </div>
    </div>
    <button class="button primary" type="submit">Save settings</button>
</form>
<?php admin_footer(); ?>
