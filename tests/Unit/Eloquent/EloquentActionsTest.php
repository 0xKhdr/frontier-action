<?php

declare(strict_types=1);

use Frontier\Actions\Eloquent\CountAction;
use Frontier\Actions\Eloquent\CreateAction;
use Frontier\Actions\Eloquent\DeleteAction;
use Frontier\Actions\Eloquent\ExistsAction;
use Frontier\Actions\Eloquent\FindAction;
use Frontier\Actions\Eloquent\FindOrFailAction;
use Frontier\Actions\Eloquent\RetrieveAction;
use Frontier\Actions\Eloquent\UpdateAction;
use Frontier\Actions\Eloquent\UpdateOrCreateAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Test model
class TestUser extends Model
{
    protected $table = 'test_users';

    protected $fillable = ['name', 'email', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}

// Setup and teardown
beforeEach(function () {
    Schema::create('test_users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::dropIfExists('test_users');
});

describe('CreateAction', function () {
    it('creates a record', function () {
        $action = new class extends CreateAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $result = $action->execute(['name' => 'John Doe', 'email' => 'john@example.com']);

        expect($result)->toBeInstanceOf(Model::class)
            ->and($result->name)->toBe('John Doe');

        $this->assertDatabaseHas('test_users', ['email' => 'john@example.com']);
    });
});

describe('FindAction', function () {
    it('finds existing record', function () {
        TestUser::create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);

        $action = new class extends FindAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $result = $action->execute(['email' => 'jane@example.com']);

        expect($result)->toBeInstanceOf(Model::class)
            ->and($result->name)->toBe('Jane Doe');
    });

    it('returns null when not found', function () {
        $action = new class extends FindAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $result = $action->execute(['email' => 'nonexistent@example.com']);

        expect($result)->toBeNull();
    });
});

describe('FindOrFailAction', function () {
    it('finds existing record', function () {
        TestUser::create(['name' => 'Bob Smith', 'email' => 'bob@example.com']);

        $action = new class extends FindOrFailAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $result = $action->execute(['email' => 'bob@example.com']);

        expect($result)->toBeInstanceOf(Model::class)
            ->and($result->name)->toBe('Bob Smith');
    });

    it('throws exception when not found', function () {
        $action = new class extends FindOrFailAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $action->execute(['email' => 'nonexistent@example.com']);
    })->throws(ModelNotFoundException::class);
});

describe('UpdateAction', function () {
    it('updates existing records', function () {
        TestUser::create(['name' => 'Old Name', 'email' => 'update@example.com']);

        $action = new class extends UpdateAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $affected = $action->execute(['email' => 'update@example.com'], ['name' => 'New Name']);

        expect($affected)->toBe(1);
        $this->assertDatabaseHas('test_users', ['name' => 'New Name', 'email' => 'update@example.com']);
    });

    it('returns zero when no match', function () {
        $action = new class extends UpdateAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $affected = $action->execute(['email' => 'nonexistent@example.com'], ['name' => 'New Name']);

        expect($affected)->toBe(0);
    });
});

describe('DeleteAction', function () {
    it('deletes records', function () {
        TestUser::create(['name' => 'To Delete', 'email' => 'delete@example.com']);

        $action = new class extends DeleteAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $deleted = $action->execute(['email' => 'delete@example.com']);

        expect($deleted)->toBe(1);
        $this->assertDatabaseMissing('test_users', ['email' => 'delete@example.com']);
    });
});

describe('CountAction', function () {
    it('counts records with conditions', function () {
        TestUser::create(['name' => 'User 1', 'email' => 'user1@example.com', 'is_active' => true]);
        TestUser::create(['name' => 'User 2', 'email' => 'user2@example.com', 'is_active' => true]);
        TestUser::create(['name' => 'User 3', 'email' => 'user3@example.com', 'is_active' => false]);

        $action = new class extends CountAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        expect($action->execute([]))->toBe(3)
            ->and($action->execute(['is_active' => true]))->toBe(2);
    });
});

describe('ExistsAction', function () {
    it('returns true when exists', function () {
        TestUser::create(['name' => 'Existing', 'email' => 'exists@example.com']);

        $action = new class extends ExistsAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        expect($action->execute(['email' => 'exists@example.com']))->toBeTrue();
    });

    it('returns false when not exists', function () {
        $action = new class extends ExistsAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        expect($action->execute(['email' => 'nonexistent@example.com']))->toBeFalse();
    });
});

describe('RetrieveAction', function () {
    it('returns all records', function () {
        TestUser::create(['name' => 'User 1', 'email' => 'user1@example.com']);
        TestUser::create(['name' => 'User 2', 'email' => 'user2@example.com']);

        $action = new class extends RetrieveAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $result = $action->execute();

        expect($result)->toHaveCount(2);
    });

    it('returns paginated results', function () {
        for ($i = 1; $i <= 15; $i++) {
            TestUser::create(['name' => "User {$i}", 'email' => "user{$i}@example.com"]);
        }

        $action = new class extends RetrieveAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $result = $action->execute(['*'], ['per_page' => 10]);

        expect($result->count())->toBe(10)
            ->and($result->total())->toBe(15);
    });
});

describe('UpdateOrCreateAction', function () {
    it('creates new record', function () {
        $action = new class extends UpdateOrCreateAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $result = $action->execute(['email' => 'new@example.com'], ['name' => 'New User']);

        expect($result)->toBeInstanceOf(Model::class)
            ->and($result->name)->toBe('New User');

        $this->assertDatabaseHas('test_users', ['email' => 'new@example.com']);
    });

    it('updates existing record', function () {
        TestUser::create(['name' => 'Old Name', 'email' => 'existing@example.com']);

        $action = new class extends UpdateOrCreateAction
        {
            public function __construct()
            {
                $this->model = new TestUser;
            }
        };

        $result = $action->execute(['email' => 'existing@example.com'], ['name' => 'Updated Name']);

        expect($result->name)->toBe('Updated Name')
            ->and(TestUser::count())->toBe(1);
    });
});
