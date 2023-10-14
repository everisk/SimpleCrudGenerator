<?php

namespace Tests\Generators;

use Tests\TestCase;

class ModelPolicyGeneratorTest extends TestCase
{
    /** @test */
    public function it_creates_correct_model_policy_content()
    {
        $userModel = config('auth.providers.users.model');

        $this->artisan('make:crud', ['name' => $this->model_name, '--no-interaction' => true]);

        $modelPolicyPath = app_path('Policies/'.$this->model_name.'Policy.php');
        $this->assertFileExists($modelPolicyPath);
        $modelPolicyContent = "<?php

namespace App\Policies;

use {$userModel};
use {$this->full_model_name};
use Illuminate\Auth\Access\HandlesAuthorization;

class {$this->model_name}Policy
{
    use HandlesAuthorization;

    public function view(User \$user, {$this->model_name} \${$this->single_model_var_name})
    {
        // Update \$user authorization to view \${$this->single_model_var_name} here.
        return true;
    }

    public function create(User \$user, {$this->model_name} \${$this->single_model_var_name})
    {
        // Update \$user authorization to create \${$this->single_model_var_name} here.
        return true;
    }

    public function update(User \$user, {$this->model_name} \${$this->single_model_var_name})
    {
        // Update \$user authorization to update \${$this->single_model_var_name} here.
        return true;
    }

    public function delete(User \$user, {$this->model_name} \${$this->single_model_var_name})
    {
        // Update \$user authorization to delete \${$this->single_model_var_name} here.
        return true;
    }
}
";
        $this->assertEquals($modelPolicyContent, file_get_contents($modelPolicyPath));

        $authSPPath = app_path('Providers/AuthServiceProvider.php');
        $this->assertFileExists($authSPPath);
        $authSPContent = "<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected \$policies = [
        '{$this->full_model_name}' => 'App\Policies\\{$this->model_name}Policy',
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        \$this->registerPolicies();

        //
    }
}
";
        $this->assertEquals($authSPContent, file_get_contents($authSPPath));
    }

    /** @test */
    public function it_creates_correct_model_policy_content_with_parent()
    {
        $userModel = config('auth.providers.users.model');

        $this->artisan('make:crud', ['name' => $this->model_name, '--parent' => 'Projects', '--no-interaction' => true]);

        $modelPolicyPath = app_path('Policies/Projects/'.$this->model_name.'Policy.php');
        $this->assertFileExists($modelPolicyPath);
        $modelPolicyContent = "<?php

namespace App\Policies\Projects;

use {$userModel};
use {$this->full_model_name};
use Illuminate\Auth\Access\HandlesAuthorization;

class {$this->model_name}Policy
{
    use HandlesAuthorization;

    public function view(User \$user, {$this->model_name} \${$this->single_model_var_name})
    {
        // Update \$user authorization to view \${$this->single_model_var_name} here.
        return true;
    }

    public function create(User \$user, {$this->model_name} \${$this->single_model_var_name})
    {
        // Update \$user authorization to create \${$this->single_model_var_name} here.
        return true;
    }

    public function update(User \$user, {$this->model_name} \${$this->single_model_var_name})
    {
        // Update \$user authorization to update \${$this->single_model_var_name} here.
        return true;
    }

    public function delete(User \$user, {$this->model_name} \${$this->single_model_var_name})
    {
        // Update \$user authorization to delete \${$this->single_model_var_name} here.
        return true;
    }
}
";
        $this->assertEquals($modelPolicyContent, file_get_contents($modelPolicyPath));

        $authSPPath = app_path('Providers/AuthServiceProvider.php');
        $this->assertFileExists($authSPPath);
        $authSPContent = "<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected \$policies = [
        '{$this->full_model_name}' => 'App\Policies\Projects\\{$this->model_name}Policy',
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        \$this->registerPolicies();

        //
    }
}
";
        $this->assertEquals($authSPContent, file_get_contents($authSPPath));
    }

    /** @test */
    public function it_doesnt_override_the_existing_model_policy_content()
    {
        $userModel = config('auth.providers.users.model');

        $this->artisan('make:policy', ['name' => $this->model_name.'Policy', '--no-interaction' => true]);
        $this->artisan('make:crud', ['name' => $this->model_name, '--no-interaction' => true]);

        $modelPolicyPath = app_path('Policies/'.$this->model_name.'Policy.php');
        $this->assertFileExists($modelPolicyPath);
        $modelPolicyContent = "<?php

namespace App\Policies;

use {$userModel};

class {$this->model_name}Policy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
}
";
        $this->assertEquals($modelPolicyContent, file_get_contents($modelPolicyPath));
    }
}
