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

    public function index(Request $request)
    {
        if($request->has("type") && $request->get("type") == "all"){
            $response =  $this->company_model->get();
            return $this->setCustomStatusCode(2000)->setResourceType('company')
            ->respondWithCollection($response->toArray());
        }else{
            $response =  $this->company_model->paginate(10);
            return $this->setCustomStatusCode(2000)->setResourceType('company')
            ->setResourceCount(count($response))
            ->setPaginatedData($response->toArray())->respondWithCollection($response->toArray());
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
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
        $response = $this->company_model->where("id", $id)->first();
        return $this->setCustomStatusCode(2000)->setResourceType('company')->respondWithItem($response->toArray());
    }

    public function update($id, Request $request)
    {
        $company = $this->company_model->where("id", $id)->first();
        $company->name = $request->get("name");
        $company->email = $request->get("email");
        $company->logo = $request->get("logo");
        $company->website = $request->get("website");
        $response = $company->save();
        if (!!$response) {
            return $this->setCustomStatusCode(2002)->setResourceIdName('companyId')->setResourceId($response)->respondWithCreated("Company Updated Successfully");
        }
        return $this->setErrorCode(4000)->setCustomStatusCode(4000)->respondWithError("Failed to update company");
    }

    public function destroy($id)
    {
        $company = $this->company_model->where("id", $id)->first();
        $response = $company->delete();
        if (!!$response) {
            return $this->setCustomStatusCode(2003)->respondWithSuccess("Company Deleted Successfully");
        }
        return $this->setErrorCode(4000)->respondWithError("Failed to delete Company");
    }
}
