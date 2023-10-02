<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrafficActivateCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TrafficActivateCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $codes = TrafficActivateCode::all();
        $count = $codes->count();
        return view('admin.codes.index', ['codes' => $codes, 'count' => $count]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.codes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $amount = $request->input('amount');
        $traffic = $request->input('traffic');
        $codes = $this->generate_key($amount);
        foreach ($codes as $code) {
            TrafficActivateCode::create([
                'code' => $code,
                'traffic' => $traffic,
            ]);
        }
        return view('admin.codes.show', compact('codes'));
    }

    private function generate_key($count): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $code = Str::random(25);
            $code = strtoupper($code);
            $formattedCode = preg_replace('/(\w{5})(\w{5})(\w{5})(\w{5})(\w{5})/', '$1-$2-$3-$4-$5', $code);
            $codes[] = $formattedCode;
        }

        return $codes;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrafficActivateCode $code)
    {
        $code->delete();

        return back()->with('success', '删除成功');
    }
}
