@extends('layouts.admin')

@section('title', 'Subject & Layanan — Admin JoeyDev')

@section('content')
    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Subject &amp; Layanan<span class="text-pink-500">.</span></h1>
    <p class="mt-1.5 text-sm text-slate-500">Kelola kategori layanan, harga dasar, dan status aktifnya.</p>

    <div class="mt-6 space-y-3">
        @foreach ($subjects as $subject)
            <details class="group rounded-3xl border border-slate-200 bg-white shadow-sm transition open:border-violet-300 open:shadow-md">
                <summary class="flex cursor-pointer flex-wrap items-center justify-between gap-2 px-6 py-4.5" style="padding-top:1.1rem;padding-bottom:1.1rem">
                    <span class="flex items-center gap-2">
                        <span class="font-bold {{ $subject->is_active ? 'text-slate-900' : 'text-slate-400 line-through' }}">{{ $subject->name }}</span>
                        @unless ($subject->is_active)
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-500">nonaktif</span>
                        @endunless
                    </span>
                    <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-bold text-violet-600">
                        {{ $subject->base_price !== null ? 'Rp'.number_format($subject->base_price, 0, ',', '.').' '.$subject->unit : 'Harga via konsultasi' }}
                    </span>
                </summary>
                <form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="grid gap-4 border-t border-slate-100 px-6 py-5 sm:grid-cols-2">
                    @csrf @method('PATCH')
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Nama</label>
                        <input type="text" name="name" value="{{ $subject->name }}" required class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Satuan</label>
                        <input type="text" name="unit" value="{{ $subject->unit }}" required class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Deskripsi</label>
                        <textarea name="description" rows="2" class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">{{ $subject->description }}</textarea>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Harga dasar (Rp) — kosongkan jika via konsultasi</label>
                        <input type="number" name="base_price" min="0" value="{{ $subject->base_price }}" class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Urutan tampil</label>
                        <input type="number" name="sort_order" min="0" value="{{ $subject->sort_order }}" class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
                    </div>
                    <div class="flex flex-wrap items-center gap-5 sm:col-span-2">
                        <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_custom" value="1" @checked($subject->is_custom) class="accent-violet-600"> Harga via konsultasi (custom)</label>
                        <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_active" value="1" @checked($subject->is_active) class="accent-violet-600"> Aktif (tampil di form order)</label>
                    </div>
                    <div class="sm:col-span-2">
                        <button class="rounded-full bg-violet-600 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/30 transition hover:bg-violet-700">Simpan</button>
                    </div>
                </form>
                <div class="border-t border-slate-100 px-6 py-4">
                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}"
                          onsubmit="return confirm('Hapus subject {{ $subject->name }}? Jika sudah dipakai order, subject hanya akan dinonaktifkan.')">
                        @csrf @method('DELETE')
                        <button class="rounded-full border border-rose-200 px-5 py-2 text-sm font-bold text-rose-500 transition hover:bg-rose-50">Hapus subject ini</button>
                    </form>
                </div>
            </details>
        @endforeach
    </div>

    <section class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="font-extrabold text-slate-900">Tambah Subject Baru<span class="text-emerald-500">.</span></h2>
        <form method="POST" action="{{ route('admin.subjects.store') }}" class="mt-4 grid gap-4 sm:grid-cols-2">
            @csrf
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Nama <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Satuan <span class="text-rose-500">*</span></label>
                <input type="text" name="unit" value="per project" required class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Deskripsi</label>
                <textarea name="description" rows="2" class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100"></textarea>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-400">Harga dasar (Rp)</label>
                <input type="number" name="base_price" min="0" class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm transition focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100">
            </div>
            <div class="flex items-end gap-5 pb-1">
                <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_custom" value="1" class="accent-violet-600"> Custom</label>
                <label class="flex items-center gap-2 text-sm font-medium"><input type="checkbox" name="is_active" value="1" checked class="accent-violet-600"> Aktif</label>
            </div>
            <div class="sm:col-span-2">
                <button class="rounded-full bg-violet-600 px-6 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/30 transition hover:bg-violet-700">Tambah Subject</button>
            </div>
        </form>
    </section>
@endsection
