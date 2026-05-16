<?php

namespace App\Http\Controllers;

use App\Models\Pretest;
use App\Models\Posttest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function showPretest()
    {
        $pretest = Auth::user()->pretest;
        return view('tests.pretest', compact('pretest'));
    }

    public function storePretest(Request $request)
    {
        $request->validate([
            'avg_screen_time' => ['required', 'numeric', 'min:0', 'max:24'],
            'sleep_time' => ['required', 'string'],
            'wake_time' => ['required', 'string'],
            'gadget_habits' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Pretest::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'avg_screen_time' => $request->avg_screen_time,
                'sleep_time' => $request->sleep_time,
                'wake_time' => $request->wake_time,
                'gadget_habits' => $request->gadget_habits ?? [],
                'notes' => $request->notes,
            ]
        );

        return redirect()->route('siswa.dashboard')->with('success', 'Pre-test berhasil disimpan! ✅');
    }

    public function showPosttest()
    {
        $posttest = Auth::user()->posttest;
        return view('tests.posttest', compact('posttest'));
    }

    public function storePosttest(Request $request)
    {
        $request->validate([
            'avg_screen_time' => ['required', 'numeric', 'min:0', 'max:24'],
            'sleep_time' => ['required', 'string'],
            'wake_time' => ['required', 'string'],
            'gadget_habits' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Posttest::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'avg_screen_time' => $request->avg_screen_time,
                'sleep_time' => $request->sleep_time,
                'wake_time' => $request->wake_time,
                'gadget_habits' => $request->gadget_habits ?? [],
                'notes' => $request->notes,
            ]
        );

        return redirect()->route('siswa.dashboard')->with('success', 'Post-test berhasil disimpan! ✅');
    }

    public function comparison()
    {
        $user = Auth::user();
        $pretest = $user->pretest;
        $posttest = $user->posttest;

        return view('tests.comparison', compact('pretest', 'posttest'));
    }
}
