<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Note;
use App\Models\Comment;
use App\Models\Tag;
use App\Policies\NotePolicy;
use App\Policies\CommentPolicy;
use App\Policies\TagPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Note::class => NotePolicy::class,
        Comment::class => CommentPolicy::class,
        Tag::class => TagPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
