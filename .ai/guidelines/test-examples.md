# Test Examples

This document provides examples for each test type and category in the testing framework. These examples can be used as templates when writing new tests.

## Unit Tests

Unit tests focus on testing individual components in isolation.

### Model Unit Test

```php
<?php
/**
 * Product Model Unit Tests
 *
 * This file contains unit tests for the Product model in the Products plugin.
 *
 * @package Tests\Unit\Plugins\Products\Models
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\Category;

/**
 * Test Product model attributes and relationships
 *
 * This test verifies that the Product model's attributes are correctly set
 * and that its relationships with other models are properly defined.
 *
 * Test data:
 * - Creates a new Product with specific attributes using the factory
 * - Tests both scalar attributes and relationships
 *
 * Assumptions:
 * - The Product factory is properly configured
 * - The Product model has the expected attributes and relationships
 *
 * Expected behavior:
 * - All attributes should match the values set during creation
 * - All relationships should be of the expected type
 */
#[Test]
#[Group('unit')]
#[Group('products')]
#[Group('database')]
#[Group('validation')]
#[PluginTest('Products')]
#[CoversClass(Product::class)]
#[Description('Test Product model attributes and relationships')]
function product_model_attributes_and_relationships()
{
    // Create a test product
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'sku' => 'TEST-001',
        'price' => 100.00,
        'description' => 'Test product description',
    ]);

    // Test attributes
    expect($product->name)->toBe('Test Product');
    expect($product->sku)->toBe('TEST-001');
    expect($product->price)->toBe(100.00);
    expect($product->description)->toBe('Test product description');

    // Test relationships
    expect($product->categories())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($product->attributes())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
}
```

### Service Unit Test

```php
<?php
/**
 * Product Service Unit Tests
 *
 * This file contains unit tests for the ProductService in the Products plugin.
 *
 * @package Tests\Unit\Plugins\Products\Services
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Services\ProductService;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductRepository;
use Mockery;

/**
 * Test ProductService create method
 *
 * This test verifies that the ProductService's create method correctly
 * creates a new product with the given attributes.
 *
 * Test data:
 * - Mock ProductRepository
 * - Test product data array
 *
 * Assumptions:
 * - The ProductService uses the ProductRepository to create products
 * - The ProductRepository's create method returns a Product model
 *
 * Expected behavior:
 * - The ProductService should call the ProductRepository's create method
 * - The ProductService should return the created Product model
 */
#[Test]
#[Group('unit')]
#[Group('products')]
#[Group('validation')]
#[PluginTest('Products')]
#[CoversClass(ProductService::class)]
#[Description('Test ProductService create method')]
function product_service_create_method()
{
    // Create mock repository
    $repository = Mockery::mock(ProductRepository::class);
    
    // Set up expectations
    $productData = [
        'name' => 'Test Product',
        'sku' => 'TEST-001',
        'price' => 100.00,
        'description' => 'Test product description',
    ];
    
    $product = new Product($productData);
    
    $repository->shouldReceive('create')
        ->once()
        ->with($productData)
        ->andReturn($product);
    
    // Create service with mock repository
    $service = new ProductService($repository);
    
    // Call the method being tested
    $result = $service->create($productData);
    
    // Assert the result
    expect($result)->toBeInstanceOf(Product::class);
    expect($result->name)->toBe('Test Product');
    expect($result->sku)->toBe('TEST-001');
    expect($result->price)->toBe(100.00);
    expect($result->description)->toBe('Test product description');
}
```

## Feature Tests

Feature tests focus on testing features from an HTTP perspective.

### API Feature Test

```php
<?php
/**
 * Product API Feature Tests
 *
 * This file contains feature tests for the Product API endpoints.
 *
 * @package Tests\Feature\Plugins\Products\API
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Models\Product;
use Webkul\Security\Models\User;

/**
 * Test product listing API endpoint
 *
 * This test verifies that the product listing API endpoint returns
 * the expected response with a list of products.
 *
 * Test data:
 * - Creates a user with appropriate permissions
 * - Creates multiple products using the factory
 *
 * Assumptions:
 * - The API endpoint requires authentication
 * - The API endpoint returns a JSON response with a list of products
 *
 * Expected behavior:
 * - The API endpoint should return a 200 status code
 * - The API endpoint should return a JSON response with a list of products
 */
#[Test]
#[Group('feature')]
#[Group('products')]
#[Group('api')]
#[Group('database')]
#[PluginTest('Products')]
#[Description('Test product listing API endpoint')]
function product_listing_api_endpoint()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();
    
    // Create products
    $products = Product::factory()->count(3)->create();
    
    // Act as the user
    actingAs($user);
    
    // Call the API endpoint
    $response = getJson('/api/products');
    
    // Assert the response
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'sku',
                'price',
                'description',
            ],
        ],
    ]);
}
```

### Controller Feature Test

```php
<?php
/**
 * Product Controller Feature Tests
 *
 * This file contains feature tests for the ProductController.
 *
 * @package Tests\Feature\Plugins\Products\Controllers
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Models\Product;
use Webkul\Security\Models\User;

/**
 * Test product creation form submission
 *
 * This test verifies that the product creation form submission
 * correctly creates a new product with the given attributes.
 *
 * Test data:
 * - Creates a user with appropriate permissions
 * - Test product data array
 *
 * Assumptions:
 * - The form submission requires authentication
 * - The form submission redirects to the product listing page on success
 *
 * Expected behavior:
 * - The form submission should create a new product
 * - The form submission should redirect to the product listing page
 */
#[Test]
#[Group('feature')]
#[Group('products')]
#[Group('database')]
#[Group('validation')]
#[PluginTest('Products')]
#[Description('Test product creation form submission')]
function product_creation_form_submission()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();
    
    // Act as the user
    actingAs($user);
    
    // Prepare product data
    $productData = [
        'name' => 'Test Product',
        'sku' => 'TEST-001',
        'price' => 100.00,
        'description' => 'Test product description',
    ];
    
    // Submit the form
    $response = post(route('admin.products.store'), $productData);
    
    // Assert the response
    $response->assertRedirect(route('admin.products.index'));
    
    // Assert the product was created
    $this->assertDatabaseHas('products', [
        'name' => 'Test Product',
        'sku' => 'TEST-001',
        'price' => 100.00,
        'description' => 'Test product description',
    ]);
}
```

## Integration Tests

Integration tests focus on testing the interactions between different components.

### Service Integration Test

```php
<?php
/**
 * Product Service Integration Tests
 *
 * This file contains integration tests for the ProductService.
 *
 * @package Tests\Integration\Plugins\Products\Services
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Services\ProductService;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\Category;

/**
 * Test product creation with categories
 *
 * This test verifies that the ProductService correctly creates a new product
 * with the given attributes and associates it with the specified categories.
 *
 * Test data:
 * - Creates categories using the factory
 * - Test product data array with category IDs
 *
 * Assumptions:
 * - The ProductService is properly configured
 * - The ProductService can create products with category associations
 *
 * Expected behavior:
 * - The ProductService should create a new product
 * - The ProductService should associate the product with the specified categories
 */
#[Test]
#[Group('integration')]
#[Group('products')]
#[Group('database')]
#[PluginTest('Products')]
#[Description('Test product creation with categories')]
function product_creation_with_categories()
{
    // Create categories
    $categories = Category::factory()->count(2)->create();
    $categoryIds = $categories->pluck('id')->toArray();
    
    // Prepare product data
    $productData = [
        'name' => 'Test Product',
        'sku' => 'TEST-001',
        'price' => 100.00,
        'description' => 'Test product description',
        'category_ids' => $categoryIds,
    ];
    
    // Get the service from the container
    $service = app(ProductService::class);
    
    // Create the product
    $product = $service->create($productData);
    
    // Assert the product was created
    expect($product)->toBeInstanceOf(Product::class);
    expect($product->name)->toBe('Test Product');
    expect($product->sku)->toBe('TEST-001');
    expect($product->price)->toBe(100.00);
    expect($product->description)->toBe('Test product description');
    
    // Assert the product was associated with the categories
    expect($product->categories)->toHaveCount(2);
    expect($product->categories->pluck('id')->toArray())->toEqual($categoryIds);
}
```

### Workflow Integration Test

```php
<?php
/**
 * Order Workflow Integration Tests
 *
 * This file contains integration tests for the order workflow.
 *
 * @package Tests\Integration\Plugins\Orders\Workflow
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use Webkul\Order\Models\Order;
use Webkul\Order\Services\OrderService;
use Webkul\Product\Models\Product;
use Webkul\Partner\Models\Partner;

/**
 * Test order creation and fulfillment workflow
 *
 * This test verifies that the order workflow correctly handles
 * the creation and fulfillment of an order.
 *
 * Test data:
 * - Creates a customer using the factory
 * - Creates products using the factory
 * - Test order data array
 *
 * Assumptions:
 * - The OrderService is properly configured
 * - The OrderService can create and fulfill orders
 *
 * Expected behavior:
 * - The OrderService should create a new order
 * - The OrderService should fulfill the order
 * - The order status should be updated to 'fulfilled'
 */
#[Test]
#[Group('integration')]
#[Group('orders')]
#[Group('database')]
#[Group('workflow')]
#[PluginTest('Orders')]
#[Description('Test order creation and fulfillment workflow')]
function order_creation_and_fulfillment_workflow()
{
    // Create a customer
    $customer = Partner::factory()->create();
    
    // Create products
    $products = Product::factory()->count(2)->create();
    
    // Prepare order data
    $orderData = [
        'customer_id' => $customer->id,
        'items' => [
            [
                'product_id' => $products[0]->id,
                'quantity' => 2,
                'price' => $products[0]->price,
            ],
            [
                'product_id' => $products[1]->id,
                'quantity' => 1,
                'price' => $products[1]->price,
            ],
        ],
    ];
    
    // Get the service from the container
    $service = app(OrderService::class);
    
    // Create the order
    $order = $service->create($orderData);
    
    // Assert the order was created
    expect($order)->toBeInstanceOf(Order::class);
    expect($order->customer_id)->toBe($customer->id);
    expect($order->items)->toHaveCount(2);
    expect($order->status)->toBe('pending');
    
    // Fulfill the order
    $fulfilledOrder = $service->fulfill($order->id);
    
    // Assert the order was fulfilled
    expect($fulfilledOrder->status)->toBe('fulfilled');
}
```

## Tests by Category

### Database Tests

```php
<?php
/**
 * Product Repository Database Tests
 *
 * This file contains database tests for the ProductRepository.
 *
 * @package Tests\Unit\Plugins\Products\Repositories
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Models\Product;

/**
 * Test product repository search method
 *
 * This test verifies that the ProductRepository's search method
 * correctly searches for products by name or SKU.
 *
 * Test data:
 * - Creates products with specific names and SKUs using the factory
 *
 * Assumptions:
 * - The ProductRepository is properly configured
 * - The ProductRepository's search method searches by name or SKU
 *
 * Expected behavior:
 * - The search method should return products matching the search term
 * - The search should be case-insensitive
 */
#[Test]
#[Group('unit')]
#[Group('products')]
#[Group('database')]
#[PluginTest('Products')]
#[Description('Test product repository search method')]
function product_repository_search_method()
{
    // Create products
    Product::factory()->create(['name' => 'Test Product A', 'sku' => 'TEST-001']);
    Product::factory()->create(['name' => 'Test Product B', 'sku' => 'TEST-002']);
    Product::factory()->create(['name' => 'Another Product', 'sku' => 'ANOTHER-001']);
    
    // Get the repository from the container
    $repository = app(ProductRepository::class);
    
    // Search by name
    $results = $repository->search('Test Product');
    expect($results)->toHaveCount(2);
    
    // Search by SKU
    $results = $repository->search('TEST-001');
    expect($results)->toHaveCount(1);
    expect($results->first()->name)->toBe('Test Product A');
    
    // Search with case-insensitive term
    $results = $repository->search('test');
    expect($results)->toHaveCount(2);
}
```

### API Tests

```php
<?php
/**
 * Product API Tests
 *
 * This file contains API tests for the Product API endpoints.
 *
 * @package Tests\Feature\Plugins\Products\API
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Models\Product;
use Webkul\Security\Models\User;

/**
 * Test product API CRUD operations
 *
 * This test verifies that the Product API endpoints correctly
 * handle CRUD operations for products.
 *
 * Test data:
 * - Creates a user with appropriate permissions
 * - Creates a product using the factory
 * - Test product data for creation and update
 *
 * Assumptions:
 * - The API endpoints require authentication
 * - The API endpoints return JSON responses
 *
 * Expected behavior:
 * - The API endpoints should handle CRUD operations correctly
 * - The API endpoints should return appropriate status codes and responses
 */
#[Test]
#[Group('feature')]
#[Group('products')]
#[Group('api')]
#[Group('database')]
#[PluginTest('Products')]
#[Description('Test product API CRUD operations')]
function product_api_crud_operations()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();
    
    // Act as the user
    actingAs($user);
    
    // Test product creation
    $createData = [
        'name' => 'API Test Product',
        'sku' => 'API-TEST-001',
        'price' => 100.00,
        'description' => 'API test product description',
    ];
    
    $response = postJson('/api/products', $createData);
    
    $response->assertStatus(201);
    $response->assertJson([
        'data' => [
            'name' => 'API Test Product',
            'sku' => 'API-TEST-001',
            'price' => 100.00,
            'description' => 'API test product description',
        ],
    ]);
    
    $productId = $response->json('data.id');
    
    // Test product retrieval
    $response = getJson("/api/products/{$productId}");
    
    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'id' => $productId,
            'name' => 'API Test Product',
            'sku' => 'API-TEST-001',
        ],
    ]);
    
    // Test product update
    $updateData = [
        'name' => 'Updated API Test Product',
        'price' => 150.00,
    ];
    
    $response = putJson("/api/products/{$productId}", $updateData);
    
    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'id' => $productId,
            'name' => 'Updated API Test Product',
            'price' => 150.00,
            'sku' => 'API-TEST-001',
        ],
    ]);
    
    // Test product deletion
    $response = deleteJson("/api/products/{$productId}");
    
    $response->assertStatus(204);
    
    // Verify the product was deleted
    $response = getJson("/api/products/{$productId}");
    $response->assertStatus(404);
}
```

### Validation Tests

```php
<?php
/**
 * Product Validation Tests
 *
 * This file contains validation tests for the Product model.
 *
 * @package Tests\Unit\Plugins\Products\Validation
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Models\Product;
use Webkul\Product\Requests\ProductRequest;
use Illuminate\Support\Facades\Validator;

/**
 * Test product validation rules
 *
 * This test verifies that the ProductRequest validation rules
 * correctly validate product data.
 *
 * Test data:
 * - Valid product data
 * - Invalid product data with various validation errors
 *
 * Assumptions:
 * - The ProductRequest class defines validation rules for products
 * - The validation rules include required fields, data types, and constraints
 *
 * Expected behavior:
 * - Valid data should pass validation
 * - Invalid data should fail validation with appropriate error messages
 */
#[Test]
#[Group('unit')]
#[Group('products')]
#[Group('validation')]
#[PluginTest('Products')]
#[Description('Test product validation rules')]
function product_validation_rules()
{
    // Get validation rules from the request class
    $rules = (new ProductRequest())->rules();
    
    // Test valid data
    $validData = [
        'name' => 'Test Product',
        'sku' => 'TEST-001',
        'price' => 100.00,
        'description' => 'Test product description',
    ];
    
    $validator = Validator::make($validData, $rules);
    expect($validator->fails())->toBeFalse();
    
    // Test missing required field
    $invalidData = $validData;
    unset($invalidData['name']);
    
    $validator = Validator::make($invalidData, $rules);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
    
    // Test invalid price format
    $invalidData = $validData;
    $invalidData['price'] = 'not-a-number';
    
    $validator = Validator::make($invalidData, $rules);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('price'))->toBeTrue();
    
    // Test negative price
    $invalidData = $validData;
    $invalidData['price'] = -10.00;
    
    $validator = Validator::make($invalidData, $rules);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('price'))->toBeTrue();
    
    // Test duplicate SKU
    Product::factory()->create(['sku' => 'TEST-001']);
    
    $validator = Validator::make($validData, $rules);
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('sku'))->toBeTrue();
}
```

### Security Tests

```php
<?php
/**
 * Product Security Tests
 *
 * This file contains security tests for the Product API endpoints.
 *
 * @package Tests\Feature\Plugins\Products\Security
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use App\Tests\Attributes\PluginTest;
use Webkul\Product\Models\Product;
use Webkul\Security\Models\User;
use Webkul\Security\Models\Role;
use Webkul\Security\Models\Permission;

/**
 * Test product API authorization
 *
 * This test verifies that the Product API endpoints correctly
 * enforce authorization rules.
 *
 * Test data:
 * - Creates users with different roles and permissions
 * - Creates a product using the factory
 *
 * Assumptions:
 * - The API endpoints require authentication
 * - The API endpoints enforce authorization based on user roles and permissions
 *
 * Expected behavior:
 * - Users with appropriate permissions should be able to access the endpoints
 * - Users without appropriate permissions should be denied access
 */
#[Test]
#[Group('feature')]
#[Group('products')]
#[Group('api')]
#[Group('security')]
#[PluginTest('Products')]
#[Description('Test product API authorization')]
function product_api_authorization()
{
    // Create a product
    $product = Product::factory()->create();
    
    // Create roles and permissions
    $viewPermission = Permission::factory()->create(['name' => 'view-products']);
    $createPermission = Permission::factory()->create(['name' => 'create-products']);
    $updatePermission = Permission::factory()->create(['name' => 'update-products']);
    $deletePermission = Permission::factory()->create(['name' => 'delete-products']);
    
    $viewerRole = Role::factory()->create(['name' => 'product-viewer']);
    $viewerRole->permissions()->attach($viewPermission);
    
    $editorRole = Role::factory()->create(['name' => 'product-editor']);
    $editorRole->permissions()->attach([$viewPermission->id, $createPermission->id, $updatePermission->id]);
    
    $adminRole = Role::factory()->create(['name' => 'product-admin']);
    $adminRole->permissions()->attach([$viewPermission->id, $createPermission->id, $updatePermission->id, $deletePermission->id]);
    
    // Create users with different roles
    $viewer = User::factory()->create();
    $viewer->roles()->attach($viewerRole);
    
    $editor = User::factory()->create();
    $editor->roles()->attach($editorRole);
    
    $admin = User::factory()->create();
    $admin->roles()->attach($adminRole);
    
    $unauthorized = User::factory()->create();
    
    // Test unauthorized user
    actingAs($unauthorized);
    getJson("/api/products")->assertStatus(403);
    getJson("/api/products/{$product->id}")->assertStatus(403);
    postJson("/api/products", [])->assertStatus(403);
    putJson("/api/products/{$product->id}", [])->assertStatus(403);
    deleteJson("/api/products/{$product->id}")->assertStatus(403);
    
    // Test viewer user
    actingAs($viewer);
    getJson("/api/products")->assertStatus(200);
    getJson("/api/products/{$product->id}")->assertStatus(200);
    postJson("/api/products", [])->assertStatus(403);
    putJson("/api/products/{$product->id}", [])->assertStatus(403);
    deleteJson("/api/products/{$product->id}")->assertStatus(403);
    
    // Test editor user
    actingAs($editor);
    getJson("/api/products")->assertStatus(200);
    getJson("/api/products/{$product->id}")->assertStatus(200);
    postJson("/api/products", [
        'name' => 'New Product',
        'sku' => 'NEW-001',
        'price' => 100.00,
    ])->assertStatus(201);
    putJson("/api/products/{$product->id}", [
        'name' => 'Updated Product',
    ])->assertStatus(200);
    deleteJson("/api/products/{$product->id}")->assertStatus(403);
    
    // Test admin user
    actingAs($admin);
    $newProduct = Product::factory()->create();
    getJson("/api/products")->assertStatus(200);
    getJson("/api/products/{$newProduct->id}")->assertStatus(200);
    postJson("/api/products", [
        'name' => 'Admin Product',
        'sku' => 'ADMIN-001',
        'price' => 100.00,
    ])->assertStatus(201);
    putJson("/api/products/{$newProduct->id}", [
        'name' => 'Updated Admin Product',
    ])->assertStatus(200);
    deleteJson("/api/products/{$newProduct->id}")->assertStatus(204);
}
```

These examples cover a wide range of test types and categories, providing templates that can be used when writing new tests for the system.
