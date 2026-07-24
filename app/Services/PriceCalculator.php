<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\PricingMultiplier;
use App\Models\Subject;

class PriceCalculator
{
    /**
     * Hitung estimasi harga.
     *
     * Rumus: Harga Dasar × Skala × Kompleksitas × Urgensi + Total Add-on.
     * Tidak ada cap atas — order besar naik proporsional.
     *
     * @param  int[]  $addonIds
     */
    public function calculate(Subject $subject, ?int $scaleId, ?int $complexityId, ?int $urgencyId, array $addonIds = []): array
    {
        $scale = $scaleId ? PricingMultiplier::active()->ofType('scale')->find($scaleId) : null;
        $complexity = $complexityId ? PricingMultiplier::active()->ofType('complexity')->find($complexityId) : null;
        $urgency = $urgencyId ? PricingMultiplier::active()->ofType('urgency')->find($urgencyId) : null;

        $addons = Addon::active()->whereIn('id', $addonIds)->get();
        $addonsTotal = (int) $addons->sum('price');

        // Subject custom: harga menunggu konsultasi admin
        if ($subject->is_custom || $subject->base_price === null) {
            return [
                'is_custom' => true,
                'base_price' => null,
                'scale' => $scale,
                'complexity' => $complexity,
                'urgency' => $urgency,
                'addons' => $addons,
                'addons_total' => $addonsTotal,
                'final_price' => null,
            ];
        }

        $multiplied = (float) $subject->base_price;
        $multiplied *= $scale ? (float) $scale->value : 1.0;
        $multiplied *= $complexity ? (float) $complexity->value : 1.0;
        $multiplied *= $urgency ? (float) $urgency->value : 1.0;

        // Bulatkan ke ribuan terdekat agar harga rapi
        $subtotal = (int) (round($multiplied / 1000) * 1000);

        return [
            'is_custom' => false,
            'base_price' => (int) $subject->base_price,
            'scale' => $scale,
            'complexity' => $complexity,
            'urgency' => $urgency,
            'addons' => $addons,
            'addons_total' => $addonsTotal,
            'subtotal' => $subtotal,
            'final_price' => $subtotal + $addonsTotal,
        ];
    }
}
