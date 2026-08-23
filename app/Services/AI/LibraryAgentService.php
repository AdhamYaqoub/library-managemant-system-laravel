<?php

namespace App\Services\AI;

use App\Services\AI\Tools\SearchBooksTool;
use App\Services\AI\Tools\AvailableBooksTool;
use App\Services\AI\Tools\GetBookTool;
use App\Services\AI\Tools\BookHistoryTool;
use App\Services\AI\Tools\MyBorrowingsTool;
use Illuminate\Support\Facades\Log;

class LibraryAgentService
{
    public function __construct(
        protected OllamaService $ollama,
        protected SearchBooksTool $searchBooksTool,
        protected AvailableBooksTool $availableBooksTool,
        protected GetBookTool $getBookTool,
        protected BookHistoryTool $bookHistoryTool,
        protected MyBorrowingsTool $myBorrowingsTool,
    ) {
    }

    public function chat(string $message): string
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Step 1 - Ask Ollama to determine the required action
            |--------------------------------------------------------------------------
            */

            $messages = [
                [
                    'role' => 'system',
                    'content' => <<<PROMPT
You are a Library Management AI Agent.

Your job is to understand the user's request and select ONE action.

Available actions:

1. search_books
2. get_available_books
3. get_book
4. get_book_history
5. get_my_borrowings
6. none

Return ONLY valid JSON.

Examples:

User:
اعطيني الكتب المتاحة حاليا

Response:
{
    "action": "get_available_books",
    "arguments": {}
}

User:
ابحث عن Clean Code

Response:
{
    "action": "search_books",
    "arguments": {
        "search": "Clean Code"
    }
}

User:
اعطيني معلومات الكتاب رقم 5

Response:
{
    "action": "get_book",
    "arguments": {
        "id": 5
    }
}

User:
اعطيني تاريخ الكتاب رقم 5

Response:
{
    "action": "get_book_history",
    "arguments": {
        "id": 5
    }
}

User:
شو الكتب اللي أنا مستعيرها؟

Response:
{
    "action": "get_my_borrowings",
    "arguments": {}
}

If the request does not require library data:

{
    "action": "none",
    "arguments": {}
}

Never invent IDs or database information.
PROMPT
                ],
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ];

            /*
            |--------------------------------------------------------------------------
            | Step 2 - Intent detection
            |--------------------------------------------------------------------------
            */

            $response = $this->ollama->chat(
                $messages,
                true
            );

            $content = $response['message']['content'] ?? null;

            if (!$content) {
                throw new \Exception(
                    'Ollama returned an empty response.'
                );
            }

            Log::info('AI intent response', [
                'content' => $content,
                'user_id' => auth()->id(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Step 3 - Decode JSON
            |--------------------------------------------------------------------------
            */

            $intent = json_decode(
                $content,
                true
            );

            if (
                !is_array($intent) ||
                !isset($intent['action'])
            ) {
                throw new \Exception(
                    'Invalid JSON returned by Ollama.'
                );
            }

            $action = $intent['action'];

            $arguments = $intent['arguments'] ?? [];

            if (!is_array($arguments)) {
                $arguments = [];
            }

            /*
            |--------------------------------------------------------------------------
            | Step 4 - Execute action
            |--------------------------------------------------------------------------
            */

            $result = $this->executeAction(
                $action,
                $arguments
            );

            /*
            |--------------------------------------------------------------------------
            | Step 5 - If no database action is required
            |--------------------------------------------------------------------------
            */

            if ($action === 'none') {
                return $this->generateFinalResponse(
                    $message,
                    $result
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Step 6 - Ask Ollama to formulate the final answer
            |--------------------------------------------------------------------------
            */

            return $this->generateFinalResponse(
                $message,
                $result
            );

        } catch (\Throwable $e) {

            Log::error('Library AI Agent Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
            ]);

            return 'حدث خطأ أثناء معالجة طلبك. حاول مرة أخرى.';
        }
    }

    protected function executeAction(
        string $action,
        array $arguments
    ): array {

        Log::info('AI action executed', [
            'action' => $action,
            'arguments' => $arguments,
            'user_id' => auth()->id(),
        ]);

        return match ($action) {

            'search_books' =>
                $this->searchBooksTool
                    ->execute($arguments),

            'get_available_books' =>
                $this->availableBooksTool
                    ->execute(),

            'get_book' =>
                $this->getBookTool
                    ->execute($arguments),

            'get_book_history' =>
                $this->bookHistoryTool
                    ->execute($arguments),

            'get_my_borrowings' =>
                $this->myBorrowingsTool
                    ->execute(),

            'none' => [],

            default => [
                'error' => 'Unknown action.'
            ],
        };
    }

    protected function generateFinalResponse(
        string $userMessage,
        array $data
    ): string {

        $messages = [
            [
                'role' => 'system',
                'content' => <<<PROMPT
You are a Library Management AI Assistant.

Answer the user using ONLY the data provided by the system.

Rules:

- Never invent books.
- Never invent members.
- Never invent borrowing information.
- If there are no results, clearly say that there are no results.
- Answer in the same language as the user.
- Keep the answer clear and concise.
- Do not mention internal tools, JSON, agents, or implementation details.
PROMPT
            ],
            [
                'role' => 'user',
                'content' => $userMessage,
            ],
            [
                'role' => 'system',
                'content' => 'Database result: ' . json_encode(
                    $data,
                    JSON_UNESCAPED_UNICODE
                ),
            ],
        ];

        $response = $this->ollama->chat($messages);

        return $response['message']['content']
            ?? 'لم أتمكن من إنشاء الإجابة.';
    }
}