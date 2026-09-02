<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiChatRequest;
use App\Services\AI\LibraryAgentService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected LibraryAgentService $agent
    ) {}

    public function chat(AiChatRequest $request)
    {
        set_time_limit(300);

        try {

            $message = $request->validated('message');

            Log::info('AI ORIGINAL MESSAGE', [
                'message' => $message,
            ]);

            $answer = $this->agent->chat($message);

            return $this->success(
                [
                    'answer' => $answer,
                ],
                'AI response generated successfully.'
            );

        } catch (\Throwable $e) {

            Log::error('AI Agent Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
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
