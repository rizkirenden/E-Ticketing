<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;

class SecurityServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Custom validation untuk password kuat
        Validator::extend('strong_password', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        }, 'Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.');

        // Custom validation untuk SQL injection prevention
        Validator::extend('no_sql_injection', function ($attribute, $value, $parameters, $validator) {
            $dangerous = ['SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'UNION', '--', ';', '/*', '*/'];
            foreach ($dangerous as $pattern) {
                if (stripos($value, $pattern) !== false) {
                    return false;
                }
            }
            return true;
        }, 'Input contains forbidden patterns.');

        // Custom directive untuk CSRF token
        Blade::directive('csrf', function () {
            return '<?php echo csrf_field(); ?>';
        });
    }
}
