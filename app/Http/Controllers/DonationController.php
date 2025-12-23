<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    // Show upload photo page
    public function showUpload()
    {
        return view('donations.upload');
    }
    
    // Handle photo upload and AI analysis
    public function analyzePhoto(Request $request)
    {
        $request->validate([
            'food_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'food_name' => 'required|string|max:255',
            'estimated_weight' => 'required|numeric|min:0.1',
        ]);
        
        // Save photo temporarily
        $photo = $request->file('food_photo');
        $photoPath = $photo->store('temp_donations', 'public');
        $fullPath = storage_path('app/public/' . $photoPath);
        
        try {
            // Call Flask API for AI analysis
            $response = Http::timeout(30)
                ->attach('file', file_get_contents($fullPath), $photo->getClientOriginalName())
                ->post('http://localhost:5000/analyze', [
                    'foodName' => $request->food_name,
                    'foodWeight' => $request->estimated_weight . ' kg',
                ]);
            
            if ($response->successful() && !isset($response->json()['error'])) {
                $aiResult = $response->json();
                
                // 🔍 DEBUG: Log the AI response to see what we're getting
                Log::info('AI Analysis Response:', $aiResult);
                
                // Store AI result in session
                session([
                    'ai_analysis' => $aiResult,
                    'temp_photo_path' => $photoPath,
                    'food_name' => $request->food_name,
                    'estimated_weight' => $request->estimated_weight
                ]);
                
                return redirect()->route('donation.form')
                    ->with('success', 'Analisis AI berhasil! Silakan lengkapi form donasi.');
            } else {
                $errorMsg = $response->json()['error'] ?? 'AI analysis failed';
                Log::error('AI Analysis Failed:', ['error' => $errorMsg, 'response' => $response->body()]);
                
                // If AI fails, still allow manual input
                session([
                    'temp_photo_path' => $photoPath,
                    'food_name' => $request->food_name,
                    'estimated_weight' => $request->estimated_weight,
                    'ai_error' => $errorMsg
                ]);
                
                return redirect()->route('donation.form')
                    ->with('warning', 'AI tidak dapat menganalisis: ' . $errorMsg);
            }
        } catch (\Exception $e) {
            Log::error('Flask API Exception:', ['message' => $e->getMessage()]);
            
            // If Flask server is not running
            session([
                'temp_photo_path' => $photoPath,
                'food_name' => $request->food_name,
                'estimated_weight' => $request->estimated_weight,
                'ai_error' => 'Flask server tidak tersedia: ' . $e->getMessage()
            ]);
            
            return redirect()->route('donation.form')
                ->with('warning', 'Server AI tidak tersedia. Silakan isi manual.');
        }
    }
    
    // Show donation form
    public function showForm(Request $request)
    {
        $aiAnalysis = session('ai_analysis');
        $tempPhotoPath = session('temp_photo_path');
        $foodName = session('food_name');
        $estimatedWeight = session('estimated_weight');
        $selectedFoodbank = $request->query('foodbank');
        
        // 🔍 DEBUG: Log what's in the session
        Log::info('Form Data:', [
            'aiAnalysis' => $aiAnalysis,
            'foodName' => $foodName,
            'estimatedWeight' => $estimatedWeight
        ]);
        
        return view('donations.form', compact(
            'aiAnalysis',
            'tempPhotoPath',
            'foodName',
            'estimatedWeight',
            'selectedFoodbank'
        ));
    }
    
    // Store donation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'food_name' => 'required|string|max:255',
            'food_photo' => 'nullable|string',
            'estimated_weight' => 'required|numeric|min:0.1',
            'nutritional_value' => 'nullable|string',
            'expiry_date' => 'required|date|after:today',
            'predicted_expiry' => 'nullable|string',
            'condition' => 'required|in:Fresh,Busuk',
            'food_type' => 'required|in:Upload Foto,Analisis AI',
            'address' => 'required|string',
            'city' => 'required|string',
            'selected_foodbank' => 'required|string',
            'foodbank_distance' => 'nullable|numeric',
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'required',
            'contact_number' => 'required|string',
        ]);
        
        // Move temp photo to permanent location
        if ($request->food_photo && Storage::disk('public')->exists($request->food_photo)) {
            $newPath = str_replace('temp_donations/', 'donations/', $request->food_photo);
            Storage::disk('public')->move($request->food_photo, $newPath);
            $validated['food_photo'] = $newPath;
        }
        
        // Add user_id and AI analysis
        $validated['user_id'] = Auth::id();
        $validated['ai_analysis'] = session('ai_analysis') ? json_encode(session('ai_analysis')) : null;
        
        // Create donation
        Donation::create($validated);
        
        // Clear session
        session()->forget(['ai_analysis', 'temp_photo_path', 'food_name', 'estimated_weight']);
        
        return redirect()->route('donation.success')
            ->with('success', 'Donasi berhasil didaftarkan!');
    }
    
    // Show success page
    public function success()
    {
        return view('donations.success');
    }
    
    // Show user's donations
    public function myDonations()
    {
        $donations = Donation::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('donations.my-donations', compact('donations'));
    }
    
    // Update donation status
    public function updateStatus(Request $request, $id)
    {
        // Find donation and ensure it belongs to current user
        $donation = Donation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Validate the new status
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,picked_up,completed,cancelled'
        ]);
        
        $newStatus = $validated['status'];
        $oldStatus = $donation->status;
        
        // Define allowed status transitions to maintain data integrity
        $allowedTransitions = [
            'pending' => ['approved', 'picked_up', 'cancelled'],
            'approved' => ['picked_up', 'cancelled'],
            'picked_up' => ['completed'],
        ];
        
        // Check if the transition is allowed
        if (isset($allowedTransitions[$oldStatus]) && !in_array($newStatus, $allowedTransitions[$oldStatus])) {
            return redirect()->back()
                ->with('error', 'Status tidak dapat diubah dari ' . $oldStatus . ' ke ' . $newStatus);
        }
        
        // Prevent changing status if already completed or cancelled
        if (in_array($oldStatus, ['completed', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'Donasi yang sudah ' . $oldStatus . ' tidak dapat diubah.');
        }
        
        // Update the status
        $donation->status = $newStatus;
        $donation->save();
        
        // Prepare success messages
        $statusMessages = [
            'picked_up' => 'Donasi berhasil ditandai sudah diambil!',
            'completed' => 'Donasi berhasil dikonfirmasi selesai!',
            'cancelled' => 'Donasi berhasil dibatalkan.',
            'approved' => 'Donasi berhasil disetujui!'
        ];
        
        return redirect()->route('donation.myDonations')
            ->with('success', $statusMessages[$newStatus] ?? 'Status donasi berhasil diperbarui!');
    }
}