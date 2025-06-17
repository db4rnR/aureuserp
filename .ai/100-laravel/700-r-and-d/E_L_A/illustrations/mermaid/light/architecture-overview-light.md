# Architecture Overview (Light Mode)

**Version:** 1.1.0
**Date:** 2025-05-20
**Author:** AI Assistant
**Status:** Updated

## Description

This diagram illustrates the high-level architecture of the Enhanced Laravel Application, showing the relationships between different components from the client layer through to the infrastructure layer.

## Diagram

```mermaid
%%{init: {'theme': 'default', 'themeVariables': {
  'primaryColor': '#f5f5f5',
  'primaryTextColor': '#333333',
  'primaryBorderColor': '#666666',
  'lineColor': '#444444',
  'secondaryColor': '#f0f0f0',
  'tertiaryColor': '#ffffff',
  'fontFamily': 'Arial, sans-serif',
  'fontSize': '16px'
}}}%%
flowchart TB
    subgraph Client ["Client Layer"]
        Browser["Web Browser"]
        MobileApp["Mobile App"]
        API["API Clients"]
    end

    subgraph Web ["Web Layer"]
        FrankenPHP["FrankenPHP"]
        Laravel["Laravel 12"]
        Livewire["Livewire/Volt"]
        Filament["Filament Admin"]
    end

    subgraph Application ["Application Layer"]
        Controllers["Controllers"]
        Commands["Commands"]
        Queries["Queries"]
        Events["Events"]
        Jobs["Jobs"]
    end

    subgraph Domain ["Domain Layer"]
        Models["Models"]
        Services["Services"]
        Repositories["Repositories"]
        Policies["Policies"]
    end

    subgraph Infrastructure ["Infrastructure Layer"]
        Database["PostgreSQL"]
        Cache["Redis Cache"]
        Queue["Redis Queue"]
        Storage["S3 Storage"]
        Search["Meilisearch"]
    end

    Browser --> FrankenPHP
    MobileApp --> FrankenPHP
    API --> FrankenPHP

    FrankenPHP --> Laravel
    Laravel --> Livewire
    Laravel --> Filament

    Livewire --> Controllers
    Filament --> Controllers

    Controllers --> Commands
    Controllers --> Queries

    Commands --> Events
    Commands --> Models
    Commands --> Services

    Queries --> Models
    Queries --> Repositories

    Services --> Models
    Services --> Repositories
    Services --> Policies

    Models --> Database
    Repositories --> Database

    Events --> Jobs
    Jobs --> Services

    Services --> Cache
    Services --> Queue
    Services --> Storage
    Services --> Search

```

## Alternative Text

This architecture diagram shows the layered structure of the Enhanced Laravel Application. It consists of:

1. **Client Layer**: Contains Web Browser, Mobile App, and API Clients that interact with the server.
2. **Web Layer**: Contains FrankenPHP, Laravel 12, Livewire/Volt, and Filament Admin components that handle HTTP requests and user interfaces.
3. **Application Layer**: Contains Controllers, Commands, Queries, Events, and Jobs that implement application logic.
4. **Domain Layer**: Contains Models, Services, Repositories, and Policies that implement domain logic.
5. **Infrastructure Layer**: Contains PostgreSQL database, Redis Cache, Redis Queue, S3 Storage, and Meilisearch that provide infrastructure support.

The diagram shows connections between components, illustrating how data flows through the system. For example, Web Browsers send requests to FrankenPHP, which forwards them to Laravel. Controllers handle these requests and use Commands and Queries to interact with the domain layer. The domain layer interacts with the infrastructure layer to persist and retrieve data.

## Version History

<div style="background-color:#f0f0f0; padding:15px; border-radius:5px; border: 1px solid #d0d0d0; margin:10px 0;">
<h4 style="margin-top: 0; color: #111;">Document History</h4>

\n<details>\n<summary>Table Details</summary>\n\n| Version | Date | Changes | Author |
| --- | --- | --- | --- |
| 1.1.0 | 2025-05-20 | Updated formatting for high contrast and accessibility, added metadata and alternative text | AI Assistant |
| 1.0.0 | 2025-05-10 | Initial version | AI Assistant |
\n</details>\n
</div>
