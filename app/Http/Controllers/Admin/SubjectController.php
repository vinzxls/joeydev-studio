<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index()
    {
        return view('admin.subjects.index', [
            'subjects' => Subject::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        Subject::create($data);

        return back()->with('status', 'Subject baru ditambahkan.');
    }

    public function update(Request $request, Subject $subject)
    {
        $subject->update($this->validated($request));

        return back()->with('status', 'Subject "'.$subject->name.'" diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->orders()->exists()) {
            // Sudah dipakai order — nonaktifkan saja agar histori tetap utuh
            $subject->update(['is_active' => false]);

            return back()->with('status', 'Subject sudah dipakai order, jadi dinonaktifkan (tidak dihapus).');
        }

        $subject->delete();

        return back()->with('status', 'Subject dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'base_price' => ['nullable', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'is_custom' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_custom'] = $request->boolean('is_custom');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        while (Subject::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
