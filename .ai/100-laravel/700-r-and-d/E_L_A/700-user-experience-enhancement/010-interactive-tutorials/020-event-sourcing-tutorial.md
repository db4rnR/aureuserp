# 1. Event Sourcing Tutorial

**Version:** 1.0.0
**Date:** 2025-05-22
**Author:** Augment Agent
**Status:** In Progress
**Progress:** 20%

---

<details>
<summary>Table of Contents</summary>

- [1. Event Sourcing Tutorial](#1-event-sourcing-tutorial)
  - [1.1. Introduction](#11-introduction)
    - [1.1.1. Overview](#111-overview)
    - [1.1.2. Learning Objectives](#112-learning-objectives)
    - [1.1.3. Time Estimate](#113-time-estimate)
    - [1.1.4. Difficulty Level](#114-difficulty-level)
  - [1.2. Prerequisites](#12-prerequisites)
    - [1.2.1. Required Knowledge](#121-required-knowledge)
    - [1.2.2. Required Packages](#122-required-packages)
    - [1.2.3. Required Environment](#123-required-environment)
  - [1.3. Step-by-Step Guide](#13-step-by-step-guide)
    - [1.3.1. Step 1: Understanding Event Sourcing](#131-step-1-understanding-event-sourcing)
    - [1.3.2. Step 2: Setting Up the Event Sourcing Package](#132-step-2-setting-up-the-event-sourcing-package)
    - [1.3.3. Step 3: Creating Events](#133-step-3-creating-events)
    - [1.3.4. Step 4: Creating an Aggregate](#134-step-4-creating-an-aggregate)
    - [1.3.5. Step 5: Implementing Projectors](#135-step-5-implementing-projectors)
    - [1.3.6. Step 6: Implementing Reactors](#136-step-6-implementing-reactors)
    - [1.3.7. Step 7: Testing Event Sourcing](#137-step-7-testing-event-sourcing)
  - [1.4. Troubleshooting](#14-troubleshooting)
  - [1.5. Next Steps](#15-next-steps)
  - [1.6. Related Documents](#16-related-documents)
  - [1.7. Version History](#17-version-history)

</details>

## 1.1. Introduction

### 1.1.1. Overview

This tutorial provides a step-by-step guide to implementing event sourcing in a Laravel application. Event sourcing is a powerful pattern that stores all changes to an application state as a sequence of events. This approach provides numerous benefits, including a complete audit trail, the ability to reconstruct past states, and improved scalability.

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">What is Event Sourcing?</h4>

<p style="color: #444;">Event sourcing is an architectural pattern where:</p>

<ul style="color: #444;">
  <li>All changes to application state are stored as a sequence of events</li>
  <li>These events are stored in an event store</li>
  <li>The application state can be reconstructed by replaying the events</li>
  <li>New events can be used to project the current state</li>
</ul>

<p style="color: #444;">This approach provides several benefits:</p>

<ul style="color: #444;">
  <li>Complete audit trail of all changes</li>
  <li>Ability to reconstruct past states</li>
  <li>Improved scalability through event-driven architecture</li>
  <li>Better separation of concerns</li>
</ul>
</div>

### 1.1.2. Learning Objectives

By the end of this tutorial, you will be able to:

- Understand the core concepts of event sourcing
- Set up the Spatie Event Sourcing package in a Laravel application
- Create and work with events, aggregates, projectors, and reactors
- Implement a simple event-sourced feature
- Test event-sourced components

### 1.1.3. Time Estimate

This tutorial will take approximately 60-90 minutes to complete.

### 1.1.4. Difficulty Level

This tutorial is intended for intermediate Laravel developers who are familiar with Laravel concepts but new to event sourcing.

## 1.2. Prerequisites

### 1.2.1. Required Knowledge

Before starting this tutorial, you should be familiar with:

- PHP and Laravel basics
- Object-oriented programming concepts
- Basic understanding of domain-driven design
- Familiarity with Laravel Eloquent ORM

### 1.2.2. Required Packages

You will need to install the following packages:

- Laravel (version 10.x or higher)
- Spatie Laravel Event Sourcing (version 7.x or higher)

### 1.2.3. Required Environment

You will need:

- PHP 8.1 or higher
- Composer
- MySQL or PostgreSQL database
- Laravel development environment (e.g., Laravel Valet, Laravel Sail, or Laravel Homestead)

## 1.3. Step-by-Step Guide

### 1.3.1. Step 1: Understanding Event Sourcing

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Step 1: Understanding Event Sourcing</h4>

<p style="color: #444;">Before implementing event sourcing, it's important to understand the key components and how they work together:</p>

<ul style="color: #444;">
  <li><strong>Events</strong>: Immutable records of something that happened in the past</li>
  <li><strong>Aggregates</strong>: Entities that handle commands and emit events</li>
  <li><strong>Projectors</strong>: Components that build read models from events</li>
  <li><strong>Reactors</strong>: Components that react to events by performing side effects</li>
  <li><strong>Event Store</strong>: Database that stores all events</li>
</ul>

<p style="color: #444;">The typical flow in an event-sourced application is:</p>

<ol style="color: #444;">
  <li>A command is sent to an aggregate</li>
  <li>The aggregate validates the command and emits events</li>
  <li>Events are stored in the event store</li>
  <li>Projectors process events to update read models</li>
  <li>Reactors process events to perform side effects</li>
</ol>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Interactive Diagram</h5>

<p style="color: #444;">Click on the diagram below to see an interactive version that explains the event sourcing flow:</p>

<a href="../../illustrations/interactive/event-sourcing-flow-interactive.html">
  <img src="../../illustrations/event-sourcing-flow-light.png" alt="Event Sourcing Flow Diagram" style="max-width: 100%; border: 1px solid #d0d0d0; border-radius: 5px;">
</a>
</div>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Validation Quiz</h5>

<p style="color: #444;">Test your understanding of event sourcing concepts:</p>

<div class="quiz-question">
  <p><strong>1. What is the primary purpose of an aggregate in event sourcing?</strong></p>
  <form class="quiz-form">
    <label><input type="radio" name="q1" value="a"> To store events in the event store</label><br>
    <label><input type="radio" name="q1" value="b"> To handle commands and emit events</label><br>
    <label><input type="radio" name="q1" value="c"> To update read models based on events</label><br>
    <label><input type="radio" name="q1" value="d"> To perform side effects in response to events</label><br>
    <button type="button" class="validate-button">Check Answer</button>
  </form>
  <div class="feedback" style="display: none;">
    <p>Correct! Aggregates handle commands and emit events. They are responsible for validating commands and ensuring business rules are followed.</p>
  </div>
</div>
</div>
</div>

### 1.3.2. Step 2: Setting Up the Event Sourcing Package

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Step 2: Setting Up the Event Sourcing Package</h4>

<p style="color: #444;">Let's start by installing the Spatie Laravel Event Sourcing package:</p>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Installation</h5>

<p style="color: #444;">Run the following command in your terminal:</p>

```bash
composer require spatie/laravel-event-sourcing
```

<p style="color: #444;">After installation, publish the configuration file and migrations:</p>

```bash
php artisan vendor:publish --provider="Spatie\EventSourcing\EventSourcingServiceProvider" --tag="event-sourcing-migrations"
php artisan vendor:publish --provider="Spatie\EventSourcing\EventSourcingServiceProvider" --tag="event-sourcing-config"
```

<p style="color: #444;">Run the migrations to create the required tables:</p>

```bash
php artisan migrate
```
</div>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Configuration</h5>

<p style="color: #444;">Open the <code>config/event-sourcing.php</code> file and review the configuration options:</p>

```php
return [
    /*
     * These directories will be scanned for projectors and reactors. They
     * will be registered automatically to the projector and reactor registries.
     */
    'auto_discover_projectors_and_reactors' => [
        app_path(),
    ],

    /*
     * Projectors are classes that build up projections. You can create them by
     * performing operations on the events in the event stream.
     */
    'projectors' => [
        \Spatie\EventSourcing\Projectors\Projectors\EloquentProjector::class,
    ],

    /*
     * Reactors are classes that handle side effects. They can be triggered
     * by projectors.
     */
    'reactors' => [
        // Add your reactor classes here
    ],

    /*
     * A queue is used to guarantee that all events get passed to the projectors in
     * the right order. Here you can set the name of the queue.
     */
    'queue' => env('EVENT_SOURCING_QUEUE', null),

    /*
     * When a projector or reactor throws an exception the process will stop and
     * no further events will be processed. You can set this to true to
     * continue processing events even when an exception is thrown.
     */
    'continue_processing_on_exception' => false,

    /*
     * This class is responsible for storing events. To add extra behavior you
     * can change this to a class of your own. The only restriction is that
     * it should implement \Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository.
     */
    'stored_event_repository' => \Spatie\EventSourcing\StoredEvents\Repositories\EloquentStoredEventRepository::class,

    /*
     * This class is responsible for storing snapshots. To add extra behavior you
     * can change this to a class of your own. The only restriction is that
     * it should implement \Spatie\EventSourcing\Snapshots\EloquentSnapshotRepository.
     */
    'snapshot_repository' => \Spatie\EventSourcing\Snapshots\EloquentSnapshotRepository::class,

    /*
     * This class is responsible for storing events in the database.
     */
    'stored_event_model' => \Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent::class,

    /*
     * This class is responsible for storing snapshots in the database.
     */
    'snapshot_model' => \Spatie\EventSourcing\Snapshots\EloquentSnapshot::class,

    /*
     * This class is responsible for handling stored events.
     */
    'stored_event_handler' => \Spatie\EventSourcing\StoredEvents\HandleStoredEventJob::class,

    /*
     * This class is responsible for serializing events.
     */
    'event_serializer' => \Spatie\EventSourcing\EventSerializers\JsonEventSerializer::class,

    /*
     * When replaying events, potentially a lot of events will have to be retrieved.
     * In order to avoid memory problems, events will be retrieved as chunks.
     * You can specify the chunk size here.
     */
    'replay_chunk_size' => 1000,

    /*
     * In production, you likely don't want the package to auto-discover the event
     * handlers on every request. The package can cache all registered event handlers.
     * This cache will be stored in the directory specified below.
     */
    'cache_path' => storage_path('app/event-sourcing'),

    /*
     * Determines the maximum number of events that will be retrieved per
     * aggregate when replaying.
     */
    'max_number_of_events_per_aggregate_to_retrieve' => 10000,
];
```

<p style="color: #444;">For this tutorial, you can leave the default configuration as is.</p>
</div>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Validation Step</h5>

<p style="color: #444;">To verify that the package is installed correctly, check that the following tables have been created in your database:</p>

<ul style="color: #444;">
  <li><code>stored_events</code>: Stores all events</li>
  <li><code>snapshots</code>: Stores aggregate snapshots</li>
</ul>

<p style="color: #444;">You can check this by running:</p>

```bash
php artisan migrate:status
```
</div>
</div>

### 1.3.3. Step 3: Creating Events

<div style="background-color: #e0f0e0; padding: 15px; border-radius: 5px; border: 1px solid #c0d0c0; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #007700;">Step 3: Creating Events</h4>

<p style="color: #444;">Events are immutable records of something that happened in the past. Let's create some events for a simple team management system:</p>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Creating Event Classes</h5>

<p style="color: #444;">First, let's create a directory for our events:</p>

```bash
mkdir -p app/Events
```

<p style="color: #444;">Now, let's create a <code>TeamCreated</code> event:</p>

```php
<?php

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamCreated extends ShouldBeStored
{
    public function __construct(
        public string $teamId,
        public string $name
    ) {
    }
}
```

<p style="color: #444;">Next, let's create a <code>TeamNameChanged</code> event:</p>

```php
<?php

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamNameChanged extends ShouldBeStored
{
    public function __construct(
        public string $teamId,
        public string $name
    ) {
    }
}
```

<p style="color: #444;">Finally, let's create a <code>TeamDeleted</code> event:</p>

```php
<?php

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class TeamDeleted extends ShouldBeStored
{
    public function __construct(
        public string $teamId
    ) {
    }
}
```
</div>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Key Points About Events</h5>

<ul style="color: #444;">
  <li>Events should be named in the past tense (e.g., <code>TeamCreated</code>, not <code>CreateTeam</code>)</li>
  <li>Events should extend <code>Spatie\EventSourcing\StoredEvents\ShouldBeStored</code></li>
  <li>Events should be immutable (properties should be public readonly or have no setters)</li>
  <li>Events should contain all data needed to understand what happened</li>
  <li>Events should be serializable (avoid complex objects)</li>
</ul>
</div>

<div style="padding: 10px; border-radius: 5px; border: 1px solid #d0d0d0; margin-top: 10px;">
<h5 style="margin-top: 0; color: #111;">Exercise: Create a Member Event</h5>

<p style="color: #444;">Now it's your turn! Create a <code>MemberAddedToTeam</code> event that records when a member is added to a team. The event should include:</p>

<ul style="color: #444;">
  <li>The team ID</li>
  <li>The member ID</li>
  <li>The member's role in the team</li>
</ul>

<textarea class="exercise-input" placeholder="Write your code here..." rows="10" style="width: 100%; margin-top: 10px;"></textarea>
<button class="validate-button" style="margin-top: 10px;">Check Solution</button>

<div class="feedback" style="display: none; margin-top: 10px;">
  <div class="success-feedback">
    <h5 style="color: #007700;">Correct!</h5>
    <p>Your implementation correctly defines a MemberAddedToTeam event with the required properties.</p>
  </div>
  <div class="error-feedback">
    <h5 style="color: #cc0000;">Not quite right</h5>
    <p>Your implementation should look something like this:</p>
    <pre><code class="language-php">
<?php

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class MemberAddedToTeam extends ShouldBeStored
{
    public function __construct(
        public string $teamId,
        public string $memberId,
        public string $role
    ) {
    }
}
    </code></pre>
  </div>
</div>
</div>
</div>

## 1.6. Related Documents

- [../000-index.md](../000-index.md) - Interactive Tutorials Index
- [../../000-index.md](../../000-index.md) - User Experience Enhancement Index
- [../../../100-implementation-plan/100-350-event-sourcing/000-index.md](../../../100-implementation-plan/100-350-event-sourcing/000-index.md) - Event Sourcing Implementation Plan

## 1.7. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-22 | Initial version | Augment Agent |
