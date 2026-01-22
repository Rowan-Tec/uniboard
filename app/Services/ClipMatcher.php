<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClipMatcher
{
    protected $token;
    protected $baseUrl = 'https://api-inference.huggingface.co/models/openai/clip-vit-large-patch14';

    public function __construct()
    {
        $this->token = env('HUGGINGFACE_API_TOKEN');
    }

    /**
     * Get embedding for image or text
     */
    public function getEmbedding($input, $type = 'image')
    {
        if (!$this->token) {
            Log::warning('Hugging Face token missing - using fallback');
            return null;
        }

        $response = Http::timeout(30)->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post($this->baseUrl, ['inputs' => $input]);

        if ($response->failed()) {
            Log::error('CLIP API error: ' . $response->body());
            return null;
        }

        return $response->json()[0] ?? null;
    }

    /**
     * Cosine similarity between two vectors
     */
    public function similarity($vec1, $vec2)
    {
        if (!$vec1 || !$vec2 || count($vec1) !== count($vec2)) return 0;

        $dot = array_sum(array_map(fn($a, $b) => $a * $b, $vec1, $vec2));
        $norm1 = sqrt(array_sum(array_map(fn($a) => $a * $a, $vec1)));
        $norm2 = sqrt(array_sum(array_map(fn($b) => $b * $b, $vec2)));

        return $norm1 && $norm2 ? $dot / ($norm1