<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'status',
        'priority',
        'due_date',
    ];

    /**
     * Get the user assigned to the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the subtasks for the project.
     */
    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
}
