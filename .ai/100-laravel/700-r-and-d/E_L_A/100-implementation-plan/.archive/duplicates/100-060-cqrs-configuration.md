# Phase 0: Phase 0.6: CQRS and State Machine Configuration

**Version:** 1.0.3
**Date:** 2025-05-25
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
  - [Required Prior Steps](#required-prior-steps)
  - [Required Packages](#required-packages)
  - [Required Knowledge](#required-knowledge)
  - [Required Environment](#required-environment)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Step 1: Configure Hirethunk Verbs](#step-1-configure-hirethunk-verbs)
- [Step 2: Configure Verbs Events and States](#step-2-configure-verbs-events-and-states)
- [Step 3: Configure Event Sourcing](#step-3-configure-event-sourcing)
- [Step 4: Set Up Enhanced Enums](#step-4-set-up-enhanced-enums)
- [Step 5: Configure State Machines](#step-5-configure-state-machines)
- [Step 6: Set Up Command Bus](#step-6-set-up-command-bus)
- [Step 7: Configure Command Validation](#step-7-configure-command-validation)
- [Step 8: Set Up Command Handlers](#step-8-set-up-command-handlers)
- [Step 9: Configure Command History](#step-9-configure-command-history)
- [Step 10: Set Up Snapshot Functionality](#step-10-set-up-snapshot-functionality)
- [Step 11: Configure Command Authorization](#step-11-configure-command-authorization)
- [Step 12: Verify CQRS Configuration](#step-12-verify-cqrs-configuration)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for configuring the Command Query Responsibility Segregation (CQRS) pattern and state machines in the Enhanced Laravel Application (ELA). This includes configuring Hirethunk Verbs, event sourcing, enhanced enums, and state machines.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Package Installation](030-core-components/010-package-installation.md) completed
- [Spatie Settings Setup](030-core-components/020-spatie-settings-setup.md) completed

### Required Packages
- Hirethunk Verbs (`hirethunk/verbs`) installed
- Spatie Laravel Event Sourcing (`spatie/laravel-event-sourcing`) installed
- Laravel Framework (`laravel/framework`) installed

### Required Knowledge
- Understanding of CQRS pattern and event sourcing
- Familiarity with state machines and finite state automata
- Basic understanding of command buses and event buses
- Knowledge of PHP enums and attributes

### Required Environment
- PHP 8.2 or higher
- Laravel 12.x
- Database connection configured

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Configure Hirethunk Verbs | 15 minutes |
| Configure Verbs Events and States | 20 minutes |
| Configure Event Sourcing | 20 minutes |
| Set Up Enhanced Enums | 15 minutes |
| Configure State Machines | 30 minutes |
| Set Up Command Bus | 15 minutes |
| Configure Command Validation | 15 minutes |
| Set Up Command Handlers | 20 minutes |
| Configure Command History | 15 minutes |
| Set Up Snapshot Functionality | 15 minutes |
| Configure Command Authorization | 15 minutes |
| Verify CQRS Configuration | 15 minutes |
| **Total** | **210 minutes** |

> **Note:** These time estimates assume familiarity with CQRS, event sourcing, and state machines. Actual time may vary based on experience level and the complexity of your application.

## Step 1: Configure Hirethunk Verbs

1. Publish the Verbs migrations if not already done:
   ```bash
   php artisan vendor:publish --tag=verbs-migrations
   php artisan migrate
   ```

2. Configure the command bus in `config/verbs-commands.php`:
   ```php
   return [
       /*
       |--------------------------------------------------------------------------
       | Command Bus
       |--------------------------------------------------------------------------
       |
       | This value determines the "bus" that commands will be dispatched through.
       |
       */
       'bus' => \Hirethunk\Verbs\Commands\CommandBus::class,

       /*
       |--------------------------------------------------------------------------
       | Command Handlers
       |--------------------------------------------------------------------------
       |
       | This array contains the command handlers for your application.
       | You can register your own command handlers here.
       |
       */
       'handlers' => [
           // Your command handlers will be registered here
       ],

       /*
       |--------------------------------------------------------------------------
       | Command Middleware
       |--------------------------------------------------------------------------
       |
       | This array contains the command middleware for your application.
       | Command middleware are executed in the order they are registered.
       |
       */
       'middleware' => [
           \Hirethunk\Verbs\Commands\Middleware\ValidateCommand::class,
           \Hirethunk\Verbs\Commands\Middleware\AuthorizeCommand::class,
       ],
   ];
   ```

3. Create a base command class in `app/Commands/Command.php`:
   ```php
   <?php

   namespace App\Commands;

   use Hirethunk\Verbs\Commands\Command as BaseCommand;

   abstract class Command extends BaseCommand
   {
       // Add common command functionality here
   }
   ```

> **Reference:** [Hirethunk Verbs Documentation](https:/verbs.thunk.dev)

## Step 2: Configure Verbs Events and States

1. Create a sample event using the Verbs artisan command:
   ```bash
   php artisan verbs:event CustomerBeganTrial
   ```

2. Customize the event to include a customer ID and validation logic:
   ```php
   <?php

   namespace App\Events;

   use Hirethunk\Verbs\Event;
   use Hirethunk\Verbs\Attributes\StateId;
   use App\States\CustomerState;

   class CustomerBeganTrial extends Event
   {
       #[StateId(CustomerState::class)]
       public int $customer_id;

       public function validate(CustomerState $state)
       {
           $this->assert(
               $state->trial_started_at === null
               || $state->trial_started_at->diffInDays() > 365,
               'This user has started a trial within the last year.'
           );
       }

       public function apply(CustomerState $state)
       {
           $state->trial_started_at = now();
       }

       public function handle()
       {
           // Additional side effects when the event is committed
           // For example, create a subscription record
       }
   }
   ```

3. Create a state using the Verbs artisan command:
   ```bash
   php artisan verbs:state CustomerState
   ```

4. Customize the state to store trial information:
   ```php
   <?php

   namespace App\States;

   use Hirethunk\Verbs\State;
   use Carbon\Carbon;

   class CustomerState extends State
   {
       public Carbon|null $trial_started_at = null;
   }
   ```

5. Fire the event in your application code:
   ```php
   use App\Events\CustomerBeganTrial;

   // In a controller or service
   CustomerBeganTrial::fire(customer_id: 1);
   ```

> **Reference:** [Hirethunk Verbs Quickstart Guide](https:/verbs.thunk.dev/docs/getting-started/quickstart)

## Step 3: Configure Event Sourcing

1. Publish the configuration file if not already done:
   ```bash
   php artisan vendor:publish --provider="Spatie\EventSourcing\EventSourcingServiceProvider"
   ```

2. Configure event sourcing in `config/event-sourcing.php`:
   ```php
   return [
       /*
       |--------------------------------------------------------------------------
       | Database Connection
       |--------------------------------------------------------------------------
       |
       | This is the database connection that will be used to store the stored events.
       | This connection should be configured in your database config.
       |
       */
       'database_connection' => env('EVENT_SOURCING_DB_CONNECTION', env('DB_CONNECTION', 'pgsql')),

       /*
       |--------------------------------------------------------------------------
       | Stored Event Model
       |--------------------------------------------------------------------------
       |
       | This is the model that will be used to store the events.
       |
       */
       'stored_event_model' => Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent::class,

       /*
       |--------------------------------------------------------------------------
       | Snapshot Model
       |--------------------------------------------------------------------------
       |
       | This is the model that will be used to store the snapshots.
       |
       */
       'snapshot_model' => Spatie\EventSourcing\Snapshots\EloquentSnapshot::class,

       /*
       |--------------------------------------------------------------------------
       | Event Serializer
       |--------------------------------------------------------------------------
       |
       | This is the serializer that will be used to serialize events.
       |
       */
       'event_serializer' => Spatie\EventSourcing\StoredEvents\Serializers\JsonSerializer::class,

       /*
       |--------------------------------------------------------------------------
       | Event Handlers
       |--------------------------------------------------------------------------
       |
       | This is the array of event handlers that will handle events.
       |
       */
       'event_handlers' => [
           // Your event handlers will be registered here
       ],

       /*
       |--------------------------------------------------------------------------
       | Projectors
       |--------------------------------------------------------------------------
       |
       | This is the array of projectors that will project events.
       |
       */
       'projectors' => [
           // Your projectors will be registered here
       ],

       /*
       |--------------------------------------------------------------------------
       | Reactors
       |--------------------------------------------------------------------------
       |
       | This is the array of reactors that will react to events.
       |
       */
       'reactors' => [
           // Your reactors will be registered here
       ],
   ];
   ```

3. Run the migrations to create the stored events and snapshots tables:
   ```bash
   php artisan migrate
   ```

> **Reference:** [Spatie Laravel Event Sourcing Documentation](https:/spatie.be/docs/laravel-event-sourcing/v7/introduction)

## Step 4: Set Up Enhanced Enums

1. Create a base enum class in `app/Enums/Enum.php`:
   ```php
   <?php

   namespace App\Enums;

   use Filament\Support\Contracts\HasColor;
   use Filament\Support\Contracts\HasIcon;
   use Filament\Support\Contracts\HasLabel;

   abstract class Enum implements HasColor, HasIcon, HasLabel
   {
       /**
        * Get the color for the enum value.
        *
        * @return string|null
        */
       public function getColor(): ?string
       {
           return match($this) {
               default => 'gray',
           };
       }

       /**
        * Get the icon for the enum value.
        *
        * @return string|null
        */
       public function getIcon(): ?string
       {
           return match($this) {
               default => null,
           };
       }

       /**
        * Get the label for the enum value.
        *
        * @return string
        */
       public function getLabel(): string
       {
           return str($this->name)->title()->replace('_', ' ');
       }
   }
   ```

2. Create a sample state enum in `app/Enums/TodoStatus.php`:
   ```php
   <?php

   namespace App\Enums;

   enum TodoStatus: string
   {
       case DRAFT = 'draft';
       case IN_PROGRESS = 'in_progress';
       case COMPLETED = 'completed';
       case ARCHIVED = 'archived';

       /**
        * Get the color for the enum value.
        *
        * @return string|null
        */
       public function getColor(): ?string
       {
           return match($this) {
               self::DRAFT => 'gray',
               self::IN_PROGRESS => 'blue',
               self::COMPLETED => 'green',
               self::ARCHIVED => 'yellow',
           };
       }

       /**
        * Get the icon for the enum value.
        *
        * @return string|null
        */
       public function getIcon(): ?string
       {
           return match($this) {
               self::DRAFT => 'heroicon-o-pencil',
               self::IN_PROGRESS => 'heroicon-o-play',
               self::COMPLETED => 'heroicon-o-check',
               self::ARCHIVED => 'heroicon-o-archive-box',
           };
       }

       /**
        * Get the label for the enum value.
        *
        * @return string
        */
       public function getLabel(): string
       {
           return match($this) {
               self::DRAFT => 'Draft',
               self::IN_PROGRESS => 'In Progress',
               self::COMPLETED => 'Completed',
               self::ARCHIVED => 'Archived',
           };
       }
   }
   ```

## Step 5: Configure State Machines with Spatie Laravel Model States

1. Create a base state class for the Todo model in `app/States/Todo/TodoState.php`:
   ```php
   <?php

   namespace App\States\Todo;

   use Spatie\ModelStates\State;
   use Spatie\ModelStates\StateConfig;

   abstract class TodoState extends State
   {
       public static function config(): StateConfig
       {
           return parent::config()
               ->default(Draft::class)
               ->allowTransition(Draft::class, InProgress::class)
               ->allowTransition(Draft::class, Archived::class)
               ->allowTransition(InProgress::class, Completed::class)
               ->allowTransition(InProgress::class, Archived::class)
               ->allowTransition(Completed::class, InProgress::class)
               ->allowTransition(Completed::class, Archived::class)
               ->allowTransition(Archived::class, Draft::class);
       }

       /**
        * Get the color for the state.
        *
        * @return string
        */
       abstract public function getColor(): string;

       /**
        * Get the icon for the state.
        *
        * @return string|null
        */
       abstract public function getIcon(): ?string;

       /**
        * Get the label for the state.
        *
        * @return string
        */
       abstract public function getLabel(): string;
   }
   ```

2. Create the concrete state classes for the Todo model:

   ```php
   <?php

   namespace App\States\Todo;

   class Draft extends TodoState
   {
       public function getColor(): string
       {
           return 'gray';
       }

       public function getIcon(): ?string
       {
           return 'heroicon-o-pencil';
       }

       public function getLabel(): string
       {
           return 'Draft';
       }
   }
   ```

   ```php
   <?php

   namespace App\States\Todo;

   class InProgress extends TodoState
   {
       public function getColor(): string
       {
           return 'blue';
       }

       public function getIcon(): ?string
       {
           return 'heroicon-o-play';
       }

       public function getLabel(): string
       {
           return 'In Progress';
       }
   }
   ```

   ```php
   <?php

   namespace App\States\Todo;

   class Completed extends TodoState
   {
       public function getColor(): string
       {
           return 'green';
       }

       public function getIcon(): ?string
       {
           return 'heroicon-o-check';
       }

       public function getLabel(): string
       {
           return 'Completed';
       }
   }
   ```

   ```php
   <?php

   namespace App\States\Todo;

   class Archived extends TodoState
   {
       public function getColor(): string
       {
           return 'yellow';
       }

       public function getIcon(): ?string
       {
           return 'heroicon-o-archive-box';
       }

       public function getLabel(): string
       {
           return 'Archived';
       }
   }
   ```

3. Update the Todo model to use the state machine:
   ```php
   <?php

   namespace App\Models;

   use App\States\Todo\TodoState;
   use Illuminate\Database\Eloquent\Model;
   use Spatie\ModelStates\HasStates;

   class Todo extends Model
   {
       use HasStates;

       protected $casts = [
           'status' => TodoState::class,
       ];

       // Rest of the model...
   }
   ```

> **Reference:** [Spatie Laravel Model States Documentation](https:/spatie.be/docs/laravel-model-states/v2/introduction)

## Step 6: Set Up Command Bus

1. Create a command bus service provider in `app/Providers/CommandBusServiceProvider.php`:
   ```php
   <?php

   namespace App\Providers;

   use Illuminate\Support\ServiceProvider;
   use Hirethunk\Verbs\Commands\CommandBus;
   use Hirethunk\Verbs\Commands\Middleware\ValidateCommand;
   use Hirethunk\Verbs\Commands\Middleware\AuthorizeCommand;
   use Hirethunk\Verbs\History\Middleware\LogCommand;

   class CommandBusServiceProvider extends ServiceProvider
   {
       /**
        * Register services.
        *
        * @return void
        */
       public function register(): void
       {
           $this->app->singleton(CommandBus::class, function ($app) {
               $bus = new CommandBus();

               // Register middleware
               $bus->registerMiddleware(ValidateCommand::class);
               $bus->registerMiddleware(AuthorizeCommand::class);
               $bus->registerMiddleware(LogCommand::class);

               return $bus;
           });
       }

       /**
        * Bootstrap services.
        *
        * @return void
        */
       public function boot(): void
       {
           //
       }
   }
   ```

2. Register the service provider in `config/app.php`:
   ```php
   'providers' => [
       // Other service providers...
       App\Providers\CommandBusServiceProvider::class,
   ],
   ```

## Step 7: Configure Command Validation

1. Create a command validator in `app/Commands/Validation/CommandValidator.php`:
   ```php
   <?php

   namespace App\Commands\Validation;

   use Illuminate\Support\Facades\Validator;
   use Illuminate\Validation\ValidationException;
   use Hirethunk\Verbs\Commands\Command;

   class CommandValidator
   {
       /**
        * Validate a command.
        *
        * @param Command $command
        * @return void
        * @throws ValidationException
        */
       public function validate(Command $command): void
       {
           if (method_exists($command, 'rules')) {
               $validator = Validator::make(
                   $command->toArray(),
                   $command->rules(),
                   $command->messages() ?? [],
                   $command->attributes() ?? []
               );

               if ($validator->fails()) {
                   throw new ValidationException($validator);
               }
           }
       }
   }
   ```

2. Update the `ValidateCommand` middleware in `config/verbs-commands.php`:
   ```php
   'middleware' => [
       \App\Commands\Validation\ValidateCommand::class,
       \Hirethunk\Verbs\Commands\Middleware\AuthorizeCommand::class,
       \Hirethunk\Verbs\History\Middleware\LogCommand::class,
   ],
   ```

3. Create a custom validate command middleware in `app/Commands/Validation/ValidateCommand.php`:
   ```php
   <?php

   namespace App\Commands\Validation;

   use Hirethunk\Verbs\Commands\Command;
   use Hirethunk\Verbs\Commands\Middleware\CommandMiddleware;

   class ValidateCommand implements CommandMiddleware
   {
       /**
        * Create a new validate command middleware instance.
        *
        * @param CommandValidator $validator
        * @return void
        */
       public function __construct(protected CommandValidator $validator)
       {
       }

       /**
        * Handle the command.
        *
        * @param Command $command
        * @param callable $next
        * @return mixed
        */
       public function handle(Command $command, callable $next): mixed
       {
           $this->validator->validate($command);

           return $next($command);
       }
   }
   ```

> **Reference:** [Laravel 12.x Validation Documentation](https:/laravel.com/docs/12.x/validation)

## Step 8: Set Up Command Handlers

1. Create a base command handler in `app/Commands/Handlers/CommandHandler.php`:
   ```php
   <?php

   namespace App\Commands\Handlers;

   use Hirethunk\Verbs\Commands\Command;
   use Hirethunk\Verbs\Commands\Handlers\CommandHandler as BaseCommandHandler;

   abstract class CommandHandler extends BaseCommandHandler
   {
       /**
        * Handle the command.
        *
        * @param Command $command
        * @return mixed
        */
       abstract public function handle(Command $command): mixed;
   }
   ```

2. Create a sample command in `app/Commands/Todos/CreateTodoCommand.php`:
   ```php
   <?php

   namespace App\Commands\Todos;

   use App\Commands\Command;
   use App\Enums\TodoStatus;

   class CreateTodoCommand extends Command
   {
       /**
        * Create a new create todo command instance.
        *
        * @param string $title
        * @param string|null $description
        * @param TodoStatus $status
        * @return void
        */
       public function __construct(
           public string $title,
           public ?string $description = null,
           public TodoStatus $status = TodoStatus::DRAFT
       ) {
       }

       /**
        * Get the validation rules for the command.
        *
        * @return array
        */
       public function rules(): array
       {
           return [
               'title' => ['required', 'string', 'max:255'],
               'description' => ['nullable', 'string'],
               'status' => ['required'],
           ];
       }
   }
   ```

3. Create a sample command handler in `app/Commands/Handlers/Todos/CreateTodoCommandHandler.php`:
   ```php
   <?php

   namespace App\Commands\Handlers\Todos;

   use App\Commands\Handlers\CommandHandler;
   use App\Commands\Todos\CreateTodoCommand;
   use Hirethunk\Verbs\Commands\Command;

   class CreateTodoCommandHandler extends CommandHandler
   {
       /**
        * Handle the command.
        *
        * @param Command $command
        * @return mixed
        */
       public function handle(Command $command): mixed
       {
           // This is just a placeholder for now
           // In a real implementation, you would create a Todo model
           return [
               'title' => $command->title,
               'description' => $command->description,
               'status' => $command->status,
           ];
       }
   }
   ```

4. Register the command handler in `config/verbs-commands.php`:
   ```php
   'handlers' => [
       \App\Commands\Todos\CreateTodoCommand::class => \App\Commands\Handlers\Todos\CreateTodoCommandHandler::class,
   ],
   ```

## Step 9: Configure Command History

1. Create a command history service provider in `app/Providers/CommandHistoryServiceProvider.php`:
   ```php
   <?php

   namespace App\Providers;

   use Illuminate\Support\ServiceProvider;
   use Hirethunk\Verbs\History\CommandHistory;
   use Hirethunk\Verbs\History\Models\CommandLog;
   use Hirethunk\Verbs\History\Models\Snapshot;
   use Hirethunk\Verbs\History\Serializers\CommandSerializer;
   use Hirethunk\Verbs\History\Serializers\SnapshotSerializer;

   class CommandHistoryServiceProvider extends ServiceProvider
   {
       /**
        * Register services.
        *
        * @return void
        */
       public function register(): void
       {
           $this->app->singleton(CommandHistory::class, function ($app) {
               return new CommandHistory(
                   $app->make(CommandLog::class),
                   $app->make(Snapshot::class),
                   $app->make(CommandSerializer::class),
                   $app->make(SnapshotSerializer::class)
               );
           });
       }

       /**
        * Bootstrap services.
        *
        * @return void
        */
       public function boot(): void
       {
           //
       }
   }
   ```

2. Register the service provider in `config/app.php`:
   ```php
   'providers' => [
       // Other service providers...
       App\Providers\CommandHistoryServiceProvider::class,
   ],
   ```

## Step 10: Set Up Snapshot Functionality

1. Create a snapshot service in `app/Services/SnapshotService.php`:
   ```php
   <?php

   namespace App\Services;

   use Hirethunk\Verbs\History\Models\CommandLog;
   use Hirethunk\Verbs\History\Models\Snapshot;
   use Illuminate\Database\Eloquent\Model;

   class SnapshotService
   {
       /**
        * Create a new snapshot service instance.
        *
        * @param Snapshot $snapshot
        * @return void
        */
       public function __construct(protected Snapshot $snapshot)
       {
       }

       /**
        * Create a snapshot for a model.
        *
        * @param Model $model
        * @param CommandLog $commandLog
        * @return Snapshot
        */
       public function createSnapshot(Model $model, CommandLog $commandLog): Snapshot
       {
           return $this->snapshot->create([
               'subject_type' => get_class($model),
               'subject_id' => $model->getKey(),
               'command_log_id' => $commandLog->id,
               'data' => $model->toArray(),
           ]);
       }

       /**
        * Get the latest snapshot for a model.
        *
        * @param Model $model
        * @return Snapshot|null
        */
       public function getLatestSnapshot(Model $model): ?Snapshot
       {
           return $this->snapshot
               ->where('subject_type', get_class($model))
               ->where('subject_id', $model->getKey())
               ->latest('id')
               ->first();
       }

       /**
        * Get all snapshots for a model.
        *
        * @param Model $model
        * @return \Illuminate\Database\Eloquent\Collection
        */
       public function getSnapshots(Model $model)
       {
           return $this->snapshot
               ->where('subject_type', get_class($model))
               ->where('subject_id', $model->getKey())
               ->orderBy('id', 'desc')
               ->get();
       }
   }
   ```

2. Register the snapshot service in the service container in `app/Providers/AppServiceProvider.php`:
   ```php
   use App\Services\SnapshotService;
   use Hirethunk\Verbs\History\Models\Snapshot;

   public function register(): void
   {
       $this->app->singleton(SnapshotService::class, function ($app) {
           return new SnapshotService($app->make(Snapshot::class));
       });
   }
   ```

## Step 11: Configure Command Authorization

1. Create a command authorizer in `app/Commands/Authorization/CommandAuthorizer.php`:
   ```php
   <?php

   namespace App\Commands\Authorization;

   use Hirethunk\Verbs\Commands\Command;
   use Illuminate\Auth\Access\AuthorizationException;
   use Illuminate\Support\Facades\Gate;

   class CommandAuthorizer
   {
       /**
        * Authorize a command.
        *
        * @param Command $command
        * @return void
        * @throws AuthorizationException
        */
       public function authorize(Command $command): void
       {
           if (method_exists($command, 'authorize')) {
               $authorized = $command->authorize();

               if ($authorized === false) {
                   throw new AuthorizationException('This action is unauthorized.');
               }
           }

           if (method_exists($command, 'authorizeUsing')) {
               $ability = $command->authorizeUsing();

               if (is_array($ability)) {
                   [$ability, $arguments] = $ability;
               } else {
                   $arguments = [$command];
               }

               $authorized = Gate::allows($ability, $arguments);

               if ($authorized === false) {
                   throw new AuthorizationException('This action is unauthorized.');
               }
           }
       }
   }
   ```

2. Create a custom authorize command middleware in `app/Commands/Authorization/AuthorizeCommand.php`:
   ```php
   <?php

   namespace App\Commands\Authorization;

   use Hirethunk\Verbs\Commands\Command;
   use Hirethunk\Verbs\Commands\Middleware\CommandMiddleware;

   class AuthorizeCommand implements CommandMiddleware
   {
       /**
        * Create a new authorize command middleware instance.
        *
        * @param CommandAuthorizer $authorizer
        * @return void
        */
       public function __construct(protected CommandAuthorizer $authorizer)
       {
       }

       /**
        * Handle the command.
        *
        * @param Command $command
        * @param callable $next
        * @return mixed
        */
       public function handle(Command $command, callable $next): mixed
       {
           $this->authorizer->authorize($command);

           return $next($command);
       }
   }
   ```

3. Update the `AuthorizeCommand` middleware in `config/verbs-commands.php`:
   ```php
   'middleware' => [
       \App\Commands\Validation\ValidateCommand::class,
       \App\Commands\Authorization\AuthorizeCommand::class,
       \Hirethunk\Verbs\History\Middleware\LogCommand::class,
   ],
   ```

> **Reference:** [Laravel Security Best Practices 2025](https:/dev.to/saif_uddin/15-laravel-security-best-practices-you-should-follow-in-2025-11c3)

## Step 12: Verify CQRS Configuration

1. Create a test command in `app/Commands/Test/TestCommand.php`:
   ```php
   <?php

   namespace App\Commands\Test;

   use App\Commands\Command;

   class TestCommand extends Command
   {
       /**
        * Create a new test command instance.
        *
        * @param string $message
        * @return void
        */
       public function __construct(public string $message)
       {
       }

       /**
        * Get the validation rules for the command.
        *
        * @return array
        */
       public function rules(): array
       {
           return [
               'message' => ['required', 'string'],
           ];
       }
   }
   ```

2. Create a test command handler in `app/Commands/Handlers/Test/TestCommandHandler.php`:
   ```php
   <?php

   namespace App\Commands\Handlers\Test;

   use App\Commands\Handlers\CommandHandler;
   use App\Commands\Test\TestCommand;
   use Hirethunk\Verbs\Commands\Command;

   class TestCommandHandler extends CommandHandler
   {
       /**
        * Handle the command.
        *
        * @param Command $command
        * @return mixed
        */
       public function handle(Command $command): mixed
       {
           return [
               'message' => $command->message,
               'timestamp' => now()->toIso8601String(),
           ];
       }
   }
   ```

3. Register the test command handler in `config/verbs-commands.php`:
   ```php
   'handlers' => [
       \App\Commands\Todos\CreateTodoCommand::class => \App\Commands\Handlers\Todos\CreateTodoCommandHandler::class,
       \App\Commands\Test\TestCommand::class => \App\Commands\Handlers\Test\TestCommandHandler::class,
   ],
   ```

4. Create a test route in `routes/web.php`:
   ```php
   use App\Commands\Test\TestCommand;
   use Hirethunk\Verbs\Commands\CommandBus;

   Route::get('/test-command', function (CommandBus $commandBus) {
       $result = $commandBus->dispatch(new TestCommand('Hello, World!'));

       return response()->json($result);
   });
   ```

5. Test the command by visiting `/test-command` in your browser

## Troubleshooting

### Common Issues and Solutions

1. **Command Handler Not Found**
   - Problem: Command handler not found when dispatching a command
   - Solution:
     - Ensure the command handler is registered in `config/verbs-commands.php`
     - Check the namespace of the command and handler
     - Verify that the handler implements the correct interface

2. **Command Validation Fails**
   - Problem: Command validation fails when dispatching a command
   - Solution:
     - Check the validation rules in the command
     - Ensure the command data matches the validation rules
     - Verify that the validation middleware is registered

3. **Command Authorization Fails**
   - Problem: Command authorization fails when dispatching a command
   - Solution:
     - Check the authorization logic in the command
     - Ensure the user has the required permissions
     - Verify that the authorization middleware is registered

4. **Command History Not Logged**
   - Problem: Command history not logged when dispatching a command
   - Solution:
     - Ensure the command history middleware is registered
     - Check the command history configuration
     - Verify that the command history tables exist in the database

5. **Snapshot Creation Fails**
   - Problem: Snapshot creation fails when dispatching a command
   - Solution:
     - Ensure the snapshot service is registered
     - Check the snapshot configuration
     - Verify that the snapshot table exists in the database

## Related Documents

- [Package Installation](030-core-components/010-package-installation.md) - For installing required packages
- [Spatie Settings Setup](030-core-components/020-spatie-settings-setup.md) - For configuring Spatie Laravel Settings
- [Filament Configuration](030-core-components/040-filament-configuration.md) - For configuring Filament admin panel
- [Database Setup](040-database/010-database-setup.md) - For detailed database configuration

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Standardized document title and metadata | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, and version history | AI Assistant |
| 1.0.3 | 2025-05-25 | Updated with current 2025 documentation references and verified compatibility with Laravel 12 | AI Assistant |

---

**Previous Step:** [Spatie Settings Setup](030-core-components/020-spatie-settings-setup.md) | **Next Step:** [Filament Configuration](030-core-components/040-filament-configuration.md)
