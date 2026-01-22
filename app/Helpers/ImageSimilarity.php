<?php

namespace App\Helpers;

class ImageSimilarity
{
    /**
     * Calculate perceptual hash of an image
     */
    public static function getHash($imagePath)
    {
        $img = imagecreatefromstring(file_get_contents($imagePath));
        if (!$img) return null;

        // Resize to 32x32
        $width = 32;
        $height = 32;
        $resized = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));

        // Grayscale
        imagefilter($resized, IMG_FILTER_GRAYSCALE);

        // Average brightness
        $pixels = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgb = imagecolorat($resized, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $pixels[] = $r;
            }
        }

        $avg = array_sum($pixels) / count($pixels);

        // Generate 1024-bit hash
        $hash = '';
        foreach ($pixels as $pixel) {
            $hash .= $pixel >= $avg ? '1' : '0';
        }

        imagedestroy($img);
        imagedestroy($resized);

        return $hash;
    }

    /**
     * Calculate similarity percentage between two hashes
     */
    public static function similarity($hash1, $hash2)
    {
        if (strlen($hash1) !== strlen($hash2)) return 0;

        $diff = 0;
        for ($i = 0; $i < strlen($hash1); $i++) {
            if ($hash1[$i] !== $hash2[$i]) $diff++;
        }

        return round(100 - ($diff / strlen($hash1) * 100), 2);
    }

    /**
     * Compare two image paths
     */
    public static function compare($path1, $path2)
    {
        $hash1 = self::getHash($path1);
        $hash2 = self::getHash($path2);

        if (!$hash1 || !$hash2) return 0;

        return self::similarity($hash1, $hash2);
    }
    public function findMatches($item, $candidates)
{
    $matches = collect();

    $targetText = trim("{$item->title} {$item->description}");
    $targetTextEmbedding = $this->getEmbedding($targetText);

    $targetImageUrls = $item->images ? array_map(fn($p) => asset('storage/' . $p), $item->images) : [];
    $targetImageEmbeddings = [];
    foreach ($targetImageUrls as $url) {
        $embed = $this->getEmbedding($url);
        if ($embed) $targetImageEmbeddings[] = $embed;
    }

    foreach ($candidates as $candidate) {
        $score = 0;

        // Text matching
        if ($targetTextEmbedding) {
            $candText = trim("{$candidate->title} {$candidate->description}");
            $candTextEmbed = $this->getEmbedding($candText);
            if ($candTextEmbed) {
                $score += $this->cosine($targetTextEmbedding, $candTextEmbed) * 50;
            }
        }

        // Image matching (best pair)
        $candImageUrls = $candidate->images ? array_map(fn($p) => asset('storage/' . $p), $candidate->images) : [];
        if ($targetImageEmbeddings && $candImageUrls) {
            $maxImageSim = 0;
            foreach ($targetImageEmbeddings as $tEmbed) {
                foreach ($candImageUrls as $url) {
                    $cEmbed = $this->getEmbedding($url);
                    if ($cEmbed) {
                        $sim = $this->cosine($tEmbed, $cEmbed);
                        $maxImageSim = max($maxImageSim, $sim);
                    }
                }
            }
            $score += $maxImageSim * 50;
        }

        // Location & date bonus
        similar_text(strtolower($item->location), strtolower($candidate->location), $locPercent);
        $score += ($locPercent / 100) * 10;

        $daysDiff = abs($item->date_lost_found->diffInDays($candidate->date_lost_found));
        $dateBonus = $daysDiff <= 30 ? (1 - $daysDiff / 30) * 10 : 0;
        $score += $dateBonus;

        $finalScore = min(100, round($score));

        if ($finalScore >= 35) {
            $candidate->match_score = $finalScore;
            $matches->push($candidate);
        }
    }

    return $matches->sortByDesc('match_score')->take(8);
}
}