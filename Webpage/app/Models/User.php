<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google2fa_secret',
        'username',
        'first_name',
        'last_name',
        'avatar',
        'header_banner',
        'avatars_gallery',
        'headers_gallery',
        'profile_theme_color',
        'account_privacy',
        'post_privacy',
        'story_privacy',
        'bio',
        'social_links',
        'provider_name',
        'provider_id',
        'deleted_reason',
        'deleted_custom_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'social_links' => 'array',
            'avatars_gallery' => 'array',
            'headers_gallery' => 'array',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        $subject = 'Reset Password Notification - Parsa Besharat';
        $userName = $this->name ?? 'User';

        $htmlContent = view('emails.password-reset', [
            'userName' => $userName,
            'resetUrl' => $resetUrl,
        ])->render();

        // 1. Try sending via Laravel Mailer
        try {
            \Illuminate\Support\Facades\Mail::html($htmlContent, function ($message) use ($subject) {
                $message->to($this->email)
                        ->subject($subject)
                        ->from('noreply@parsabe.com', 'Parsa Besharat');
            });
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Laravel Password Reset Mailer failed: ' . $e->getMessage());
        }

        // 2. Native PHP mail fallback (HTML format)
        $headers = "MIME-Version: 1.0\r\n" .
                   "Content-Type: text/html; charset=UTF-8\r\n" .
                   "From: Parsa Besharat <noreply@parsabe.com>\r\n" .
                   "Reply-To: noreply@parsabe.com\r\n" .
                   "X-Mailer: PHP/" . phpversion();
        @mail($this->email, $subject, $htmlContent, $headers);
    }

    /**
     * User's published blog articles.
     */
    public function articles()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    /**
     * Users following this user.
     */
    public function followers()
    {
        return $this->hasMany(UserFollow::class, 'following_id');
    }

    /**
     * Users this user is following.
     */
    public function following()
    {
        return $this->hasMany(UserFollow::class, 'follower_id');
    }

    /**
     * Check if user is following target user ID.
     */
    public function isFollowing($targetUserId)
    {
        return $this->following()->where('following_id', $targetUserId)->exists();
    }

    /**
     * Twitter/X style user profile posts.
     */
    public function userPosts()
    {
        return $this->hasMany(UserPost::class, 'user_id');
    }

    /**
     * Get avatar URL with proper fallback per user.
     */
    public function getAvatarUrlAttribute()
    {
        if (!empty($this->avatar)) {
            return asset($this->avatar);
        }
        if ($this->email === 'parsabe99@gmail.com') {
            return asset('images/profile.jpg');
        }
        return asset('images/default-avatar.svg');
    }
}

