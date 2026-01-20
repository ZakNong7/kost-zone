<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
    

    public function dashboard(Request $request)
    {
        $query = auth()->guard('owner')->user()->kosts();

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $kosts = $query->orderBy('created_at', 'desc')->get();

        return view('owner.dashboard', compact('kosts'));
    }
}