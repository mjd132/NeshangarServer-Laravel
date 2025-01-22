<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Events\User\UserStatusChanged;
use App\Models\Enums\UserStatus;
use Database\Factories\UserFactory;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'token',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => UserStatus::class,
            'password' => 'hashed',
        ];
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function changeStatus(UserStatus $status, string|null $expirationInterval = null): bool
    {
        if ($status === UserStatus::BUSY && $expirationInterval !== null) {
            $timeParts = explode(':', $expirationInterval);
            $timeInterval = new DateInterval("PT{$timeParts[0]}H{$timeParts[1]}M{$timeParts[2]}S");
            $this->setExpiredAtByDateInterval($timeInterval);
        }

        if ($status !== UserStatus::BUSY) {
            $this->forgotExpiredAt();
        }

        $res = $this->update(['status' => $status->value]);
        if ($res) {
            UserStatusChanged::dispatch();
        }


        return $res;
    }

    public function forgotExpiredAt(): void
    {
        $this->expired_at = null;
        $this->save();
    }

    /**
     * @throws Exception
     */
    public function getExpiredAt(): DateTime|null
    {
        return new DateTime($this->expired_at);
    }

    public function setExpiredAtByDateInterval(DateInterval $expiration): void
    {
        $intervalString = $expiration
            ->format("PT{$expiration->h}H{$expiration->i}M{$expiration->s}S");
        $expiredAt = (new DateTime())->add($expiration);

        $this->expired_at = $expiredAt;
        $this->save();
    }
}
