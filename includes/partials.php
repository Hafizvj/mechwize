<?php

declare(strict_types=1);

function render_breadcrumbs(array $crumbs): void
{
    echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    foreach ($crumbs as $index => $crumb) {
        if ($index > 0) {
            echo '<span>/</span>';
        }
        if (!empty($crumb['url']) && $index < count($crumbs) - 1) {
            echo '<a href="' . e(url($crumb['url'])) . '">' . e($crumb['name']) . '</a>';
        } else {
            echo '<span>' . e($crumb['name']) . '</span>';
        }
    }
    echo '</nav>';
}

function render_enquiry_form(?array $flash = null, string $selectedService = ''): void
{
    $options = service_interest_options();
    ?>
    <form class="contact-form" action="<?= e(url('/enquiry.php')); ?>" method="post">
        <?= render_flash($flash); ?>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()); ?>">
        <div class="hidden-field" aria-hidden="true">
            <label for="website">Website</label>
            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
        </div>
        <div class="form-row">
            <label for="name">Name *</label>
            <input id="name" name="name" type="text" maxlength="120" required>
        </div>
        <div class="form-row">
            <label for="company">Company</label>
            <input id="company" name="company" type="text" maxlength="160">
        </div>
        <div class="form-row two">
            <div>
                <label for="email">Email *</label>
                <input id="email" name="email" type="email" maxlength="190" required>
            </div>
            <div>
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="tel" maxlength="60">
            </div>
        </div>
        <div class="form-row two">
            <div>
                <label for="service_interest">Service interest</label>
                <select id="service_interest" name="service_interest">
                    <?php foreach ($options as $option): ?>
                        <option value="<?= e($option); ?>" <?= $selectedService === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="project_location">Project location</label>
                <input id="project_location" name="project_location" type="text" maxlength="160" placeholder="Dubai, Abu Dhabi, Sharjah...">
            </div>
        </div>
        <div class="form-row">
            <label for="message">Requirement *</label>
            <textarea id="message" name="message" rows="5" maxlength="2000" required placeholder="Tell us about the system, site, issue, equipment or spare parts you need."></textarea>
        </div>
        <button class="button primary full" type="submit">Submit Enquiry</button>
        <p class="form-note">Submissions are saved only to the configured remote MySQL database. There is no local database fallback.</p>
    </form>
    <?php
}
