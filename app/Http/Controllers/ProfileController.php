<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // ========== ADD THESE NEW METHODS BELOW ==========

    /**
     * Display the user's profile page (different from edit)
     */
    public function userProfile(): View
    {
        $user = auth()->user();
        
        // Get user statistics
        $totalListings = \App\Models\Item::where('UserID', $user->UserID)->count();
        $totalBookings = \App\Models\Booking::where('UserID', $user->UserID)->count();
        $totalReviews = \App\Models\Review::where('UserID', $user->UserID)->count();
        
        return view('user.profile', compact('user', 'totalListings', 'totalBookings', 'totalReviews'));
    }

    /**
     * Update the user's profile (for user profile page)
     */
    public function userUpdateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'UserName' => 'required|string|max:255',
            'Email' => 'required|email|unique:users,Email,' . $user->UserID . ',UserID',
            'PhoneNumber' => 'nullable|string|max:20',
            'ProfileImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle profile image upload
        if ($request->hasFile('ProfileImage')) {
            // Delete old image if exists
            if ($user->ProfileImage) {
                Storage::disk('public')->delete($user->ProfileImage);
            }
            
            $validated['ProfileImage'] = $request->file('ProfileImage')->store('profile_images', 'public');
        }

        $user->update($validated);

        return Redirect::route('user.profile')->with('success', 'Profile updated successfully');
    }

    /**
 * Submit a report against another user
 */
public function submitReport(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'ReportedUserID' => 'required|exists:users,UserID',
        'BookingID' => 'nullable|exists:booking,BookingID',
        'ItemID' => 'nullable|exists:items,ItemID',
        'Description' => 'required|string|max:1000',
        'EvidencePath' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // Handle evidence upload
    if ($request->hasFile('EvidencePath')) {
        $validated['EvidencePath'] = $request->file('EvidencePath')->store('evidence', 'public');
    }

    \App\Models\Penalty::create([
        'ReportedByID' => auth()->id(),
        'ReportedUserID' => $validated['ReportedUserID'],
        'BookingID' => $validated['BookingID'] ?? null,
        'ItemID' => $validated['ItemID'] ?? null,
        'Description' => $validated['Description'],
        'EvidencePath' => $validated['EvidencePath'] ?? null,
        'PenaltyAmount' => null,
        'ResolvedStatus' => 0,
        'DateReported' => now(),
        'ApprovedByAdminID' => null
    ]);

    return Redirect::route('user.profile')->with('success', 'Report submitted successfully. Admin will review it soon.');
}


    /**
     * Update user password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->Password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->update([
            'Password' => Hash::make($validated['new_password'])
        ]);

        return back()->with('success', 'Password updated successfully');
    }
}