<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasLikes {

    /**
     * Gets a lowercase class name sans any namespace
     *
     * @return string
     */
    private function getClassName(): string {
        $class = strrchr(get_class(), '\\');
        if ($class[0] == '\\') $class = substr($class, 1);
        return lcfirst($class);
    }

    /**
     * Takes a user who is liking a HasLikes table and adds their like
     * Does not take into account if they've already liked it
     * 
     * @return bool 
     */
    public function like(User $user): bool {
        $classname = $this->getClassName();
        return DB::table($classname.'_likes')
            ->insert([$classname.'_id' => $this->id, 'user_id' => $user->id]);
    }

    /**
     * Takes a user who is unliking a HasLikes table and removes all likes
     * 
     * @return bool 
     */
    public function unlike(User $user): bool {
        $classname = $this->getClassName();
        return DB::table($classname($this->table))
            ->where($classname.'_id', $this->id)
            ->where('user_id', $user->id)
            ->delete();
    }

    /**
     * Takes a user and toggles a single like for the attached trait
     * 
     * @return bool 
     */
    public function toggleLike(User $user): bool {
        $classname = $this->getClassName();
        $exists = DB::table($classname.'_likes')
            ->where($classname.'_id', $user->id)
            ->where('user_id', $user->id)
            ->exists();
        return !$exists ? $this->like($user) : $this->unlike($user);
    }

    /**
     * Get's all the aggregate likes on the attached trait
     *
     * @\Illuminate\Database\Eloquent\Casts\Attribute (int)
     */
    protected function likes(): Attribute {
        $classname = $this->getClassName();
        return Attribute::make(
            get: fn() =>
                DB::table($classname.'_likes')
                    ->where($classname.'_id', $this->id)
                    ->count()
        );
    }
}
