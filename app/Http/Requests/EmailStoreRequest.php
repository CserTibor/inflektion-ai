<?php

namespace App\Http\Requests;

class EmailStoreRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'affiliate_id' => 'required|integer',
            'envelope' => 'required|string',
            'from' => 'required|string|max:255',
            'subject' => 'required|string',
            'dkim' => 'required|nullable|string|max:255',
            'SPF' => 'required|nullable|string|max:255',
            'spam_score' => 'required|nullable|numeric',
            'email' => 'required|string',
            'raw_text' => 'required|string',
            'sender_ip' => 'required|nullable|string|max:50',
            'to' => 'required|string',
            'timestamp' => 'required|integer',
        ];
    }
}
