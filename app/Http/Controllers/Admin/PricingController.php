<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\PricingMultiplier;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        return view('admin.pricing.index', [
            'multipliers' => PricingMultiplier::orderBy('type')->orderBy('sort_order')->get()->groupBy('type'),
            'addons' => Addon::orderBy('sort_order')->get(),
        ]);
    }

    public function updateMultiplier(Request $request, PricingMultiplier $multiplier)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:200'],
            'value' => ['required', 'numeric', 'min:0.1', 'max:99'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $multiplier->update($data);

        return back()->with('status', 'Multiplier "'.$multiplier->label.'" diperbarui.');
    }

    public function storeMultiplier(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:scale,complexity,urgency'],
            'label' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:200'],
            'value' => ['required', 'numeric', 'min:0.1', 'max:99'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 99;

        PricingMultiplier::create($data);

        return back()->with('status', 'Level multiplier baru ditambahkan.');
    }

    public function storeAddon(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:200'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        Addon::create($data + ['sort_order' => 99]);

        return back()->with('status', 'Add-on baru ditambahkan.');
    }

    public function updateAddon(Request $request, Addon $addon)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:200'],
            'price' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $addon->update($data);

        return back()->with('status', 'Add-on "'.$addon->name.'" diperbarui.');
    }
}
