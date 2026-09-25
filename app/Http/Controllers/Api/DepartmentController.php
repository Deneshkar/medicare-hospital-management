<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('doctors')->paginate(10);

        return DepartmentResource::collection($departments)
            ->additional(['success' => true]);
    }

    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->validated());

        return (new DepartmentResource($department))
            ->additional(['success' => true, 'message' => 'Department created successfully']);
    }

    public function show(Department $department)
    {
        return (new DepartmentResource($department->loadCount('doctors')))
            ->additional(['success' => true]);
    }

    public function update(StoreDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        return (new DepartmentResource($department))
            ->additional(['success' => true, 'message' => 'Department updated successfully']);
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json(['success' => true, 'message' => 'Department deleted successfully']);
    }
}
