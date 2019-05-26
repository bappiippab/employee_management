<?php

namespace App\Http\Controllers;
use App\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{

    public function __construct(Employee $employee) {
        $this->employee_model = $employee;
    }

    public function home()
    {
        return view("employee.index");
    }

    public function index()
    {
        $response =  $this->employee_model->with(["company_details"])->paginate(10);
        return $this->setCustomStatusCode(2000)->setResourceType('employee')
            ->setResourceCount(count($response))
            ->setPaginatedData($response->toArray())->respondWithCollection($response->toArray());
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[ 
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'unique:employees',
            'phone' => 'unique:employees'
        ]);

        $error = $validator->errors()->toArray();
        if ($validator->fails()) {
            return $this->setErrorMessage("Validation failed")->setCustomStatusCode(4000)->setErrorCode(4000)->respondWithValidationError($error);
        }

        $employee = $this->employee_model;
        $employee->first_name = $request->get("first_name");
        $employee->last_name = $request->get("last_name");
        $employee->company = $request->get("company");
        $employee->email = $request->get("email");
        $employee->phone = $request->get("phone");
        $response = $employee->save();
        if (!!$response) {
            return $this->setCustomStatusCode(2001)->setResourceIdName('employeeId')->setResourceId($response)->respondWithCreated("employee Saved Successfully");
        }
        return $this->setErrorCode(4000)->setCustomStatusCode(4000)->respondWithError("Failed to save employee");
    }

    public function edit($id)
    {
        $response = $this->employee_model->where("id", $id)->first();
        return $this->setCustomStatusCode(2000)->setResourceType('employee')->respondWithItem($response->toArray());
    }

    public function update($id, Request $request)
    {
        $employee = $this->employee_model->where("id", $id)->first();
        $employee->first_name = $request->get("first_name");
        $employee->last_name = $request->get("last_name");
        $employee->company = $request->get("company");
        $employee->email = $request->get("email");
        $employee->phone = $request->get("phone");
        $response = $employee->save();
        if (!!$response) {
            return $this->setCustomStatusCode(2002)->setResourceIdName('employeeId')->setResourceId($response)->respondWithCreated("Employee Updated Successfully");
        }
        return $this->setErrorCode(4000)->setCustomStatusCode(4000)->respondWithError("Failed to update employee");
    }

    public function destroy($id)
    {
        $employee = $this->employee_model->where("id", $id)->first();
        $response = $employee->delete();
        if (!!$response) {
            return $this->setCustomStatusCode(2003)->respondWithSuccess("Employee Deleted Successfully");
        }
        return $this->setErrorCode(4000)->respondWithError("Failed to delete Employee");
    }
}
