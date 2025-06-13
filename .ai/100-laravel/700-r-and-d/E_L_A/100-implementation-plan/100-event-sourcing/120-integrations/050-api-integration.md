# Phase 1: Event Sourcing API Integration

**Version:** 1.0.0 **Date:** 2025-05-19 **Author:** AI Assistant **Status:** New **Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [API Architecture](#api-architecture)
  - [RESTful API](#restful-api)
  - [GraphQL API](#graphql-api)
- [Integration with Event Sourcing](#integration-with-event-sourcing)
  - [Command Dispatching](#command-dispatching)
  - [Query Handling](#query-handling)
  - [Event Streaming](#event-streaming)
- [Implementation](#implementation)
  - [RESTful API Controllers](#restful-api-controllers)
  - [GraphQL Schema and Resolvers](#graphql-schema-and-resolvers)
  - [API Resources](#api-resources)
  - [API Authentication](#api-authentication)
- [Example: Team Management API](#example-team-management-api)
  - [RESTful API Implementation](#restful-api-implementation)
  - [GraphQL API Implementation](#graphql-api-implementation)
- [API Documentation](#api-documentation)
  - [OpenAPI Specification](#openapi-specification)
  - [GraphQL Playground](#graphql-playground)
- [Testing API Endpoints](#testing-api-endpoints)
- [Security Considerations](#security-considerations)
- [Performance Considerations](#performance-considerations)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document describes the integration of APIs with event sourcing in the Enhanced Laravel Application (ELA). This
integration enables external systems to interact with the event-sourced entities through RESTful and GraphQL APIs,
providing a consistent and powerful interface for integration.

## API Architecture

### RESTful API

The RESTful API follows standard REST principles:

- **Resources**: API endpoints represent resources (e.g., teams, users, posts)
- **HTTP Methods**: Standard HTTP methods (GET, POST, PUT, DELETE) for CRUD operations
- **Status Codes**: Standard HTTP status codes for responses
- **Content Negotiation**: Support for different content types (JSON, XML)
- **Pagination**: Consistent pagination for collection endpoints
- **Filtering**: Query parameters for filtering collections
- **Sorting**: Query parameters for sorting collections
- **Versioning**: API versioning to ensure backward compatibility

### GraphQL API

The GraphQL API provides a flexible query language:

- **Schema**: Defines the types and operations available
- **Queries**: Retrieve data with specific fields
- **Mutations**: Modify data
- **Subscriptions**: Real-time updates
- **Resolvers**: Map GraphQL operations to application logic
- **Directives**: Add metadata to schema elements

## Integration with Event Sourcing

### Command Dispatching

API endpoints will dispatch commands to the event sourcing system:

1. API request is received
2. Request is validated
3. Command is dispatched to the command bus
4. Command handler retrieves the aggregate and applies the command
5. Aggregate emits events
6. Events are stored and dispatched to projectors and reactors
7. API response is generated based on the command result

### Query Handling

API endpoints will retrieve data from the read models:

1. API request is received
2. Request is validated
3. Query is dispatched to the query bus
4. Query handler retrieves data from the read model
5. API response is generated based on the query result

### Event Streaming

API endpoints will provide access to event streams:

1. API request is received
2. Request is validated
3. Events are retrieved from the event store
4. API response is generated with the events

## Implementation

### RESTful API Controllers

````php
namespace App\Http\Controllers\Api;

use App\CQRS\Commands\CommandBus;
use App\CQRS\Commands\Teams\CreateTeamCommand;
use App\CQRS\Queries\QueryBus;
use App\CQRS\Queries\Teams\GetTeamQuery;
use App\CQRS\Queries\Teams\ListTeamsQuery;
use App\Http\Requests\Api\CreateTeamRequest;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $teams = $this->queryBus->dispatch(new ListTeamsQuery(
            $request->input('page', 1),
            $request->input('per_page', 15),
            $request->input('sort', 'created_at'),
            $request->input('order', 'desc')
        ));

        return TeamResource::collection($teams);
    }

    public function show(string $id): TeamResource
    {
        $team = $this->queryBus->dispatch(new GetTeamQuery($id));

        return new TeamResource($team);
    }

    public function store(CreateTeamRequest $request): TeamResource
    {
        $teamUuid = Str::uuid()->toString();

        $this->commandBus->dispatch(new CreateTeamCommand(
            $teamUuid,
            $request->input('name'),
            $request->input('description')
        ));

        $team = $this->queryBus->dispatch(new GetTeamQuery($teamUuid));

        return new TeamResource($team);
    }

    public function update(UpdateTeamRequest $request, string $id): TeamResource
    {
        $this->commandBus->dispatch(new UpdateTeamCommand(
            $id,
            $request->input('name'),
            $request->input('description')
        ));

        $team = $this->queryBus->dispatch(new GetTeamQuery($id));

        return new TeamResource($team);
    }

    public function destroy(string $id): \Illuminate\Http\Response
    {
        $this->commandBus->dispatch(new DeleteTeamCommand($id));

        return response()->noContent();
    }
}
```bash
### GraphQL Schema and Resolvers

```graphql
type Team {
  id: ID!
  name: String!
  description: String
  members: [User!]!
  createdAt: DateTime!
  updatedAt: DateTime!
}

type Query {
  teams(page: Int, perPage: Int, sort: String, order: String): [Team!]!
  team(id: ID!): Team
}

type Mutation {
  createTeam(input: CreateTeamInput!): Team!
  updateTeam(id: ID!, input: UpdateTeamInput!): Team!
  deleteTeam(id: ID!): Boolean!
  addTeamMember(teamId: ID!, userId: ID!): Team!
  removeTeamMember(teamId: ID!, userId: ID!): Team!
}

input CreateTeamInput {
  name: String!
  description: String
}

input UpdateTeamInput {
  name: String
  description: String
}
```php
```php
namespace App\GraphQL\Mutations;

use App\CQRS\Commands\CommandBus;
use App\CQRS\Commands\Teams\CreateTeamCommand;
use App\CQRS\Queries\QueryBus;
use App\CQRS\Queries\Teams\GetTeamQuery;
use Illuminate\Support\Str;

class CreateTeam
{
    private CommandBus $commandBus;
    private QueryBus $queryBus;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function __invoke($_, array $args)
    {
        $teamUuid = Str::uuid()->toString();

        $this->commandBus->dispatch(new CreateTeamCommand(
            $teamUuid,
            $args['input']['name'],
            $args['input']['description'] ?? null
        ));

        return $this->queryBus->dispatch(new GetTeamQuery($teamUuid));
    }
}
```php
### API Resources

```php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'members_count' => $this->members_count,
            'members' => UserResource::collection($this->whenLoaded('members')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```php
### API Authentication

```php
// config/sanctum.php
return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),
    'expiration' => null,
    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],
];
```php
```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('teams', TeamController::class);
    Route::post('teams/{team}/members', [TeamController::class, 'addMember']);
    Route::delete('teams/{team}/members/{user}', [TeamController::class, 'removeMember']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
```text
## Example: Team Management API

### RESTful API Implementation

#### Create Team

**Request:**

```http
POST /api/teams HTTP/1.1
Host: example.com
Authorization: Bearer 1|5Uc9vV6zXmJH8jKLM7NoPqRsTuVwXyZ
Content-Type: application/json

{
  "name": "Engineering",
  "description": "Engineering team"
}
```javascript
**Response:**

```http
HTTP/1.1 201 Created
Content-Type: application/json

{
  "data": {
    "id": "1234567890123456",
    "name": "Engineering",
    "description": "Engineering team",
    "members_count": 0,
    "created_at": "2025-05-19T12:00:00Z",
    "updated_at": "2025-05-19T12:00:00Z"
  }
}
```text
#### Add Team Member

**Request:**

```http
POST /api/teams/1234567890123456/members HTTP/1.1
Host: example.com
Authorization: Bearer 1|5Uc9vV6zXmJH8jKLM7NoPqRsTuVwXyZ
Content-Type: application/json

{
  "user_id": "9876543210987654"
}
```javascript
**Response:**

```http
HTTP/1.1 200 OK
Content-Type: application/json

{
  "data": {
    "id": "1234567890123456",
    "name": "Engineering",
    "description": "Engineering team",
    "members_count": 1,
    "members": [
      {
        "id": "9876543210987654",
        "name": "John Doe",
        "email": "john@example.com"
      }
    ],
    "created_at": "2025-05-19T12:00:00Z",
    "updated_at": "2025-05-19T12:05:00Z"
  }
}
```text
### GraphQL API Implementation

#### Create Team

**Request:**

```graphql
mutation {
  createTeam(input: {
    name: "Engineering",
    description: "Engineering team"
  }) {
    id
    name
    description
    members {
      id
      name
    }
    createdAt
  }
}
```javascript
**Response:**

```json
{
  "data": {
    "createTeam": {
      "id": "1234567890123456",
      "name": "Engineering",
      "description": "Engineering team",
      "members": [],
      "createdAt": "2025-05-19T12:00:00Z"
    }
  }
}
```php
## API Documentation

### OpenAPI Specification

```yaml
openapi: 3.0.0
info:
  title: Enhanced Laravel Application API
  version: 1.0.0
  description: API for the Enhanced Laravel Application
paths:
  /api/teams:
    get:
      summary: List teams
      parameters:
        - name: page
          in: query
          schema:
            type: integer
            default: 1
        - name: per_page
          in: query
          schema:
            type: integer
            default: 15
      responses:
        '200':
          description: A list of teams
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:
                    type: array
                    items:
                      $ref: '#/components/schemas/Team'
    post:
      summary: Create a team
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                description:
                  type: string
      responses:
        '201':
          description: The created team
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:
                    $ref: '#/components/schemas/Team'
components:
  schemas:
    Team:
      type: object
      properties:
        id:
          type: string
        name:
          type: string
        description:
          type: string
        members_count:
          type: integer
        created_at:
          type: string
          format: date-time
        updated_at:
          type: string
          format: date-time
````

## Related Documents

- [Event Sourcing Implementation](../050-implementation.md)
- [API Documentation](../030-core-components/050-api-documentation.md)
- [CQRS Integration](../130-cqrs-integration.md)
- [Sanctum Setup](../060-configuration/050-sanctum-setup.md)

## Version History

| Version | Date       | Changes         | Author       |
| ------- | ---------- | --------------- | ------------ |
| 1.0.0   | 2025-05-19 | Initial version | AI Assistant |
