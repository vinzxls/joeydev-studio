<?php

namespace Tests\Unit;

use App\Models\Addon;
use App\Models\PricingMultiplier;
use App\Models\Subject;
use App\Services\PriceCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PriceCalculatorTest extends TestCase
{
    use RefreshDatabase;

    private PriceCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        // Tes harus berjalan di SQLite :memory:, bukan MySQL joeydev.
        $this->assertSame('sqlite', DB::connection()->getDriverName());

        $this->calculator = new PriceCalculator();
    }

    private function subject(array $attributes = []): Subject
    {
        return Subject::create(array_merge([
            'name' => 'Website Company Profile',
            'slug' => 'website-company-profile',
            'base_price' => 1000000,
            'is_active' => true,
            'sort_order' => 1,
        ], $attributes));
    }

    private function multiplier(string $type, float $value, array $attributes = []): PricingMultiplier
    {
        return PricingMultiplier::create(array_merge([
            'type' => $type,
            'label' => $type,
            'value' => $value,
            'is_active' => true,
            'sort_order' => 1,
        ], $attributes));
    }

    private function addon(int $price, array $attributes = []): Addon
    {
        return Addon::create(array_merge([
            'name' => 'Addon '.$price,
            'price' => $price,
            'is_active' => true,
            'sort_order' => 1,
        ], $attributes));
    }

    public function test_final_price_mengalikan_faktor_dan_menambah_addon(): void
    {
        $subject = $this->subject();
        $scale = $this->multiplier('scale', 1.5);
        $complexity = $this->multiplier('complexity', 2.0);
        $urgency = $this->multiplier('urgency', 1.25);
        $addon = $this->addon(250000);

        $result = $this->calculator->calculate($subject, $scale->id, $complexity->id, $urgency->id, [$addon->id]);

        $this->assertFalse($result['is_custom']);
        $this->assertSame(3750000, $result['subtotal']);
        $this->assertSame(250000, $result['addons_total']);
        $this->assertSame(4000000, $result['final_price']);
    }

    public function test_pembulatan_ke_ribuan_terdekat(): void
    {
        $subject = $this->subject(['base_price' => 1000010]);

        $result = $this->calculator->calculate($subject, null, null, null);

        $this->assertSame(0, $result['subtotal'] % 1000);
        $this->assertSame(1000000, $result['subtotal']);
    }

    public function test_subject_custom_mengembalikan_null_price(): void
    {
        $subject = $this->subject(['is_custom' => true, 'base_price' => null]);
        $addon = $this->addon(250000);

        $result = $this->calculator->calculate($subject, null, null, null, [$addon->id]);

        $this->assertTrue($result['is_custom']);
        $this->assertNull($result['base_price']);
        $this->assertNull($result['final_price']);
    }

    public function test_tanpa_faktor_menggunakan_1(): void
    {
        $subject = $this->subject(['base_price' => 2000000]);
        $addonA = $this->addon(250000);
        $addonB = $this->addon(150000);

        $result = $this->calculator->calculate($subject, null, null, null, [$addonA->id, $addonB->id]);

        $this->assertSame(2000000, $result['subtotal']);
        $this->assertSame(400000, $result['addons_total']);
        $this->assertSame(2400000, $result['final_price']);
    }

    public function test_addon_tidak_aktif_tidak_terhitung(): void
    {
        $subject = $this->subject();
        $inactive = $this->addon(300000, ['is_active' => false]);
        $active = $this->addon(50000);

        $result = $this->calculator->calculate($subject, null, null, null, [$inactive->id, $active->id]);

        $this->assertSame(1, $result['addons']->count());
        $this->assertSame(50000, $result['addons_total']);
    }
}