<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasLikes {

    private function getClassName() {
        $class = strrchr(get_class(), '\\');
        if ($class[0] == '\\') $class = substr($class, 1);
        return lcfirst($class);
    }

    public function like(User $user) {
        $classname = $this->getClassName();
        return DB::table($classname.'_likes')
            ->insert([$classname.'_id' => $this->id, 'user_id' => $user->id]);
    }

    public function unlike(User $user) {
        $classname = $this->getClassName();
        return DB::table($classname($this->table))
            ->where($classname.'_id', $this->id)
            ->where('user_id', $user->id)
            ->delete();
    }

    public function toggleLike(User $user) {
        $classname = $this->getClassName();
        $exists = DB::table($classname.'_likes')
            ->where($classname.'_id', $user->id)
            ->where('user_id', $user->id)
            ->exists();
        return !$exists ? $this->like($user) : $this->unlike($user);
    }

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
