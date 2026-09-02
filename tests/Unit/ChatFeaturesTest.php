<?php

namespace Tests\Unit;

use App\Services\ChatService;
use App\Services\KnowledgeSearchService;
use Tests\TestCase;

class ChatFeaturesTest extends TestCase
{
    /**
     * Test bahwa KnowledgeSearchService memetakan dialek Basa Jawa Krama Alus dengan tepat.
     */
    public function test_javanese_synonym_mapping(): void
    {
        $searchService = app(KnowledgeSearchService::class);

        $reflection = new \ReflectionClass($searchService);
        $method = $reflection->getMethod('expandSynonyms');
        $method->setAccessible(true);

        $expanded = $method->invoke($searchService, ['tiyang', 'mlarat', 'cacahe']);

        $this->assertContains('kemiskinan', $expanded);
        $this->assertContains('penduduk', $expanded);
    }

    /**
     * Test bahwa ChatService mengidentifikasi data grafik resmi BPS Karanganyar.
     */
    public function test_chart_data_resolution(): void
    {
        $chatService = app(ChatService::class);

        $reflection = new \ReflectionClass($chatService);
        $method = $reflection->getMethod('resolveChartForMessage');
        $method->setAccessible(true);

        // 1. Uji grafik kemiskinan
        $chartPoverty = $method->invoke($chatService, 'Tolong tampilkan grafik tren kemiskinan Karanganyar', 'Berikut datanya');
        $this->assertNotNull($chartPoverty);
        $this->assertEquals('line', $chartPoverty['type']);
        $this->assertContains('7.92', array_map('strval', $chartPoverty['data']));

        // 2. Uji grafik IPM
        $chartIpm = $method->invoke($chatService, 'Bisa lihat grafik IPM Karanganyar?', 'Data IPM');
        $this->assertNotNull($chartIpm);
        $this->assertEquals('line', $chartIpm['type']);
        $this->assertContains('78.15', array_map('strval', $chartIpm['data']));

        // 3. Uji grafik kecamatan terpadat
        $chartDistrict = $method->invoke($chatService, 'Diagram perbandingan penduduk kecamatan', 'Data');
        $this->assertNotNull($chartDistrict);
        $this->assertEquals('bar', $chartDistrict['type']);
        $this->assertContains('Colomadu', $chartDistrict['labels']);
    }

    /**
     * Test bahwa prompt sistem AiLlmService memuat instruksi Basa Jawa Krama Alus.
     */
    public function test_multilingual_system_prompt(): void
    {
        $llmService = app(\App\Services\AiLlmService::class);

        $reflection = new \ReflectionClass($llmService);
        $method = $reflection->getMethod('buildSystemPrompt');
        $method->setAccessible(true);

        $prompt = $method->invoke($llmService, 'Rujukan BPS');

        $this->assertStringContainsStringIgnoringCase('Basa Jawa Krama', $prompt);
        $this->assertStringContainsString('BPS Kabupaten Karanganyar', $prompt);
    }

    /**
     * Test bahwa 17 Kecamatan Karanganyar memiliki kode wilayah BPS resmi (3313010 - 3313170).
     */
    public function test_official_district_codes_integrity(): void
    {
        $seederPath = database_path('seeders/DistrictStatisticSeeder.php');
        $content = file_get_contents($seederPath);

        preg_match_all("/'code'\s*=>\s*'(\d+)'/", $content, $matches);
        $codes = $matches[1] ?? [];

        $this->assertCount(17, $codes);

        $expectedCodes = [
            '3313010', '3313020', '3313030', '3313040', '3313050',
            '3313060', '3313070', '3313080', '3313090', '3313100',
            '3313110', '3313120', '3313130', '3313140', '3313150',
            '3313160', '3313170'
        ];

        sort($codes);
        $this->assertEquals($expectedCodes, $codes);
    }
}
