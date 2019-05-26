<?php

namespace App\Http\Controllers;
use App\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{

    public function __construct(Company $company) {
        $this->company_model = $company;
    }

    public function home()
    {
        return view("company.index");
    }

    public function index()
    {
        $response =  $this->company_model->paginate(10);
        return $this->setCustomStatusCode(2000)->setResourceType('company')
            ->setResourceCount(count($response))
            ->setPaginatedData($response->toArray())->respondWithCollection($response->toArray());
    }

    public function create()
    {
        //
    }

    public function store()
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
