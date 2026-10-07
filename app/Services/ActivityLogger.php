<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Ghi một hoạt động vào
     * nhật ký hệ thống.
     */
    public static function log(
        string $action,
        string $description,
        ?Model $entity = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?User $actor = null
    ): ActivityLog {
        /*
        |--------------------------------------------------------------------------
        | ACTOR
        |--------------------------------------------------------------------------
        */

        if (!$actor) {
            $authenticatedUser =
                Auth::user();


            if (
                $authenticatedUser
                instanceof User
            ) {
                $actor =
                    $authenticatedUser;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ROLE SNAPSHOT
        |--------------------------------------------------------------------------
        */

        $roleCode =
            null;


        if ($actor) {
            if (
                !$actor
                    ->relationLoaded(
                        'role'
                    )
            ) {
                $actor->load(
                    'role'
                );
            }


            $roleCode =
                $actor
                    ->role
                    ?->code;
        }


        /*
        |--------------------------------------------------------------------------
        | REQUEST CONTEXT
        |--------------------------------------------------------------------------
        */

        $ipAddress =
            null;


        $userAgent =
            null;


        if (
            !app()
                ->runningInConsole()
        ) {
            $ipAddress =
                request()->ip();


            $userAgent =
                request()
                    ->userAgent();


            if ($userAgent) {
                $userAgent =
                    mb_substr(
                        $userAgent,
                        0,
                        1000
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ENTITY
        |--------------------------------------------------------------------------
        */

        $entityType =
            null;


        $entityId =
            null;


        if ($entity) {
            $entityType =
                class_basename(
                    $entity
                );


            $entityId =
                $entity
                    ->getKey();
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE LOG
        |--------------------------------------------------------------------------
        */

        return ActivityLog::query()
            ->create([
                'user_id' =>
                    $actor
                        ?->id,

                'role_code' =>
                    $roleCode,

                'action' =>
                    strtoupper(
                        trim(
                            $action
                        )
                    ),

                'entity_type' =>
                    $entityType,

                'entity_id' =>
                    $entityId,

                'description' =>
                    $description,

                'old_values' =>
                    $oldValues,

                'new_values' =>
                    $newValues,

                'ip_address' =>
                    $ipAddress,

                'user_agent' =>
                    $userAgent,
            ]);
    }
}