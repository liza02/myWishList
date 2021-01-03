<?php


namespace mywishlist\models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "message";
    protected $primaryKey = "id";
    public $timestamps = false;


    public function Liste() {
        return $this->belongsTo('\mywishlist\models\Liste', 'no');
    }

    public function Item(){
        return $this->belongsTo('\mywishlist\models\Item', 'id_item');
    }

    public function User(){
        return $this->belongsTo('\mywishlist\models\User', 'id_user');
    }
}