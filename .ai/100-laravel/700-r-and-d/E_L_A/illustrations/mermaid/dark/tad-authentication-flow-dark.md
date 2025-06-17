# TAD Authentication Flow (Dark Mode)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
sequenceDiagram
    actor User
    participant Browser
    participant App as Laravel Application
    participant Auth as Authentication Service
    participant MFA as MFA Service
    participant DB as Database

    %% Registration Flow
    User->>Browser: Access Registration Page
    Browser->>App: GET /register
    App->>Browser: Return Registration Form
    User->>Browser: Fill Registration Form
    Browser->>App: POST /register
    App->>Auth: Validate Registration Data
    Auth->>DB: Create User Account
    DB-->>Auth: User Created
    Auth->>App: Return Success
    App->>Browser: Redirect to Email Verification

    %% Login Flow
    User->>Browser: Access Login Page
    Browser->>App: GET /login
    App->>Browser: Return Login Form
    User->>Browser: Enter Credentials
    Browser->>App: POST /login
    App->>Auth: Validate Credentials
    Auth->>DB: Check Credentials
    DB-->>Auth: Credentials Valid
    Auth->>DB: Check MFA Enabled
    DB-->>Auth: MFA Status

    alt MFA Enabled
        Auth->>App: Request MFA Code
        App->>Browser: Show MFA Input Form
        User->>Browser: Enter MFA Code
        Browser->>App: POST MFA Code
        App->>MFA: Validate MFA Code
        MFA-->>App: MFA Valid
    end

    Auth->>App: Create Session
    App->>Browser: Redirect to Dashboard
```
