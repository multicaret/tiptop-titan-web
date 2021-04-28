<?php

namespace App\Models\OldModels;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\OldModels\OldFirebaseUsersTokens
 *
 * @property int $id
 * @property int $user_id
 * @property int $receive_fcm_notification
 * @property string $locale
 * @property string|null $token
 * @property string|null $device_type
 * @property string|null $device_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|OldFirebaseUsersTokens newModelQuery()
 * @method static Builder|OldFirebaseUsersTokens newQuery()
 * @method static Builder|OldFirebaseUsersTokens query()
 * @method static Builder|OldFirebaseUsersTokens whereCreatedAt($value)
 * @method static Builder|OldFirebaseUsersTokens whereDeviceId($value)
 * @method static Builder|OldFirebaseUsersTokens whereDeviceType($value)
 * @method static Builder|OldFirebaseUsersTokens whereId($value)
 * @method static Builder|OldFirebaseUsersTokens whereLocale($value)
 * @method static Builder|OldFirebaseUsersTokens whereReceiveFcmNotification($value)
 * @method static Builder|OldFirebaseUsersTokens whereToken($value)
 * @method static Builder|OldFirebaseUsersTokens whereUpdatedAt($value)
 * @method static Builder|OldFirebaseUsersTokens whereUserId($value)
 * @mixin Eloquent
 */
class OldFirebaseUsersTokens extends OldModel
{
    protected $table = 'firebase_users_tokens';
    protected $primaryKey = 'id';
}
