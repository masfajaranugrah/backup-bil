<?php

namespace App\Http\Controllers;

use App\Models\CustomerFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerFeedbackController extends Controller
{
    public function create()
    {
        $user = Auth::guard('customer')->user();

        if (! $user) {
            return redirect()->route('users.member');
        }

        $feedbacks = CustomerFeedback::query()
            ->where('pelanggan_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('content.apps.Customer.feedback.feedback', compact('user', 'feedbacks'));
    }

    public function index(Request $request)
    {
        $query = CustomerFeedback::with(['pelanggan', 'admin'])
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                    ->orWhere('admin_note', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($pelanggan) use ($search) {
                        $pelanggan->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nomer_id', 'like', "%{$search}%")
                            ->orWhere('no_telp', 'like', "%{$search}%")
                            ->orWhere('no_whatsapp', 'like', "%{$search}%");
                    });
            });
        }

        $statistics = [
            'total' => CustomerFeedback::count(),
            'pending' => CustomerFeedback::whereNull('admin_note')
                ->orWhere('admin_note', '')
                ->count(),
            'with_attachment' => CustomerFeedback::whereNotNull('attachment')->count(),
        ];

        $feedbacks = $query->paginate(20)->withQueryString();

        return view('content.apps.Feedback.index', compact('feedbacks', 'statistics'));
    }

    public function store(Request $request)
    {
        $pelanggan = Auth::guard('customer')->user();

        abort_if(! $pelanggan, 401);

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ]);

        $path = null;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('feedback-attachments', 'public');
        }

        CustomerFeedback::create([
            'pelanggan_id' => $pelanggan->id,
            'message' => $validated['message'],
            'attachment' => $path,
        ]);

        return back()->with('feedback_success', 'Terima kasih, masukan Anda berhasil dikirim.');
    }

    public function update(Request $request, CustomerFeedback $feedback)
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $feedback->update([
            'admin_note' => $validated['admin_note'] ?? null,
            'admin_id' => Auth::id(),
        ]);

        return back()->with('success', 'Catatan tindak lanjut berhasil disimpan.');
    }

    public function destroy(CustomerFeedback $feedback)
    {
        if ($feedback->attachment) {
            Storage::disk('public')->delete($feedback->attachment);
        }

        $feedback->delete();

        return back()->with('success', 'Feedback berhasil dihapus.');
    }
}
