<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeArticle;
use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;

class KnowledgeArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = KnowledgeArticle::with(['category', 'creator']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('knowledge_category_id', $request->input('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $articles = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $categories = KnowledgeCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.knowledge_articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = KnowledgeCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.knowledge_articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'knowledge_category_id' => ['required', 'exists:knowledge_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'keywords' => ['nullable', 'string'],
            'source_title' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Parse keywords dari teks pisah koma
        $keywords = [];
        if (!empty($validated['keywords'])) {
            $keywords = array_values(array_filter(array_map('trim', explode(',', $validated['keywords']))));
        }

        KnowledgeArticle::create([
            'knowledge_category_id' => $validated['knowledge_category_id'],
            'title' => $validated['title'],
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'keywords' => $keywords,
            'source_title' => $validated['source_title'] ?? null,
            'source_url' => $validated['source_url'] ?? null,
            'published_at' => now(),
            'is_active' => $request->has('is_active'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel basis pengetahuan berhasil ditambahkan.');
    }

    public function edit(KnowledgeArticle $article)
    {
        $categories = KnowledgeCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.knowledge_articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, KnowledgeArticle $article)
    {
        $validated = $request->validate([
            'knowledge_category_id' => ['required', 'exists:knowledge_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'keywords' => ['nullable', 'string'],
            'source_title' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $keywords = [];
        if (!empty($validated['keywords'])) {
            $keywords = array_values(array_filter(array_map('trim', explode(',', $validated['keywords']))));
        }

        $article->update([
            'knowledge_category_id' => $validated['knowledge_category_id'],
            'title' => $validated['title'],
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'keywords' => $keywords,
            'source_title' => $validated['source_title'] ?? null,
            'source_url' => $validated['source_url'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function toggleActive(KnowledgeArticle $article)
    {
        $article->update(['is_active' => !$article->is_active]);
        $status = $article->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Artikel berhasil {$status}.");
    }

    public function destroy(KnowledgeArticle $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
