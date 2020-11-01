<?php namespace Ignite\Database;

class Model extends \Illuminate\Database\Eloquent\Model {
    protected $primaryKey   = 'id';
    protected $incrementing = true;
    protected $timestamps   = true;
}