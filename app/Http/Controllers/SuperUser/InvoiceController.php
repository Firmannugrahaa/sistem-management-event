<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of all invoices for the SuperUser.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['event.user'])->latest();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(20)->withQueryString();

        return view('superuser.invoices.index', compact('invoices'));
    }
}
