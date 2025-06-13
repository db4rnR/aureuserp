# Event Catalog

**Version:** 1.0.0
**Date:** 2025-05-24
**Author:** Augment Agent
**Status:** Draft
**Progress:** 10%

---

<details>
<summary>Table of Contents</summary>

- [1. Overview](#1-overview)
- [2. User Management Events](#2-user-management-events)
- [3. Content Management Events](#3-content-management-events)
- [4. Team Management Events](#4-team-management-events)
- [5. Collaboration Events](#5-collaboration-events)
- [6. Related Documents](#6-related-documents)
- [7. Version History](#7-version-history)
</details>


## 1. Overview

This document catalogs all events used in the event sourcing system of the Enhanced Laravel Application (ELA). Events represent facts that have occurred in the system and are stored in the event store.

Each event is documented with its properties, purpose, and the projectors and reactors that handle it.


## 2. User Management Events

### 2.1. UserRegistered

**Purpose:** Indicates that a new user has been registered

**Properties:**
- userId (UUID): Unique identifier for the user
- email (string): User's email address
- name (string): User's full name
- registeredAt (datetime): Time of registration

**Handled By:**
- UserProjector: Creates a user record in the read model
- UserRegistrationReactor: Sends welcome email

**Produced By:**
- RegisterUser command


### 2.2. UserProfileUpdated

**Purpose:** Indicates that a user's profile information has been updated

**Properties:**
- userId (UUID): Unique identifier for the user
- name (string, optional): User's new full name
- avatarUrl (string, optional): URL to user's avatar image
- updatedAt (datetime): Time of update

**Handled By:**
- UserProjector: Updates user record in the read model

**Produced By:**
- UpdateUserProfile command


### 2.3. UserEmailChanged

**Purpose:** Indicates that a user's email address has been changed

**Properties:**
- userId (UUID): Unique identifier for the user
- oldEmail (string): Previous email address
- newEmail (string): New email address
- changedAt (datetime): Time of change

**Handled By:**
- UserProjector: Updates user email in the read model
- EmailChangeReactor: Sends confirmation email to both addresses

**Produced By:**
- ChangeUserEmail command


## 3. Content Management Events

### 3.1. DocumentCreated

**Purpose:** Indicates that a new document has been created

**Properties:**
- documentId (UUID): Unique identifier for the document
- title (string): Document title
- content (string): Document content
- authorId (UUID): User ID of the author
- teamId (UUID): Team ID the document belongs to
- createdAt (datetime): Time of creation

**Handled By:**
- DocumentProjector: Creates document record in the read model
- ActivityLogReactor: Logs document creation activity

**Produced By:**
- CreateDocument command


### 3.2. DocumentUpdated

**Purpose:** Indicates that an existing document has been updated

**Properties:**
- documentId (UUID): Unique identifier for the document
- title (string, optional): New document title
- content (string, optional): New document content
- editorId (UUID): User ID of the editor making changes
- version (integer): New version number
- updatedAt (datetime): Time of update

**Handled By:**
- DocumentProjector: Updates document in the read model
- DocumentVersionProjector: Creates version history record
- ActivityLogReactor: Logs document update activity

**Produced By:**
- UpdateDocument command


### 3.3. DocumentArchived

**Purpose:** Indicates that a document has been archived

**Properties:**
- documentId (UUID): Unique identifier for the document
- userId (UUID): User ID of the person archiving the document
- reason (string, optional): Reason for archiving
- archivedAt (datetime): Time of archiving

**Handled By:**
- DocumentProjector: Updates document status in the read model
- ActivityLogReactor: Logs document archiving activity
- TeamNotificationReactor: Notifies team members of archiving

**Produced By:**
- ArchiveDocument command


## 4. Team Management Events

### 4.1. TeamCreated

**Purpose:** Indicates that a new team has been created

**Properties:**
- teamId (UUID): Unique identifier for the team
- name (string): Team name
- description (string, optional): Team description
- creatorId (UUID): User ID of the team creator
- createdAt (datetime): Time of creation

**Handled By:**
- TeamProjector: Creates team record in the read model
- ActivityLogReactor: Logs team creation activity

**Produced By:**
- CreateTeam command


### 4.2. UserAddedToTeam

**Purpose:** Indicates that a user has been added to a team

**Properties:**
- teamId (UUID): Unique identifier for the team
- userId (UUID): User ID added to the team
- role (string): Role of the user in the team
- addedBy (UUID): User ID of the person adding the user
- addedAt (datetime): Time of addition

**Handled By:**
- TeamMembershipProjector: Creates team membership record in the read model
- UserTeamProjector: Updates user's teams in the read model
- TeamNotificationReactor: Notifies team members of new addition
- UserNotificationReactor: Notifies user of team addition

**Produced By:**
- AddUserToTeam command
- CreateTeam command (for the creator)


### 4.3. UserRemovedFromTeam

**Purpose:** Indicates that a user has been removed from a team

**Properties:**
- teamId (UUID): Unique identifier for the team
- userId (UUID): User ID removed from the team
- removedBy (UUID): User ID of the person removing the user
- reason (string, optional): Reason for removal
- removedAt (datetime): Time of removal

**Handled By:**
- TeamMembershipProjector: Removes team membership record from the read model
- UserTeamProjector: Updates user's teams in the read model
- TeamNotificationReactor: Notifies team members of removal
- UserNotificationReactor: Notifies user of team removal

**Produced By:**
- RemoveUserFromTeam command


## 5. Collaboration Events

### 5.1. CollaborationSessionStarted

**Purpose:** Indicates that a real-time collaboration session has been started

**Properties:**
- sessionId (UUID): Unique identifier for the session
- documentId (UUID): Document ID for collaboration
- initiatorId (UUID): User ID of the session initiator
- startedAt (datetime): Time the session was started

**Handled By:**
- CollaborationSessionProjector: Creates session record in the read model
- DocumentProjector: Updates document status to 'in collaboration'
- ActivityLogReactor: Logs session start activity

**Produced By:**
- StartCollaborationSession command


### 5.2. UserJoinedCollaborationSession

**Purpose:** Indicates that a user has joined a collaboration session

**Properties:**
- sessionId (UUID): Unique identifier for the session
- userId (UUID): User ID of the person joining
- joinedAt (datetime): Time the user joined

**Handled By:**
- CollaborationSessionProjector: Updates session participants in the read model
- SessionNotificationReactor: Notifies other participants of new user

**Produced By:**
- JoinCollaborationSession command


### 5.3. ChatMessageSent

**Purpose:** Indicates that a chat message has been sent in a collaboration session

**Properties:**
- messageId (UUID): Unique identifier for the message
- sessionId (UUID): Session ID where the message was sent
- senderId (UUID): User ID of the message sender
- content (string): Message content
- timestamp (datetime): Time the message was sent

**Handled By:**
- ChatMessageProjector: Creates message record in the read model
- RealTimeNotificationReactor: Broadcasts message to session participants

**Produced By:**
- SendChatMessage command


### 5.4. CollaborationSessionEnded

**Purpose:** Indicates that a collaboration session has ended

**Properties:**
- sessionId (UUID): Unique identifier for the session
- endedBy (UUID): User ID of the person ending the session
- duration (integer): Duration of the session in seconds
- endedAt (datetime): Time the session ended

**Handled By:**
- CollaborationSessionProjector: Updates session status in the read model
- DocumentProjector: Updates document status to 'not in collaboration'
- ActivityLogReactor: Logs session end activity
- SessionSummaryReactor: Generates and sends session summary to participants

**Produced By:**
- EndCollaborationSession command


## 6. Related Documents

- [000-index.md](000-index.md) - Event Sourcing Guides Index
- [010-event-sourcing-guide.md](010-event-sourcing-guide.md) - Comprehensive guide to event sourcing
- [020-event-sourcing-summary.md](020-event-sourcing-summary.md) - Summary of event sourcing concepts
- [030-command-catalog.md](030-command-catalog.md) - Catalog of commands

## 7. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-24 | Initial version | Augment Agent |
