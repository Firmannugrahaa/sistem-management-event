<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Voucher::class);

        return view('vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Voucher::class);

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:vouchers,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date|after:today',
            'max_uses' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        Voucher::create($validated);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        return redirect()->route('vouchers.edit', $voucher->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        $this->authorize('update', $voucher);

        return view('vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $this->authorize('update', $voucher);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vouchers')->ignore($voucher->id),
            ],
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date|after:today',
            'max_uses' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $voucher->update($validated);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        $this->authorize('delete', $voucher);

        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus');
    }
}
