# Command Catalog

**Version:** 1.0.0
**Date:** 2025-05-24
**Author:** Augment Agent
**Status:** Draft
**Progress:** 10%

---

<details>
<summary>Table of Contents</summary>

- [1. Overview](#1-overview)
- [2. User Management Commands](#2-user-management-commands)
- [3. Content Management Commands](#3-content-management-commands)
- [4. Team Management Commands](#4-team-management-commands)
- [5. Collaboration Commands](#5-collaboration-commands)
- [6. Related Documents](#6-related-documents)
- [7. Version History](#7-version-history)
</details>


## 1. Overview

This document catalogs all commands used in the event sourcing system of the Enhanced Laravel Application (ELA). Commands represent intentions to change the system state and are handled by aggregates to produce events.

Each command is documented with its properties, validation rules, and the events it may produce.


## 2. User Management Commands

### 2.1. RegisterUser

**Purpose:** Register a new user in the system

**Properties:**
- userId (UUID): Unique identifier for the user
- email (string): User's email address
- name (string): User's full name
- password (string): Hashed password


**Validation:**
- Email must be valid and unique
- Name must not be empty
- Password must meet security requirements

**Produces Events:**
- UserRegistered


### 2.2. UpdateUserProfile

**Purpose:** Update a user's profile information

**Properties:**
- userId (UUID): Unique identifier for the user
- name (string, optional): User's new full name
- avatarUrl (string, optional): URL to user's avatar image

**Validation:**
- User must exist
- At least one optional field must be provided

**Produces Events:**
- UserProfileUpdated


### 2.3. ChangeUserEmail

**Purpose:** Change a user's email address

**Properties:**
- userId (UUID): Unique identifier for the user
- newEmail (string): New email address

**Validation:**
- User must exist
- Email must be valid and unique

**Produces Events:**
- UserEmailChanged


## 3. Content Management Commands

### 3.1. CreateDocument

**Purpose:** Create a new document

**Properties:**
- documentId (UUID): Unique identifier for the document
- title (string): Document title
- content (string): Document content
- authorId (UUID): User ID of the author
- teamId (UUID): Team ID the document belongs to

**Validation:**
- Author must exist and be active
- Team must exist and author must be a member
- Title must not be empty

**Produces Events:**
- DocumentCreated


### 3.2. UpdateDocument

**Purpose:** Update an existing document

**Properties:**
- documentId (UUID): Unique identifier for the document
- title (string, optional): New document title
- content (string, optional): New document content
- editorId (UUID): User ID of the editor making changes

**Validation:**
- Document must exist
- Editor must have permission to edit the document
- At least one of title or content must be provided

**Produces Events:**
- DocumentUpdated


### 3.3. ArchiveDocument

**Purpose:** Archive an existing document

**Properties:**
- documentId (UUID): Unique identifier for the document
- userId (UUID): User ID of the person archiving the document
- reason (string, optional): Reason for archiving

**Validation:**
- Document must exist and not already be archived
- User must have permission to archive the document

**Produces Events:**
- DocumentArchived


## 4. Team Management Commands

### 4.1. CreateTeam

**Purpose:** Create a new team

**Properties:**
- teamId (UUID): Unique identifier for the team
- name (string): Team name
- description (string, optional): Team description
- creatorId (UUID): User ID of the team creator

**Validation:**
- Creator must exist and be active
- Team name must be unique
- Team name must not be empty

**Produces Events:**
- TeamCreated
- UserAddedToTeam (for the creator)


### 4.2. AddUserToTeam

**Purpose:** Add a user to a team

**Properties:**
- teamId (UUID): Unique identifier for the team
- userId (UUID): User ID to add to the team
- role (string): Role of the user in the team
- addedBy (UUID): User ID of the person adding the user

**Validation:**
- Team must exist
- User must exist and be active
- User must not already be a member of the team
- The person adding must have permission to add users

**Produces Events:**
- UserAddedToTeam


### 4.3. RemoveUserFromTeam

**Purpose:** Remove a user from a team

**Properties:**
- teamId (UUID): Unique identifier for the team
- userId (UUID): User ID to remove from the team
- removedBy (UUID): User ID of the person removing the user
- reason (string, optional): Reason for removal

**Validation:**
- Team must exist
- User must be a member of the team
- The person removing must have permission to remove users
- Cannot remove the last admin of a team

**Produces Events:**
- UserRemovedFromTeam


## 5. Collaboration Commands

### 5.1. StartCollaborationSession

**Purpose:** Start a real-time collaboration session

**Properties:**
- sessionId (UUID): Unique identifier for the session
- documentId (UUID): Document ID for collaboration
- initiatorId (UUID): User ID of the session initiator

**Validation:**
- Document must exist
- Initiator must have access to the document

**Produces Events:**
- CollaborationSessionStarted


### 5.2. JoinCollaborationSession

**Purpose:** Join an existing collaboration session

**Properties:**
- sessionId (UUID): Unique identifier for the session
- userId (UUID): User ID of the person joining

**Validation:**
- Session must exist and be active
- User must have access to the document

**Produces Events:**
- UserJoinedCollaborationSession


### 5.3. SendChatMessage

**Purpose:** Send a chat message in a collaboration session

**Properties:**
- messageId (UUID): Unique identifier for the message
- sessionId (UUID): Session ID where the message is sent
- senderId (UUID): User ID of the message sender
- content (string): Message content
- timestamp (datetime): Time the message was sent

**Validation:**
- Session must exist and be active
- Sender must be a participant in the session
- Content must not be empty

**Produces Events:**
- ChatMessageSent


### 5.4. EndCollaborationSession

**Purpose:** End a collaboration session

**Properties:**
- sessionId (UUID): Unique identifier for the session
- endedBy (UUID): User ID of the person ending the session

**Validation:**
- Session must exist and be active
- User must have permission to end the session

**Produces Events:**
- CollaborationSessionEnded


## 6. Related Documents

- [000-index.md](000-index.md) - Event Sourcing Guides Index
- [010-event-sourcing-guide.md](010-event-sourcing-guide.md) - Comprehensive guide to event sourcing
- [020-event-sourcing-summary.md](020-event-sourcing-summary.md) - Summary of event sourcing concepts
- [040-event-catalog.md](040-event-catalog.md) - Catalog of events

## 7. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-24 | Initial version | Augment Agent |
