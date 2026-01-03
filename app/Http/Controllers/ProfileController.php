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
            'Location' => 'nullable|string|max:255',
            'BankName' => 'nullable|string|max:100',
            'BankAccountNumber' => 'nullable|string|max:50',
            'BankAccountHolderName' => 'nullable|string|max:100',
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
     * Update bank account details
     */
    public function updateBankDetails(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'BankName' => 'required|string|max:100',
            'BankAccountNumber' => 'required|string|max:50',
            'BankAccountHolderName' => 'required|string|max:100',
        ]);

        $user->update($validated);

        return Redirect::route('user.profile')->with('success', 'Bank account details updated successfully');
    }

    /**
     * Submit a report against another user
     */
    /**
     * Show report form
     */
    public function showReportForm(): View
    {
        $currentUserId = auth()->id();

        // Get users that the current user has interacted with
        // 1. Users whose items the current user has booked (item owners)
        $itemOwnerIds = \App\Models\Booking::where('booking.UserID', $currentUserId)
            ->join('items', 'booking.ItemID', '=', 'items.ItemID')
            ->pluck('items.UserID')
            ->unique();

        // 2. Users who have booked the current user's items (renters)
        $renterIds = \App\Models\Booking::join('items', 'booking.ItemID', '=', 'items.ItemID')
            ->where('items.UserID', $currentUserId)
            ->pluck('booking.UserID')
            ->unique();

        // Combine both lists and remove duplicates
        $relatedUserIds = $itemOwnerIds->merge($renterIds)->unique()->values();

        // Get user details for these related users
        $users = \App\Models\User::whereIn('UserID', $relatedUserIds)
            ->where('UserID', '!=', $currentUserId)
            ->where('IsAdmin', 0)
            ->select('UserID', 'UserName', 'Email')
            ->orderBy('UserName')
            ->get();

        // Get all bookings (both as renter and as item owner)
        // Bookings where current user is the renter
        $myBookings = \App\Models\Booking::where('booking.UserID', $currentUserId)
            ->with(['item', 'item.user'])
            ->get();

        // Bookings where current user is the item owner
        $bookingsOnMyItems = \App\Models\Booking::join('items', 'booking.ItemID', '=', 'items.ItemID')
            ->where('items.UserID', $currentUserId)
            ->with(['item', 'user'])
            ->select('booking.*')
            ->get();

        // Combine both booking types
        $bookings = $myBookings->merge($bookingsOnMyItems)
            ->sortByDesc('BookingDate')
            ->unique('BookingID')
            ->values();

        return view('user.report', compact('users', 'bookings'));
    }

    /**
     * Submit a report
     */
    public function submitReport(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ReportType' => 'required|in:item-damage,late-return,dispute,fraud,harassment,other',
            'Subject' => 'required|string|max:255',
            'ReportedUserID' => 'nullable|exists:users,UserID',
            'BookingID' => 'nullable|exists:booking,BookingID',
            'Description' => 'required|string|min:20|max:2000',
            'EvidencePath' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        // Handle evidence upload
        $evidencePath = null;
        if ($request->hasFile('EvidencePath')) {
            $evidencePath = $request->file('EvidencePath')->store('evidence', 'public');
        }

        // Get ItemID from booking if provided
        $itemId = null;
        if (isset($validated['BookingID'])) {
            $booking = \App\Models\Booking::find($validated['BookingID']);
            $itemId = $booking->ItemID ?? null;
        }

        $report = \App\Models\Report::create([
            'ReportedByID' => auth()->id(),
            'ReportedUserID' => $validated['ReportedUserID'] ?? null,
            'BookingID' => $validated['BookingID'] ?? null,
            'ItemID' => $itemId,
            'ReportType' => $validated['ReportType'],
            'Priority' => 'medium',
            'Subject' => $validated['Subject'],
            'Description' => $validated['Description'],
            'EvidencePath' => $evidencePath,
            'Status' => 'pending',
            'DateReported' => now(),
        ]);

        // Create notification for the user who submitted the report
        \App\Models\Notification::create([
            'UserID' => auth()->id(),
            'Type' => 'report_submitted',
            'Title' => 'Report Submitted',
            'Content' => 'Your report "' . $validated['Subject'] . '" has been submitted successfully and is pending admin review.',
            'RelatedID' => $report->ReportID,
            'RelatedType' => 'report',
            'IsRead' => false,
            'CreatedAt' => now(),
        ]);

        return Redirect::route('user.report')->with('success', 'Report submitted successfully. Admin will review it soon.');
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