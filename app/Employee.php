<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function company_details()
    {
        return $this->hasOne(Company::class, 'id', 'company');
    }
}
