
Event Booking & Ticketing API

A Laravel-based API for managing events, ticketing, and payments. Designed with a facade-style service architecture for clean, scalable, and maintainable code.


Features

 Event & Ticket Management

Create and manage events with categories and tags.

Multiple ticket types per event (VIP, Regular, Early-bird, etc.).

Automatic ticket availability check during booking.

Users can view their bookings via API.


 Payment Integration

Integrated Opay payment gateway.

Handles secure transactions and records transaction references.

Payment status tracked (paid, pending, refunded).


 Refund System

Refund only allowed if booking is paid.

Refunds are recorded in refunds table with status (pending, success, failed).

Supports manual refund by organizer/admin.

Supports future auto-refund workflow on event cancellation.


Revenue Reports

Organizer dashboard can retrieve revenue summaries.

Gross revenue, refunds, and net revenue calculated.

Optional date range filtering for reports.


Event Notifications (future)

Events triggered for ticket booking, cancellation, and refunds.

Can integrate with email, SMS, or real-time notifications.


Architecture & Patterns

Facade-style service calls: e.g., BookingService::bookTicket(...).

Service layer handles all business logic (Booking, Refund, Organizer reporting).

Event-driven architecture for booking and refund lifecycle.

Lean controllers, all heavy logic is in services.



Database Models

Event: stores event info, category, date, etc.

Ticket: multiple types per event, quantity, price.

Booking: user bookings, quantity, total price, status, payment reference.

Refund: logs all refund attempts with amount, status, reason.

Category: event categorization.



API Endpoints

Booking

POST /api/bookings → Book a ticket.

POST /api/bookings/cancel/{id} → Cancel booking.

GET /api/bookings/my → Get current user bookings.


Refunds

POST /api/refund → Trigger a refund (admin/organizer).

GET /api/refunds → List all refunds.

GET /api/refunds/{id} → View a single refund.


Organizer Reports

GET /api/organizer/revenue-summary → Overall revenue.

GET /api/organizer/revenue-by-date?start=YYYY-MM-DD&end=YYYY-MM-DD → Revenue for a date range.







Usage

Book a Ticket

BookingService::bookTicket($userId, $ticketId, $quantity);

Cancel Booking

BookingService::cancelBooking($bookingId);

Refund Booking

BookingService::refundBooking($bookingId, 'Event cancelled');

Organizer Revenue

OrganizerService::revenueReport($organizerId);



Next Steps / Enhancements

front end development with Vue.js
