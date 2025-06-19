<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Helper functions for tests.
 */
class TestHelpers
{
    /**
     * Generate a random string.
     */
    public static function randomString(int $length = 10): string
    {
        return Str::random($length);
    }

    /**
     * Generate a random email.
     */
    public static function randomEmail(?string $domain = null): string
    {
        $domain = $domain !== null && $domain !== '' && $domain !== '0' ? $domain : 'example.com';

        return Str::random(10).'@'.$domain;
    }

    /**
     * Generate a random phone number.
     */
    public static function randomPhoneNumber(): string
    {
        return '+'.random_int(1, 9).random_int(100000000, 999999999);
    }

    /**
     * Generate a random date.
     */
    public static function randomDate(string $format = 'Y-m-d', ?string $startDate = null, ?string $endDate = null): string
    {
        $startDate = $startDate !== null && $startDate !== '' && $startDate !== '0' ? $startDate : '2000-01-01';
        $endDate = $endDate !== null && $endDate !== '' && $endDate !== '0' ? $endDate : '2030-12-31';

        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);

        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);

        return date($format, $randomTimestamp);
    }

    /**
     * Generate a random boolean.
     */
    public static function randomBoolean(): bool
    {
        return (bool) random_int(0, 1);
    }

    /**
     * Generate a random element from an array.
     */
    public static function randomElement(array $array): mixed
    {
        return Arr::random($array);
    }

    /**
     * Generate a random number.
     */
    public static function randomNumber(int $min = 1, int $max = 100): int
    {
        return random_int($min, $max);
    }

    /**
     * Generate a random decimal.
     */
    public static function randomDecimal(int $min = 1, int $max = 100, int $decimals = 2): float
    {
        return round(random_int($min * 100, $max * 100) / 100, $decimals);
    }

    /**
     * Create a test image file.
     */
    public static function createTestImage(string $filename = 'test.jpg', int $width = 100, int $height = 100): UploadedFile
    {
        $path = sys_get_temp_dir().'/'.$filename;

        $image = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);

        imagefilledrectangle($image, 0, 0, $width, $height, $background);
        imagestring($image, 5, 20, 20, 'Test Image', $textColor);

        imagejpeg($image, $path);
        imagedestroy($image);

        return new UploadedFile(
            $path,
            $filename,
            'image/jpeg',
            null,
            true
        );
    }

    /**
     * Create a test PDF file.
     */
    public static function createTestPdf(string $filename = 'test.pdf'): UploadedFile
    {
        $path = sys_get_temp_dir().'/'.$filename;

        $content = '%PDF-1.4
1 0 obj
<< /Type /Catalog
/Outlines 2 0 R
/Pages 3 0 R
>>
endobj
2 0 obj
<< /Type /Outlines
/Count 0
>>
endobj
3 0 obj
<< /Type /Pages
/Kids [4 0 R]
/Count 1
>>
endobj
4 0 obj
<< /Type /Page
/Parent 3 0 R
/MediaBox [0 0 612 792]
/Contents 5 0 R
/Resources << /ProcSet 6 0 R
/Font << /F1 7 0 R >>
>>
>>
endobj
5 0 obj
<< /Length 73 >>
stream
BT
/F1 24 Tf
100 700 Td
(Test PDF File) Tj
ET
endstream
endobj
6 0 obj
[/PDF /Text]
endobj
7 0 obj
<< /Type /Font
/Subtype /Type1
/Name /F1
/BaseFont /Helvetica
/Encoding /MacRomanEncoding
>>
endobj
xref
0 8
0000000000 65535 f
0000000009 00000 n
0000000074 00000 n
0000000120 00000 n
0000000179 00000 n
0000000364 00000 n
0000000466 00000 n
0000000496 00000 n
trailer
<< /Size 8
/Root 1 0 R
>>
startxref
625
%%EOF';

        file_put_contents($path, $content);

        return new UploadedFile(
            $path,
            $filename,
            'application/pdf',
            null,
            true
        );
    }

    /**
     * Create a test file.
     */
    public static function createTestFile(string $filename, string $content = 'Test content', string $mimeType = 'text/plain'): UploadedFile
    {
        $path = sys_get_temp_dir().'/'.$filename;

        file_put_contents($path, $content);

        return new UploadedFile(
            $path,
            $filename,
            $mimeType,
            null,
            true
        );
    }

    /**
     * Get the attributes of a model.
     */
    public static function getModelAttributes(Model $model, array $except = []): array
    {
        return Arr::except($model->getAttributes(), $except);
    }

    /**
     * Convert a collection to an array of IDs.
     */
    public static function collectionToIds(Collection $collection): array
    {
        return $collection->pluck('id')->toArray();
    }

    /**
     * Get a random model from the database.
     */
    public static function getRandomModel(string $model): ?Model
    {
        return $model::inRandomOrder()->first();
    }

    /**
     * Get a random subset of models from the database.
     */
    public static function getRandomModels(string $model, int $count = 3): Collection
    {
        return $model::inRandomOrder()->limit($count)->get();
    }

    /**
     * Clean up test files.
     */
    public static function cleanupTestFiles(string $disk = 'local', string $directory = 'test'): void
    {
        if (Storage::disk($disk)->exists($directory)) {
            Storage::disk($disk)->deleteDirectory($directory);
        }
    }
}
