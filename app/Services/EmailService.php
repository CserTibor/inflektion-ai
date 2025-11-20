<?php

namespace App\Services;

use App\Models\SuccessfulEmail;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class EmailService
{

    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return SuccessfulEmail::all();
    }

    /**
     * @param array $data
     * @return SuccessfulEmail|null
     */
    public function store(array $data): ?SuccessfulEmail
    {
        try {
            $email = $data['email'];

            return SuccessfulEmail::create(
                [
                    'email' => $email,
                    'raw_text' => $this->parseEmailContent($email),
                    'affiliate_id' => $data['affiliate_id'],
                    'envelope' => $data['envelope'],
                    'from' => $data['from'],
                    'subject' => $data['subject'],
                    'dkim' => $data['dkim'],
                    'SPF' => $data['SPF'],
                    'spam_score' => $data['spam_score'],

                    'sender_ip' => $data['sender_ip'],
                    'to' => $data['to'],
                    'timestamp' => $data['timestamp'],
                ]
            );
        } catch (Exception $exception) {
            Log::error(
                'could_not_create',
                [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ]
            );
        }

        return null;
    }

    public function update(SuccessfulEmail $email, array $data): SuccessfulEmail
    {
        try {
            $email->update($data);
        } catch (Exception $exception) {
            Log::error(
                'could_not_create',
                [
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString(),
                ]
            );
        }

        return $email;
    }

    public function delete(SuccessfulEmail $email): void
    {
        $email->delete();
    }

    public function parseEmailContent(string $rawEmail): string
    {
        $rawEmail = quoted_printable_decode($rawEmail);

        $bodyContent = $this->getBodyContent($rawEmail);
        $bodyContent = preg_replace('#<br\s*/?>#i', "\n", $bodyContent);
        $bodyContent = preg_replace('#</p>#i', "\n", $bodyContent);

        $text = strip_tags($bodyContent);

        $text = preg_replace('/<o:p>.*?<\/o:p>/i', '', $text);
        $text = preg_replace('/=\s*/', '', $text); // leftover =3D style
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5);
        $text = str_replace(["\u{A0}", "\xA0"], ' ', $text);
        $text = preg_replace("/\r\n|\r|\n/", "\n", $text);
        $text = preg_replace("/[ \t]+/", " ", $text);
        $text = preg_replace("/\n{2,}/", "\n", $text);

        return trim($text);
    }

    /**
     * @param string $rawEmail
     * @return string
     */
    private function getBodyContent(string $rawEmail): string
    {
        $start = stripos($rawEmail, '<body');
        $end = strripos($rawEmail, '</body>');

        if ($start !== false && $end !== false && $end > $start) {
            $bodyContent = substr($rawEmail, $start, $end - $start + 7); // +7 to include </body>
        } else {
            $start = stripos($rawEmail, '<html');
            $end = strripos($rawEmail, '</html>');
            if ($start !== false && $end !== false && $end > $start) {
                $bodyContent = substr($rawEmail, $start, $end - $start + 7);
            } else {
                $bodyContent = $rawEmail;
            }
        }
        return $bodyContent;
    }
}
