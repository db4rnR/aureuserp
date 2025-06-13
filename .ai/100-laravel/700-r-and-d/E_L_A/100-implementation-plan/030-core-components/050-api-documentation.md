# Phase 0: API Documentation

**Version:** 1.0.0
**Date:** 2025-05-19
**Author:** AI Assistant
**Status:** New
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Authentication](#authentication)
  - [Obtaining an API Token](#obtaining-an-api-token)
  - [Using API Tokens](#using-api-tokens)
- [API Endpoints](#api-endpoints)
  - [Teams](#teams)
  - [Users](#users)
  - [Posts](#posts)
  - [Comments](#comments)
  - [Todos](#todos)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [Rate Limiting](#rate-limiting)
- [Versioning](#versioning)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides comprehensive documentation for the Enhanced Laravel Application (ELA) API. The API allows developers to programmatically interact with the ELA, enabling integration with other systems and the development of custom clients.

## Authentication

The ELA API uses Laravel Sanctum for API token authentication. All API requests must include a valid API token.

### Obtaining an API Token

API tokens can be obtained through the following endpoint:

```text
POST /api/tokens/create
```javascript
**Request Body:**

```json
{
  "email": "user@example.com",
  "password": "password",
  "device_name": "My API Client"
}
```javascript
**Response:**

```json
{
  "token": "1|5Uc9vV6zXmJH8jKLM7NoPqRsTuVwXyZ"
}
```javascript
### Using API Tokens

Include the API token in the `Authorization` header of all API requests:

```text
Authorization: Bearer 1|5Uc9vV6zXmJH8jKLM7NoPqRsTuVwXyZ
```sql
## API Endpoints

### Teams

#### List Teams

```text
GET /api/teams
```sql
**Query Parameters:**

- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)
- `sort`: Sort field (default: 'created_at')
- `order`: Sort order ('asc' or 'desc', default: 'desc')

**Response:**

```json
{
  "data": [
    {
      "id": "1234567890123456",
      "name": "Engineering",
      "slug": "engineering",
      "description": "Engineering team",
      "created_at": "2025-05-19T12:00:00Z",
      "updated_at": "2025-05-19T12:00:00Z"
    }
  ],
  "links": {
    "first": "https://example.com/api/teams?page=1",
    "last": "https://example.com/api/teams?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "https://example.com/api/teams",
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```javascript
#### Get Team

```text
GET /api/teams/{id}
```javascript
**Response:**

```json
{
  "data": {
    "id": "1234567890123456",
    "name": "Engineering",
    "slug": "engineering",
    "description": "Engineering team",
    "created_at": "2025-05-19T12:00:00Z",
    "updated_at": "2025-05-19T12:00:00Z"
  }
}
```text
### Users

Similar endpoints are available for users, posts, comments, and todos. See the full API documentation for details.

## Response Format

All API responses follow a consistent format:

- Successful responses return a `data` object containing the requested resource(s)
- Collection responses include pagination metadata and links
- Error responses include an `errors` object with error details

## Error Handling

The API uses standard HTTP status codes to indicate the success or failure of requests:

- `200 OK`: The request was successful
- `201 Created`: A resource was successfully created
- `400 Bad Request`: The request was malformed or invalid
- `401 Unauthorized`: Authentication failed
- `403 Forbidden`: The authenticated user does not have permission to access the requested resource
- `404 Not Found`: The requested resource was not found
- `422 Unprocessable Entity`: Validation errors
- `429 Too Many Requests`: Rate limit exceeded
- `500 Internal Server Error`: An error occurred on the server

## Rate Limiting

API requests are subject to rate limiting to prevent abuse. The default rate limit is 60 requests per minute per user. Rate limit information is included in the response headers:

- `X-RateLimit-Limit`: The maximum number of requests allowed per minute
- `X-RateLimit-Remaining`: The number of requests remaining in the current minute
- `X-RateLimit-Reset`: The time at which the rate limit will reset (Unix timestamp)

## Versioning

The API is versioned to ensure backward compatibility. The current version is v1. The version can be specified in the URL:

```text
/api/v1/teams
```

## Related Documents

- [Sanctum Setup](060-configuration/050-sanctum-setup.md) - For API authentication setup
- [Event Sourcing API Integration](100-event-sourcing/000-index.md) - For event sourcing API integration

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-19 | Initial version | AI Assistant |
