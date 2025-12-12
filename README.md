<p align="center">
  <h1 align="center">Frontier Action</h1>
  <p align="center">
    <strong>Elegant, Reusable Action Classes for Laravel</strong>
  </p>
</p>

<p align="center">
  <a href="#installation">Installation</a> •
  <a href="#quick-start">Quick Start</a> •
  <a href="#eloquent-actions">Eloquent Actions</a> •
  <a href="#commands">Commands</a> •
  <a href="#testing">Testing</a>
</p>

<p align="center">
  <img src="https://img.shields.io/packagist/v/frontier/action" alt="Latest Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4" alt="PHP Version">
  <img src="https://img.shields.io/badge/Laravel-10|11|12-FF2D20" alt="Laravel Version">
</p>

---

## Features

- ✅ **Single Responsibility** — Encapsulate business logic into dedicated classes
- ✅ **Static Execution** — Clean `Action::exec($args)` syntax
- ✅ **Dependency Injection** — Fully supported in constructors
- ✅ **Eloquent Ready** — Pre-built CRUD actions for models
- ✅ **Module Support** — Works seamlessly with internachi/modular
- ✅ **Strict Types** — Built with modern PHP standards

---

## Installation

```bash
composer require frontier/action
```

---

## Quick Start

### 1. Generate an Action

```bash
php artisan frontier:action CreateUser
```

### 2. Implement Logic

```php
<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Frontier\Actions\BaseAction;
use Illuminate\Support\Facades\Hash;
use App\Services\NotificationService;

class CreateUser extends BaseAction
{
    public function __construct(
        protected NotificationService $notifications
    ) {}

    public function handle(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $this->notifications->sendWelcome($user);

        return $user;
    }
}
```

### 3. Execute

Use the static `exec()` helper for automatic dependency resolution:

```php
// In Controller, Job, or Command
$user = CreateUser::exec($request->validated());
```

---

## Eloquent Actions

The package includes a set of abstract actions optimized for Eloquent operations. Extend `Frontier\Actions\EloquentAction` or specific implementations to save time.

### Available Actions

| Class | Returns | Description |
|-------|---------|-------------|
| `Frontier\Actions\Eloquent\CreateAction` | `Model` | Create a new record |
| `Frontier\Actions\Eloquent\UpdateAction` | `int` | Update matching records |
| `Frontier\Actions\Eloquent\DeleteAction` | `int` | Delete matching records |
| `Frontier\Actions\Eloquent\FindAction` | `?Model` | Find a single record |
| `Frontier\Actions\Eloquent\FindOrFailAction` | `Model` | Find or throw exception |
| `Frontier\Actions\Eloquent\RetrieveAction` | `Collection\|Paginator` | Get all or paginate |
| `Frontier\Actions\Eloquent\CountAction` | `int` | Count records |
| `Frontier\Actions\Eloquent\ExistsAction` | `bool` | Check existence |
| `Frontier\Actions\Eloquent\UpdateOrCreateAction` | `Model` | Upsert record |

### Usage Example

```php
namespace App\Actions\Posts;

use App\Models\Post;
use Frontier\Actions\Eloquent\CreateAction;

class CreatePost extends CreateAction
{
    public function __construct()
    {
        // Simply set the model
        $this->model = new Post();
    }
}
```

```php
// Usage
$post = CreatePost::exec(['title' => 'Hello World']);
```

---

## Artisan Commands

### Generate Action

```bash
php artisan frontier:action [Name]
```

### Modular Generation
If you are using `internachi/modular`:

```bash
# Interactive selection
php artisan frontier:action CreateUser --module

# Direct module target
php artisan frontier:action CreateUser --module=blog
```

---

## Architecture

### The `BaseAction`

All actions extend `Frontier\Actions\BaseAction`.

1. **`exec(...$args)`**: Static entry point. Resolves class from container (injecting constructor dependencies) and calls `execute`.
2. **`execute(...$args)`**: Instance method. Calls `handle`.
3. **`handle(...$args)`**: Your logic goes here.

### Why Actions?

- **Refactoring**: Move complex logic out of Controllers.
- **Reusability**: Call the same action from a Controller, an API endpoint, and a CLI command.
- **Testing**: Test business logic in isolation without HTTP layer overhead.

---

## Testing

Action classes are easy to test because they are just simple PHP classes.

```php
use App\Actions\CreateUser;
use App\Models\User;

it('creates a user', function () {
    // Arrange
    $data = ['name' => 'Test', 'email' => 'test@example.com', 'password' => '123456'];

    // Act
    $user = CreateUser::exec($data);

    // Assert
    expect($user)->toBeInstanceOf(User::class);
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});
```

Because `BaseAction` resolves via the container, you can mock injected dependencies easily using Laravel's `mock()` or `spy()` before calling `exec()`.

---

## Development

```bash
composer test          # Run tests
composer lint          # Fix code style
composer rector        # Apply refactorings
```

---

## Related Packages

| Package | Description |
|---------|-------------|
| [frontier/frontier](https://github.com/0xKhdr/frontier) | Laravel Starter Kit |
| [frontier/repository](https://github.com/0xKhdr/frontier-repository) | Repository Pattern |
| [frontier/module](https://github.com/0xKhdr/frontier-module) | Modular Architecture |

---

## License

MIT License. See [LICENSE](LICENSE) for details.

---

<p align="center">
  Made with ❤️ by <a href="https://github.com/0xKhdr">Mohamed Khedr</a>
</p>
