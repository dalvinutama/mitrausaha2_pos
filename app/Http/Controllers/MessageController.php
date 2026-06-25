<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use App\Models\MessageRead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MessageController extends Controller
{
    /**
     * 1. MENYIMPAN PESAN BARU + AUTO-DELETE PESAN 1 BULAN
     */
    public function store(StoreMessageRequest $request)
    {
        if (!$request->content && !$request->hasFile('file')) {
            return response()->json(['success' => false, 'message' => 'Pesan atau lampiran tidak boleh kosong']);
        }

        // A. SIMPAN PESAN BARU
        $msg = Message::create([
            'from_user_id'    => Auth::id(),
            'conversation_id' => $request->conversation_id, // bisa null untuk grup global default
            'content'         => $request->content ?? '',
        ]);

        // B. HANDLE LAMPIRAN FILE JIKA ADA
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('chat_attachments', $fileName, 'public');
            
            \App\Models\MessageAttachment::create([
                'message_id' => $msg->id,
                'file_path'  => '/storage/' . $filePath,
                'file_type'  => $file->getMimeType(),
                'file_name'  => $file->getClientOriginalName(),
                'file_size'  => $file->getSize(),
            ]);
        }

        // C. LOAD ATTACHMENTS
        $msg->load('attachments');

        // RESPONSE UNTUK AJAX (Realtime)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $msg->id,
                    'content' => $msg->content,
                    'attachments' => $msg->attachments
                ],
                'time'    => $msg->created_at->format('H:i')
            ]);
        }

        return redirect()->back()->with('open_chat', true); 
    }

    /**
     * 2. MENGEDIT PESAN
     */
    public function update(UpdateMessageRequest $request, $id)
    {
        $msg = Message::findOrFail($id);
        
        if ($msg->from_user_id == Auth::id()) { 
            $msg->update(['content' => $request->content]); 
        }
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('open_chat', true);
    }

    /**
     * 3. MENGHAPUS PESAN
     */
    public function destroy(Request $request, $id)
    {
        $msg = Message::findOrFail($id);
        
        if ($msg->from_user_id == Auth::id()) { 
            $msg->delete(); 
        }
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('open_chat', true);
    }

    /**
     * 4. MENCATAT STATUS "SUDAH DIBACA" (DIOPTIMASI AGAR TIDAK NGE-HANG)
     */
    public function markAsRead()
    {
        try {
            $userId = Auth::id();

            // 1. Cari ID pesan yang belum dibaca secara massal
            $unreadMessageIds = Message::where('from_user_id', '!=', $userId)
                ->whereDoesntHave('reads', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->pluck('id');

            // Jika tidak ada pesan baru, langsung kembalikan sukses
            if ($unreadMessageIds->isEmpty()) {
                return response()->json(['success' => true]);
            }

            // 2. Siapkan data untuk BULK INSERT (Super Cepat)
            // Alih-alih nge-loop save ke database 1 per 1 (bikin XAMPP hang),
            // kita jadikan 1 array besar dan tembak ke database sekaligus.
            $insertData = [];
            $now = Carbon::now();
            
            foreach ($unreadMessageIds as $msgId) {
                $insertData[] = [
                    'message_id' => $msgId,
                    'user_id'    => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 3. Tembak ke database dalam 1 kali query!
            MessageRead::insert($insertData);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Mark as Read Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status baca.']);
        }
    }

    /**
     * 5. SMART POLLING (SINKRONISASI REALTIME UNTUK SEMUA USER)
     */
    public function sync(Request $request)
    {
        $lastId = $request->query('last_id', 0);
        $conversationId = $request->query('conversation_id'); 
        $myMessageIds = $request->input('my_message_ids', []);
        $userId = Auth::id();

        // A. AMBIL PESAN BARU
        $query = Message::with(['sender', 'reads', 'attachments'])->where('id', '>', $lastId);
        
        if ($conversationId) {
            $query->where('conversation_id', $conversationId);
        } else {
            $query->whereNull('conversation_id'); // Global chat
        }

        $newMessages = $query->get()->map(function ($msg) use ($userId) {
                return [
                    'id'          => $msg->id,
                    'is_mine'     => $msg->from_user_id == $userId,
                    'sender_name' => $msg->sender->name ?? 'Sistem',
                    'content'     => $msg->content,
                    'attachments' => $msg->attachments,
                    'time'        => $msg->created_at->format('H:i'),
                    'is_read'     => $msg->reads->count() > 0
                ];
            });

        // B. CEK CENTANG BIRU UNTUK PESAN KITA
        $readUpdates = [];
        if (!empty($myMessageIds)) {
            $readUpdates = Message::whereIn('id', $myMessageIds)
                ->whereHas('reads') 
                ->pluck('id');
        }

        // C. HITUNG JUMLAH NOTIFIKASI MERAH AKURAT PER USER
        $unreadCount = Message::where('from_user_id', '!=', $userId)
            ->whereDoesntHave('reads', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        return response()->json([
            'new_messages' => $newMessages,
            'read_updates' => $readUpdates,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * 6. LOAD CONVERSATIONS UNTUK SIDEBAR
     */
    public function getConversations()
    {
        $user = Auth::user();
        $conversations = $user->conversations()->get();
        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

    /**
     * 7. BERSIHKAN SEMUA PESAN (HANYA ADMIN/OWNER)
     */
    public function clearGlobalChat()
    {
        $user = Auth::user();
        if (in_array($user->role, ['owner', 'admin'])) {
            // Delete attachments physically
            $attachments = \App\Models\MessageAttachment::all();
            foreach ($attachments as $att) {
                $path = str_replace('/storage/', 'public/', $att->file_path);
                \Illuminate\Support\Facades\Storage::delete($path);
            }
            // Delete records
            Message::whereNull('conversation_id')->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
}