<?php

namespace Tests\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Helper functions for tests.
 */
class TestHelpers
{
    /**
     * Generate a random string.
     *
     * @param int $length
     * @return string
     */
    public static function randomString(int $length = 10): string
    {
        return Str::random($length);
    }

    /**
     * Generate a random email.
     *
     * @param string|null $domain
     * @return string
     */
    public static function randomEmail(?string $domain = null): string
    {
        $domain = $domain ?: 'example.com';
        return Str::random(10) . '@' . $domain;
    }

    /**
     * Generate a random phone number.
     *
     * @return string
     */
    public static function randomPhoneNumber(): string
    {
        return '+' . rand(1, 9) . rand(100000000, 999999999);
    }

    /**
     * Generate a random date.
     *
     * @param string $format
     * @param string|null $startDate
     * @param string|null $endDate
     * @return string
     */
    public static function randomDate(string $format = 'Y-m-d', ?string $startDate = null, ?string $endDate = null): string
    {
        $startDate = $startDate ?: '2000-01-01';
        $endDate = $endDate ?: '2030-12-31';

        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);

        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);

        return date($format, $randomTimestamp);
    }

    /**
     * Generate a random boolean.
     *
     * @return bool
     */
    public static function randomBoolean(): bool
    {
        return (bool) rand(0, 1);
    }

    /**
     * Generate a random element from an array.
     *
     * @param array $array
     * @return mixed
     */
    public static function randomElement(array $array)
    {
        return Arr::random($array);
    }

    /**
     * Generate a random number.
     *
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function randomNumber(int $min = 1, int $max = 100): int
    {
        return rand($min, $max);
    }

    /**
     * Generate a random decimal.
     *
     * @param int $min
     * @param int $max
     * @param int $decimals
     * @return float
     */
    public static function randomDecimal(int $min = 1, int $max = 100, int $decimals = 2): float
    {
        return round(rand($min * 100, $max * 100) / 100, $decimals);
    }

    /**
     * Create a test image file.
     *
     * @param string $filename
     * @param int $width
     * @param int $height
     * @return UploadedFile
     */
    public static function createTestImage(string $filename = 'test.jpg', int $width = 100, int $height = 100): UploadedFile
    {
        $path = sys_get_temp_dir() . '/' . $filename;

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
     *
     * @param string $filename
     * @return UploadedFile
     */
    public static function createTestPdf(string $filename = 'test.pdf'): UploadedFile
    {
        $path = sys_get_temp_dir() . '/' . $filename;

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
     *
     * @param string $filename
     * @param string $content
     * @param string $mimeType
     * @return UploadedFile
     */
    public static function createTestFile(string $filename, string $content = 'Test content', string $mimeType = 'text/plain'): UploadedFile
    {
        $path = sys_get_temp_dir() . '/' . $filename;

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
     *
     * @param Model $model
     * @param array $except
     * @return array
     */
    public static function getModelAttributes(Model $model, array $except = []): array
    {
        return Arr::except($model->getAttributes(), $except);
    }

    /**
     * Convert a collection to an array of IDs.
     *
     * @param Collection $collection
     * @return array
     */
    public static function collectionToIds(Collection $collection): array
    {
        return $collection->pluck('id')->toArray();
    }

    /**
     * Get a random model from the database.
     *
     * @param string $model
     * @return Model|null
     */
    public static function getRandomModel(string $model): ?Model
    {
        return $model::inRandomOrder()->first();
    }

    /**
     * Get a random subset of models from the database.
     *
     * @param string $model
     * @param int $count
     * @return Collection
     */
    public static function getRandomModels(string $model, int $count = 3): Collection
    {
        return $model::inRandomOrder()->limit($count)->get();
    }

    /**
     * Clean up test files.
     *
     * @param string $disk
     * @param string $directory
     * @return void
     */
    public static function cleanupTestFiles(string $disk = 'local', string $directory = 'test'): void
    {
        if (Storage::disk($disk)->exists($directory)) {
            Storage::disk($disk)->deleteDirectory($directory);
        }
    }
}
