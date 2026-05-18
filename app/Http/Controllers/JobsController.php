<?php

namespace App\Http\Controllers;

use App\Models\Ticket; // ← jangan lupa ini
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobsController extends Controller
{
    /**
     * Display all tickets assigned to the logged-in teknisi (excluding status approve)
     */
    public function index()
    {
        $user = auth()->user();

        $tickets = Ticket::with(['user', 'creator', 'pelanggan'])
            ->where('user_id', $user->id)
            ->where('status', '!=', 'approved') // status approve dihilangkan
            ->latest()
            ->get();

        return view('content.apps.Karyawan.jobs.jobs', compact('tickets'));
    }

    /**
     * Polling endpoint - returns current ticket statuses as JSON
     */
    public function pollStatus()
    {
        $user = auth()->user();
        $tickets = Ticket::where('user_id', $user->id)
            ->where('status', '!=', 'approved')
            ->get(['id', 'status', 'technician_note', 'updated_at', 'assignment_date', 'created_at']);

        return response()->json($tickets->map(function($t) {
            $tDate = $t->assignment_date ? \Carbon\Carbon::parse($t->assignment_date) : $t->created_at;
            return [
                'id'              => $t->id,
                'status'          => $t->status,
                'technician_note' => $t->technician_note,
                'date'            => $tDate->format('Y-m-d'),
                'updated_at'      => $t->updated_at->timestamp,
            ];
        }));
    }

    /**
     * Display tickets with status "approve"
     */
    public function approved()
    {
        $user = auth()->user();

        $tickets = Ticket::with(['user', 'creator'])
            ->where('user_id', $user->id)
            ->where('status', 'Approved') // hanya tiket approve
            ->latest()
            ->get()
            ->groupBy(fn ($item) => strtolower($item->priority));

        return view('content.apps.Karyawan.jobs.approved-jobs', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit(Ticket $ticket)
    {
        $users = User::all(); // kalau mau assign teknisi

        return view('content.apps.Karyawan.jobs.edit-jobs', compact('ticket', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Auto update status ticket (untuk tombol mulai & selesai)
     */
    public function autoUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status'                => 'required|in:pending,progress,finished',
            'technician_note'       => 'nullable|string',
            'technician_attachment' => 'required_if:status,finished|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $ticket = Ticket::findOrFail($id);

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->has('technician_note')) {
            $updateData['technician_note'] = $request->technician_note;
        }

        // Simpan foto bukti pekerjaan (hanya saat status finished)
        if ($request->status === 'finished' && $request->hasFile('technician_attachment')) {
            $file = $request->file('technician_attachment');
            $path = $file->store('ticket_attachments', 'public');
            $updateData['technician_attachment'] = $path;
        }

        // Update status
        $ticket->update($updateData);

        // Simpan log status
        \App\Models\TicketStatusLog::create([
            'ticket_id' => $ticket->id,
            'status'    => $request->status,
            'user_id'   => auth()->id(),
        ]);

        // Tentukan pesan sesuai status
        if ($request->status == 'pending' && $request->has('technician_note')) {
            $message = 'Permintaan reschedule telah dikirim.';
        } else {
            $message = match ($request->status) {
                'progress' => 'Ticket telah dimulai pengerjaannya.',
                'finished' => 'Ticket telah diselesaikan. Foto bukti tersimpan.',
                default    => 'Status ticket diperbarui.',
            };
        }

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success'    => true,
                'message'    => $message,
                'new_status' => $request->status,
                'photo_url'  => isset($updateData['technician_attachment'])
                                    ? asset('storage/' . $updateData['technician_attachment'])
                                    : null,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with(['user', 'creator'])->findOrFail($id); // ambil ticket sesuai ID
        $users = User::all(); // kalau mau assign teknisi

        return view('content.apps.Karyawan.jobs.preview-jobs', compact('ticket', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:pending,progress,finished',
            'technician_note' => 'nullable|string',
            'technician_attachment' => 'required_if:status,finished|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'status' => $request->status,
            'technician_note' => $request->technician_note,
        ];

        if ($request->hasFile('technician_attachment')) {
            // Hapus file lama jika ada
            if ($ticket->technician_attachment) {
                Storage::disk('public')->delete($ticket->technician_attachment);
            }
            // Simpan file baru
            $data['technician_attachment'] = $request->file('technician_attachment')->store('tickets/technician', 'public');
        }

        // Update ticket
        $ticket->update($data);

        // Simpan log status
        \App\Models\TicketStatusLog::create([
            'ticket_id' => $ticket->id,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('jobs.index')->with('success', 'Progress ticket berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
