<?php
namespace mywishlist\models;

class User extends \Illuminate\Database\Eloquent\Model {
	protected $table = 'user';
	protected $primaryKey = 'id';
	public $timestamps = false;

    public function Liste() {
        return $this->hasOne('\mywishlist\models\Liste', 'user_id');
    }
	
}