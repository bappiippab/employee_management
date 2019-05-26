<?php

namespace App\Http\Controllers;
use App\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        /* $input = $request->all();
        $validator = $this->validateInput($input, [
            'name' => 'required',
            'email' => 'unique',
        ]); */

        $input = $request->all();
        $validator = Validator::make($input,[ 
            'name' => 'required',
            'email' => 'unique:users'
        ]);

        $error = $validator->errors()->toArray();
        if ($validator->fails()) {
            return $this->setErrorMessage("Validation failed")->setCustomStatusCode(4000)->setErrorCode(4000)->respondWithValidationError($error);
        }

        $company = $this->company_model;
        $company->name = $request->get("name");
        $company->email = $request->get("email");
        $company->logo = $request->get("logo");
        $company->website = $request->get("website");
        $response = $company->save();
        if (!!$response) {
            return $this->setCustomStatusCode(2001)->setResourceIdName('companyId')->setResourceId($response)->respondWithCreated("Company Saved Successfully");
        }
        return $this->setErrorCode(4000)->setCustomStatusCode(4000)->respondWithError("Failed to save company");
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
