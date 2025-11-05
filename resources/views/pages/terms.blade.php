@extends('layouts.app')

@section('title', 'Terms of Service - GoRentUMS')

@push('styles')
<style>
    .terms-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .terms-header {
        text-align: center;
        margin-bottom: 40px;
        padding-bottom: 30px;
        border-bottom: 3px solid #4461F2;
    }

    .terms-title {
        font-size: 36px;
        font-weight: 700;
        color: #1E3A5F;
        margin-bottom: 10px;
    }

    .terms-subtitle {
        font-size: 14px;
        color: #6B7280;
    }

    .terms-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #1E3A5F;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #E5E7EB;
    }

    .section-subtitle {
        font-size: 18px;
        font-weight: 600;
        color: #374151;
        margin: 25px 0 15px 0;
    }

    .terms-content {
        font-size: 15px;
        line-height: 1.8;
        color: #4B5563;
        margin-bottom: 15px;
    }

    .terms-list {
        margin: 15px 0;
        padding-left: 30px;
    }

    .terms-list li {
        margin-bottom: 12px;
        line-height: 1.7;
        color: #4B5563;
    }

    .highlight-box {
        background: #F3F4F6;
        border-left: 4px solid #4461F2;
        padding: 20px;
        margin: 20px 0;
        border-radius: 6px;
    }

    .highlight-box strong {
        color: #1E3A5F;
        display: block;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .warning-box {
        background: #FEF3C7;
        border-left: 4px solid #F59E0B;
        padding: 20px;
        margin: 20px 0;
        border-radius: 6px;
    }

    .warning-box strong {
        color: #92400E;
        display: block;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .success-box {
        background: #D1FAE5;
        border-left: 4px solid #10B981;
        padding: 20px;
        margin: 20px 0;
        border-radius: 6px;
    }

    .success-box strong {
        color: #065F46;
        display: block;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .contact-section {
        background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        margin-top: 50px;
    }

    .contact-section h3 {
        font-size: 24px;
        margin-bottom: 15px;
    }

    .contact-section p {
        font-size: 16px;
        margin-bottom: 10px;
        opacity: 0.95;
    }

    .back-button {
        display: inline-block;
        padding: 12px 30px;
        background: #4461F2;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        margin-bottom: 30px;
    }

    .back-button:hover {
        background: #3651E2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
    }

    @media (max-width: 768px) {
        .terms-title {
            font-size: 28px;
        }

        .section-title {
            font-size: 20px;
        }

        .section-subtitle {
            font-size: 16px;
        }

        .terms-content {
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="terms-container">
    <a href="{{ url()->previous() }}" class="back-button">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <div class="terms-header">
        <h1 class="terms-title">Terms of Service</h1>
        <p class="terms-subtitle">Last Updated: {{ date('F d, Y') }}</p>
    </div>

    <!-- Introduction -->
    <div class="terms-section">
        <h2 class="section-title">1. Introduction</h2>
        <p class="terms-content">
            Welcome to GoRentUMS, a peer-to-peer rental marketplace platform. By accessing or using our platform, you agree to be bound by these Terms of Service. Please read them carefully before using our services.
        </p>
        <p class="terms-content">
            GoRentUMS operates as a facilitator connecting renters (users seeking to rent items) and owners (users listing items for rent). We provide the platform but are not a party to the rental agreements between users.
        </p>
    </div>

    <!-- User Accounts -->
    <div class="terms-section">
        <h2 class="section-title">2. User Accounts</h2>
        <h3 class="section-subtitle">2.1 Account Registration</h3>
        <p class="terms-content">
            To use our platform, you must create an account by providing accurate and complete information. You are responsible for:
        </p>
        <ul class="terms-list">
            <li>Maintaining the confidentiality of your account credentials</li>
            <li>All activities that occur under your account</li>
            <li>Notifying us immediately of any unauthorized use</li>
            <li>Ensuring your contact information remains current</li>
        </ul>

        <h3 class="section-subtitle">2.2 Account Eligibility</h3>
        <p class="terms-content">
            You must be at least 18 years old or the age of majority in your jurisdiction to use GoRentUMS. By creating an account, you represent that you meet these requirements.
        </p>
    </div>

    <!-- Booking & Rental Process -->
    <div class="terms-section">
        <h2 class="section-title">3. Booking & Rental Process</h2>

        <h3 class="section-subtitle">3.1 Booking Procedure</h3>
        <p class="terms-content">
            When you book an item on GoRentUMS:
        </p>
        <ul class="terms-list">
            <li>You select available dates for the rental period</li>
            <li>The system calculates the rental duration and total costs</li>
            <li>You submit a booking request to the item owner</li>
            <li>The booking is created with "pending" status awaiting owner approval</li>
        </ul>

        <div class="highlight-box">
            <strong>Booking Status Flow:</strong>
            <p style="margin: 10px 0; color: #4B5563;">
                Pending → Confirmed → Ongoing → Completed
            </p>
            <p style="margin: 0; font-size: 14px; color: #6B7280;">
                Or alternatively: Pending → Rejected/Cancelled
            </p>
        </div>

        <h3 class="section-subtitle">3.2 Owner Approval</h3>
        <p class="terms-content">
            Item owners have the right to approve or reject booking requests. Once approved, the booking status changes to "confirmed" and the rental period becomes reserved.
        </p>

        <h3 class="section-subtitle">3.3 Rental Period</h3>
        <ul class="terms-list">
            <li>The minimum rental period is 1 day</li>
            <li>Rental dates are calculated from the Start Date to End Date selected during booking</li>
            <li>Items must be returned by the End Date specified in the booking</li>
            <li>Late returns may result in penalties as determined by the item owner</li>
        </ul>

        <h3 class="section-subtitle">3.4 Item Availability</h3>
        <p class="terms-content">
            Items are automatically marked as unavailable during confirmed booking periods. The system prevents double-booking to ensure rental integrity.
        </p>
    </div>

    <!-- Payment Terms -->
    <div class="terms-section">
        <h2 class="section-title">4. Payment Terms</h2>

        <h3 class="section-subtitle">4.1 Payment Structure</h3>
        <p class="terms-content">
            GoRentUMS uses a split payment system designed to protect both renters and owners:
        </p>

        <div class="success-box">
            <strong>Payment Breakdown:</strong>
            <ul style="margin: 10px 0 0 20px; color: #065F46;">
                <li><strong>Security Deposit:</strong> Paid online through our platform via ToyyibPay</li>
                <li><strong>Platform Service Fee:</strong> RM 1.00 (included with deposit payment)</li>
                <li><strong>Rental Fee:</strong> Paid directly to the item owner (not through platform)</li>
            </ul>
        </div>

        <h3 class="section-subtitle">4.2 Security Deposit</h3>
        <p class="terms-content">
            The security deposit serves as protection for item owners against damage, loss, or breach of rental terms:
        </p>
        <ul class="terms-list">
            <li>Deposit amount is set by the item owner for each listing</li>
            <li>Must be paid online through ToyyibPay before booking confirmation</li>
            <li>Held by the platform throughout the rental period</li>
            <li>Automatically refunded after successful rental completion</li>
            <li>Refund processing time: 3-5 business days</li>
        </ul>

        <div class="warning-box">
            <strong>Important:</strong>
            <p style="margin: 0; color: #92400E;">
                The security deposit is NOT the rental fee. It is a refundable deposit that will be returned to you when the item is returned in good condition. You must arrange payment of the rental fee directly with the item owner.
            </p>
        </div>

        <h3 class="section-subtitle">4.3 Service Fee</h3>
        <p class="terms-content">
            GoRentUMS charges a flat service fee of RM 1.00 per booking to maintain and improve the platform. This fee is:
        </p>
        <ul class="terms-list">
            <li>Non-refundable once the booking is created</li>
            <li>Charged together with the security deposit payment</li>
            <li>Used to cover platform maintenance, payment processing, and operational costs</li>
        </ul>

        <h3 class="section-subtitle">4.4 Rental Fee Payment</h3>
        <p class="terms-content">
            <strong>The rental fee must be arranged and paid directly between the renter and item owner.</strong> This includes:
        </p>
        <ul class="terms-list">
            <li>Negotiating payment method (cash, bank transfer, etc.)</li>
            <li>Agreeing on payment timing (before, during, or after rental)</li>
            <li>Obtaining receipts or payment confirmation</li>
        </ul>
        <p class="terms-content">
            GoRentUMS is not responsible for disputes regarding rental fee payments made outside the platform.
        </p>

        <h3 class="section-subtitle">4.5 Payment Processing</h3>
        <p class="terms-content">
            Online payments (deposit + service fee) are processed through ToyyibPay, our secure payment gateway partner. We support:
        </p>
        <ul class="terms-list">
            <li>FPX (Online Banking)</li>
            <li>All major Malaysian banks</li>
            <li>Real-time payment confirmation</li>
        </ul>
    </div>

    <!-- Deposit Refund Policy -->
    <div class="terms-section">
        <h2 class="section-title">5. Deposit Refund Policy</h2>

        <h3 class="section-subtitle">5.1 Automatic Refunds</h3>
        <p class="terms-content">
            Security deposits are automatically refunded when:
        </p>
        <ul class="terms-list">
            <li>The booking is marked as "completed" by the item owner</li>
            <li>The rental period ends and the item is returned in good condition</li>
            <li>The system auto-completes bookings after the end date (if not manually completed)</li>
            <li>A booking request is rejected by the owner</li>
            <li>A booking is cancelled before confirmation</li>
        </ul>

        <h3 class="section-subtitle">5.2 Refund Timeline</h3>
        <div class="highlight-box">
            <strong>Refund Processing:</strong>
            <p style="margin: 10px 0 0 0; color: #4B5563;">
                Deposits are marked as "refunded" immediately upon booking completion. The actual funds will be credited to your account within 3-5 business days, depending on your bank's processing time.
            </p>
        </div>

        <h3 class="section-subtitle">5.3 Deposit Deductions</h3>
        <p class="terms-content">
            Item owners may request deposit deductions in cases of:
        </p>
        <ul class="terms-list">
            <li>Damage to the rented item</li>
            <li>Loss or theft of the item</li>
            <li>Late return penalties</li>
            <li>Cleaning fees (if applicable)</li>
            <li>Breach of rental agreement terms</li>
        </ul>
        <p class="terms-content">
            Any deposit deduction disputes will be reviewed by GoRentUMS support team with evidence from both parties.
        </p>

        <h3 class="section-subtitle">5.4 Refund Methods</h3>
        <p class="terms-content">
            All refunds are processed back to the original payment method used for the deposit payment.
        </p>
    </div>

    <!-- Cancellation Policy -->
    <div class="terms-section">
        <h2 class="section-title">6. Cancellation Policy</h2>

        <h3 class="section-subtitle">6.1 Renter Cancellation</h3>
        <p class="terms-content">
            Renters can cancel bookings under the following conditions:
        </p>
        <ul class="terms-list">
            <li><strong>Before Confirmation:</strong> Full refund of deposit (service fee non-refundable)</li>
            <li><strong>After Confirmation:</strong> Subject to owner's cancellation policy and timeline</li>
            <li><strong>During Rental:</strong> Early termination does not entitle partial refunds unless agreed with owner</li>
        </ul>

        <h3 class="section-subtitle">6.2 Owner Cancellation</h3>
        <p class="terms-content">
            If an owner needs to cancel a confirmed booking:
        </p>
        <ul class="terms-list">
            <li>The renter receives a full refund of deposit and service fee</li>
            <li>The owner may face penalties or account restrictions for repeated cancellations</li>
            <li>The renter is notified immediately via platform notification</li>
        </ul>

        <h3 class="section-subtitle">6.3 System Cancellations</h3>
        <p class="terms-content">
            GoRentUMS may cancel bookings in cases of:
        </p>
        <ul class="terms-list">
            <li>Fraudulent activity detected</li>
            <li>Violation of terms of service</li>
            <li>Payment disputes or chargebacks</li>
            <li>Account suspension or termination</li>
        </ul>
    </div>

    <!-- User Responsibilities -->
    <div class="terms-section">
        <h2 class="section-title">7. User Responsibilities</h2>

        <h3 class="section-subtitle">7.1 Renter Responsibilities</h3>
        <ul class="terms-list">
            <li>Use rented items responsibly and according to their intended purpose</li>
            <li>Return items on time and in the same condition as received</li>
            <li>Pay the rental fee directly to the owner as agreed</li>
            <li>Report any damage or issues immediately</li>
            <li>Not sublet or transfer rental rights to third parties</li>
            <li>Maintain communication with the item owner</li>
        </ul>

        <h3 class="section-subtitle">7.2 Owner Responsibilities</h3>
        <ul class="terms-list">
            <li>Provide accurate descriptions and photos of items</li>
            <li>Ensure items are clean, safe, and in working condition</li>
            <li>Respond to booking requests promptly</li>
            <li>Honor confirmed bookings unless exceptional circumstances arise</li>
            <li>Set fair pricing and deposit amounts</li>
            <li>Complete bookings promptly after item return to facilitate deposit refunds</li>
        </ul>
    </div>

    <!-- Prohibited Activities -->
    <div class="terms-section">
        <h2 class="section-title">8. Prohibited Activities</h2>
        <p class="terms-content">
            Users are strictly prohibited from:
        </p>
        <ul class="terms-list">
            <li>Listing illegal items or items that violate Malaysian law</li>
            <li>Fraudulent bookings or payment activities</li>
            <li>Attempting to circumvent platform fees</li>
            <li>Harassing or threatening other users</li>
            <li>Providing false information or impersonating others</li>
            <li>Manipulating reviews or ratings</li>
            <li>Using the platform for money laundering or illegal activities</li>
        </ul>

        <div class="warning-box">
            <strong>Violation Consequences:</strong>
            <p style="margin: 0; color: #92400E;">
                Violations of these terms may result in immediate account suspension, permanent ban, forfeiture of deposits, and potential legal action.
            </p>
        </div>
    </div>

    <!-- Liability & Disputes -->
    <div class="terms-section">
        <h2 class="section-title">9. Liability & Disputes</h2>

        <h3 class="section-subtitle">9.1 Platform Liability</h3>
        <p class="terms-content">
            GoRentUMS acts solely as a facilitator and is not liable for:
        </p>
        <ul class="terms-list">
            <li>Quality, safety, or legality of listed items</li>
            <li>Ability of users to complete transactions</li>
            <li>Disputes between renters and owners regarding rental fees</li>
            <li>Loss, damage, or injury resulting from item use</li>
            <li>Payment disputes conducted outside the platform</li>
        </ul>

        <h3 class="section-subtitle">9.2 Dispute Resolution</h3>
        <p class="terms-content">
            In case of disputes:
        </p>
        <ul class="terms-list">
            <li>Users should first attempt to resolve directly with each other</li>
            <li>Report issues through our platform's reporting system</li>
            <li>Provide evidence (photos, messages, receipts) for investigation</li>
            <li>GoRentUMS support will mediate and make final decisions on deposit disputes</li>
        </ul>

        <h3 class="section-subtitle">9.3 Insurance</h3>
        <p class="terms-content">
            Users are encouraged to obtain appropriate insurance for high-value items. GoRentUMS does not provide insurance coverage for rented items.
        </p>
    </div>

    <!-- Account Termination -->
    <div class="terms-section">
        <h2 class="section-title">10. Account Termination</h2>
        <p class="terms-content">
            GoRentUMS reserves the right to suspend or terminate accounts that:
        </p>
        <ul class="terms-list">
            <li>Violate these Terms of Service</li>
            <li>Engage in fraudulent activities</li>
            <li>Receive multiple user complaints</li>
            <li>Remain inactive for extended periods</li>
            <li>Are involved in legal disputes affecting platform integrity</li>
        </ul>
        <p class="terms-content">
            Users may also request account deletion at any time, subject to completion of active bookings.
        </p>
    </div>

    <!-- Changes to Terms -->
    <div class="terms-section">
        <h2 class="section-title">11. Changes to Terms</h2>
        <p class="terms-content">
            GoRentUMS may update these Terms of Service from time to time. We will notify users of material changes via:
        </p>
        <ul class="terms-list">
            <li>Platform notifications</li>
            <li>Email announcements</li>
            <li>Prominent notices on the website</li>
        </ul>
        <p class="terms-content">
            Continued use of the platform after changes constitutes acceptance of the updated terms.
        </p>
    </div>

    <!-- Governing Law -->
    <div class="terms-section">
        <h2 class="section-title">12. Governing Law</h2>
        <p class="terms-content">
            These Terms of Service are governed by the laws of Malaysia. Any disputes arising from these terms or platform use shall be subject to the exclusive jurisdiction of Malaysian courts.
        </p>
    </div>

    <!-- Contact Information -->
    <div class="contact-section">
        <h3>Need Help or Have Questions?</h3>
        <p><i class="fa-solid fa-envelope"></i> Email: support@gorentums.com</p>
        <p><i class="fa-solid fa-phone"></i> Phone: +60 12-345 6789</p>
        <p style="margin-top: 20px; font-size: 14px;">
            Our support team is available Monday to Friday, 9:00 AM - 6:00 PM (MYT)
        </p>
    </div>
</div>
@endsection
