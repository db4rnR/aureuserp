# 1. Business Capabilities Implementation Roadmap

## 1.1. Current Business Logic Assessment

**Current State**: Basic Laravel starter with minimal business functionality
**Target State**: Multi-tenant platform with CMS, Social, PM, and eCommerce capabilities
**Implementation Status**: ~5% complete (basic user auth only)
**Confidence: 93%** - Clear gap analysis from package comparison

## 1.2. Content Management System (CMS) Capability

### 1.2.1. CMS Architecture Design

~~~markdown
**Core CMS Components**:
- Content Types: Pages, Posts, Media, Collections
- Revision System: Event-sourced content history
- Publishing Workflow: Draft → Review → Published states
- Multi-tenant Content: Organisation-scoped content trees
- Media Management: File uploads, transformations, CDN integration
~~~

### 1.2.2. CMS Implementation Strategy

~~~php
// Event-sourced content model
class Content extends Model implements EventSourced
{
    use HasEvents, BelongsToOrganisation;
    
    protected $fillable = [
        'title', 'slug', 'content_type', 'status', 
        'meta_data', 'published_at', 'organisation_id'
    ];
    
    protected $casts = [
        'meta_data' => 'array',
        'status' => ContentStatus::class,
        'published_at' => 'datetime',
    ];
    
    // Event sourcing methods
    public function recordContentCreated(array $data): void
    {
        $this->recordThat(new ContentCreated($this->id, $data));
    }
    
    public function recordContentUpdated(array $changes): void
    {
        $this->recordThat(new ContentUpdated($this->id, $changes));
    }
    
    public function recordContentPublished(): void
    {
        $this->recordThat(new ContentPublished($this->id, now()));
    }
    
    // Business logic
    public function publish(): void
    {
        if (!$this->canBePublished()) {
            throw new InvalidContentStateException();
        }
        
        $this->update([
            'status' => ContentStatus::PUBLISHED,
            'published_at' => now()
        ]);
        
        $this->recordContentPublished();
    }
    
    public function canBePublished(): bool
    {
        return $this->status->canTransitionTo(ContentStatus::PUBLISHED)
            && $this->hasRequiredFields()
            && $this->passesValidation();
    }
}

// Enhanced enum for content status
enum ContentStatus: string implements HasLabel, HasColor, HasWorkflow
{
    case DRAFT = 'draft';
    case REVIEW = 'review';
    case APPROVED = 'approved';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    
    public function getWorkflowTransitions(): array
    {
        return match($this) {
            self::DRAFT => [self::REVIEW],
            self::REVIEW => [self::DRAFT, self::APPROVED],
            self::APPROVED => [self::DRAFT, self::PUBLISHED],
            self::PUBLISHED => [self::ARCHIVED],
            self::ARCHIVED => [self::DRAFT],
        };
    }
}
~~~

### 1.2.3. CMS Filament Resources

~~~php
// Filament resource for content management
class ContentResource extends Resource
{
    protected static ?string $model = Content::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Content Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Set $set) => 
                                $set('slug', Str::slug($state))
                            ),
                            
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                            
                        Select::make('content_type')
                            ->options([
                                'page' => 'Page',
                                'post' => 'Blog Post',
                                'product' => 'Product',
                                'collection' => 'Collection'
                            ])
                            ->required(),
                            
                        StatusField::make('status')
                            ->enum(ContentStatus::class)
                            ->workflow(true),
                    ]),
                    
                Section::make('Content Body')
                    ->schema([
                        RichEditor::make('content')
                            ->toolbarButtons([
                                'blockquote', 'bold', 'bulletList', 'codeBlock',
                                'h2', 'h3', 'italic', 'link', 'orderedList',
                                'redo', 'strike', 'underline', 'undo'
                            ])
                            ->columnSpanFull(),
                    ]),
                    
                Section::make('Publishing')
                    ->schema([
                        DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->nullable(),
                            
                        Toggle::make('featured')
                            ->label('Featured Content'),
                            
                        KeyValue::make('meta_data')
                            ->label('Meta Data')
                            ->keyLabel('Property')
                            ->valueLabel('Value'),
                    ])
                    ->collapsible(),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                BadgeColumn::make('content_type')
                    ->colors([
                        'primary' => 'page',
                        'success' => 'post',
                        'warning' => 'product',
                        'danger' => 'collection',
                    ]),
                    
                BadgeColumn::make('status')
                    ->enum(ContentStatus::class)
                    ->color(fn ($state) => $state?->getColor()),
                    
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('content_type')
                    ->options([
                        'page' => 'Page',
                        'post' => 'Blog Post',
                        'product' => 'Product',
                        'collection' => 'Collection'
                    ]),
                    
                SelectFilter::make('status')
                    ->enum(ContentStatus::class),
                    
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Content $record) => route('content.preview', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
~~~

## 1.3. Social Capability Implementation

### 1.3.1. Social Features Architecture

~~~php
// Social interaction models with event sourcing
class SocialPost extends Model implements EventSourced
{
    use HasEvents, BelongsToOrganisation;
    
    protected $fillable = [
        'user_id', 'content', 'type', 'visibility',
        'parent_id', 'organisation_id'
    ];
    
    protected $casts = [
        'type' => PostType::class,
        'visibility' => PostVisibility::class,
    ];
    
    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    
    public function interactions(): HasMany
    {
        return $this->hasMany(SocialInteraction::class);
    }
    
    public function likes(): HasMany
    {
        return $this->interactions()->where('type', InteractionType::LIKE);
    }
    
    public function shares(): HasMany
    {
        return $this->interactions()->where('type', InteractionType::SHARE);
    }
    
    // Event sourcing methods
    public function recordPostCreated(): void
    {
        $this->recordThat(new SocialPostCreated($this->id, $this->toArray()));
    }
    
    public function recordPostLiked(User $user): void
    {
        $this->recordThat(new SocialPostLiked($this->id, $user->id));
    }
    
    public function recordPostShared(User $user): void
    {
        $this->recordThat(new SocialPostShared($this->id, $user->id));
    }
}

class SocialInteraction extends Model
{
    protected $fillable = [
        'user_id', 'social_post_id', 'type', 'created_at'
    ];
    
    protected $casts = [
        'type' => InteractionType::class,
        'created_at' => 'datetime',
    ];
}

enum PostType: string implements HasLabel, HasIcon
{
    case TEXT = 'text';
    case IMAGE = 'image';
    case VIDEO = 'video';
    case LINK = 'link';
    case POLL = 'poll';
    
    public function getIcon(): string
    {
        return match($this) {
            self::TEXT => 'heroicon-o-document-text',
            self::IMAGE => 'heroicon-o-photo',
            self::VIDEO => 'heroicon-o-video-camera',
            self::LINK => 'heroicon-o-link',
            self::POLL => 'heroicon-o-chart-bar',
        };
    }
}

enum InteractionType: string implements HasLabel
{
    case LIKE = 'like';
    case SHARE = 'share';
    case COMMENT = 'comment';
    case REACTION = 'reaction';
}
~~~

### 1.3.2. Social Feed AlpineJS Component

~~~javascript
// Social feed management with real-time updates
document.addEventListener('alpine:init', () => {
    Alpine.data('socialFeed', (initialPosts = []) => ({
        posts: initialPosts,
        newPostContent: '',
        selectedPostType: 'text',
        loading: false,
        
        async createPost() {
            if (!this.newPostContent.trim()) return;
            
            this.loading = true;
            
            try {
                const response = await fetch('/api/social/posts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        content: this.newPostContent,
                        type: this.selectedPostType
                    })
                });
                
                const newPost = await response.json();
                this.posts.unshift(newPost);
                this.newPostContent = '';
                
                $store.notifications.add({
                    type: 'success',
                    title: 'Post Created',
                    message: 'Your post has been shared successfully'
                });
                
            } catch (error) {
                $store.notifications.add({
                    type: 'error',
                    title: 'Error',
                    message: 'Failed to create post'
                });
            } finally {
                this.loading = false;
            }
        },
        
        async toggleLike(postId) {
            const post = this.posts.find(p => p.id === postId);
            if (!post) return;
            
            try {
                const response = await fetch(`/api/social/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const result = await response.json();
                post.likes_count = result.likes_count;
                post.user_liked = result.user_liked;
                
            } catch (error) {
                console.error('Failed to toggle like:', error);
            }
        },
        
        async sharePost(postId) {
            const post = this.posts.find(p => p.id === postId);
            if (!post) return;
            
            try {
                const response = await fetch(`/api/social/posts/${postId}/share`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const result = await response.json();
                post.shares_count = result.shares_count;
                
                $store.notifications.add({
                    type: 'success',
                    title: 'Post Shared',
                    message: 'Post has been shared to your timeline'
                });
                
            } catch (error) {
                console.error('Failed to share post:', error);
            }
        },
        
        formatTimeAgo(date) {
            const now = new Date();
            const postDate = new Date(date);
            const diffInMinutes = Math.floor((now - postDate) / 60000);
            
            if (diffInMinutes < 1) return 'Just now';
            if (diffInMinutes < 60) return `${diffInMinutes}m ago`;
            if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}h ago`;
            return `${Math.floor(diffInMinutes / 1440)}d ago`;
        }
    }));
});
~~~

## 1.4. Project Management Capability

### 1.4.1. Project Management Models

~~~php
// Event-sourced project management
class Project extends Model implements EventSourced
{
    use HasEvents, BelongsToOrganisation;
    
    protected $fillable = [
        'name', 'description', 'status', 'priority',
        'start_date', 'end_date', 'budget', 'organisation_id'
    ];
    
    protected $casts = [
        'status' => ProjectStatus::class,
        'priority' => ProjectPriority::class,
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];
    
    // Relationships
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot(['role', 'joined_at'])
                    ->withTimestamps();
    }
    
    public function timeEntries(): HasManyThrough
    {
        return $this->hasManyThrough(TimeEntry::class, Task::class);
    }
    
    // Business logic
    public function addMember(User $user, ProjectRole $role): void
    {
        $this->members()->attach($user->id, [
            'role' => $role->value,
            'joined_at' => now()
        ]);
        
        $this->recordThat(new ProjectMemberAdded($this->id, $user->id, $role));
    }
    
    public function calculateProgress(): float
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;
        
        $completedTasks = $this->tasks()
            ->where('status', TaskStatus::COMPLETED)
            ->count();
            
        return ($completedTasks / $totalTasks) * 100;
    }
    
    public function getTotalTimeSpent(): int
    {
        return $this->timeEntries()->sum('duration_minutes');
    }
    
    public function getBudgetUtilization(): float
    {
        if (!$this->budget || $this->budget <= 0) return 0;
        
        $totalTimeMinutes = $this->getTotalTimeSpent();
        $averageHourlyRate = 75; // Should come from user rates
        $totalCost = ($totalTimeMinutes / 60) * $averageHourlyRate;
        
        return ($totalCost / $this->budget) * 100;
    }
}

class Task extends Model implements EventSourced
{
    use HasEvents;
    
    protected $fillable = [
        'project_id', 'assignee_id', 'title', 'description',
        'status', 'priority', 'due_date', 'estimated_hours'
    ];
    
    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'due_date' => 'datetime',
        'estimated_hours' => 'decimal:2',
    ];
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
    
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }
    
    public function complete(): void
    {
        if ($this->status === TaskStatus::COMPLETED) return;
        
        $this->update(['status' => TaskStatus::COMPLETED]);
        $this->recordThat(new TaskCompleted($this->id, now()));
    }
    
    public function getTotalTimeSpent(): int
    {
        return $this->timeEntries()->sum('duration_minutes');
    }
    
    public function isOverdue(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status !== TaskStatus::COMPLETED;
    }
}

enum ProjectPriority: string implements HasLabel, HasColor
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case URGENT = 'urgent';
    
    public function getColor(): string
    {
        return match($this) {
            self::LOW => 'gray',
            self::MEDIUM => 'blue',
            self::HIGH => 'orange',
            self::URGENT => 'red',
        };
    }
}

enum TaskStatus: string implements HasLabel, HasColor, HasWorkflow
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case REVIEW = 'review';
    case COMPLETED = 'completed';
    case BLOCKED = 'blocked';
    
    public function getWorkflowTransitions(): array
    {
        return match($this) {
            self::TODO => [self::IN_PROGRESS, self::BLOCKED],
            self::IN_PROGRESS => [self::REVIEW, self::COMPLETED, self::BLOCKED],
            self::REVIEW => [self::IN_PROGRESS, self::COMPLETED],
            self::COMPLETED => [],
            self::BLOCKED => [self::TODO, self::IN_PROGRESS],
        };
    }
}
~~~

## 1.5. eCommerce Capability Framework

### 1.5.1. Basic eCommerce Models

~~~php
// Multi-tenant eCommerce foundation
class Product extends Model implements EventSourced
{
    use HasEvents, BelongsToOrganisation;
    
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'cost',
        'sku', 'stock_quantity', 'status', 'organisation_id'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'status' => ProductStatus::class,
    ];
    
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
    
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot(['quantity', 'price', 'total']);
    }
    
    public function adjustStock(int $adjustment, string $reason): void
    {
        $this->increment('stock_quantity', $adjustment);
        $this->recordThat(new ProductStockAdjusted(
            $this->id, 
            $adjustment, 
            $this->stock_quantity, 
            $reason
        ));
    }
    
    public function isInStock(int $quantity = 1): bool
    {
        return $this->stock_quantity >= $quantity;
    }
}

class Order extends Model implements EventSourced
{
    use HasEvents, BelongsToOrganisation;
    
    protected $fillable = [
        'user_id', 'status', 'subtotal', 'tax_amount',
        'shipping_amount', 'total', 'organisation_id'
    ];
    
    protected $casts = [
        'status' => OrderStatus::class,
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    
    public function fulfillOrder(): void
    {
        if ($this->status !== OrderStatus::PAID) {
            throw new InvalidOrderStateException();
        }
        
        $this->update(['status' => OrderStatus::FULFILLED]);
        $this->recordThat(new OrderFulfilled($this->id, now()));
        
        // Adjust stock for all items
        foreach ($this->items as $item) {
            $item->product->adjustStock(-$item->quantity, "Order fulfillment: {$this->id}");
        }
    }
}

enum OrderStatus: string implements HasLabel, HasColor, HasWorkflow
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    
    public function getWorkflowTransitions(): array
    {
        return match($this) {
            self::PENDING => [self::PAID, self::CANCELLED],
            self::PAID => [self::PROCESSING, self::CANCELLED],
            self::PROCESSING => [self::SHIPPED, self::CANCELLED],
            self::SHIPPED => [self::DELIVERED],
            self::DELIVERED => [self::REFUNDED],
            self::CANCELLED => [],
            self::REFUNDED => [],
        };
    }
}
~~~

## 1.6. Implementation Timeline and Priorities

### 1.6.1. Phase-Based Development Approach

**Phase 1: Foundation (Months 1-4)**
- Event sourcing infrastructure
- Enhanced enum system
- Basic STI models (User/Organisation)
- Confidence: 85%

**Phase 2: Core Business Capabilities (Months 5-10)**
- CMS implementation with workflow
- Project management basic features
- Social interaction foundation
- Confidence: 75%

**Phase 3: Advanced Features (Months 11-16)**
- eCommerce basic implementation
- Advanced social features
- Project management analytics
- Confidence: 65%

**Phase 4: Integration & Optimization (Months 17-18)**
- Cross-capability integrations
- Performance optimization
- Advanced reporting
- Confidence: 60%

### 1.6.2. Resource Requirements

**Development Team**:
- 1 Senior Laravel Developer (Event Sourcing expertise)
- 1 Frontend Developer (AlpineJS/Livewire)
- 1 UI/UX Developer (Filament customization)
- 1 DevOps Engineer (deployment/optimization)

**Estimated Budget**: $180,000 - $240,000 total
**Risk Assessment**: High - Complex architectural changes
**Success Dependencies**: Event sourcing expertise, clear business requirements

**Overall Confidence: 72%** - Achievable with proper team and timeline
