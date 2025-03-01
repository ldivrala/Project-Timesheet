<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'required|string',
            ]);

            $project = Project::create($validatedData);

            return $this->successResponse($project, 'Project created successfully', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create project', 500);
        }
    }

    public function show($id)
    {
        try {
            $project = Project::findOrFail($id);
            return $this->successResponse($project, 'Project retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Project not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve project', 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Project::query();

            if ($request->has('name')) {
                $query->where('name', $request->input('name'));
            }
            if ($request->has('department')) {
                $query->where('department', $request->input('department'));
            }

            $projects = $query->get();

            return $this->successResponse($projects, 'Projects retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve projects', 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|exists:projects,id',
                'name' => 'sometimes|required|string|max:255',
                'department' => 'sometimes|required|string|max:255',
                'start_date' => 'sometimes|required|date',
                'end_date' => 'sometimes|nullable|date|after_or_equal:start_date',
                'status' => 'sometimes|required|string',
            ]);

            $project = Project::findOrFail($request->id);
            $project->update($validatedData);

            return $this->successResponse($project, 'Project updated successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Project not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update project', 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate(['id' => 'required|exists:projects,id']);
            $project = Project::findOrFail($request->id);
            $project->delete();

            return $this->successResponse(null, 'Project deleted successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Project not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete project', 500);
        }
    }
}
