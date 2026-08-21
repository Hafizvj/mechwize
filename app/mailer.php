<?php

declare(strict_types=1);

function notify_team(array $data): void
{
    $config = require __DIR__ . '/config.php';
    $mail = $config['mail'] ?? [];
    $to = $mail['to'] ?? 'info@mechwize.com';
    $from = $mail['from'] ?? 'website@mechwize.com';

    if (!filter_var($to, FILTER_VALIDATE_EMAIL) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
        return;
    }

    $subject = 'New Mechwize website enquiry: ' . $data['service_interest'];
    $body = implode("\n", [
        'A new enquiry was submitted on mechwize.com.',
        '',
        'Name: ' . $data['name'],
        'Company: ' . ($data['company'] ?: 'Not provided'),
        'Email: ' . $data['email'],
        'Phone: ' . ($data['phone'] ?: 'Not provided'),
        'Service Interest: ' . $data['service_interest'],
        'Project Location: ' . ($data['project_location'] ?: 'Not provided'),
        '',
        'Message:',
        $data['message'],
    ]);

    $headers = [
        'From: Mechwize Website <' . $from . '>',
        'Reply-To: ' . $data['email'],
        'Content-Type: text/plain; charset=UTF-8',
    ];

    @mail($to, $subject, $body, implode("\r\n", $headers));
}

function save_enquiry(array $data): void
{
    $statement = database()->prepare(
        'INSERT INTO enquiries
            (name, company, email, phone, service_interest, project_location, message, ip_address, user_agent, status, created_at)
        VALUES
            (:name, :company, :email, :phone, :service_interest, :project_location, :message, :ip_address, :user_agent, :status, NOW())'
    );

    $statement->execute([
        'name' => $data['name'],
        'company' => $data['company'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'service_interest' => $data['service_interest'],
        'project_location' => $data['project_location'],
        'message' => $data['message'],
        'ip_address' => $data['ip_address'],
        'user_agent' => $data['user_agent'],
        'status' => 'new',
    ]);
}
