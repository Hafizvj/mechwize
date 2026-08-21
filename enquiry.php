<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

if (!is_post()) {
    redirect(url('/contact'));
}

if (!empty($_POST['website'] ?? '')) {
    set_flash('success', 'Thank you. Our team will review your enquiry and contact you shortly.');
    redirect(url('/contact'));
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Your form session expired. Please try again.');
    redirect(url('/contact'));
}

$options = service_interest_options();

$data = [
    'name' => trim((string) ($_POST['name'] ?? '')),
    'company' => trim((string) ($_POST['company'] ?? '')),
    'email' => trim((string) ($_POST['email'] ?? '')),
    'phone' => trim((string) ($_POST['phone'] ?? '')),
    'service_interest' => trim((string) ($_POST['service_interest'] ?? '')),
    'project_location' => trim((string) ($_POST['project_location'] ?? '')),
    'message' => trim((string) ($_POST['message'] ?? '')),
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
    'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
];

$errors = [];

if ($data['name'] === '' || strlen($data['name']) > 120) {
    $errors[] = 'Please enter your name.';
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strlen($data['email']) > 190) {
    $errors[] = 'Please enter a valid email address.';
}

if ($data['phone'] !== '' && strlen($data['phone']) > 60) {
    $errors[] = 'Please enter a shorter phone number.';
}

if ($data['company'] !== '' && strlen($data['company']) > 160) {
    $errors[] = 'Please enter a shorter company name.';
}

if (!in_array($data['service_interest'], $options, true)) {
    $data['service_interest'] = 'General HVAC Enquiry';
}

if ($data['project_location'] !== '' && strlen($data['project_location']) > 160) {
    $errors[] = 'Please enter a shorter project location.';
}

if ($data['message'] === '' || strlen($data['message']) < 10) {
    $errors[] = 'Please add a short message about your requirement.';
}

if (strlen($data['message']) > 2000) {
    $errors[] = 'Please keep your message under 2,000 characters.';
}

if ($errors !== []) {
    set_flash('error', implode(' ', $errors));
    redirect(url('/contact'));
}

try {
    save_enquiry($data);
    notify_team($data);
    unset($_SESSION['csrf_token']);
    set_flash('success', 'Thank you. Our team will review your enquiry and contact you shortly.');
} catch (Throwable $exception) {
    error_log('Mechwize enquiry failed: ' . $exception->getMessage());
    set_flash('error', 'We could not submit your enquiry right now. Please call or email Mechwize Group directly.');
}

redirect(url('/contact'));
