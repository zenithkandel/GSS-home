# LifeLine API Documentation

> Emergency Response System REST API for LoRa mesh network communication

**Base URL:** `/API/`

---

## Table of Contents

- [Response Format](#response-format)
- [Authentication](#authentication)
  - [Login](#auth-login)
  - [Check Session](#auth-check-session)
  - [Logout](#auth-logout)
- [Devices](#devices)
  - [Create Device](#devices-create)
  - [Read Device(s)](#devices-read)
  - [Update Device](#devices-update)
  - [Delete Device](#devices-delete)
- [Messages](#messages)
  - [Create Message](#messages-create)
  - [Read Message(s)](#messages-read)
  - [Update Message](#messages-update)
  - [Delete Message](#messages-delete)
- [Help Resources](#help-resources)
  - [Create Help](#helps-create)
  - [Read Help(s)](#helps-read)
  - [Update Help](#helps-update)
  - [Delete Help](#helps-delete)
- [Email Receivers](#email-receivers)
  - [Create Email](#emails-create)
  - [Read Email(s)](#emails-read)
  - [Update Email](#emails-update)
  - [Delete Email](#emails-delete)
- [Index Mappings](#index-mappings)
  - [Create Index](#indexes-create)
  - [Read Index(es)](#indexes-read)
  - [Update Index](#indexes-update)
  - [Delete Index](#indexes-delete)
- [FCM Tokens](#fcm-tokens)
  - [Register Token](#fcm-register)

---

## Response Format

All API responses follow this JSON structure:

```json
{
  "success": true,
  "data": { ... },
  "message": "Operation successful",
  "timestamp": "2026-01-21 12:00:00"
}
```

---

## Authentication

### Auth Login

**POST** `/auth/login.php`

Authenticates a user and starts a session.

**Body Parameters:**

- `email` (string, required): User email
- `password` (string, required): User password
- `remember` (boolean, optional): Remember me option

### Auth Check Session

**GET** `/auth/check.php`

Verifies if the current user is authenticated and returns user details.

### Auth Logout

**POST** `/auth/logout.php` (or GET)

Destroys the current user session and clears cookies.

---

## Devices

### Devices Create

**POST** `/Create/device.php`

Creates a new device in the mesh network.

**Body Parameters:**

- `LID` (int, required): Location ID (relates to Index Mappings)
- `device_name` (string, optional): Human-readable name
- `status` (string, optional): 'active', 'inactive', 'maintenance'. Default: 'active'

### Devices Read

**GET** `/Read/device.php`

Retrieves device(s) from the database.

**Query Parameters:**

- `id` (int, optional): Get specific device by ID
- `status` (string, optional): Filter by status
- `lid` (int, optional): Filter by Location ID

**Returns:** Device object(s). If specific ID requested, includes decoded location name.

### Devices Update

**PUT** `/Update/device.php`

Updates an existing device.

**Body Parameters:**

- `DID` (int, required): Device ID
- `device_name` (string, optional)
- `LID` (int, optional)
- `status` (string, optional): 'active', 'inactive', 'maintenance'
- `last_ping` (timestamp, optional)

### Devices Delete

**DELETE** `/Delete/device.php`

Deletes a device. Messages associated with the device will also be deleted (cascade).

**Query Parameters:**

- `id` (int, required): Device ID

---

## Messages

### Messages Create

**POST** `/Create/message.php`

Creates a new emergency message. Triggered by LoRa gateway.

**Body Parameters:**

- `DID` (int, required): Device ID
- `message_code` (int, required): Code representing the message type
- `RSSI` (int, optional): Signal strength

### Messages Read

**GET** `/Read/message.php`

Retrieves emergency message(s).

**Query Parameters:**

- `id` (int, optional): Get specific message by MID
- `did` (int, optional): Filter by Device ID
- `lid` (int, optional): Filter by Location ID
- `message_code` (int, optional): Filter by message code
- `from` (date, optional): Start date
- `to` (date, optional): End date
- `status` (string, optional): 'active' (active/pending) or 'resolved'
- `page` (int, optional): Page number (default 1)
- `limit` (int, optional): Items per page (default 50, max 100)

**Returns:** Message object(s) with decoded `message_text` and `location_name`.

### Messages Update

**PUT** `/Update/message.php`

Updates an existing message (e.g. status or details).

**Body Parameters:**

- `MID` (int, required): Message ID
- `RSSI` (int, optional)
- `message_code` (int, optional)

### Messages Delete

**DELETE** `/Delete/message.php`

Deletes a message. Primarily for test data cleanup.

**Query Parameters:**

- `id` (int, required): Message ID

---

## Help Resources

### Helps Create

**POST** `/Create/helps.php`

Creates a new help resource (responder/service).

**Body Parameters:**

- `name` (string, required): Responder name
- `contact` (string, required): Phone number or contact info
- `eta` (string, optional): Estimated time of arrival info
- `status` (string, optional): 'available', 'dispatched', 'busy'. Default: 'available'
- `location` (string, optional): Current location
- `for_messages` (array|string, optional): List of message codes this resource handles (e.g. `[1,2]` or `"1,2"`)

### Helps Read

**GET** `/Read/helps.php`

Retrieves help resource(s).

**Query Parameters:**

- `id` (int, optional): Get specific help resource by HID
- `status` (string, optional): Filter by status
- `type` (string, optional): Filter by type (if applicable)

### Helps Update

**PUT** `/Update/helps.php`

Updates an existing help resource.

**Body Parameters:**

- `HID` (int, required): Help ID
- `name` (string, optional)
- `contact` (string, optional)
- `eta` (string, optional)
- `status` (string, optional): 'available', 'dispatched', 'busy'
- `location` (string, optional)
- `for_messages` (array|string, optional)

### Helps Delete

**DELETE** `/Delete/helps.php`

Deletes a help resource.

**Query Parameters:**

- `id` (int, required): Help ID

---

## Email Receivers

### Emails Create

**POST** `/Create/emails.php`

Adds a new email recipient for emergency alerts.

**Body Parameters:**

- `email` (string, required): Valid email address

### Emails Read

**GET** `/Read/emails.php`

Retrieves email recipient(s).

**Query Parameters:**

- `sn` (int, optional): Get specific email by Serial Number (SN)

### Emails Update

**PUT/PATCH** `/Update/emails.php`

Updates an existing email address.

**Body Parameters:**

- `sn` (int, required): Serial Number
- `email` (string, required): New email address

### Emails Delete

**DELETE** `/Delete/emails.php`

Removes an email recipient.

**Body Parameters:** (Note: this endpoint uses JSON body, not query params)

- `sn` (int, required): Serial Number to delete

---

## Index Mappings

Used to decode integer codes from LoRa packets into meaningful strings (locations, messages, etc.).

### Indexes Create

**POST** `/Create/index.php`

Creates a new index mapping type.

**Body Parameters:**

- `type` (string, required): 'location', 'message', 'help', 'status'
- `mapping` (object, required): JSON object mapping codes to strings (e.g., `{"1": "Main St"}`)
- `description` (string, optional)

### Indexes Read

**GET** `/Read/index.php`

Retrieves index mapping(s) or decodes a code.

**Query Parameters:**

- `type` (string, optional): Filter by type
- `id` (int, optional): Get by Index ID (IID)
- `decode` (boolean, optional): set `true` to decode mode
  - Requires `type` and `code` parameters when `decode=true`

### Indexes Update

**PUT** `/Update/index.php`

Updates an existing index mapping.

**Body Parameters:**

- `IID` (int) OR `type` (string): Required to identify the index
- `mapping` (object, optional): Replace entire mapping
- `add` (object, optional): Add or update specific codes
- `remove` (array, optional): Remove specific codes

### Indexes Delete

**DELETE** `/Delete/index.php`

Deletes an index mapping. Warning: breaks decoding.

**Query Parameters:**

- `id` (int, optional): Index ID
- `type` (string, optional): Index Type (required if id not provided)

---

## FCM Tokens

### FCM Register

**POST** `/Create/fcm_token.php`

Saves or updates FCM tokens for push notifications.

**Body Parameters:**

- `token` (string, required): The Firebase Cloud Messaging token
- `user_id` (int, optional): Associate token with a specific user ID
