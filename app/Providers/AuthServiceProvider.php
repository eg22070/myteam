<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Komanda;
use App\Models\User;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('is-owner', function ($user){
            return $user->role==='Owner';
        });
        Gate::define('is-coach-or-owner', function ($user){
            return in_array($user->role, ['Coach', 'Owner']);
        });
        Gate::define('is-coach', function ($user){
            return $user->role==='Coach';
        });
        Gate::define('access-comments-view', function ($user, Komanda $team) {

            // Condition 1: User is a Coach and their ID matches the team's coach_id
            if ($user->role === 'Coach' && $user->id === $team->coach_id) {
                return true;
            }

            // Condition 3: User is a Player and belongs to this team
            // This assumes your User model has a 'player' relationship,
            // and your Player model has a 'komandas_id' column.
            if ($user->role === 'Player'  && $user->komandas_id === $team->id) {
                return true;
            }

            // If none of the above conditions are met, access is denied
            return false;
        });
    }
}
