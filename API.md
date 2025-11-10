# MEPCO API

## Base URL
- Local: `http://127.0.0.1:8000/api/v1`

## Authentication
- Auth method: Laravel Sanctum bearer token
- Login: `POST /login` → returns `token`
- Use header: `Authorization: Bearer <token>`
- Logout: `POST /logout` (current token), `POST /logout-all` (all tokens)

Example:
```bash
curl -X POST http://127.0.0.1:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@mepco.gov.pk","password":"password@123"}'

# then call protected endpoint
curl http://127.0.0.1:8000/api/v1/applications \
  -H "Authorization: Bearer <TOKEN>"
```

## Conventions
- Pagination: `?per_page=15&page=1`
- Filtering: resource-specific query params (see below)
- Responses: `{ success: boolean, data?: any, message?: string }`
- Errors: HTTP status with `{ success: false, message }`

---

## Public Endpoints (no auth)
- Auth
  - `POST /register`
  - `POST /login`
- Applications
  - `POST /applications` (submit new)
  - `POST /applications/track` (by `application_no`)
- Complaints
  - `POST /complaints` (submit new)
  - `POST /complaints/track` (by `complaint_id`)
- Directory
  - `GET /companies`
  - `GET /subdivisions` (filter: `company_id`)
- Meters
  - `POST /meters/check` (by `meter_no`)

---

## Protected Endpoints (auth:sanctum)

### Auth
- `GET /user`
- `POST /logout`
- `POST /logout-all`

### Dashboard
- `GET /dashboard/admin`
- `GET /dashboard/ls`
- `GET /dashboard/activities`
- `GET /dashboard/revenue-trend`
- `GET /dashboard/subdivisions`

### Applications
- `GET /applications` (filters: `status`, `subdivision_id`, `search`, `per_page`, `page`)
- `GET /applications/{id}`
- `PUT /applications/{id}`
- `DELETE /applications/{id}`
- `POST /applications/{id}/status` (body: `status`, `remarks?`)

Create (public):
- `POST /applications` body:
  - `application_no` (unique), `customer_name`, `phone (11 digits)`,
  - `company_id`, `subdivision_id`, optional: `customer_cnic`, `address`, `meter_number`, `connection_type`

### Complaints
- `GET /complaints` (filters: `status`, `type`, `subdivision_id`, `search`, `per_page`, `page`)
- `GET /complaints/{id}`
- `PUT /complaints/{id}`
- `DELETE /complaints/{id}`

Create (public):
- `POST /complaints` body:
  - `company_id`, `subdivision_id`, `customer_name`, `phone`,
  - `complaint_type (billing|power_outage|meter|service|other)`,
  - `subject`, `description`, `priority (normal|high|urgent)`, optional `consumer_ref`

### Consumers
- `GET /consumers` (filters: `status`, `subdivision_id`, `connection_type`, `search`, `per_page`, `page`)
- `GET /consumers/{id}`
- `POST /consumers`
- `PUT /consumers/{id}`
- `DELETE /consumers/{id}`
- `POST /consumers/find-by-cnic` (body: `cnic: 13 chars`)
- `GET /consumers/{id}/history` (latest bills and complaints)

Create body:
- `name`, `cnic (13, unique)`, `consumer_id (unique)`, `subdivision_id`
- optional: `phone`, `address`, `connection_type`, `status (active|inactive)`

### Meters
- `GET /meters` (filters: `status`, `subdivision_id`, `search`, `per_page`, `page`)
- `GET /meters/{id}`
- `POST /meters`
- `PUT /meters/{id}`
- `DELETE /meters/{id}`

Create body:
- `meter_no (unique)`, `subdivision_id`
- optional: `meter_make`, `consumer_id`, `reading`, `sim_number`, `installed_on (date)`, `status (active|faulty|disconnected)`

### Bills
- `GET /bills` (filters: `payment_status`, `subdivision_id`, `consumer_id`, `month`, `year`, `search`, `per_page`, `page`)
- `GET /bills/{id}`
- `POST /bills/search` (body: `bill_no`)
- `GET /billing/statistics` (filter: `subdivision_id`)

---

## Admin Endpoints (auth:sanctum + admin)

### Companies
- `GET /companies/admin`
- `GET /companies/admin/{id}`
- `POST /companies/admin`
- `PUT /companies/admin/{id}`
- `DELETE /companies/admin/{id}`

### Subdivisions
- `GET /subdivisions/admin` (filter: `company_id`, `per_page`)
- `GET /subdivisions/admin/{id}`
- `POST /subdivisions/admin`
- `PUT /subdivisions/admin/{id}`
- `DELETE /subdivisions/admin/{id}`

### Tariffs
- `GET /tariffs` (filter: `category`, `per_page`)
- `GET /tariffs/{id}`
- `POST /tariffs`
- `PUT /tariffs/{id}`
- `DELETE /tariffs/{id}`

### Users
- `GET /users` (filters: `role`, `active`, `search`, `per_page`, `page`)
- `GET /users/{id}`
- `POST /users`
- `PUT /users/{id}`
- `DELETE /users/{id}`

Create user body:
- `name`, `username (unique)`, `password (min 8)`, `role (admin|user|ls|sdc|ro)`
- optional: `email (unique)`, `is_active`

---

## Notes
- RBAC: admin-only routes are grouped under `admin` middleware; fine-grained policies can be added per model.
- Validation: all write endpoints validate input; errors return 422 with messages.
- Rate limiting: consider enabling for public submission and tracking endpoints in production.


