<?php
namespace App\Http\Controllers;

use App\Models\Timesheet;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TimesheetController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'task_name' => 'required|string|max:255',
                'date' => 'required|date',
                'hours' => 'required|numeric|min:0',
                'user_id' => 'required|exists:users,id',
                'project_id' => 'required|exists:projects,id',
            ]);

            $timesheet = Timesheet::create($validatedData);

            return $this->successResponse($timesheet, 'Timesheet created successfully', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create timesheet', 500);
        }
    }

    public function show($id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            return $this->successResponse($timesheet, 'Timesheet retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Timesheet not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve timesheet', 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = Timesheet::query();

            if ($request->has('task_name')) {
                $query->where('task_name', $request->input('task_name'));
            }
            if ($request->has('date')) {
                $query->where('date', $request->input('date'));
            }
            if ($request->has('user_id')) {
                $query->where('user_id', $request->input('user_id'));
            }
            if ($request->has('project_id')) {
                $query->where('project_id', $request->input('project_id'));
            }

            $timesheets = $query->get();

            return $this->successResponse($timesheets, 'Timesheets retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve timesheets', 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|exists:timesheets,id',
                'task_name' => 'sometimes|required|string|max:255',
                'date' => 'sometimes|required|date',
                'hours' => 'sometimes|required|numeric|min:0',
                'user_id' => 'sometimes|required|exists:users,id',
                'project_id' => 'sometimes|required|exists:projects,id',
            ]);

            $timesheet = Timesheet::findOrFail($request->id);
            $timesheet->update($validatedData);

            return $this->successResponse($timesheet, 'Timesheet updated successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Timesheet not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update timesheet', 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate(['id' => 'required|exists:timesheets,id']);
            $timesheet = Timesheet::findOrFail($request->id);
            $timesheet->delete();

            return $this->successResponse(null, 'Timesheet deleted successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Timesheet not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete timesheet', 500);
        }
    }
}
