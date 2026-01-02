# Automatic Refund Queue Feature

## Overview
When a booking is cancelled by the renter or rejected by the owner, the system automatically creates a refund queue entry for processing the deposit refund. This ensures all refunds are tracked and processed systematically by administrators.

## How It Works

### 1. Booking Cancellation by Renter
**Trigger:** When a renter cancels their booking via [BookingController::cancel()](app/Http/Controllers/BookingController.php#L351)

**Process:**
1. Booking status is updated to `'cancelled'`
2. Deposit status is updated to `'pending_refund'`
3. System automatically creates a `RefundQueue` entry with:
   - Booking details
   - Deposit amount
   - User's bank account information
   - Status: `'pending'`
   - Notes: "Auto-added: Booking cancelled by renter"
4. Notification sent to item owner
5. Success message to renter: "Your deposit refund request has been added to the queue"

**Code Location:** [BookingController.php:366-415](app/Http/Controllers/BookingController.php#L366-L415)

### 2. Booking Rejection by Owner
**Trigger:** When an owner rejects a pending booking via [BookingController::reject()](app/Http/Controllers/BookingController.php#L634)

**Process:**
1. Booking status is updated to `'rejected'`
2. Deposit status is updated to `'pending_refund'`
3. System automatically creates a `RefundQueue` entry with:
   - Booking details
   - Deposit amount
   - Renter's bank account information
   - Status: `'pending'`
   - Notes: "Auto-added: Booking rejected by owner"
4. Notification sent to renter
5. Success message to owner: "The renter refund request has been added to the queue"

**Code Location:** [BookingController.php:648-705](app/Http/Controllers/BookingController.php#L648-L705)

## Database Structure

### RefundQueue Table Fields
```php
- RefundQueueID (Primary Key)
- DepositID (Foreign Key - nullable)
- BookingID (Foreign Key)
- UserID (Foreign Key - the renter receiving refund)
- RefundAmount (Decimal)
- Status (pending/processing/completed/failed)
- BankName
- BankAccountNumber
- BankAccountHolderName
- RefundReference (nullable)
- Notes
- ProcessedAt (DateTime - nullable)
- ProcessedBy (Foreign Key - admin who processed)
- ProofOfTransfer (File path - nullable)
- created_at
- updated_at
```

## Key Features

### 1. Duplicate Prevention
The system checks if a refund queue entry already exists for a booking before creating a new one:

```php
$existingRefund = RefundQueue::where('BookingID', $booking->BookingID)->first();

if (!$existingRefund && $refundAmount > 0) {
    // Create refund queue entry
}
```

### 2. Zero Amount Protection
Refund queue entries are only created if the refund amount is greater than zero.

### 3. Automatic Bank Details
The system automatically populates the refund queue with the user's saved bank account information:
- BankName
- BankAccountNumber
- BankAccountHolderName

### 4. Deposit Status Tracking
When a refund is queued, the deposit status changes to `'pending_refund'` instead of immediately marking it as `'refunded'`. This allows better tracking until the admin actually processes the refund.

## Models & Relationships

### Booking Model
New relationship added in [Booking.php:106-109](app/Models/Booking.php#L106-L109):

```php
public function refundQueue()
{
    return $this->hasOne(RefundQueue::class, 'BookingID', 'BookingID');
}
```

**Usage:**
```php
$booking = Booking::with('refundQueue')->find($id);
if ($booking->refundQueue) {
    echo "Refund Status: " . $booking->refundQueue->Status;
}
```

### RefundQueue Model
Located at [RefundQueue.php](app/Models/RefundQueue.php)

**Relationships:**
- `deposit()` - BelongsTo Deposit
- `booking()` - BelongsTo Booking
- `user()` - BelongsTo User (renter)
- `processor()` - BelongsTo User (admin who processed)

## Admin Processing Workflow

1. Admin navigates to Refund Queue management
2. Views all pending refund requests
3. For each refund:
   - Verifies booking cancellation/rejection
   - Confirms bank account details
   - Processes refund via bank transfer
   - Uploads proof of transfer
   - Updates status to 'completed'
   - System records ProcessedAt timestamp and ProcessedBy admin ID

4. Deposit status is updated to 'refunded' once processed
5. User receives notification of completed refund

## User Experience

### For Renters (Cancelling Booking)
**Before:**
- "Booking cancelled successfully. Your deposit will be refunded."

**After:**
- "Booking cancelled successfully. Your deposit refund request has been added to the queue."

### For Renters (Booking Rejected)
**Before:**
- "Your deposit will be refunded within 3-5 business days."

**After:**
- "Your deposit refund request has been added to the queue."

### For Owners (Rejecting Booking)
**Before:**
- "Booking rejected. The renter will be refunded."

**After:**
- "Booking rejected. The renter refund request has been added to the queue."

## Benefits

### 1. Better Tracking
- All refunds go through a centralized queue
- Audit trail of all refund requests
- Clear status for each refund

### 2. Accountability
- Records who processed each refund
- Timestamp of processing
- Proof of transfer storage

### 3. Transparency
- Users can see their refund request status
- No ambiguity about refund processing
- Clear communication about refund timeline

### 4. Financial Control
- Admin has full control over refund processing
- Prevents automatic/immediate refunds
- Better cash flow management

## Testing

### Manual Testing Steps

1. **Test Renter Cancellation:**
   ```
   - Login as renter
   - Create a booking
   - Cancel the booking
   - Check refund_queue table for new entry
   - Verify deposit status is 'pending_refund'
   - Verify notification sent to owner
   ```

2. **Test Owner Rejection:**
   ```
   - Login as owner
   - Have a renter create a booking
   - Reject the booking
   - Check refund_queue table for new entry
   - Verify deposit status is 'pending_refund'
   - Verify notification sent to renter
   ```

3. **Test Duplicate Prevention:**
   ```
   - Cancel a booking twice (shouldn't create duplicate refund entries)
   - Verify only one refund_queue entry exists
   ```

4. **Test Bank Details:**
   ```
   - Ensure user has bank details saved
   - Cancel booking
   - Verify refund_queue entry has correct bank details
   ```

### Database Verification Queries

```sql
-- Check refund queue entries
SELECT * FROM refund_queue WHERE Status = 'pending';

-- Check booking with refund queue
SELECT b.BookingID, b.Status, d.Status as DepositStatus, rq.Status as RefundStatus
FROM booking b
LEFT JOIN deposit d ON b.BookingID = d.BookingID
LEFT JOIN refund_queue rq ON b.BookingID = rq.BookingID
WHERE b.Status IN ('cancelled', 'rejected');

-- Verify no duplicate refund entries
SELECT BookingID, COUNT(*) as count
FROM refund_queue
GROUP BY BookingID
HAVING count > 1;
```

## Future Enhancements

1. **Email Notifications:** Send email when refund is queued and when processed
2. **Refund Timeline:** Show estimated refund processing time
3. **User Dashboard:** Allow users to track refund status
4. **Auto-processing:** Option to auto-process refunds under certain amount
5. **Batch Processing:** Allow admins to process multiple refunds at once
6. **Refund Analytics:** Dashboard showing refund statistics and trends

## Important Notes

⚠️ **Bank Account Requirement:** Users must have their bank account details saved in their profile for automatic refund queue creation to work properly.

⚠️ **Deposit Amount:** The system uses `$booking->DepositAmount` for the refund. Ensure this field is always populated during booking creation.

⚠️ **Transaction Safety:** All refund queue operations are wrapped in database transactions to ensure data integrity.

⚠️ **Status Consistency:** Deposit status should be 'pending_refund' while in queue, and updated to 'refunded' after admin processing.
