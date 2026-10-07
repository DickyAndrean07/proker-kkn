<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AiDraftRequest;
use App\Services\GeminiAiService;
use Exception;
use Illuminate\Http\JsonResponse;

class AiAssistantController extends Controller
{
    /**
     * Generate structured draft or text improvement.
     */
    public function generate(AiDraftRequest $request, GeminiAiService $aiService): JsonResponse
    {
        try {
            $validated = $request->validated();

            $feature = $validated['feature'];
            $mode = $validated['mode'];
            $notes = $validated['notes'] ?? null;
            $existingText = $validated['existing_text'] ?? null;
            $structuredInput = $validated['structured_input'] ?? null;
            $draftLength = $validated['draft_length'] ?? 'standar';

            $result = $aiService->generate($feature, $mode, $notes, $existingText, $structuredInput, $draftLength);
            $meta = $aiService->getLastGenerationMeta();

            return response()->json([
                'success' => true,
                'data' => $result,
                'meta' => $meta,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Layanan bantuan AI sedang tidak tersedia. Silakan lanjutkan pengisian secara manual.',
            ], 422);
        }
    }
}
