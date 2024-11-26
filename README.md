### Backend README (Laravel)

# Tour Booking App - Backend

This is the backend API for the Tour Booking application built using **Laravel**.

### Authentication

- The API uses **Laravel Sanctum** for authentication.
- For all requests (except those related to authentication pages), include the API token in the **Authorization** header using the `Bearer` token format.

### API Endpoints

#### Destinations
- **GET /api/tours/destinations**: List all tour destinations.
- **GET /api/tours/destinations/{id}**: View a specific destination.
- **POST /api/admin/destinations**: Create a new destination (Admin only).
- **PUT /api/admin/destinations/{id}**: Update an existing destination (Admin only).
- **DELETE /api/admin/destinations/{id}**: Delete a destination (Admin only).

#### Tours
- **GET /api/tours**: List all tours.
- **POST /api/tours**: Create a new tour (Admin only).
- **PUT /api/tours/{id}**: Update an existing tour (Admin only).
- **DELETE /api/tours/{id}**: Delete a tour (Admin only).

#### Bookings
- **GET /api/tours/bookings**: List all bookings for the authenticated user.
- **POST /api/tours/bookings**: Create a new booking.
- **GET /api/tours/bookings/{id}**: View a specific booking.
- **GET /api/admin/tours/bookings**: List all bookings (Admin only).
- **PUT /api/admin/tours/bookings/{id}**: Update a booking (Admin only).
- **DELETE /api/admin/tours/bookings/{id}**: Delete a booking (Admin only).

#### Tickets
- **GET /api/tours/tickets**: List all generated tickets (Admin only).
- **GET /api/tours/tickets/{id}**: View a specific ticket.
- **POST /api/admin/tours/tickets**: Create a new ticket (Admin only).
- **PUT /api/admin/tours/tickets/{id}**: Update a ticket (Admin only).
- **DELETE /api/admin/tours/tickets/{id}**: Delete a ticket (Admin only).

### Database

- **MySQL** is used for data storage.

### Error Handling

- API errors are handled with clear and descriptive error messages for easy debugging.

### Testing

- API endpoints have been tested and verified using Postman. A **Postman collection** with expected body/params is available.

### Deployment

Deployed on **Google Cloud Platform** for both frontend and backend.

### Versioning

The project uses **GitHub** for version control. Below are the repo links:

- [Frontend Repo](https://github.com/felixkpt/tour-booking-nuxt)
- [Backend Repo](https://github.com/felixkpt/tour-booking-laravel)

---