<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailStoreRequest;
use App\Http\Requests\EmailUpdateRequest;
use App\Http\Resources\EmailResource;
use App\Models\SuccessfulEmail;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;

class SuccessfulEmailController extends Controller
{

    private EmailService $emailService;

    /**
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->ok(EmailResource::collection($this->emailService->list()));
    }

    /**
     * @param SuccessfulEmail $email
     * @return JsonResponse
     */
    public function show(SuccessfulEmail $email): JsonResponse
    {
        return $this->ok(EmailResource::make($email));
    }

    /**
     * @param EmailStoreRequest $request
     * @return JsonResponse
     */
    public function store(EmailStoreRequest $request): JsonResponse
    {
        $requestData = $request->only([
            'affiliate_id',
            'envelope',
            'from',
            'subject',
            'dkim',
            'SPF',
            'spam_score',
            'email',
            'raw_text',
            'sender_ip',
            'to',
            'timestamp',
        ]);

        return $this->ok(EmailResource::make($this->emailService->store($requestData)));
    }

    /**
     * @param EmailUpdateRequest $request
     * @return JsonResponse
     */
    public function update(EmailUpdateRequest $request): JsonResponse
    {
        $requestData = $request->only([
            'affiliate_id',
            'envelope',
            'from',
            'subject',
            'dkim',
            'SPF',
            'spam_score',
            'email',
            'raw_text',
            'sender_ip',
            'to',
            'timestamp',
        ]);

        return $this->ok(EmailResource::make($this->emailService->store($requestData)));
    }

    /**
     * @param SuccessfulEmail $email
     * @return JsonResponse
     */
    public function destroy(SuccessfulEmail $email): JsonResponse
    {
        $this->emailService->delete($email);

        return $this->noContent();
    }
}
