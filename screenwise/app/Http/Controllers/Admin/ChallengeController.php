<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::orderBy('day_number')->get();
        return view('admin.challenges.index', compact('challenges'));
    }

    public function create()
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'day_number' => ['required', 'integer', 'min:1', 'max:7'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'challenge_date' => ['nullable', 'date'],
        ]);

        Challenge::create([
            'day_number' => $request->day_number,
            'title' => $request->title,
            'description' => $request->description,
            'challenge_date' => $request->challenge_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil ditambahkan!');
    }

    public function edit(Challenge $challenge)
    {
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, Challenge $challenge)
    {
        $request->validate([
            'day_number' => ['required', 'integer', 'min:1', 'max:7'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'challenge_date' => ['nullable', 'date'],
        ]);

        $challenge->update($request->only(['day_number', 'title', 'description', 'challenge_date']));

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil diperbarui!');
    }

    public function destroy(Challenge $challenge)
    {
        $challenge->delete();
        return redirect()->route('admin.challenges.index')->with('success', 'Challenge berhasil dihapus!');
    }
}
