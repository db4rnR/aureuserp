# Laravel Naming conventions

[](/web/20250126084624/https://codersmile.com/tutorial-details/LARAVEL/undefined)

Go to Previous

[](/web/20250126084624/https://codersmile.com/tutorial-details/LARAVEL/undefined)

Go to next page

- - -

In **Laravel**, naming conventions are essential for maintaining a clean and consistent codebase. Following naming conventions makes your code more readable and understandable for other developers who might work on your project. Here are some common naming conventions used in Laravel:

## 1\. Class Names:

*   **Studly Case:** Class names should be in StudlyCase, also known as PascalCase, where each word in the class name starts with a capital letter and there are no separators between words. For example: `UserController`, `PostController`.

## 2\. Method Names:

*   **Camel Case:** Method names should be in camelCase, where the first word is in lowercase and subsequent words start with a capital letter. For example: `getUserById`, `createNewPost`.

## 3\. Variable Names:

*   **Camel Case:** Variable names should also be in camelCase. For example: `$userName`, `$postContent`.

## 4\. Table Names:

*   **Plural Form:** Table names should be in plural form. For example, if you have a table for users, the table name should be `users`, not `user`.

## 5\. Column Names:

*   **Snake Case:** Column names should be in snake\_case, where words are separated by underscores. For example: `first_name`, `email_address`.

## 6\. Route Names:

*   **Descriptive Names:** Route names should be descriptive and follow a logical naming convention. For example, if you have a route to show a user profile, you might name it `profile.show`.

## 7\. View Names:

*   **Descriptive Names:** Views should have descriptive names that reflect their purpose. For example, if you have a view for displaying user information, you might name it `user_profile.blade.php`.

## 8\. Configuration File Names:

*   **Snake Case:** Configuration file names should be in snake\_case. For example, `database.php`, `mail.php`.

## 9\. Resource Names:

*   **Plural Form:** Resource names should be in plural form to represent a collection of resources. For example, if you have a resource for managing posts, you might name it `PostsResource`.

## Follow Laravel naming conventions

Follow [PSR standards](https://web.archive.org/web/20250126084624/https://www.php-fig.org/psr/psr-12/).

Also, follow naming conventions accepted by Laravel community:

| What | How | Good | Bad |
| --- | --- | --- | --- |
| Controller | singular | ArticleController | ~ArticlesController~ |
| Route | plural | articles/1 | ~article/1~ |
| Route name | snake\_case with dot notation | users.show\_active | ~users.show-active, show-active-users~ |
| Model | singular | User | ~Users~ |
| hasOne or belongsTo relationship | singular | articleComment | ~articleComments, article\_comment~ |
| All other relationships | plural | articleComments | ~articleComment, article\_comments~ |
| Table | plural | article\_comments | ~article\_comment, articleComments~ |
| Pivot table | singular model names in alphabetical order | article\_user | ~user\_article, articles\_users~ |
| Table column | snake\_case without model name | meta\_title | ~MetaTitle; article\_meta\_title~ |
| Model property | snake\_case | $model->created\_at | ~$model->createdAt~ |
| Foreign key | singular model name with \_id suffix | article\_id | ~ArticleId, id\_article, articles\_id~ |
| Primary key | \-  | id  | ~custom\_id~ |
| Migration | \-  | 2017\_01\_01\_000000\_create\_articles\_table | ~2017\_01\_01\_000000\_articles~ |
| Method | camelCase | getAll | ~get\_all~ |
| Method in resource controller | [table](https://web.archive.org/web/20250126084624/https://laravel.com/docs/master/controllers#resource-controllers) | store | ~saveArticle~ |
| Method in test class | camelCase | testGuestCannotSeeArticle | ~test\_guest\_cannot\_see\_article~ |
| Variable | camelCase | $articlesWithAuthor | ~$articles\_with\_author~ |
| Collection | descriptive, plural | $activeUsers = User::active()->get() | ~$active, $data~ |
| Object | descriptive, singular | $activeUser = User::active()->first() | ~$users, $obj~ |
| Config and language files index | snake\_case | articles\_enabled | ~ArticlesEnabled; articles-enabled~ |
| View | kebab-case | show-filtered.blade.php | ~showFiltered.blade.php, show\_filtered.blade.php~ |
| Config | snake\_case | google\_calendar.php | ~googleCalendar.php, google-calendar.php~ |
| Contract (interface) | adjective or noun | AuthenticationInterface | ~Authenticatable, IAuthentication~ |
| Trait | adjective | Notifiable | ~NotificationTrait~ |
| Trait [(PSR)](https://web.archive.org/web/20250126084624/https://www.php-fig.org/bylaws/psr-naming-conventions/) | adjective | NotifiableTrait | ~Notification~ |
| Enum | singular | UserType | ~UserTypes~, ~UserTypeEnum~ |
| FormRequest | singular | UpdateUserRequest | ~UpdateUserFormRequest~, ~UserFormRequest~, ~UserRequest~ |
| Seeder | singular | UserSeeder | ~UsersSeeder~ |

## **Convention over configuration**

As long as you follow certain conventions, you do not need to add additional configuration.

**Bad:**

```php
// Table name 'Customer'
// Primary key 'customer_id'
class Customer extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'Customer';
    protected $primaryKey = 'customer_id';
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_customer', 'customer_id', 'role_id');
    }
}
```

**Good:**

```php
// Table name 'customers'
// Primary key 'id'
class Customer extends Model
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
```

## Use shorter and more readable syntax where possible

**Bad:**

```php
$request->session()->get('cart');
$request->input('name');
```

**Good:**

```php
session('cart');
$request->name;
```

More examples:

| Common syntax | Shorter and more readable syntax |
| --- | --- |
| `Session::get('cart')` | `session('cart')` |
| `$request->session()->get('cart')` | `session('cart')` |
| `Session::put('cart', $data)` | `session(['cart' => $data])` |
| `$request->input('name'), Request::get('name')` | `$request->name, request('name')` |
| `return Redirect::back()` | `return back()` |
| `is_null($object->relation) ? null : $object->relation->id` | `optional($object->relation)->id` (in PHP 8: `$object->relation?->id`) |
| `return view('index')->with('title', $title)->with('client', $client)` | `return view('index', compact('title', 'client'))` |
| `$request->has('value') ? $request->value : 'default';` | `$request->get('value', 'default')` |
| `Carbon::now(), Carbon::today()` | `now(), today()` |
| `App::make('Class')` | `app('Class')` |
| `->where('column', '=', 1)` | `->where('column', 1)` |
| `->orderBy('created_at', 'desc')` | `->latest()` |
| `->orderBy('age', 'desc')` | `->latest('age')` |
| `->orderBy('created_at', 'asc')` | `->oldest()` |
| `->select('id', 'name')->get()` | `->get(['id', 'name'])` |
| `->first()->name` | `->value('name')` |

## Use IoC / Service container instead of new Class

new Class syntax creates tight coupling between classes and complicates testing. Use IoC container or facades instead.

**Bad:**

```php
$user = new User;
$user->create($request->validated());
```

**Good:**

```php
public function __construct(User $user)
{
    $this->user = $user;
}
...
$this->user->create($request->validated());
```

## Do not get data from the `.env` file directly

Pass the data to config files instead and then use the `config()` helper function to use the data in an application.

**Bad:**

```php
$apiKey = env('API_KEY');
```

**Good:**

```php
// config/api.php
'key' => env('API_KEY'),
// Use the data
$apiKey = config('api.key');
```

## Store dates in the standard format. Use accessors and mutators to modify date format

A date as a string is less reliable than an object instance, e.g. a Carbon-instance. It's recommended to pass Carbon objects between classes instead of date strings. Rendering should be done in the display layer (templates):

**Bad:**

```php
{{ Carbon::createFromFormat('Y-d-m H-i', $object->ordered_at)->toDateString() }}
{{ Carbon::createFromFormat('Y-d-m H-i', $object->ordered_at)->format('m-d') }}
```

**Good:**

```php
// Model
protected $casts = [
    'ordered_at' => 'datetime',
];
// Blade view
{{ $object->ordered_at->toDateString() }}
{{ $object->ordered_at->format('m-d') }}
```

## Do not use DocBlocks

DocBlocks reduce readability. Use a descriptive method name and modern PHP features like return type hints instead.

**Bad:**

```php
/**
 * The function checks if given string is a valid ASCII string
 *
 * @param string $string String we get from frontend which might contain
 *                       illegal characters. Returns True is the string
 *                       is valid.
 *
 * @return bool
 * @author  John Smith
 *
 * @license GPL
 */
public function checkString($string)
{
}
```

**Good:**

```php
public function isValidAsciiString(string $string): bool
{
}
```

## **Other good practices**

Avoid using patterns and tools that are alien to Laravel and similar frameworks (i.e. RoR, Django). If you like Symfony (or Spring) approach for building apps, it's a good idea to use these frameworks instead.

Never put any logic in routes files.

Minimize usage of vanilla PHP in Blade templates.

Use in-memory DB for testing.

Do not override standard framework features to avoid problems related to updating the framework version and many other issues.

Use modern PHP syntax where possible, but don't forget about readability.

Avoid using View Composers and similar tools unless you really know what you're doing. In most cases, there is a better way to solve the problem.

## Use standard Laravel tools accepted by community

Prefer to use built-in Laravel functionality and community packages instead of using 3rd party packages and tools. Any developer who will work with your app in the future will need to learn new tools. Also, chances to get help from the Laravel community are significantly lower when you're using a 3rd party package or tool. Do not make your client pay for that.

| Task | Standard tools | 3rd party tools |
| --- | --- | --- |
| Authorization | Policies | Entrust, Sentinel and other packages |
| Compiling assets | Laravel Mix, Vite | Grunt, Gulp, 3rd party packages |
| Development Environment | Laravel Sail, Homestead | Docker |
| Deployment | Laravel Forge | Deployer and other solutions |
| Unit testing | PHPUnit, Mockery | Phpspec, Pest |
| Browser testing | Laravel Dusk | Codeception |
| DB  | Eloquent | SQL, Doctrine |
| Templates | Blade | Twig |
| Working with data | Laravel collections | Arrays |
| Form validation | Request classes | 3rd party packages, validation in controller |
| Authentication | Built-in | 3rd party packages, your own solution |
| API authentication | Laravel Passport, Laravel Sanctum | 3rd party JWT and OAuth packages |
| Creating API | Built-in | Dingo API and similar packages |
| Working with DB structure | Migrations | Working with DB structure directly |
| Localization | Built-in | 3rd party packages |
| Realtime user interfaces | Laravel Echo, Pusher | 3rd party packages and working with WebSockets directly |
| Generating testing data | Seeder classes, Model Factories, Faker | Creating testing data manually |
| Task scheduling | Laravel Task Scheduler | Scripts and 3rd party packages |
| DB  | MySQL, PostgreSQL, SQLite, SQL Server | MongoDB |

- - -
