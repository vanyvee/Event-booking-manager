use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\SeatZoneController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\OrganizerReportController;


Route::middleware(['auth:sanctum'])->group(function () {

    /** ---------------------------
     *  EVENTS
     * -------------------------- */
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events/{event}', [EventController::class, 'show']);

    /** ---------------------------
     *  TICKETS
     * -------------------------- */
    Route::post('/events/{event}/tickets', [TicketController::class, 'store']);

    /** ---------------------------
     *  BOOKINGS
     * -------------------------- */
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    Route::put('/bookings/{id}/cancel', [BookingController::class, 'cancel']);

    /** ---------------------------
     *  CATEGORIES
     * -------------------------- */
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // events under category
    Route::get('/categories/{id}/events', [CategoryController::class, 'events']);
    
    
    // Booking 
    Route::post('bookings', [BookingController::class, 'store']);
    Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel']);
    Route::get('my-bookings', [BookingController::class, 'myBookings']);
    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    
    //OPAY PAYMENT
    Route::post('/initialize/{bookingId}', [PaymentController::class, 'initialize']);
    Route::get('/verify/{reference}', [PaymentController::class, 'verify']);
    Route::post('/webhook', [PaymentController::class, 'webhook']);

    
    Route::get('/bookings/{booking}/invoice', function(App\Models\Booking $booking) {
    if ($booking->status !== 'paid') {
        return response()->json(['message' => 'Invoice available only for paid bookings'], 403);
    }
    $url = App\Services\InvoiceService::generateInvoice($booking);
    return response()->json(['invoice_url' => $url]);
    });
    
    // Refunds
    Route::post('/refund', [RefundController::class, 'refund']);
    Route::get('/refunds', [RefundController::class, 'index']);
    Route::get('/refunds/{id}', [RefundController::class, 'show']);

    // Organizer reports
    Route::get('/organizer/revenue-summary', [OrganizerReportController::class, 'revenueSummary']);
    Route::get('/organizer/revenue-by-date', [OrganizerReportController::class, 'revenueByDate']);
    
});

    
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'ensure.organizer'])->group(function () {
    Route::post('events', [EventController::class, 'store']);
    Route::post('events/{event}/zones', [SeatZoneController::class, 'store']);
  Route::get('events/{event}/zones', [SeatZoneController::class, 'index']);
      Route::put('zones/{zone}', [SeatZoneController::class, 'update']);
    Route::delete('zones/{zone}', [SeatZoneController::class, 'destroy']);

    // Seats
    Route::get('zones/{zone}/seats', [SeatController::class, 'index']);
    Route::post('zones/{zone}/generate-seats', [SeatController::class, 'autoGenerate']);
 // Ticket Types
    Route::get('events/{event}/ticket-types', [TicketTypeController::class, 'index']);
    Route::post('events/{event}/ticket-types', [TicketTypeController::class, 'store']);
    Route::put('ticket-types/{ticketType}', [TicketTypeController::class, 'update']);
    Route::delete('ticket-types/{ticketType}', [TicketTypeController::class, 'destroy']);

    
});




