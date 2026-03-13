<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Svg\Tag\Rect;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $portfolios = $request->user()
            ->portfolios()
            ->with('portfolioType')
            ->latest()
            ->get();

        return $this->successResponse($portfolios, 'Data portfolio berhasil ditampilkan');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'portfolio_type_id' => ['required', 'exists:portfolio_types,id'],
            'scheme_id' => ['nullable', 'exists:schemes,id'],
            'description' => ['required', 'string', 'max:500'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        $file = $request->file('file');
        $filename = "portfolio_{$user->id}_" . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("portfolios/{$user->id}", $filename, 'public');

        $portfolio = $user->portfolios()->create([
            'portfolio_type_id' => $validated['portfolio_type_id'],
            'scheme_id' => $validated['scheme_id'] ?? null,
            'description' => $validated['description'],
            'file_path' => $path,
            'file_url' => asset('storage/' . $path)
        ]);

        return $this->successResponse($portfolio, 'Portfolio berhasil diupload');
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        if ($portfolio->user_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $validated = $request->validate([
            'portfolio_type_id' => ['sometimes', 'exists:portfolio_types,id'],
            'scheme_id' => ['nullable', 'exists:schemes,id'],
            'description' => ['sometimes', 'string', 'max:500'],
            'file' => ['sometimes', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($portfolio->file_path);

            $user = $request->user();
            $file = $request->file('file');
            $filename = "portfolio_{$user->id}_" . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("portfolios/{$user->id}", $filename, 'public');

            $validated['file_path'] = $path;
            $validated['file_url'] = asset('storage/' . $path);

            unset($validated['file']);
        }

        $portfolio->update($validated);

        return $this->successResponse(
            $portfolio->fresh()->load('portfolioType'),
            'Portfolio berhasil diupdate'
        );
    }
}

