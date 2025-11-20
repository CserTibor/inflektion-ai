<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'affiliate_id' => $this->resource->affiliate_id,
            'envelope' => $this->resource->envelope,
            'from' => $this->resource->from,
            'subject' => $this->resource->subject,
            'dkim' => $this->resource->dkim,
            'SPF' => $this->resource->SPF,
            'spam_score' => $this->resource->spam_score,
            'email' => $this->resource->email,
            'raw_text' => $this->resource->raw_text,
            'sender_ip' => $this->resource->sender_ip,
            'to' => $this->resource->to,
            'timestamp' => $this->resource->timestamp,
        ];
    }
}
