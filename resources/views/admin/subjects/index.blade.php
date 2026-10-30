@extends('layouts.admin')

@section('title', 'Subject & Layanan — Admin JoeyDev')
@section('crumb', 'Subject & Layanan')

@section('content')
    <div class="ad-page-head">
        <div>
            <p class="ad-eyebrow">Katalog</p>
            <h1 class="ad-title">Subject &amp; Layanan<span>.</span></h1>
            <p style="margin-top:8px;font-size:12.5px;color:#667085">
                Kelola kategori layanan, harga dasar, dan status aktifnya.
            </p>
        </div>
        <p class="ad-mono text-[10px] uppercase tracking-[0.14em] text-[#98a2b3]">
            {{ $subjects->count() }} kategori
        </p>
    </div>

    <div class="mt-5 flex flex-col gap-2.5">
        @foreach ($subjects as $subject)
            <details class="ad-card group" style="overflow:hidden">
                <summary style="display:flex;cursor:pointer;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:14px 20px;list-style:none">
                    <span style="display:flex;align-items:center;gap:9px;min-width:0">
                        <span class="ad-mono" style="font-size:9.5px;letter-spacing:0.14em;color:#d0d5dd">
                            {{ str_pad((string) $subject->sort_order, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <span style="font-size:13.5px;font-weight:700;color:{{ $subject->is_active ? '#101828' : '#98a2b3' }};{{ $subject->is_active ? '' : 'text-decoration:line-through' }}">
                            {{ $subject->name }}
                        </span>
                        @unless ($subject->is_active)
                            <span class="ad-chip baru">nonaktif</span>
                        @endunless
                        @if ($subject->is_custom)
                            <span class="ad-chip revisi">konsultasi</span>
                        @endif
                    </span>
                    <span class="ad-mono ad-num" style="font-size:11.5px;font-weight:700;color:#1d4ed8;white-space:nowrap">
                        {{ $subject->base_price !== null ? 'Rp'.number_format($subject->base_price, 0, ',', '.').' '.$subject->unit : 'Via konsultasi' }}
                    </span>
                </summary>

                <form method="POST" action="{{ route('admin.subjects.update', $subject) }}"
                      class="ad-form-2" style="border-top:1px solid #eef1f6;padding:18px 20px 20px">
                    @csrf @method('PATCH')

                    <div>
                        <label class="ad-label" for="nm-{{ $subject->id }}">Nama</label>
                        <input id="nm-{{ $subject->id }}" type="text" name="name" value="{{ $subject->name }}" required class="ad-input">
                    </div>

                    <div>
                        <label class="ad-label" for="un-{{ $subject->id }}">Satuan</label>
                        <input id="un-{{ $subject->id }}" type="text" name="unit" value="{{ $subject->unit }}" required class="ad-input">
                    </div>

                    <div style="grid-column:1/-1">
                        <label class="ad-label" for="ds-{{ $subject->id }}">Deskripsi</label>
                        <textarea id="ds-{{ $subject->id }}" name="description" rows="2" class="ad-textarea">{{ $subject->description }}</textarea>
                    </div>

                    <div>
                        <label class="ad-label" for="bp-{{ $subject->id }}">Harga dasar (Rp) — kosongkan jika via konsultasi</label>
                        <input id="bp-{{ $subject->id }}" type="number" name="base_price" min="0" value="{{ $subject->base_price }}" class="ad-input ad-num">
                    </div>

                    <div>
                        <label class="ad-label" for="so-{{ $subject->id }}">Urutan tampil</label>
                        <input id="so-{{ $subject->id }}" type="number" name="sort_order" min="0" value="{{ $subject->sort_order }}" class="ad-input ad-num">
                    </div>

                    <div style="grid-column:1/-1;display:flex;flex-wrap:wrap;align-items:center;gap:18px;font-size:12.5px;color:#344054">
                        <label style="display:flex;align-items:center;gap:7px;font-weight:600">
                            <input type="checkbox" name="is_custom" value="1" @checked($subject->is_custom) style="accent-color:#1d4ed8">
                            Harga via konsultasi (custom)
                        </label>
                        <label style="display:flex;align-items:center;gap:7px;font-weight:600">
                            <input type="checkbox" name="is_active" value="1" @checked($subject->is_active) style="accent-color:#1d4ed8">
                            Aktif (tampil di form order)
                        </label>
                    </div>

                    <div style="grid-column:1/-1">
                        <button class="ad-btn">Simpan perubahan</button>
                    </div>
                </form>

                <div style="border-top:1px solid #eef1f6;padding:12px 20px">
                    <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}"
                          onsubmit="return confirm('Hapus subject {{ $subject->name }}? Jika sudah dipakai order, subject hanya akan dinonaktifkan.')">
                        @csrf @method('DELETE')
                        <button class="ad-btn ad-btn-sm ad-btn-danger">Hapus subject ini</button>
                    </form>
                </div>
            </details>
        @endforeach
    </div>

    {{-- Form tambah: dipisah jelas dari daftar agar tidak salah klik Simpan --}}
    <section class="ad-card" style="margin-top:22px">
        <div class="ad-card-head">
            <h2 class="ad-card-title">Tambah subject baru</h2>
            <span class="ad-mono" style="font-size:9.5px;letter-spacing:0.14em;text-transform:uppercase;color:#98a2b3">
                kategori baru
            </span>
        </div>

        <form method="POST" action="{{ route('admin.subjects.store') }}"
              class="ad-form-2" style="padding:18px 20px 20px">
            @csrf

            <div>
                <label class="ad-label" for="new-name">Nama <span style="color:#b42318">*</span></label>
                <input id="new-name" type="text" name="name" required class="ad-input">
            </div>

            <div>
                <label class="ad-label" for="new-unit">Satuan <span style="color:#b42318">*</span></label>
                <input id="new-unit" type="text" name="unit" value="per project" required class="ad-input">
            </div>

            <div style="grid-column:1/-1">
                <label class="ad-label" for="new-desc">Deskripsi</label>
                <textarea id="new-desc" name="description" rows="2" class="ad-textarea"></textarea>
            </div>

            <div>
                <label class="ad-label" for="new-price">Harga dasar (Rp)</label>
                <input id="new-price" type="number" name="base_price" min="0" class="ad-input ad-num">
            </div>

            <div style="display:flex;align-items:center;gap:18px;font-size:12.5px;color:#344054">
                <label style="display:flex;align-items:center;gap:7px;font-weight:600">
                    <input type="checkbox" name="is_custom" value="1" style="accent-color:#1d4ed8"> Custom
                </label>
                <label style="display:flex;align-items:center;gap:7px;font-weight:600">
                    <input type="checkbox" name="is_active" value="1" checked style="accent-color:#1d4ed8"> Aktif
                </label>
            </div>

            <div style="grid-column:1/-1">
                <button class="ad-btn">Tambah subject</button>
            </div>
        </form>
    </section>
@endsection
