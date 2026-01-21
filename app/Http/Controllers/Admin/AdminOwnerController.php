<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;

class AdminOwnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Owner::withCount('kosts');

        // Filter berdasarkan verifikasi
        if ($request->filled('verified')) {
            if ($request->verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->verified === 'no') {
                $query->whereNull('email_verified_at');
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $owners = $query->latest()->paginate(15);

        return view('admin.owners', compact('owners'));
    }

    public function show(Owner $owner)
    {
        $owner->load('kosts');
        return view('admin.owner-detail', compact('owner'));
    }
}