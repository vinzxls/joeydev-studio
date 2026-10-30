@extends('layouts.admin')

@section('title', 'Harga & Add-on — Admin JoeyDev')
@section('crumb', 'Harga & Add-on')

@section('content')
    <div class="ad-page-head">
        <div>
            <p class="ad-eyebrow">Konfigurasi</p>
            <h1 class="ad-title">Konfigurasi Harga<span>.</span></h1>
            <p style="margin-top:8px;font-size:12.5px;color:#667085">
                Semua multiplier dan add-on bisa diubah tanpa menyentuh kode.
                Perubahan hanya memengaruhi order baru.
            </p>
        </div>
    </div>

    <div class="ad-cols-3 mt-5">

        @foreach (\App\Models\PricingMultiplier::TYPES as $type => $typeLabel)
            <section class="ad-card">
                <div class="ad-card-head">
                    <h2 class="ad-card-title">{{ $typeLabel }}</h2>
                    <span class="ad-mono" style="font-size:9.5px;letter-spacing:0.14em;text-transform:uppercase;color:#98a2b3">
                        &#215; faktor
                    </span>
                </div>

                <div style="display:flex;flex-direction:column;gap:1px;background:#eef1f6;padding:1px 0">
                    @foreach ($multipliers->get($type, collect()) as $m)
                        <form method="POST" action="{{ route('admin.pricing.multipliers.update', $m) }}"
                              style="background:#fff;padding:12px 16px 14px">
                            @csrf @method('PATCH')

                            <div style="display:flex;gap:8px;align-items:center">
                                <input type="text" name="label" value="{{ $m->label }}" required
                                       class="ad-input" style="font-weight:700;flex:1;min-width:0">
                                <input type="number" name="value" value="{{ $m->value }}" step="0.05" min="0.1" max="99" required
                                       class="ad-input ad-num" style="width:72px;flex:none;text-align:center;font-weight:700;color:#1d4ed8">
                            </div>

                            <input type="text" name="description" value="{{ $m->description }}" placeholder="Deskripsi singkat"
                                   class="ad-input" style="margin-top:7px;font-size:11.5px;color:#667085">

                            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:9px">
                                <label style="display:flex;align-items:center;gap:6px;font-size:11.5px;font-weight:600;color:#475467">
                                    <input type="checkbox" name="is_active" value="1" @checked($m->is_active) style="accent-color:#1d4ed8">
                                    Aktif
                                </label>
                                <button class="ad-btn ad-btn-sm">Simpan</button>
                            </div>
                        </form>
                    @endforeach
                </div>

                <details style="border-top:1px solid #eef1f6">
                    <summary class="ad-mono" style="cursor:pointer;padding:11px 16px;font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:#1d4ed8">
                        + Tambah level
                    </summary>
                    <form method="POST" action="{{ route('admin.pricing.multipliers.store') }}"
                          style="display:flex;flex-direction:column;gap:7px;padding:0 16px 16px">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="text" name="label" placeholder="Label" required class="ad-input">
                        <input type="number" name="value" placeholder="Nilai (mis. 1.25)" step="0.05" min="0.1" max="99" required class="ad-input ad-num">
                        <input type="text" name="description" placeholder="Deskripsi singkat" class="ad-input">
                        <button class="ad-btn ad-btn-sm" style="align-self:flex-start">Tambah</button>
                    </form>
                </details>
            </section>
        @endforeach
    </div>

    <section class="ad-card" style="margin-top:22px">
        <div class="ad-card-head">
            <h2 class="ad-card-title">Add-on (kebutuhan tambahan)</h2>
            <span class="ad-mono" style="font-size:9.5px;letter-spacing:0.14em;text-transform:uppercase;color:#98a2b3">
                {{ $addons->count() }} item
            </span>
        </div>

        <div class="ad-cols-2" style="padding:14px 16px;gap:12px">
            @foreach ($addons as $addon)
                <form method="POST" action="{{ route('admin.pricing.addons.update', $addon) }}"
                      style="border:1px solid #eef1f6;border-radius:6px;background:#fff;padding:12px 14px 14px">
                    @csrf @method('PATCH')

                    <div style="display:flex;gap:8px">
                        <input type="text" name="name" value="{{ $addon->name }}" required
                               class="ad-input" style="font-weight:700;flex:1;min-width:0">
                        <input type="number" name="price" value="{{ $addon->price }}" min="0" step="1000" required
                               class="ad-input ad-num" style="width:104px;flex:none;text-align:right;font-weight:700">
                    </div>

                    <input type="text" name="description" value="{{ $addon->description }}" placeholder="Deskripsi"
                           class="ad-input" style="margin-top:7px;font-size:11.5px;color:#667085">

                    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:9px">
                        <label style="display:flex;align-items:center;gap:6px;font-size:11.5px;font-weight:600;color:#475467">
                            <input type="checkbox" name="is_active" value="1" @checked($addon->is_active) style="accent-color:#1d4ed8">
                            Aktif
                        </label>
                        <button class="ad-btn ad-btn-sm">Simpan</button>
                    </div>
                </form>
            @endforeach
        </div>

        <details style="border-top:1px solid #eef1f6">
            <summary class="ad-mono" style="cursor:pointer;padding:11px 16px;font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:#1d4ed8">
                + Tambah add-on
            </summary>
            <form method="POST" action="{{ route('admin.pricing.addons.store') }}"
                  style="display:grid;gap:7px;grid-template-columns:1fr 130px;padding:0 16px 16px;max-width:620px">
                @csrf
                <input type="text" name="name" placeholder="Nama add-on" required class="ad-input">
                <input type="number" name="price" placeholder="Harga" min="0" step="1000" required class="ad-input ad-num">
                <input type="text" name="description" placeholder="Deskripsi" class="ad-input" style="grid-column:1/-1">
                <button class="ad-btn ad-btn-sm" style="grid-column:1/-1;justify-self:start">Tambah</button>
            </form>
        </details>
    </section>
@endsection
