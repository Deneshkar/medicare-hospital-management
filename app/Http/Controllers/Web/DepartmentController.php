<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('doctors')->paginate(10);

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        $department = Department::create($request->validated());

        return redirect()->route('web.departments.show', $department)
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $department->loadCount('doctors');

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(StoreDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        return redirect()->route('web.departments.show', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('web.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
