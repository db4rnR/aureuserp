<?php

declare(strict_types=1);

namespace Webkul\Security\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Webkul\Security\Enums\PermissionType;

final readonly class UserPermissionScope implements Scope
{
    /**
     * Create a new scope instance.
     */
    public function __construct(private string $ownerRelation) {}

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if ($user->resource_permission === PermissionType::GLOBAL->value) {
            return;
        }

        if ($user->resource_permission === PermissionType::INDIVIDUAL->value) {
            $builder->whereHas($this->ownerRelation, function ($q) use ($user): void {
                $q->where('users.id', $user->id);
            });

            $builder->orWhereHas('followers', function ($q) use ($user): void {
                $q->where('chatter_followers.partner_id', $user->partner_id);
            });
        }

        if ($user->resource_permission === PermissionType::GROUP->value) {
            $teamIds = $user->teams()->pluck('id');

            $builder->whereHas("$this->ownerRelation.teams", function ($q) use ($teamIds): void {
                $q->whereIn('teams.id', $teamIds);
            });
        }
    }
}
