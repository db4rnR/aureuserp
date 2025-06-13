# Multi-Tenancy Testing Guide

This document provides guidance on testing the multi-tenancy implementation to ensure it works correctly.

## Prerequisites

Before testing, ensure you have:

1. Installed all required packages
2. Run all migrations
3. Set up wildcard DNS for local development (e.g., `*.localhost` or Dnsmasq)
4. Created at least one root-level team

## Testing Phase 1: Foundation

### Test 1: Tenant Creation

1. Create a tenant for a root-level team:
   ```php
   $team = Team::where('parent_id', null)->first();
   $tenant = $team->createTenant('tenant1.localhost');
   ```

2. Verify the tenant was created:
   ```php
   $tenant = Tenant::where('team_id', $team->id)->first();
   echo $tenant->domain; // Should output 'tenant1.localhost'
   ```

### Test 2: Domain-Based Tenant Resolution

1. Visit the tenant domain in your browser (e.g., `http://tenant1.localhost`)
2. Verify that the application loads and the correct tenant is active:
   ```php
   echo Tenant::current()->name; // Should output the tenant name
   ```

### Test 3: Database Prefixing

1. Create a tenant-aware model:
   ```php
   class Post extends TenantAwareModel
   {
       protected $fillable = ['title', 'content'];
   }
   ```

2. Create a post in the tenant context:
   ```php
   Tenant::current($tenant);
   Post::create(['title' => 'Test Post', 'content' => 'This is a test post']);
   ```

3. Verify the post was created with the correct prefix:
   ```sql
   SELECT * FROM tenant1_posts;
   ```

### Test 4: Tenant Middleware

1. Create a route that requires a tenant:
   ```php
   Route::middleware(['web', 'tenant'])->get('/tenant-test', function () {
       return 'This route requires a tenant';
   });
   ```

2. Visit the route with a tenant domain (e.g., `http://tenant1.localhost/tenant-test`)
3. Verify that the route works
4. Visit the route with a non-tenant domain (e.g., `http://localhost/tenant-test`)
5. Verify that the route returns a 404 error

## Testing Phase 2: Tenant Management UI

### Test 1: Tenant Dashboard

1. Log in as a user with access to root-level teams
2. Visit the tenant dashboard route (e.g., `/tenants`)
3. Verify that the dashboard displays all root-level teams
4. Verify that teams with tenants show their domain
5. Verify that teams without tenants show a "Configure Tenant" button

### Test 2: Tenant Creation UI

1. Click the "Configure Tenant" button for a team without a tenant
2. Enter a domain name (e.g., `tenant2.localhost`)
3. Submit the form
4. Verify that a new tenant is created for the team
5. Verify that the tenant dashboard now shows the domain for the team

### Test 3: Tenant Switching

1. Create multiple tenants
2. Implement the tenant switcher component
3. Use the tenant switcher to switch between tenants
4. Verify that the current tenant changes
5. Verify that tenant-specific data is displayed correctly after switching

## Testing Phase 3: Advanced Features

### Test 1: Tenant Settings

1. Create tenant settings:
   ```php
   Tenant::current($tenant);
   TenantSetting::set('site_name', 'Test Tenant Site');
   TenantSetting::set('theme_color', '#336699');
   ```

2. Verify that the settings are stored correctly:
   ```php
   echo TenantSetting::get('site_name'); // Should output 'Test Tenant Site'
   echo TenantSetting::get('theme_color'); // Should output '#336699'
   ```

3. Test the settings manager service:
   ```php
   $settingsManager = app(TenantSettingsManager::class);
   echo $settingsManager->get('site_name'); // Should output 'Test Tenant Site'
   ```

4. Test the settings UI:
   - Visit the tenant settings page
   - Verify that existing settings are displayed
   - Add a new setting
   - Verify that the new setting is saved and displayed

### Test 2: Tenant Data Import/Export

1. Create test data in a tenant
2. Use the data export functionality to export the data
3. Create a new tenant
4. Use the data import functionality to import the data into the new tenant
5. Verify that the data was imported correctly

## Testing Phase 4: Filament Integration

### Test 1: Landlord Admin Panel

1. Visit the landlord admin panel (e.g., `/admin`)
2. Verify that you can see all tenants
3. Create a new tenant from the admin panel
4. Verify that the new tenant is created correctly

### Test 2: Tenant Admin Panel

1. Visit the tenant admin panel (e.g., `http://tenant1.localhost/admin`)
2. Verify that you can only see data for the current tenant
3. Create new data in the tenant admin panel
4. Verify that the data is created with the correct tenant prefix

### Test 3: Tenant Switching in Filament

1. Implement the tenant switcher in Filament
2. Use the tenant switcher to switch between tenants
3. Verify that the admin panel updates to show the selected tenant's data
4. Verify that tenant-specific resources are displayed correctly after switching

### Test 4: Tenant-Specific Resources

1. Create a tenant-specific resource (e.g., Posts)
2. Create posts in different tenants
3. Verify that each tenant only sees their own posts
4. Switch tenants and verify that the posts change accordingly

## Automated Testing

### Unit Tests

Create unit tests for the core components:

```php
class TenantTest extends TestCase
{
    public function test_tenant_creation()
    {
        $team = Team::factory()->create(['parent_id' => null]);
        $tenant = $team->createTenant('test.localhost');
        
        $this->assertNotNull($tenant);
        $this->assertEquals($team->id, $tenant->team_id);
        $this->assertEquals('test.localhost', $tenant->domain);
    }
    
    public function test_tenant_database_prefixing()
    {
        $team = Team::factory()->create(['parent_id' => null]);
        $tenant = $team->createTenant('test.localhost');
        
        Tenant::current($tenant);
        
        $post = Post::create(['title' => 'Test', 'content' => 'Content']);
        
        $this->assertDatabaseHas('tenant' . $tenant->id . '_posts', [
            'title' => 'Test',
            'content' => 'Content',
        ]);
    }
}
```

### Feature Tests

Create feature tests for the UI components:

```php
class TenantDashboardTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_tenant_dashboard_displays_tenants()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id, 'parent_id' => null]);
        $tenant = $team->createTenant('test.localhost');
        
        $this->actingAs($user)
            ->get(route('tenants.dashboard'))
            ->assertSee($tenant->name)
            ->assertSee($tenant->domain);
    }
    
    public function test_tenant_creation_form()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['owner_id' => $user->id, 'parent_id' => null]);
        
        $this->actingAs($user)
            ->post(route('tenants.store'), [
                'team_id' => $team->id,
                'domain' => 'new-tenant.localhost',
            ])
            ->assertRedirect(route('tenants.dashboard'));
        
        $this->assertDatabaseHas('tenants', [
            'team_id' => $team->id,
            'domain' => 'new-tenant.localhost',
        ]);
    }
}
```

## Troubleshooting

### Common Issues

1. **Tenant Not Found**: Ensure that the domain is correctly configured and that the tenant exists in the database.
2. **Database Prefix Issues**: Check that the database prefix is being set correctly and that the tenant-aware models are using the tenant connection.
3. **Middleware Issues**: Verify that the tenant middleware is correctly applied to the routes.
4. **Tenant Switching Issues**: Ensure that the tenant is being correctly set in the session and that the tenant connection is being updated.

### Debugging Tips

1. Use `dd(Tenant::current())` to check the current tenant
2. Use `DB::connection()->getTablePrefix()` to check the current table prefix
3. Use `DB::connection()->getName()` to check the current connection name
4. Enable query logging to see the actual SQL queries being executed
