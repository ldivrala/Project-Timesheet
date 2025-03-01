<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'gender' => 'required|string',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create(array_merge($validatedData, ['password' => Hash::make($validatedData['password'])]));

            return $this->successResponse($user, 'User created successfully', 201);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create user', 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $this->successResponse($user, 'User retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('User not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve user', 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $query = User::query();

            if ($request->has('first_name')) {
                $query->where('first_name', $request->input('first_name'));
            }
            if ($request->has('gender')) {
                $query->where('gender', $request->input('gender'));
            }
            if ($request->has('date_of_birth')) {
                $query->where('date_of_birth', $request->input('date_of_birth'));
            }

            $users = $query->get();

            return $this->successResponse($users, 'Users retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve users', 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|exists:users,id',
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'date_of_birth' => 'sometimes|required|date',
                'gender' => 'sometimes|required|string',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $request->id,
                'password' => 'sometimes|required|string|min:8',
            ]);

            $user = User::findOrFail($request->id);
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }
            $user->update($validatedData);

            return $this->successResponse($user, 'User updated successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('User not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update user', 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate(['id' => 'required|exists:users,id']);
            $user = User::findOrFail($request->id);
            $user->delete();

            return $this->successResponse(null, 'User deleted successfully');
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('User not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete user', 500);
        }
    }
}
