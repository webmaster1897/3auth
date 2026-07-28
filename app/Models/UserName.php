<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserName extends Model
{
    protected $table = 'user_names';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'hobbies',
        'image'
    ];

    // Never expose the password hash in JSON responses
    protected $hidden = [
        'password',
    ];

    // Automatically included in every JSON response as "hobbies_list"
    protected $appends = [
        'hobbies_list',
    ];

    public function getHobbiesListAttribute()
    {
       return $this->hobbies ? explode(',', $this->hobbies) : [];
    }


}