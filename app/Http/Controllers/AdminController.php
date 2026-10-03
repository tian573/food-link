<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $donations = Donation::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $users = User::withCount('donations')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.dashboard', compact('donations', 'users'));
    }
    
    public function updateDonationStatus(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,picked_up,completed,cancelled'
        ]);
        
        $donation->status = $validated['status'];
        $donation->save();
        
        $statusMessages = [
            'approved' => 'Donasi berhasil diapprove!',
            'picked_up' => 'Donasi berhasil ditandai sudah diambil!',
            'completed' => 'Donasi berhasil dikonfirmasi selesai!',
            'cancelled' => 'Donasi berhasil dibatalkan.'
        ];
        
        return redirect()->route('admin.dashboard')
            ->with('success', $statusMessages[$validated['status']] ?? 'Status donasi berhasil diperbarui!');
    }
    
    public function deleteDonation($id)
    {
        $donation = Donation::findOrFail($id);
        
        if ($donation->food_photo && Storage::disk('public')->exists($donation->food_photo)) {
            Storage::disk('public')->delete($donation->food_photo);
        }
        
        $donation->delete();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Donasi berhasil dihapus!');
    }
    
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->usertype === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Tidak dapat menghapus admin!');
        }
        
        $donations = Donation::where('user_id', $id)->get();
        foreach ($donations as $donation) {
            if ($donation->food_photo && Storage::disk('public')->exists($donation->food_photo)) {
                Storage::disk('public')->delete($donation->food_photo);
            }
        }
        
        $user->delete();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'User dan semua donasinya berhasil dihapus!');
    }
}