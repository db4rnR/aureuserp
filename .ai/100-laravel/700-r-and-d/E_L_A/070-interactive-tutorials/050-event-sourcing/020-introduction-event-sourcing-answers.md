<!-- filepath: /Users/s-a-c/nc/PhpstormProjects/ela-docs/docs/E_L_A/070-interactive-tutorials/050-event-sourcing/020-introduction-event-sourcing-answers.md -->
# Answer Key: Introduction to Event Sourcing in ELA

**Version:** 0.1.0
**Date:** 2025-05-22
**Author:** GitHub Copilot
**Status:** Draft

---

<details>
<summary>Table of Contents</summary>

- [Answer Key: Introduction to Event Sourcing in ELA](#answer-key-introduction-to-event-sourcing-in-ela)
  - [1. Exercise 1: Core Concepts](#1-exercise-1-core-concepts)
  - [2. Exercise 2: Identifying Components](#2-exercise-2-identifying-components)
  - [3. Exercise 3: Why Bother?](#3-exercise-3-why-bother)
  - [4. Quiz Answers](#4-quiz-answers)
    - [4.1. Question 1](#41-question-1)
    - [4.2. Question 2](#42-question-2)
    - [4.3. Question 3](#43-question-3)
  - [5. Related Documents](#5-related-documents)
  - [6. Version History](#6-version-history)

</details>

## 1. Exercise 1: Core Concepts

**Task:** Based on the [Event Sourcing Summary](../../../event-sourcing-summary.md) (Section 1. Overview), in your own words, what is the fundamental difference between how a traditional application stores data versus how an Event-Sourced application like ELA stores data?

**Solution:**
A traditional application typically stores only the latest, or current, state of data. If a piece of data changes, the old value is overwritten. In contrast, an Event-Sourced application like ELA stores a full sequence of all changes (Events) that have ever occurred. The current state is derived by replaying these events.

## 2. Exercise 2: Identifying Components

**Task:** The [Event Sourcing Implementation Guide](../../../event-sourcing-guide.md) (Section 1.3 Key Concepts and Section 2.1 Event Sourcing Components) mentions several key components. List and briefly describe three of these that are central to how ELA processes changes and derives state.

**Solution:**
1.  **Aggregates:** These are domain objects (like `UserAggregate` or `PostAggregate`) that handle commands (user intentions like "register user" or "publish post") and decide if an event should be created. They apply business rules.
2.  **Domain Events:** These are immutable facts about something that happened (e.g., `UserRegistered`, `PostPublished`). They contain the data related to the change. Once an event occurs, it cannot be changed.
3.  **Projectors:** These components listen to events and build or update "Read Models." Read Models are optimized views of data (like a user list or a post detail page) that the application uses for display.

## 3. Exercise 3: Why Bother?

**Task:** The [Event Sourcing Implementation Guide](../../../event-sourcing-guide.md) (Section 1.2 Benefits of Event Sourcing) lists several advantages. Choose two benefits that you think are particularly impactful for a complex application like ELA and explain why.

**Solution (Example - other valid answers based on the document are acceptable):**
1.  **Complete Audit Trail:** For ELA, which manages user data, team collaboration, and content, having a complete history of every change is invaluable for security, debugging, and understanding data evolution. If something unexpected happens, you can trace back exactly what led to it.
2.  **Temporal Queries:** The ability to determine the state of the system (or a specific entity like a User or Team) at *any point in time* is incredibly powerful. For ELA, this could mean easily implementing features like "view post history," understanding team membership changes over time, or debugging issues by seeing what the data looked like before a problem occurred.

## 4. Quiz Answers

### 4.1. Question 1

In ELA, what is primarily responsible for handling a user's command (e.g., `CreateTeam`) and deciding to record an event (e.g., `TeamCreated`)?

**Answer:** (c) An Aggregate

**Explanation:** Aggregates receive commands, apply business logic, and if valid, record events. (See [Event Sourcing Implementation Guide](../../../event-sourcing-guide.md) Section 2.1 & 3.2).

### 4.2. Question 2

True or False: In ELA's Event Sourcing model, once an event like `UserProfileUpdated` is stored, it can be modified by an Administrator if the update was incorrect.

**Answer:** False

**Explanation:** Events are immutable. If a mistake is made, a new compensating event would be recorded to correct the state. (See [Event Sourcing Implementation Guide](../../../event-sourcing-guide.md) Section 1.3 - Domain Events are immutable).

### 4.3. Question 3

Which component in ELA's Event Sourcing architecture is responsible for creating and updating the data views (Read Models) that are shown to users in the interface?

**Answer:** (d) Projectors

**Explanation:** Projectors listen to events and build/update read models. (See [Event Sourcing Implementation Guide](../../../event-sourcing-guide.md) Section 1.3 & 3.4).

## 5. Related Documents

- [010-introduction-event-sourcing.md](./010-introduction-event-sourcing.md) - The main tutorial document.
- [../../../event-sourcing-summary.md](../../../event-sourcing-summary.md) - Event Sourcing Summary.
- [../../../event-sourcing-guide.md](../../../event-sourcing-guide.md) - Event Sourcing Implementation Guide.

---

## 6. Version History

| Version | Date       | Changes                                      | Author          |
|---------|------------|----------------------------------------------|-----------------|
| 0.1.0   | 2025-05-22 | Initial draft of the answer key.             | GitHub Copilot  |
