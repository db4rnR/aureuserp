<?php

declare(strict_types=1);

namespace Webkul\Support\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Webkul\Support\Mail\DynamicEmail;
use Webkul\Support\Models\EmailLog;
use Webkul\Support\Models\EmailTemplate;

class EmailTemplateService
{
    public function getTemplate(string $templateCode)
    {
        return EmailTemplate::where('code', $templateCode)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function replaceVariables(string $content, array $variables): string
    {
        return preg_replace_callback('/\{\{(.*?)\}\}/', function (array $matches) use ($variables) {
            $key = mb_trim($matches[1]);

            return $variables[$key] ?? $matches[0];
        }, $content);
    }

    public function composeEmail(string $templateCode, array $variables = []): array
    {
        $template = $this->getTemplate($templateCode);

        return [
            'body' => $this->replaceVariables($template->content, $variables),
            'subject' => $this->replaceVariables($template->subject, $variables),
            'layout' => $template->layout,
            ...$variables,
        ];
    }

    public function send(string $templateCode, string $recipientEmail, string $recipientName, array $variables = [], string $locale = 'en', array $attachments = []): bool
    {
        $emailData = $this->composeEmail($templateCode, $variables);

        $template = $this->getTemplate($templateCode);

        try {
            Mail::to($recipientEmail, $recipientName)
                ->send(new DynamicEmail($emailData, Auth::user())->withAttachments($attachments));

            $this->logEmail($template->id, $recipientEmail, $recipientName, $emailData['subject'], $variables, 'sent');

            return true;
        } catch (Exception $e) {
            $this->logEmail($template->id, $recipientEmail, $recipientName, $emailData['subject'] ?? '', $variables, 'failed', $e->getMessage());

            throw $e;
        }
    }

    private function logEmail(int $templateId, string $recipientEmail, string $recipientName, string $subject, array $variables, string $status, ?string $errorMessage = null): void
    {
        EmailLog::create([
            'email_template_id' => $templateId,
            'recipient_email' => $recipientEmail,
            'recipient_name' => $recipientName,
            'subject' => $subject,
            'variables' => $variables,
            'status' => $status,
            'error_message' => $errorMessage,
            'sent_at' => now(),
        ]);
    }
}
