@extends('layouts.admin')

@section('title', 'Harga & Add-on — Admin JoeyDev')

@section('content')
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Konfigurasi Harga<span class="text-emerald-500">.</span></h1>
    <p class="mt-1.5 text-sm text-slate-500">Semua multiplier dan add-on bisa diubah tanpa menyentuh kode. Perubahan hanya memengaruhi order baru.</p>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        @foreach (\App\Models\PricingMultiplier::TYPES as $type => $typeLabel)
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-extrabold text-slate-900">{{ $typeLabel }}<span class="text-violet-600">.</span></h2>
                <div class="mt-4 space-y-3">
                    @foreach ($multipliers->get($type, collect()) as $m)
                        <form method="POST" action="{{ route('admin.pricing.multipliers.update', $m) }}" class="rounded-2xl border border-slate-200 p-4 transition focus-within:border-violet-300">
                            @csrf @method('PATCH')
                            <div class="flex gap-2">
                                <input type="text" name="label" value="{{ $m->label }}" required class="w-full min-w-0 rounded-xl border border-slate-300 px-3 py-2 text-sm font-semibold transition focus:border-violet-500 focus:outline-none">
                                <input type="number" name="value" value="{{ $m->value }}" step="0.05" min="0.1" max="99" required class="w-20 rounded-xl border border-slate-300 px-3 py-2 text-sm font-bold transition focus:border-violet-500 focus:outline-none">
                            </div>
                            <input type="text" name="description" value="{{ $m->description }}" placeholder="Deskripsi singkat" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2 text-xs transition focus:border-violet-500 focus:outline-none">
                            <div class="mt-3 flex items-center justify-between">
                                <label class="flex items-center gap-1.5 text-xs font-medium text-slate-600"><input type="checkbox" name="is_active" value="1" @checked($m->is_active) class="accent-violet-600"> Aktif</label>
                                <button class="rounded-full bg-violet-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm shadow-violet-600/30 transition hover:bg-violet-700">Simpan</button>
                            </div>
                        </form>
                    @endforeach
                </div>

                <details class="mt-4">
                    <summary class="cursor-pointer text-sm font-bold text-violet-600">+ Tambah level</summary>
                    <form method="POST" action="{{ route('admin.pricing.multipliers.store') }}" class="mt-3 space-y-2">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="text" name="label" placeholder="Label" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm transition focus:border-violet-500 focus:outline-none">
                        <input type="number" name="value" placeholder="Nilai (mis. 1.25)" step="0.05" min="0.1" max="99" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm transition focus:border-violet-500 focus:outline-none">
                        <input type="text" name="description" placeholder="Deskripsi singkat" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm transition focus:border-violet-500 focus:outline-none">
                        <button class="rounded-full bg-violet-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm shadow-violet-600/30 transition hover:bg-violet-700">Tambah</button>
                    </form>
                </details>
            </section>
        @endforeach
    </div>

    <section class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="font-extrabold text-slate-900">Add-on (Kebutuhan Tambahan)<span class="text-amber-500">.</span></h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2">
            @foreach ($addons as $addon)
                <form method="POST" action="{{ route('admin.pricing.addons.update', $addon) }}" class="rounded-2xl border border-slate-200 p-4 transition focus-within:border-violet-300">
                    @csrf @method('PATCH')
                    <div class="flex gap-2">
                        <input type="text" name="name" value="{{ $addon->name }}" required class="w-full min-w-0 rounded-xl border border-slate-300 px-3 py-2 text-sm font-semibold transition focus:border-violet-500 focus:outline-none">
                        <input type="number" name="price" value="{{ $addon->price }}" min="0" step="1000" required class="w-28 rounded-xl border border-slate-300 px-3 py-2 text-sm font-bold transition focus:border-violet-500 focus:outline-none">
                    </div>
                    <input type="text" name="description" value="{{ $addon->description }}" placeholder="Deskripsi" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2 text-xs transition focus:border-violet-500 focus:outline-none">
                    <div class="mt-3 flex items-center justify-between">
                        <label class="flex items-center gap-1.5 text-xs font-medium text-slate-600"><input type="checkbox" name="is_active" value="1" @checked($addon->is_active) class="accent-violet-600"> Aktif</label>
                        <button class="rounded-full bg-violet-600 px-4 py-1.5 text-xs font-bold text-white shadow-sm shadow-violet-600/30 transition hover:bg-violet-700">Simpan</button>
                    </div>
                </form>
            @endforeach
        </div>

        <details class="mt-5">
            <summary class="cursor-pointer text-sm font-bold text-violet-600">+ Tambah add-on</summary>
            <form method="POST" action="{{ route('admin.pricing.addons.store') }}" class="mt-3 flex max-w-xl flex-wrap gap-2">
                @csrf
                <input type="text" name="name" placeholder="Nama add-on" required class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm transition focus:border-violet-500 focus:outline-none">
                <input type="number" name="price" placeholder="Harga" min="0" step="1000" required class="w-32 rounded-xl border border-slate-300 px-3 py-2 text-sm transition focus:border-violet-500 focus:outline-none">
                <input type="text" name="description" placeholder="Deskripsi" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm transition focus:border-violet-500 focus:outline-none">
                <button class="rounded-full bg-violet-600 px-5 py-2 text-xs font-bold text-white shadow-sm shadow-violet-600/30 transition hover:bg-violet-700">Tambah</button>
            </form>
        </details>
    </section>
@endsection
