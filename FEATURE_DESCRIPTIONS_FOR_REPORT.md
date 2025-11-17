# GoRentUMS System - Feature Descriptions for Report Screenshots

**Document Purpose:** This document provides detailed descriptions for every function/feature in the GoRentUMS system. Use these descriptions as captions for screenshots in your project report.

---

## 1. USER AUTHENTICATION & REGISTRATION

### 1.1 User Registration Page
**Screenshot Description:**
"User Registration interface where new users can create an account by providing their username, email address, and password. The system validates email format and password strength, ensuring secure account creation. Upon successful registration, users receive a verification email to activate their account."

### 1.2 Login Page
**Screenshot Description:**
"User Login interface allowing registered users to access the system using their email and password credentials. The interface includes a 'Remember Me' option for convenient access and a 'Forgot Password' link for account recovery. Failed login attempts are tracked for security purposes."

### 1.3 Password Reset Request
**Screenshot Description:**
"Password Reset Request page where users can initiate password recovery by entering their registered email address. The system generates a secure reset token and sends a password reset link via email, valid for 60 minutes."

### 1.4 Password Reset Form
**Screenshot Description:**
"Password Reset Form displaying fields for users to enter their new password after clicking the reset link from their email. The form validates password strength and requires password confirmation to prevent typing errors."

### 1.5 Email Verification Notice
**Screenshot Description:**
"Email Verification Notice page informing users that their account requires email verification before accessing core features. Users can request a new verification link if the original email was not received."

---

## 2. USER PROFILE MANAGEMENT

### 2.1 View User Profile
**Screenshot Description:**
"User Profile Dashboard displaying comprehensive user information including profile picture, username, email address, phone number, location, and member since date. The dashboard shows user statistics including total listings, completed bookings, and average rating received. Users can access profile editing, password change, and account deletion options from this page."

### 2.2 Edit Profile Information
**Screenshot Description:**
"Edit Profile interface allowing users to update their personal information including username, email address, phone number, and location. The form includes validation to ensure data integrity and displays the current profile picture with an option to upload a new image (max 2MB, JPG/PNG/GIF formats)."

### 2.3 Change Password
**Screenshot Description:**
"Change Password form requiring users to enter their current password for verification, followed by the new password and confirmation. This ensures account security by validating user identity before allowing password changes."

### 2.4 Upload Profile Picture
**Screenshot Description:**
"Profile Picture Upload interface with image preview functionality. Users can select an image file from their device, preview it before uploading, and replace or remove existing profile pictures. Supported formats include JPG, PNG, and GIF with a maximum file size of 2MB."

### 2.5 Delete Account Confirmation
**Screenshot Description:**
"Account Deletion confirmation dialog warning users about the permanent nature of account deletion. The system checks for active bookings or pending transactions before allowing deletion to prevent data inconsistencies."

---

## 3. HOMEPAGE & ITEM BROWSING

### 3.1 Homepage - Featured Items
**Screenshot Description:**
"Homepage displaying featured rental items in a grid layout. Each item card shows the item image, name, category, location, price per day, and average rating. Users can search items using the search bar, filter by category and location, and view detailed information by clicking on any item. Pagination controls allow browsing through multiple pages of listings."

### 3.2 Search Functionality
**Screenshot Description:**
"Search Results page showing filtered items based on user search query. The search function looks through item names, descriptions, categories, and locations to provide relevant results. Active search terms are displayed with an option to clear filters."

### 3.3 Category Filter
**Screenshot Description:**
"Category Filtering interface displaying available categories (Electronics, Sports Equipment, Tools, Vehicles, etc.). Selected categories are highlighted, and the item list updates dynamically to show only items from chosen categories. Users can select multiple categories simultaneously."

### 3.4 Location Filter
**Screenshot Description:**
"Location Filtering dropdown showing available rental locations within UMS campus (Main Campus, FKI Campus, etc.). Filtering by location helps users find items available near their location for convenient pickup."

### 3.5 Public Item Browsing (Guest View)
**Screenshot Description:**
"Public browsing interface accessible without login, allowing visitors to explore available rental items. Guest users can view item details, images, prices, and reviews but must register and login to make bookings."

---

## 4. ITEM DETAILS & BOOKING

### 4.1 Item Details Page (Authenticated User)
**Screenshot Description:**
"Comprehensive Item Details page displaying item images in a gallery format, item name, category, location, description with paragraph formatting, pricing per day, deposit amount, total quantity, availability status, and listing date. The page includes owner information with profile picture, contact button, and member since date. A booking calendar shows available and booked dates with a legend. Reviews section displays user ratings with star ratings, comments, and optional images."

### 4.2 Item Image Gallery
**Screenshot Description:**
"Interactive Item Image Gallery supporting 1-4 images. The first image is displayed larger, with remaining images in a responsive grid. Images are clickable for full-screen modal view. If no images are uploaded, a placeholder with the item name is shown."

### 4.3 Availability Calendar
**Screenshot Description:**
"Interactive Booking Calendar displaying monthly view with navigation controls. Past dates are greyed out, currently booked dates are marked in red, and available dates in green. Users can select start and end dates by clicking on available dates. The calendar includes a note explaining that past bookings are shown for reference only."

### 4.4 Booking Cost Calculator
**Screenshot Description:**
"Real-time Booking Cost Calculator that automatically calculates total costs when users select rental dates. It displays: rental cost (price per day × number of days), amount to pay owner in cash, deposit amount (paid online), service fee of RM 1.00 (paid online), and total amount to pay online. This breakdown helps users understand the complete cost structure before confirming."

### 4.5 Contact Owner Button
**Screenshot Description:**
"Contact Owner interface that redirects to the messaging system with a pre-selected conversation with the item owner. The button includes the owner's profile information and is disabled if the current user is viewing their own item listing."

### 4.6 Owner Cannot Book Own Item
**Screenshot Description:**
"Information message displayed when item owners view their own listings, explaining they cannot book their own items. The booking form is replaced with this message to prevent self-booking."

---

## 5. BOOKING MANAGEMENT

### 5.1 Booking Confirmation Page
**Screenshot Description:**
"Booking Confirmation page summarizing the booking request before payment. Displays item details, selected rental dates with duration, cost breakdown (rental amount, deposit, service fee), payment instructions explaining that deposit is paid online while rental fee is paid to owner, and a 'Proceed to Payment' button."

### 5.2 My Bookings List (Renter View)
**Screenshot Description:**
"My Bookings dashboard showing all bookings made by the user in a card-based layout. Each booking card displays item image, item name, rental dates with duration, booking date, location, payment status, current booking status (Pending, Confirmed, Ongoing, Completed, Cancelled, Rejected), total amount paid, and action buttons (View Details, Add Review for completed bookings). Pagination controls allow browsing through booking history."

### 5.3 Pending Approval Status
**Screenshot Description:**
"Booking with Pending status showing 'Waiting for Owner Approval' banner. This appears when payment has been received but the owner hasn't approved the booking yet. The banner displays a message informing the renter that their payment was successful and the owner will review the request shortly."

### 5.4 Booking Details Page (Renter)
**Screenshot Description:**
"Detailed Booking Information page showing comprehensive booking details including: booking ID and date, item information with image and link, owner contact details with profile picture, rental period with date breakdown, cost breakdown (rental amount, deposit, service fee, total), payment information (transaction ID, status), current booking status, and available actions (Cancel Booking, View Messages, Contact Owner)."

### 5.5 Item Bookings List (Owner View)
**Screenshot Description:**
"Item-specific Bookings Management page for item owners, displaying all bookings received for a particular item in a table format. Shows item information card at top with image, statistics (total bookings, confirmed, pending, completed), and a table listing each booking with renter information (name, email, profile picture), booking date, rental period with duration, total amount, status, and action buttons (Approve, Reject, View Details). Pending bookings are highlighted with a payment received indicator."

### 5.6 Approve Booking Action
**Screenshot Description:**
"Booking Approval confirmation dialog where item owners can approve pending bookings. Upon approval, the system updates booking status to 'Confirmed', sends notifications to the renter, and updates item availability. A confirmation prompt prevents accidental approvals."

### 5.7 Reject Booking Action
**Screenshot Description:**
"Booking Rejection confirmation dialog allowing owners to decline booking requests. The system automatically processes a full refund of the deposit to the renter and sends a notification. A warning explains that the deposit will be refunded before proceeding."

### 5.8 Complete Booking Action
**Screenshot Description:**
"Complete Booking interface for item owners to mark a booking as completed after the rental period ends and item is returned. The system automatically processes deposit refund to the renter (if no damages reported) and updates the booking status. This action is only available after the rental end date."

### 5.9 Cancel Booking (Renter)
**Screenshot Description:**
"Cancel Booking confirmation dialog for renters to cancel their bookings. Cancellation policies are displayed, explaining deposit refund conditions. The system processes automatic refund if cancellation occurs before owner approval."

---

## 6. PAYMENT & DEPOSIT MANAGEMENT

### 6.1 Payment Gateway Integration
**Screenshot Description:**
"Payment Gateway (ToyyibPay) integration showing secure payment interface. Users are redirected to ToyyibPay to complete deposit and service fee payment using FPX (online banking). The payment page displays the booking reference, amount breakdown, and secure payment form."

### 6.2 Payment Success Callback
**Screenshot Description:**
"Payment Success confirmation page displayed after successful ToyyibPay payment. Shows transaction ID, amount paid, booking reference, and success message. Informs users that their booking is now pending owner approval. Includes buttons to view booking details and return to bookings list."

### 6.3 Payment Pending Status
**Screenshot Description:**
"Payment Pending indicator showing bookings awaiting payment completion. Users can check payment status or complete payment if it was interrupted. A 'Check Payment Status' button allows real-time verification of payment completion."

### 6.4 Deposit Refund Process
**Screenshot Description:**
"Deposit Management interface (Admin) showing all deposit transactions with status tracking. Displays deposit ID, booking reference, user information, deposit amount, status (Held, Refunded, Forfeited), dates, and action buttons. Administrators can manually process refunds or forfeit deposits in case of damages."

### 6.5 Refund Queue Management
**Screenshot Description:**
"Refund Queue dashboard listing pending refunds requiring processing. Shows refund details including user, amount, reason (booking completion, cancellation, rejection), status (Pending, Processing, Completed, Failed), submission date, and action buttons to mark status changes. Statistics display total pending refunds and amounts."

### 6.6 Service Fee Tracking
**Screenshot Description:**
"Service Fee Reports page displaying RM 1.00 service fee collected per booking. Shows monthly and yearly breakdown with total bookings, total service fees, and average monthly revenue. Includes charts for visualization and export to CSV option."

---

## 7. MY LISTINGS MANAGEMENT

### 7.1 My Listings Dashboard
**Screenshot Description:**
"My Listings page showing all items listed by the current user in a grid layout. Each listing card displays item image, name, category, location, price per day, availability status toggle, and action buttons (View, Edit, Delete, View Bookings). Users can quickly manage all their rental listings from this centralized dashboard."

### 7.2 Create New Listing
**Screenshot Description:**
"Create Item Listing form with fields for: item name, detailed description with paragraph support, category selection dropdown, location selection dropdown, price per day (RM), deposit amount (RM), quantity (total units available), availability toggle, and image upload section supporting up to 4 images (2MB max each, JPG/PNG/GIF). Form includes validation messages and helpful tooltips."

### 7.3 Edit Listing Details
**Screenshot Description:**
"Edit Listing interface displaying the existing item information in editable form fields. Users can update any listing detail including item name, description, category, location, pricing, quantity, and availability status. Current images are displayed with options to remove or replace them."

### 7.4 Upload/Manage Listing Images
**Screenshot Description:**
"Image Management section within listing editor showing current uploaded images (up to 4) with display order, remove buttons, and add new image option. Drag-and-drop functionality allows reordering images. The first image serves as the primary thumbnail in listing cards."

### 7.5 Delete Listing Confirmation
**Screenshot Description:**
"Delete Listing confirmation dialog warning about permanent deletion. The system checks for active or pending bookings before allowing deletion. If active bookings exist, deletion is prevented with an explanation message. Users must confirm deletion twice to prevent accidental removal."

### 7.6 Toggle Item Availability
**Screenshot Description:**
"Availability Toggle switch allowing item owners to quickly mark items as available or unavailable without deleting the listing. When toggled off, the item is hidden from public browsing but remains visible in the owner's dashboard. This is useful for temporary unavailability or maintenance periods."

---

## 8. REVIEW & RATING SYSTEM

### 8.1 Add Review Modal
**Screenshot Description:**
"Add Review modal window with interactive 5-star rating selector, review comment textarea (10-500 characters), and optional image upload for evidence. The modal displays the item name being reviewed and validates that users have completed a booking before allowing review submission. Real-time character counter helps users stay within limits."

### 8.2 Item Reviews Display
**Screenshot Description:**
"Reviews Section on item details page showing aggregated rating summary (average stars out of 5, total review count) and rating distribution chart (5 stars to 1 star breakdown with percentages). Individual review cards display reviewer profile picture, name, review date, star rating, comment text, and optional review image. Reviews are sorted by date with most recent first."

### 8.3 Review Submitted Confirmation
**Screenshot Description:**
"Review submission success message confirming review has been posted. The 'Add Review' button changes to 'Reviewed' badge with checkmark, preventing duplicate reviews. Users can view their submitted review immediately in the item's review section."

### 8.4 Average Rating Display
**Screenshot Description:**
"Item Rating Summary showing overall average rating (calculated from all reviews) displayed with star icons and numerical value (e.g., 4.5). The total number of reviews is shown in parentheses. This appears on item cards in browsing views and prominently on item detail pages."

---

## 9. MESSAGING SYSTEM

### 9.1 Messages List (Inbox)
**Screenshot Description:**
"Messages Inbox displaying all conversations in a list format. Each conversation shows the other user's profile picture, name, last message preview (truncated to 50 characters), timestamp, and unread indicator badge. Conversations are sorted by most recent activity. The interface includes a search function to find specific conversations."

### 9.2 Conversation View
**Screenshot Description:**
"Message Conversation page showing full chat history with a specific user. Messages are displayed in a chat-bubble format with sender profile pictures, timestamps, and read status. The current user's messages appear on the right (blue), received messages on the left (gray). Optional item reference links to the discussed listing. Real-time message updates via AJAX every 3 seconds."

### 9.3 Send Message Interface
**Screenshot Description:**
"Message Composition form at the bottom of conversation view with text area for message content (max 1000 characters), optional item selector to reference a specific listing, and send button. Character counter displays remaining characters. Form validates message content before submission."

### 9.4 Unread Message Counter
**Screenshot Description:**
"Unread Message Badge displayed in the navigation bar showing the number of unread messages. Updates in real-time via AJAX polling. Clicking the badge navigates to the messages inbox where unread conversations are highlighted."

### 9.5 Message Notification
**Screenshot Description:**
"New Message Notification appearing in the notifications dropdown when a new message is received. Shows sender name, message preview, timestamp, and clicking it navigates directly to that conversation. Notifications are marked as read when the conversation is opened."

---

## 10. WISHLIST FEATURE

### 10.1 Add to Wishlist
**Screenshot Description:**
"Add to Wishlist heart icon button on item cards and detail pages. When clicked, the outline heart fills solid with red color, indicating the item has been added to the user's wishlist. AJAX functionality provides instant feedback without page reload."

### 10.2 Wishlist Page
**Screenshot Description:**
"My Wishlist page displaying all saved items in a grid layout similar to homepage. Each wishlist item card shows item image, name, price per day, location, rating, and a 'Remove from Wishlist' button. Empty wishlist displays a friendly message encouraging users to explore items and add favorites."

### 10.3 Remove from Wishlist
**Screenshot Description:**
"Remove from Wishlist action toggling the heart icon back to outline state and removing the item from the wishlist collection. Users can remove items from both the wishlist page and item detail pages with instant visual feedback."

---

## 11. NOTIFICATION SYSTEM

### 11.1 Notifications Dropdown
**Screenshot Description:**
"Notifications Dropdown menu accessible from the navigation bar bell icon. Displays recent notifications with icons, titles, brief messages, and timestamps. Unread notifications are highlighted with a blue background. The dropdown shows up to 10 recent notifications with a 'View All' link to the full notifications page."

### 11.2 All Notifications Page
**Screenshot Description:**
"Complete Notifications page showing all user notifications in chronological order with pagination (20 per page). Each notification displays: icon based on type (booking, payment, message, report), title, full message, timestamp, read/unread status, and mark as read button. Header includes 'Mark All as Read' button and unread count."

### 11.3 Notification Types Overview
**Screenshot Description:**
"Various notification types displayed in the system:
- Booking Request (new booking received)
- Booking Approved (owner approved your booking)
- Booking Rejected (owner rejected your booking)
- Booking Completed (rental period ended)
- Booking Cancelled (user cancelled booking)
- Payment Successful (deposit payment confirmed)
- Refund Processed (deposit refunded)
- New Message (message received)
- Report Submitted (report acknowledgment)
- System Notification (auto-complete, reminders)"

### 11.4 Mark Notification as Read
**Screenshot Description:**
"Mark as Read action changing notification background from blue (unread) to white (read) and decreasing the unread counter. Users can mark individual notifications as read or use 'Mark All as Read' to clear all unread notifications at once."

---

## 12. REPORT & PENALTY SYSTEM

### 12.1 Submit Report Form
**Screenshot Description:**
"Report Submission form allowing users to report issues with other users. Fields include: report type selection (Damage to Item, Late Return, Booking Dispute, Fraudulent Activity, Harassment/Misconduct, Other), reported user selection, linked booking/item reference, detailed description (20-2000 characters), evidence image upload (max 10MB), priority level (automatically assigned), and submit button. Form includes validation and helpful tooltips."

### 12.2 Report Types Selection
**Screenshot Description:**
"Report Type dropdown showing categorized report options:
- Damage to Item (physical damage to rented items)
- Late Return (item not returned on time)
- Booking Dispute (disagreements about booking terms)
- Fraudulent Activity (scam or fraud attempts)
- Harassment/Misconduct (inappropriate behavior)
- Other (miscellaneous issues)
Each type has specific handling procedures for resolution."

### 12.3 Report Submission Confirmation
**Screenshot Description:**
"Report submitted confirmation page displaying report ID, submission timestamp, and message confirming the report has been received. Informs users that administrators will review the report within 24-48 hours and take appropriate action. Provides a link to view report details and status."

### 12.4 User Penalty History
**Screenshot Description:**
"My Penalties page showing all penalties issued to the current user. Displays penalty ID, reason, amount charged, associated booking reference, date issued, status (Pending, Resolved), resolution details, and appeal option. Helps users track penalty history and outstanding charges."

---

## 13. ADMIN DASHBOARD

### 13.1 Admin Dashboard Overview
**Screenshot Description:**
"Admin Dashboard home showing comprehensive system statistics in card widgets: total users count, total active listings, total deposits (held/refunded/forfeited amounts), total reports (pending/resolved), total penalties, total bookings by status, service fee revenue, and monthly active users. Quick action buttons provide access to pending items requiring attention. Recent activity feed shows latest system events."

### 13.2 Dashboard Statistics Cards
**Screenshot Description:**
"Dashboard Statistics displaying key metrics in visual cards:
- Total Users (with growth percentage)
- Active Listings (vs inactive)
- Total Deposits (breakdown by status)
- Pending Reports (requiring review)
- Total Penalties (issued this month)
- Active Bookings (by status)
- Service Fee Revenue (monthly)
- System Health (storage, database)
Each card links to detailed management pages."

### 13.3 Admin Quick Actions
**Screenshot Description:**
"Quick Actions panel on admin dashboard with buttons for:
- View Pending Bookings (requiring approval)
- Process Refunds (pending refund queue)
- Review Reports (unresolved reports)
- Manage Penalties (active penalties)
- View Recent Users (last 10 registrations)
- Export Data (various CSV exports)
Provides rapid access to common administrative tasks."

### 13.4 Recent Activity Feed
**Screenshot Description:**
"Real-time Activity Feed showing recent system events in chronological order: user registrations, new listings, bookings created, payments processed, reports submitted, penalties issued, refunds completed. Each activity shows icon, description, involved users, and timestamp. Auto-refreshes every 30 seconds."

---

## 14. ADMIN USER MANAGEMENT

### 14.1 All Users List
**Screenshot Description:**
"Admin Users Management page displaying all registered users in a table format with columns: user ID, profile picture, username, email, phone number, location, registration date, total listings, total bookings, user role (User/Admin), account status, and action buttons (View, Delete, Suspend/Unsuspend). Includes search functionality, sorting options, and pagination (20 per page)."

### 14.2 Search & Filter Users
**Screenshot Description:**
"User Search and Filter interface with search box (by name or email), filter dropdown by user type (All, Regular Users, Admins), sort options (Newest First, Oldest First, Name A-Z, Name Z-A), date range filter for registration date, and results counter showing filtered results out of total users."

### 14.3 User Details (Admin View)
**Screenshot Description:**
"Detailed User Information page (Admin) showing comprehensive user profile including: personal information (name, email, phone, location), account details (registration date, email verification status, account status), activity statistics (total listings, active bookings, completed rentals, reviews given, reviews received, average rating), listing portfolio, booking history, penalty records, reports submitted, and reports against user. Action buttons allow editing user information, suspending/unsuspending account, resetting password, and deleting user."

### 14.4 Suspend User Account
**Screenshot Description:**
"Suspend User confirmation dialog explaining suspension consequences: user loses access to create listings, cannot make new bookings, existing bookings remain valid, messages remain accessible. Requires administrator to enter suspension reason. Suspended users see a notice when attempting restricted actions."

### 14.5 Unsuspend User Account
**Screenshot Description:**
"Unsuspend User action restoring full account access. Dialog confirms restoration of privileges and optionally sends notification to user informing them of account reactivation. Tracks suspension history for audit purposes."

### 14.6 Delete User (Admin)
**Screenshot Description:**
"Delete User confirmation requiring multiple confirmations due to permanent nature. System checks for active bookings, held deposits, and pending transactions. If any exist, deletion is prevented with detailed explanation. If safe to delete, removes all user data including listings, messages, reviews, while preserving essential transaction records for accounting."

### 14.7 Export Users to CSV
**Screenshot Description:**
"Export Users Data dialog allowing administrators to download user information as CSV file. Options to select which fields to export (basic info, statistics, activity data), date range filter, and user type filter. Generated CSV includes usernames, emails, registration dates, listings count, bookings count, and other selected metrics."

---

## 15. ADMIN LISTING MANAGEMENT

### 15.1 All Listings Management
**Screenshot Description:**
"Admin Listings page displaying all items listed on the platform in a grid/table format. Shows item image, name, owner information, category, location, price per day, deposit amount, quantity, availability status, listing date, total bookings received, and action buttons (View, Delete). Search bar allows finding items by name or owner. Filters available for category, location, and availability status."

### 15.2 Search & Filter Listings
**Screenshot Description:**
"Listing Search and Filter panel with: text search (by item name or owner name), category dropdown filter (all categories available), location dropdown filter, availability filter (All, Available, Unavailable), price range slider, sort options (Newest, Oldest, Price Low-High, Price High-Low), and date range for listings created. Active filters display as removable tags."

### 15.3 Delete Listing (Admin)
**Screenshot Description:**
"Admin Delete Listing confirmation dialog with warnings about permanent removal. System checks for active bookings linked to this listing. If active bookings exist, deletion is blocked and shows count of affected bookings with owner contact information. Successful deletion removes listing and all associated images from storage."

### 15.4 Listing Statistics (Admin)
**Screenshot Description:**
"Listing Analytics page showing comprehensive statistics: total listings by category (pie chart), listings by location (bar chart), average price by category, most booked items (top 10), least active listings, new listings trend (last 6 months), availability rate percentage, items pending verification, and total items by availability status. Export options available."

### 15.5 Export Listings Data
**Screenshot Description:**
"Export Listings dialog for generating CSV reports. Select fields to include: item details, owner information, pricing, availability, booking statistics, ratings. Filter by date range, category, location, and availability. Generated file includes all selected data for offline analysis and reporting."

---

## 16. ADMIN DEPOSIT MANAGEMENT

### 16.1 Deposits Dashboard
**Screenshot Description:**
"Deposits Management dashboard showing all deposit transactions with comprehensive filtering. Table displays: deposit ID, booking reference, renter information, item name, deposit amount, status (Held, Refunded, Forfeited), submission date, refund/forfeit date, reason, and actions. Statistics cards show total deposits held, total refunded, total forfeited, and amounts for each. Search by user, item, or booking reference available."

### 16.2 Manually Process Refund
**Screenshot Description:**
"Manual Refund Processing form for administrators to refund deposits outside automated workflow. Displays deposit details, original amount, current status, and refund form fields: refund amount (with partial refund option), refund reference number, refund method (bank transfer, FPX reversal), refund reason, processing notes, and estimated completion date (3-5 business days). Requires confirmation before processing."

### 16.3 Forfeit Deposit for Damages
**Screenshot Description:**
"Forfeit Deposit interface allowing administrators to charge users for damages. Shows deposit information, booking details, damage report (if filed), and forfeiture form: forfeit amount (full or partial), reason for forfeiture (damage description), supporting evidence upload, notification to user toggle, and appeal information. Requires detailed justification and approval confirmation."

### 16.4 Deposit Status Tracking
**Screenshot Description:**
"Deposit Status Timeline showing lifecycle of deposit: Received (when booking created), Held (during rental period), Refunded/Forfeited (after booking completion/damage report), with timestamps for each stage. Visual timeline with status indicators helps track deposit processing stages."

### 16.5 Export Deposits Report
**Screenshot Description:**
"Export Deposits dialog for generating financial reports. Options include: date range selection, status filter (all, held, refunded, forfeited), amount range filter, export format (CSV, PDF), and fields to include (booking details, user information, amounts, dates, reasons). Generated reports useful for accounting and auditing purposes."

---

## 17. ADMIN REFUND QUEUE

### 17.1 Refund Queue Dashboard
**Screenshot Description:**
"Refund Queue interface listing all pending refunds requiring processing attention. Table shows: refund ID, booking reference, user name and email, item name, deposit amount to refund, refund reason (Booking Completed, Booking Cancelled, Booking Rejected), submission date, current status (Pending, Processing, Completed, Failed), assigned administrator, and action buttons. Statistics show pending count, processing count, total amount pending refund."

### 17.2 Mark Refund as Processing
**Screenshot Description:**
"Update Refund Status to 'Processing' action indicating refund has been initiated with payment provider. Form captures: processing reference number, payment gateway transaction ID, estimated completion date, processor notes, and automatically timestamps processing start. Sends notification to user confirming refund is being processed."

### 17.3 Complete Refund
**Screenshot Description:**
"Complete Refund action finalizing refund processing. Form requires: actual completion date, final refund reference number, confirmation of amount credited, processor signature/ID, and completion notes. Updates deposit status to 'Refunded' and sends confirmation notification to user with transaction details."

### 17.4 Mark Refund as Failed
**Screenshot Description:**
"Mark Refund Failed interface for documenting unsuccessful refund attempts. Captures: failure reason (bank details incorrect, account closed, technical error), error code/message from payment gateway, retry count, resolution plan, and escalation options. Allows administrators to manually investigate and retry failed refunds."

### 17.5 Refund Statistics Report
**Screenshot Description:**
"Refund Analytics dashboard displaying key metrics: total refunds processed (monthly/yearly), average processing time, success rate vs failure rate, refund amounts by reason category, processing time distribution chart, common failure reasons, refunds by status, and trend analysis. Helps identify bottlenecks in refund processing."

---

## 18. ADMIN REPORT MANAGEMENT

### 18.1 All Reports List
**Screenshot Description:**
"Admin Reports Management page displaying all user-submitted reports in a table. Columns show: report ID, report type with icon, reporter name, reported user name, submission date, priority level (High/Medium/Low color-coded), status (Pending/Under Review/Resolved/Dismissed), linked booking/item, and action buttons (View Details, Resolve, Dismiss). Search and filter options by type, status, priority, and date range."

### 18.2 Report Details View (JSON)
**Screenshot Description:**
"Detailed Report Information modal showing complete report data: report metadata (ID, type, priority, status), reporter information (name, email, profile link), reported user information with history, linked booking/item details, full description, uploaded evidence images (if any), submission timestamp, admin notes, status history timeline, and action buttons (Resolve with Penalty, Dismiss, Contact Users, Suspend User)."

### 18.3 Resolve Report with Actions
**Screenshot Description:**
"Resolve Report interface providing multiple resolution options: Apply Penalty (specify amount and reason), Issue Warning (formal written warning), Suspend User Account (temporary or permanent), Hold User's Deposit (prevent withdrawals), Dismiss Report (if unsubstantiated), Add Admin Notes (internal documentation), Notify Users (send resolution details), and Close Report. Each action requires justification and admin confirmation."

### 18.4 Apply Penalty from Report
**Screenshot Description:**
"Create Penalty from Report form pre-filled with report details. Fields include: penalty amount (suggested based on report type), penalty reason (auto-filled from report), affected booking reference, payment deadline, late payment consequences, user notification toggle, and penalty notes. Links penalty record to original report for tracking."

### 18.5 Dismiss Report
**Screenshot Description:**
"Dismiss Report dialog for closing unsubstantiated or duplicate reports. Requires administrator to select dismissal reason: Insufficient Evidence, Duplicate Report, Resolved Outside System, False Report, User Agreement Reached, Other. Includes text field for detailed explanation. Sends notification to reporter explaining dismissal decision."

### 18.6 Suspend User from Report
**Screenshot Description:**
"Suspend User Account action triggered directly from report review. Pre-fills suspension reason from report details. Options include: suspension duration (days/permanent), restriction level (full suspension, booking only, listing only), automatic notification to user, appeal process information, and link suspension to report for audit trail."

### 18.7 Export Reports Data
**Screenshot Description:**
"Export Reports dialog for generating reports summary. Select date range, report types to include, status filter, priority filter, and export format (CSV/PDF). Generated file includes: report details, involved users, resolution status, penalties applied, processing time, admin notes, and outcomes. Useful for compliance and trend analysis."

---

## 19. ADMIN PENALTY MANAGEMENT

### 19.1 Penalties Dashboard
**Screenshot Description:**
"Penalties Management page listing all penalties issued on the platform. Table displays: penalty ID, user name and profile picture, penalty amount (RM), reason for penalty, linked booking/report reference, issue date, due date, payment status (Pending/Paid/Overdue), resolution status, and action buttons (View, Resolve, Delete). Statistics show total penalties issued, total amount charged, total pending, total resolved, and collection rate percentage."

### 19.2 Create Manual Penalty
**Screenshot Description:**
"Create Penalty form for administrators to issue penalties outside the report system. Fields include: select user (with search), penalty amount (RM), penalty reason (dropdown: Late Return, Damage, Policy Violation, Fraudulent Activity, Other), detailed description, linked booking (optional), payment deadline, payment instructions, notification settings, and priority level. Validates amount and requires detailed justification."

### 19.3 Penalty Details View
**Screenshot Description:**
"Detailed Penalty Information page showing: penalty metadata (ID, amount, status), user information with penalty history, penalty reason and full description, linked booking/report details with evidence, issue date and due date, payment status and transaction details, resolution information (if resolved), admin notes, status change history, payment reminder history, and action options (Resolve, Extend Deadline, Waive Penalty, Send Reminder)."

### 19.4 Resolve Penalty
**Screenshot Description:**
"Resolve Penalty interface documenting penalty resolution. Form captures: resolution method (Paid in Full, Partial Payment, Waived, Dispute Resolved), payment amount and reference, resolution date, resolution notes, evidence of payment (upload receipt), admin approver, notification to user toggle, and update penalty status. Links resolution to accounting records."

### 19.5 Delete Penalty
**Screenshot Description:**
"Delete Penalty confirmation dialog for removing penalty records. Only allows deletion if penalty is not paid/resolved. Requires administrator to provide deletion reason: Created in Error, Duplicate Entry, User Appealed Successfully, Incorrect Amount, Other. Maintains audit log of deleted penalties for compliance. Cannot delete penalties with payment history."

### 19.6 User Penalty History
**Screenshot Description:**
"User Penalty History report showing all penalties issued to a specific user. Timeline view displays: penalty date, reason, amount, status, resolution details, and payment information. Summary statistics include: total penalties received, total amount charged, total paid, total outstanding, payment compliance rate, and average penalty amount. Helps assess user behavior patterns."

### 19.7 Export Penalties Report
**Screenshot Description:**
"Export Penalties dialog for generating financial and compliance reports. Options: date range, status filter (all, pending, paid, overdue, resolved), amount range, user filter, reason category filter, export format (CSV/PDF). Generated report includes penalty details, user information, payment status, resolution details, and aging analysis. Used for financial audits and compliance."

---

## 20. ADMIN SERVICE FEES & TAXES

### 20.1 Service Fee Reports
**Screenshot Description:**
"Service Fee Analytics dashboard displaying RM 1.00 per booking fee collection. Shows monthly breakdown with: bookings count per month, service fees collected (bookings × RM 1.00), trend chart for last 12 months, yearly summary, average monthly revenue, growth rate percentage, and payment method breakdown. Export options available for accounting purposes."

### 20.2 Monthly Service Fee Breakdown
**Screenshot Description:**
"Monthly Service Fee Details table showing daily breakdown for selected month. Displays: date, number of bookings, service fee collected (bookings × RM 1.00), running total, payment gateway fees (if applicable), net revenue, and comparison to previous month. Visual charts show booking volume trends and revenue patterns throughout the month."

### 20.3 Yearly Service Fee Summary
**Screenshot Description:**
"Annual Service Fee Summary presenting fiscal year overview: total bookings processed, total service fees collected, month-by-month breakdown with bar chart, quarter-over-quarter comparison, year-over-year growth, peak months identification, and revenue forecast based on trends. Export to PDF for annual reports."

### 20.4 Export Service Fee Data
**Screenshot Description:**
"Export Service Fee Reports dialog for financial reporting. Select reporting period (monthly, quarterly, yearly, custom date range), include detailed breakdown toggle, format selection (CSV, Excel, PDF), and data fields to include (booking IDs, user information, payment dates, transaction references). Generated reports integrate with accounting software."

---

## 21. ADMIN PROFILE & SETTINGS

### 21.1 Admin Profile Dashboard
**Screenshot Description:**
"Admin Profile page displaying administrator account information: name, email, phone number, profile picture, admin role designation, account creation date, last login timestamp, login history (last 10 logins with IP addresses), and admin activity statistics (total actions performed, reports resolved, users managed, penalties issued). Access to profile editing, password change, and system settings."

### 21.2 Update Admin Information
**Screenshot Description:**
"Edit Admin Profile form allowing administrators to update their account information: name, email (requires verification if changed), phone number, profile picture upload, notification preferences (email alerts for reports, penalties, refunds), and display preferences. Form includes validation and confirmation for sensitive changes."

### 21.3 Change Admin Password
**Screenshot Description:**
"Admin Change Password form with enhanced security requirements: current password verification, new password field (minimum 8 characters, must include uppercase, lowercase, number, special character), password confirmation, and security tips. Logs password changes with timestamp and IP address for security audit."

### 21.4 Admin Activity Log
**Screenshot Description:**
"Admin Activity Log showing comprehensive history of administrative actions: timestamp, action type (User Managed, Report Resolved, Penalty Issued, Refund Processed, Listing Deleted, etc.), affected entity (user/listing/booking ID), action details, IP address, browser/device information, and outcome. Filterable by date range and action type. Export capabilities for compliance auditing."

---

## 22. PUBLIC PAGES

### 22.1 Public Item Details (Guest View)
**Screenshot Description:**
"Public Item Details page accessible without login. Displays item images, name, description, pricing, deposit, owner information (limited), reviews, and availability calendar. Booking functionality is replaced with a 'Login to Book' or 'Register to Book' call-to-action button. Allows visitors to browse items before deciding to register."

### 22.2 Terms of Service Page
**Screenshot Description:**
"Terms of Service page displaying comprehensive platform rules and policies: user responsibilities, item listing guidelines, booking terms and conditions, payment and refund policies, dispute resolution procedures, privacy policy summary, acceptable use policy, liability disclaimers, and contact information for support. Accessible to all users (authenticated and guest) via footer link."

### 22.3 Welcome/Landing Page
**Screenshot Description:**
"Landing Page (Guest) featuring hero section with platform overview, key benefits (safe rentals, verified users, secure payments), featured item showcase, category highlights, how it works steps (List Items, Browse & Book, Rent & Return, Review), testimonials section, and call-to-action buttons (Get Started, Browse Items). Responsive design optimized for desktop and mobile."

---

## 23. ADDITIONAL FEATURES

### 23.1 Search with Multiple Filters
**Screenshot Description:**
"Advanced Search interface combining multiple filters simultaneously: text search (item name, description), category multi-select, location multi-select, price range slider, availability date picker, minimum rating filter, and sort options. Active filters display as removable tags. Real-time results update as filters change. Search history saved for quick re-use."

### 23.2 Responsive Mobile View
**Screenshot Description:**
"Mobile-optimized interface demonstrating responsive design: hamburger menu for navigation, touch-friendly buttons and form elements, swipeable image galleries, collapsible filter panels, bottom navigation bar for quick actions, and mobile-optimized pagination. All features fully functional on smartphones and tablets with adaptive layouts."

### 23.3 Loading States & Feedback
**Screenshot Description:**
"User Feedback Mechanisms throughout the platform: loading spinners during data fetch, progress indicators for file uploads, success/error toast notifications with auto-dismiss, form validation messages inline with fields, confirmation dialogs for destructive actions, and skeleton loaders for content placeholders. Enhances user experience with clear status communication."

### 23.4 Error Handling & Validation
**Screenshot Description:**
"Error Handling Examples showing user-friendly error messages: form validation errors highlighted in red with specific guidance, 404 page with navigation options, 403 unauthorized access page with explanation, server error page with support contact, payment failure page with retry options, and network error messages with refresh prompts. All errors logged for administrator review."

### 23.5 Empty State Messages
**Screenshot Description:**
"Empty State Designs for pages with no data: empty bookings page encouraging users to browse items, empty wishlist with suggestions to explore categories, no notifications message with cute illustration, zero search results with alternative suggestions, and empty admin dashboards prompting first actions. All empty states include relevant calls-to-action."

---

## 24. SYSTEM TECHNICAL FEATURES

### 24.1 Real-time Updates (AJAX)
**Screenshot Description:**
"Real-time Features powered by AJAX: notification badge updates without page refresh, message polling for new messages every 3 seconds, wishlist toggle instant feedback, payment status checking, unread count auto-updates, booking availability live verification, and auto-save for draft forms. Improves user experience with dynamic content updates."

### 24.2 Image Upload & Management
**Screenshot Description:**
"Image Upload System supporting multiple file uploads with preview: drag-and-drop interface, file type validation (JPG, PNG, GIF), file size checking (max 2MB), image preview before upload, upload progress indicators, crop/resize options, display order management, and removal functionality. Secure storage with Laravel filesystem."

### 24.3 CSV Export Functionality
**Screenshot Description:**
"Data Export Features available throughout admin panel: export users list, export listings catalog, export bookings history, export deposits report, export refunds queue, export reports log, export penalties record, and export service fees summary. Each export includes customizable fields, date range filters, and generates downloadable CSV files for offline analysis."

### 24.4 Email Notifications
**Screenshot Description:**
"Automated Email Notifications sent for key events: welcome email upon registration, email verification link, password reset link, booking confirmation, booking status updates (approved/rejected/completed), payment confirmation, deposit refund notification, message notification, report acknowledgment, and penalty notice. All emails use branded templates with action buttons."

### 24.5 Security Features
**Screenshot Description:**
"Security Implementations throughout the system: password hashing (bcrypt), CSRF token protection on all forms, XSS prevention with input sanitization, SQL injection protection via Eloquent ORM, authentication middleware on protected routes, role-based authorization checks, secure password reset tokens (60-minute expiry), login attempt throttling, and secure file upload validation."

---

## CONCLUSION

This document provides comprehensive descriptions for all 100+ features and functions in the GoRentUMS system. Each description is formatted to be used as a caption or explanation for screenshots in your project report.

**Usage Instructions:**
1. Take screenshots of each feature in the system
2. Use the corresponding description from this document as the caption
3. Ensure screenshots show the feature in action with realistic data
4. Organize screenshots by category as structured in this document
5. Add page numbers and figure labels as per your report requirements

**Screenshot Best Practices:**
- Use consistent browser/device for all screenshots
- Show realistic user data (not empty forms)
- Highlight key UI elements being described
- Include browser chrome to show full context
- Capture at appropriate resolution for print
- Annotate screenshots with numbered callouts if needed

**Total Features Documented:** 120+ functions across 24 major categories
