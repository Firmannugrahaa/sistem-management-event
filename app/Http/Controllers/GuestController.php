<?php

namespace App\Http\Controllers;

use App\Imports\GuestsImport;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class GuestController extends Controller
{
    use AuthorizesRequests;

    public function showImportForm(Event $event)
    {
        $this->authorize('create', [Guest::class, $event]);

        return view('guests.import', compact('event'));
    }

    public function import(Request $request, Event $event)
    {
        $this->authorize('create', [Guest::class, $event]);

        $request->validate([
            'guest_file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new GuestsImport($event->id), $request->file('guest_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()->with('error', 'Gagal mengimpor tamu. Pastikan format file sesuai.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.' . $e->getMessage());
        }
        return redirect()->route('events.show', $event->id)->with('success', 'Tamu berhasil diimpor.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $this->authorize('create', [Guest::class, $event]);

        return view('guests.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Event $event, Request $request,)
    {
        $this->authorize('create', [Guest::class, $event]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => [
                'required',
                'string',
                'max:12',

                Rule::unique('guests')->where(function ($query) use ($event) {
                    return $query->where('event_id', $event->id);
                }),
            ],
        ]);

        $event->guests()->create($validated);

        return redirect()->route('events.show', $event->id)->with('success', 'tamu berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, Guest $guest)
    {
        $this->authorize('update', $guest);

        if ($guest->event_id !== $event->id) {
            abort(404);
        }

        return view('guests.edit', compact('event', 'guest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event, Guest $guest)
    {
        $this->authorize('update', $guest);

        if ($guest->event_id !== $event->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => [
                'required',
                'string',
                'max:12',
                Rule::unique('guests')->where(function ($query) use ($event) {
                    return $query->where('event_id', $event->id);
                })->ignore($guest->id)
            ],
        ]);

        $guest->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Tamu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Guest $guest)
    {
        $this->authorize('delete', $guest);

        if ($guest->event_id !== $event->id) {
            abort(404);
        }

        $guest->delete();
        return redirect()->route('events.show', $event)->with('success', 'Tamu berhasil dihapus.');
    }
}
