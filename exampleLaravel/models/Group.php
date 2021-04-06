<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public static function addUserToGroup($group_id, $user_id) {
        $data = ['group_id' => $group_id, 'linked_user_id' => $user_id];
        $record = Group_user::firstOrCreate($data);
        return $record;
    }

    public function users()
    {
        return $this->belongsToMany(
            'App\User',
            'App\Group_user',
            'group_id',
            'linked_user_id',
            'id',
            'id'
        );
    }

    public function groupUsers()
    {
        return $this->hasMany(
            'App\Group_user'
        );
    }

    public static function getImgUrl($group)
    {
        return !empty($group->image)
            ? url('/').'/user_storage/groups/group'.$group->id .'/images/'.$group->image
            : 'statics/avatars/group_avatar_default.png';
    }

    public static function openUserGroupsIDs($users) {
        $result = [];
        foreach ($users as $user) {
            if (isset($user['isGroup'])) {
                $group = Group::find($user['id']);
                foreach ($group->users as $gu) {
                    $result[] =  $gu->id;
                }
            } else {
                $result[] =  $user['id'];
            }
        }
        $result = array_unique($result);
        return array_values($result);
    }
}
