# Post Aggregate States (Dark Mode)

```mermaid
%%{init: {'theme': 'dark', 'themeVariables': { 'primaryColor': '#2c3e50', 'primaryTextColor': '#ecf0f1', 'primaryBorderColor': '#7f8c8d', 'lineColor': '#ecf0f1', 'secondaryColor': '#34495e', 'tertiaryColor': '#282c34' }}}%%
stateDiagram-v2
    [*] --> Draft: PostCreatedEvent
    Draft --> Published: PostPublishedEvent
    Published --> Draft: PostUnpublishedEvent
    Draft --> Archived: PostArchivedEvent
    Published --> Archived: PostArchivedEvent
    Archived --> Draft: PostRestoredEvent
    Draft --> Deleted: PostDeletedEvent
    Published --> Deleted: PostDeletedEvent
    Archived --> Deleted: PostDeletedEvent
    Draft --> PendingReview: PostSubmittedForReviewEvent
    PendingReview --> Draft: PostRejectedEvent
    PendingReview --> Published: PostApprovedEvent
    Draft --> Scheduled: PostScheduledEvent
    Scheduled --> Published: PostPublishedEvent
    Scheduled --> Draft: PostUnscheduledEvent
    
    %% State styling with classes
    classDef draftState fill:#2980B9,stroke:#ecf0f1,color:white
    classDef pendingReviewState fill:#F39C12,stroke:#ecf0f1,color:black
    classDef publishedState fill:#27AE60,stroke:#ecf0f1,color:black
    classDef scheduledState fill:#8E44AD,stroke:#ecf0f1,color:white
    classDef archivedState fill:#7F8C8D,stroke:#ecf0f1,color:white
    classDef deletedState fill:#C0392B,stroke:#ecf0f1,color:white
    
    class Draft draftState
    class PendingReview pendingReviewState
    class Published publishedState
    class Scheduled scheduledState
    class Archived archivedState
    class Deleted deletedState
    
    %% Notes
    note right of Draft
        Post is in draft mode
        Only visible to author and team members
        Can be edited freely
    end note
    
    note right of PendingReview
        Post is awaiting review
        Cannot be edited while in review
        Reviewers can approve or reject
    end note
    
    note right of Published
        Post is published and visible to all
        Changes require a new revision
        Appears in feeds and search results
    end note
    
    note right of Scheduled
        Post is scheduled for future publication
        Will automatically publish at scheduled time
        Can be unscheduled to return to draft
    end note
    
    note right of Archived
        Post is archived and not visible in normal views
        Still accessible via direct link
        Can be restored to draft state
    end note
    
    note right of Deleted
        Post has been permanently deleted
        Cannot be recovered
        All associated data is soft-deleted
    end note
```
