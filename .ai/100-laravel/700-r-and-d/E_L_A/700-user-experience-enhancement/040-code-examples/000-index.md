# 1. Code Examples

**Version:** 1.0.0
**Date:** 2025-05-22
**Author:** Augment Agent
**Status:** Planned
**Progress:** 0%

---

<details>
<summary>Table of Contents</summary>

- [1. Code Examples](#1-code-examples)
  - [1.1. Overview](#11-overview)
  - [1.2. Code Example Standards](#12-code-example-standards)
  - [1.3. Available Code Examples](#13-available-code-examples)
    - [1.3.1. Event Sourcing Examples](#131-event-sourcing-examples)
    - [1.3.2. Aggregate Examples](#132-aggregate-examples)
    - [1.3.3. Projector Examples](#133-projector-examples)
    - [1.3.4. Reactor Examples](#134-reactor-examples)
    - [1.3.5. Real-time Event Broadcasting Examples](#135-real-time-event-broadcasting-examples)
  - [1.4. Implementation Status](#14-implementation-status)
  - [1.5. Related Documents](#15-related-documents)
  - [1.6. Version History](#16-version-history)

</details>

## 1.1. Overview

This section provides comprehensive code examples for implementing key features of the Enhanced Laravel Application. These examples demonstrate best practices, include detailed comments and explanations, and provide a solid foundation for implementing the features in your own applications.

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Code Example Objectives</h4>

<p style="color: #444;">The code examples aim to achieve the following objectives:</p>

<ul style="color: #444;">
  <li><strong>Clarity</strong>: Provide clear, well-commented code that is easy to understand</li>
  <li><strong>Completeness</strong>: Include all necessary code for implementing the feature</li>
  <li><strong>Best Practices</strong>: Demonstrate best practices for Laravel and PHP development</li>
  <li><strong>Reusability</strong>: Create code that can be easily adapted for different use cases</li>
  <li><strong>Testability</strong>: Include tests to demonstrate how to test the code</li>
</ul>
</div>

## 1.2. Code Example Standards

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Code Example Standards</h4>

<p style="color: #444;">All code examples follow these standards to ensure consistency and quality:</p>

<ul style="color: #444;">
  <li><strong>PSR-12 Coding Style</strong>: All code follows the PSR-12 coding style</li>
  <li><strong>PHP 8.1+ Features</strong>: Code uses PHP 8.1+ features where appropriate</li>
  <li><strong>Laravel Best Practices</strong>: Code follows Laravel best practices</li>
  <li><strong>Comprehensive Comments</strong>: Code includes detailed comments explaining key concepts</li>
  <li><strong>Type Hints</strong>: Code uses type hints for parameters and return values</li>
  <li><strong>Error Handling</strong>: Code includes proper error handling</li>
  <li><strong>Tests</strong>: Code includes tests demonstrating how to test the feature</li>
</ul>

<p style="color: #444;">For more details on code example standards, see <a href="./010-code-example-standards.md">Code Example Standards</a>.</p>
</div>

## 1.3. Available Code Examples

### 1.3.1. Event Sourcing Examples

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Event Sourcing Examples</h4>

<p style="color: #444;">These examples demonstrate how to implement event sourcing in a Laravel application using the Spatie Event Sourcing package.</p>

<p style="color: #444;"><strong>Status:</strong> Planned</p>

<p style="color: #444;"><strong>Examples Include:</strong></p>
<ul style="color: #444;">
  <li>Setting up the event sourcing package</li>
  <li>Creating and working with events</li>
  <li>Implementing event serialization</li>
  <li>Working with the event store</li>
  <li>Handling event versioning</li>
</ul>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Example: Creating an Event</h5>

```php
<?php

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamCreated extends ShouldBeStored
{
    /**
     * Create a new TeamCreated event.
     *
     * @param string $teamId The unique identifier for the team
     * @param string $name The name of the team
     */
    public function __construct(
        public string $teamId,
        public string $name
    ) {
    }
}
```
</div>

<p style="color: #444;">For more event sourcing examples, see [Event Sourcing Examples](./020-event-sourcing-examples.md).</p>
</div>

### 1.3.2. Aggregate Examples

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Aggregate Examples</h4>

<p style="color: #444;">These examples demonstrate how to implement aggregates in an event-sourced Laravel application.</p>

<p style="color: #444;"><strong>Status:</strong> Planned</p>

<p style="color: #444;"><strong>Examples Include:</strong></p>
<ul style="color: #444;">
  <li>Creating aggregate roots</li>
  <li>Implementing aggregate methods</li>
  <li>Handling commands</li>
  <li>Applying events</li>
  <li>Working with aggregate repositories</li>
</ul>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Example: Creating an Aggregate Root</h5>

```php
<?php

namespace App\Aggregates;

use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use App\Events\TeamCreated;
use App\Events\TeamNameChanged;
use App\Events\TeamDeleted;

class TeamAggregate extends AggregateRoot
{
    /**
     * Create a new team.
     *
     * @param string $name The name of the team
     * @return $this
     */
    public function createTeam(string $name): self
    {
        // Record the TeamCreated event
        $this->recordThat(new TeamCreated(
            teamId: $this->uuid(),
            name: $name
        ));
        
        return $this;
    }
    
    /**
     * Change the team's name.
     *
     * @param string $name The new name of the team
     * @return $this
     */
    public function changeName(string $name): self
    {
        // Record the TeamNameChanged event
        $this->recordThat(new TeamNameChanged(
            teamId: $this->uuid(),
            name: $name
        ));
        
        return $this;
    }
    
    /**
     * Delete the team.
     *
     * @return $this
     */
    public function deleteTeam(): self
    {
        // Record the TeamDeleted event
        $this->recordThat(new TeamDeleted(
            teamId: $this->uuid()
        ));
        
        return $this;
    }
}
```
</div>

<p style="color: #444;">For more aggregate examples, see [Aggregate Examples](./030-aggregate-examples.md).</p>
</div>

### 1.3.3. Projector Examples

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Projector Examples</h4>

<p style="color: #444;">These examples demonstrate how to implement projectors in an event-sourced Laravel application.</p>

<p style="color: #444;"><strong>Status:</strong> Planned</p>

<p style="color: #444;"><strong>Examples Include:</strong></p>
<ul style="color: #444;">
  <li>Creating projector classes</li>
  <li>Implementing projector methods</li>
  <li>Handling events</li>
  <li>Building read models</li>
  <li>Resetting projections</li>
</ul>

<p style="color: #444;">For more projector examples, see <a href="./040-projector-examples.md">Projector Examples</a>.</p>
</div>

### 1.3.4. Reactor Examples

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Reactor Examples</h4>

<p style="color: #444;">These examples demonstrate how to implement reactors in an event-sourced Laravel application.</p>

<p style="color: #444;"><strong>Status:</strong> Planned</p>

<p style="color: #444;"><strong>Examples Include:</strong></p>
<ul style="color: #444;">
  <li>Creating reactor classes</li>
  <li>Implementing reactor methods</li>
  <li>Handling events</li>
  <li>Performing side effects</li>
  <li>Testing reactors</li>
</ul>

<p style="color: #444;">For more reactor examples, see <a href="./050-reactor-examples.md">Reactor Examples</a>.</p>
</div>

### 1.3.5. Real-time Event Broadcasting Examples

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Real-time Event Broadcasting Examples</h4>

<p style="color: #444;">These examples demonstrate how to implement real-time event broadcasting in an event-sourced Laravel application.</p>

<p style="color: #444;"><strong>Status:</strong> Planned</p>

<p style="color: #444;"><strong>Examples Include:</strong></p>
<ul style="color: #444;">
  <li>Setting up Laravel Echo and Pusher</li>
  <li>Broadcasting events</li>
  <li>Listening for events on the client</li>
  <li>Implementing real-time updates</li>
  <li>Testing real-time event broadcasting</li>
</ul>

<p style="color: #444;">For more real-time event broadcasting examples, see <a href="./060-real-time-event-broadcasting-examples.md">Real-time Event Broadcasting Examples</a>.</p>
</div>

## 1.4. Implementation Status

<div style="padding: 15px; border-radius: 5px; border: 1px solid #d0d0d0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #111;">Implementation Status</h4>

\n<details>\n<summary>Table Details</summary>\n\n| Example Set | Status | Progress |
| --- | --- | --- |
| Code Example Standards | Planned | 0% |
| Event Sourcing Examples | Planned | 0% |
| Aggregate Examples | Planned | 0% |
| Projector Examples | Planned | 0% |
| Reactor Examples | Planned | 0% |
| Real-time Event Broadcasting Examples | Planned | 0% |
\n</details>\n
</div>

## 1.5. Related Documents

- [../000-index.md](../000-index.md) - User Experience Enhancement Index
- [../010-interactive-tutorials/000-index.md](../010-interactive-tutorials/000-index.md) - Interactive Tutorials Index
- [../../100-implementation-plan/100-350-event-sourcing/000-index.md](../../100-implementation-plan/100-350-event-sourcing/000-index.md) - Event Sourcing Implementation Plan
- [../../tools/code-examples-template.md](../../tools/code-examples-template.md) - Code Examples Template

## 1.6. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-22 | Initial version | Augment Agent |
