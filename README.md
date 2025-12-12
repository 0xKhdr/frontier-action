<p align="center">
  <h1 align="center">Frontier Action</h1>
  <p align="center">
    <strong>Elegant Action Classes for Laravel</strong>
  </p>
  <p align="center">
    Encapsulate your business logic in clean, testable, single-responsibility classes.
  </p>
</p>

<p align="center">
  <a href="#installation">Installation</a> •
  <a href="#quick-start">Quick Start</a> •
  <a href="#pre-built-actions">Pre-built Actions</a> •
  <a href="#examples">Examples</a> •
  <a href="#testing">Testing</a> •
  <a href="#license">License</a>
</p>

---

## Why Actions?

Actions (also known as Single Action Classes or Command Pattern) provide a clean way to:

- ✅ **Keep controllers thin** — Move business logic out of controllers
- ✅ **Single responsibility** — One class, one job
- ✅ **Reusable** — Use the same action in controllers, commands, jobs, etc.
- ✅ **Testable** — Isolated units with dependency injection support
- ✅ **Consistent** — Standardized pattern across your codebase

## Requirements

- PHP 8.2+
- Laravel 10.x, 11.x, or 12.x

## Installation

```bash
composer require frontier/action
```

Laravel automatically discovers the service provider. No additional configuration needed.

## Quick Start

### 1. Create an Action

```bash
php artisan frontier:action CreateUser
```

This generates `app/Actions/CreateUser.php`:

```php
<?php

namespace App\Actions;

use Frontier\Actions\EloquentAction as FrontierAction;

class CreateUser extends FrontierAction
{
    public function handle()
    {
        // Your business logic here
    }
}
```

### 2. Implement Your Logic

```php
<?php

namespace App\Actions;

use App\Models\User;
use Frontier\Actions\AbstractAction;
use Illuminate\Support\Facades\Hash;

class CreateUser extends AbstractAction
{
    public function handle(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
```

### 3. Execute the Action

```php
use App\Actions\CreateUser;

// Static execution (recommended)
$user = CreateUser::exec([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secret123',
]);
```

## Pre-built Actions

Extend these ready-to-use Eloquent actions for common CRUD operations:

| Action | Description | Returns |
|--------|-------------|---------|
| `CreateAction` | Create a new record | `Model` |
| `UpdateAction` | Update matching records | `int` |
| `DeleteAction` | Delete matching records | `int` |
| `FindAction` | Find first matching record | `?Model` |
| `FindOrFailAction` | Find or throw 404 | `Model` |
| `RetrieveAction` | Get all or paginated | `Collection\|Paginator` |
| `CountAction` | Count matching records | `int` |
| `ExistsAction` | Check if records exist | `bool` |
| `UpdateOrCreateAction` | Upsert operation | `Model` |

### Using Pre-built Actions

```php
<?php

namespace App\Actions\User;

use App\Models\User;
use Frontier\Actions\CreateAction;

class CreateUser extends CreateAction
{
    public function __construct()
    {
        $this->model = new User();
    }
}
```

```php
// Usage
$user = CreateUser::exec(['name' => 'John', 'email' => 'john@example.com']);
```

## Examples

### Action with Dependency Injection

```php
<?php

namespace App\Actions;

use App\Models\User;
use App\Services\EmailService;
use Frontier\Actions\AbstractAction;

class CreateUserWithEmail extends AbstractAction
{
    public function __construct(
        private EmailService $emailService
    ) {}

    public function handle(array $data): User
    {
        $user = User::create($data);
        
        $this->emailService->sendWelcome($user);
        
        return $user;
    }
}
```

### Using in Controllers

```php
<?php

namespace App\Http\Controllers;

use App\Actions\CreateUser;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {
        $user = CreateUser::exec($request->validated());

        return response()->json($user, 201);
    }
}
```

### Pagination with RetrieveAction

```php
<?php

namespace App\Actions\User;

use App\Models\User;
use Frontier\Actions\RetrieveAction;

class ListUsers extends RetrieveAction
{
    public function __construct()
    {
        $this->model = new User();
    }
}
```

```php
// Get all users
$users = ListUsers::exec(['id', 'name', 'email']);

// Get paginated (15 per page)
$users = ListUsers::exec(['*'], ['per_page' => 15]);
```

### Complex Business Logic

```php
<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Services\PaymentGateway;
use Frontier\Actions\AbstractAction;
use Illuminate\Support\Facades\DB;

class PlaceOrder extends AbstractAction
{
    public function __construct(
        private PaymentGateway $payment
    ) {}

    public function handle(array $items, string $paymentMethod): Order
    {
        return DB::transaction(function () use ($items, $paymentMethod) {
            $total = $this->calculateTotal($items);
            
            $this->payment->charge($total, $paymentMethod);
            
            return Order::create([
                'total' => $total,
                'status' => 'confirmed',
            ]);
        });
    }
}
```

## Testing

Actions are highly testable due to their isolation:

```php
<?php

namespace Tests\Unit\Actions;

use App\Actions\CreateUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_user(): void
    {
        $user = CreateUser::exec([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('secret'),
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
    }
}
```

### Mocking Dependencies

```php
public function test_sends_welcome_email(): void
{
    $emailService = Mockery::mock(EmailService::class);
    $emailService->shouldReceive('sendWelcome')->once();
    
    $this->app->instance(EmailService::class, $emailService);

    CreateUserWithEmail::exec([
        'name' => 'John',
        'email' => 'john@example.com',
    ]);
}
```

## Architecture

```
┌──────────────────────────────────────────────────────────┐
│                     Action Contract                       │
│                      (Interface)                          │
└──────────────────────────┬───────────────────────────────┘
                           │
┌──────────────────────────▼───────────────────────────────┐
│                    AbstractAction                         │
│              exec() → execute() → handle()                │
└──────────────────────────┬───────────────────────────────┘
                           │
┌──────────────────────────▼───────────────────────────────┐
│                    EloquentAction                         │
│                  protected $model                         │
└──────────────────────────┬───────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        ▼                  ▼                  ▼
   CreateAction      FindAction         RetrieveAction
   UpdateAction      DeleteAction       CountAction
   ...
```

## Extending Actions

### Custom Base Action with Logging

```php
<?php

namespace App\Actions;

use Frontier\Actions\AbstractAction;
use Illuminate\Support\Facades\Log;

abstract class LoggedAction extends AbstractAction
{
    public function execute(...$arguments): mixed
    {
        Log::info('Executing: ' . static::class);
        
        return parent::execute(...$arguments);
    }
}
```

### Adding Events

```php
<?php

namespace App\Actions\User;

use App\Events\UserCreated;
use App\Models\User;
use Frontier\Actions\CreateAction;

class CreateUser extends CreateAction
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function handle(array $values): User
    {
        $user = parent::handle($values);
        
        event(new UserCreated($user));
        
        return $user;
    }
}
```

## When to Use Actions

| ✅ Use Actions | ❌ Consider Alternatives |
|----------------|-------------------------|
| Business logic with multiple steps | Simple CRUD with no logic |
| Reusable operations | Model-specific behavior (use accessors) |
| Logic requiring external services | Async operations (use Jobs) |
| Complex controller methods | Cross-cutting concerns (use Middleware) |

## Related Packages

This package is part of the **Frontier** ecosystem:

- **[frontier/frontier](https://github.com/frontier/frontier)** — Laravel Starter Kit (includes this package)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/0xkhdr">Mohamed Khedr</a>
</p>
