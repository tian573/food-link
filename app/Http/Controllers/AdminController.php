<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Show admin dashboard
    public function dashboard()
    {
        // Get all donations with user relationship
        $donations = Donation::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all users with donations count
        $users = User::withCount('donations')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.dashboard', compact('donations', 'users'));
    }
    
    // Update donation status (admin version)
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
    
    // Delete donation
    public function deleteDonation($id)
    {
        $donation = Donation::findOrFail($id);
        
        // Delete photo if exists
        if ($donation->food_photo && Storage::disk('public')->exists($donation->food_photo)) {
            Storage::disk('public')->delete($donation->food_photo);
        }
        
        $donation->delete();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Donasi berhasil dihapus!');
    }
    
    // Delete user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting admin users
        if ($user->usertype === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Tidak dapat menghapus admin!');
        }
        
        // Delete all user's donation photos
        $donations = Donation::where('user_id', $id)->get();
        foreach ($donations as $donation) {
            if ($donation->food_photo && Storage::disk('public')->exists($donation->food_photo)) {
                Storage::disk('public')->delete($donation->food_photo);
            }
        }
        
        // Delete user (will cascade delete donations if set in migration)
        $user->delete();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'User dan semua donasinya berhasil dihapus!');
    }
}