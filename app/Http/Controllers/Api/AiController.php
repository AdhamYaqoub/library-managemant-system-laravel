<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiChatRequest;
use App\Services\AI\LibraryAgentService;
use App\Traits\ApiResponse;
use Throwable;

class AiController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected LibraryAgentService $agent
    ) {
    }

    public function chat(AiChatRequest $request)
    {    set_time_limit(300);

        try {

            $answer = $this->agent->chat(
                $request->validated('message')
            );

            return $this->success(
                [
                    'answer' => $answer,
                ],
                'AI response generated successfully.'
            );

        } catch (\Exception $e) {

    \Log::error('AI Agent Error', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString(),
    ]);

    return $this->error(
        'Unable to process AI request.',
        500,
        [
            'error' => $e->getMessage(),
        ]
    );
}
    }
}