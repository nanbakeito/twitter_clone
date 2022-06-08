<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    // クラス定数を定義
    private const FOLLOW = 0;
    private const FOLLOWER = 1;
    private const ALL = 2;

    /**
     * Mass Assignment 可能
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * シリアライズのために隠す必要
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * cast　する必要
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * モデルの配列フォームに追加するアクセサ
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * 複数ユーザーの値取得しページネイト
     *
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getAllUsers(int $userId)
    {
        return $this->Where('id', '<>', $userId)->paginate(5);
    }

    /**
     * favoriteテーブルリレーション　
     */
    public function likes()
    {
        return $this->hasMany('App\Model\Favorite');
    }

    /**
     * フォロワーテーブルリレーション　フォロワー　（1対多）
     */
    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'followed_id', 'following_id');
    }

    /**
     * フォロワーテーブルリレーション　フォロー　（1対多）
     */
    public function follows()
    {
        return $this->belongsToMany(self::class, 'followers', 'following_id', 'followed_id');
    }

    /**
     * フォローしているかの確認
     *
     * @param  int  $userId
     * 
     * @return bool
     */
    public function isFollowing(int $userId) 
    {
        return (boolean) $this->follows()->where('followed_id', $userId)->first();
    }

    /**
     * フォローされているかの確認
     *
     * @param  int  $userId
     * 
     * @return bool
     */
    public function isFollowed(int $userId) 
    {
        return (boolean) $this->followers()->where('following_id', $userId)->first();
    }

    /**
     * フォロワー取得
     *
     * @param  array $followerIds
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollower(array $followerIds) {

        foreach ($followerIds as $followerId) {
            $follower = $this->where('id', $followerId)->get();
            $followerData[] = $follower;
        } 

        return $followerData;
    }

    /**
     * フォローしている人取得
     *
     * @param  array $followingIds
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowing(array $followingIds) {

        foreach ($followingIds as $followingId) {
            $following = $this->where('id', $followingId)->get();
            $followingData[] = $following;
        } 

        return $followingData;
    }

    /**
     * ユーザー情報更新
     *
     * @param  array  $params
     * 
     * @return void
     */
    public function updateProfile(array $params) : void
    {
        // 画像が更新される時の処理
        if (isset($params['profile_image'])) {
            $fileName = $params['profile_image']->store('public/profile_image/');

            $this::where('id', $this->id)
                ->update([
                    'name'          => $params['name'],
                    'profile_image' => basename($fileName),
                    'email'         => $params['email'],
                ]);
        } else {
            $this::where('id', $this->id)
                ->update([
                    'name'          => $params['name'],
                    'email'         => $params['email'],
                ]); 
        }
    }

    /**
     * 全ユーザーid取得
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchUserIds()
    {
        $users = User::all();

        foreach ($users as $user) {
            $ids[] = $user->id;
        };

        return $ids;
    }

    /**
     * ユーザーid取得（条件付き）
     *
     * @param  array  $checkLists
     * @param  array  $followingIds
     * @param  array  $followerIds
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchUserIdsByRequest(array $checkLists, array $followingIds, array $followerIds)
    {

        if (in_array(self::ALL, $checkLists)) {
            $ids = $this->fetchUserIds();

        } elseif (in_array(self::FOLLOW, $checkLists) and in_array(self::FOLLOWER, $checkLists)) {
            $ids = array_unique(array_merge($followingIds, $followerIds));

        } elseif(in_array(self::FOLLOW, $checkLists)) {
            $ids = $followingIds;

        } elseif(in_array(self::FOLLOWER, $checkLists)) {
            $ids = $followerIds;
        };
        
        return $ids ;
    }

    /**
     * ユーザータイムライン取得（自身以外）
     *
     * @param  array  $userIds
     * @param  int  $loginUserId
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchUserTimeLines(array $userIds ,int $loginUserId) 
    {
        $userIds = array_diff($userIds, array($loginUserId));
        $loginUser = $this->where('id', $loginUserId)->first();

        foreach($userIds as $userId) {

            $person = $this->where('id', $userId)->first();
                $userTimeLine = ([
                    'id'                    => $userId,
                    'userName'              => $person->name,
                    'userProfileImage'      => $person->profile_image,
                    'followingJudgement'    => $loginUser->isFollowing($userId),
                ]);
                $userTimeLines[] = $userTimeLine;
        }

        return $userTimeLines;
    }
}
