<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\LawFirm;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255|:users,name',
            'address' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|unique:law_firm,email', // 
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'userType' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // ✅ Create the Law Firm entry
        $lawFirm = LawFirm::create([
            'name' => $request->company_name,
            'email' => $request->company_email,
            'address' => $request->address,
            'phone' => $request->phone,
            'website' => $request->website,
        ]);

        // ✅ Create the User entry
        $user = User::create([
            'name' => $request->name,
            'userType' => $request->userType,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'law_firm_id' => $lawFirm->id, // if your users table has this foreign key
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Law firm and user created successfully',
            'data' => [
                'law_firm' => $lawFirm,
                'user' => $user,
            ],
        ], 201);
    }

    public function createEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
            'law_firm_id' => 'nullable|exists:law_firm,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'userType' => 'employee', // fixed value
            'law_firm_id' => $request->law_firm_id ?? auth()->user()->law_firm_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Employee created successfully',
            'data' => $user,
        ]);
    }


    public function listEmployees(Request $request)
    {
        $lawFirmId = auth()->user()->law_firm_id;
        $employees = User::where('law_firm_id', $lawFirmId)
                        ->where('userType', 'employee')
                        ->get();

        return response()->json([
            'status' => true,
            'data' => $employees,
        ]);
    }


    public function login(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:4',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication Failed',
                'errors' => $validateUser->errors()->all(),
            ], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'User has been logged in successfully',
                'token' => $authUser->createToken('User API Token')->plainTextToken,
                'token_type' => 'bearer',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Email and Password not matched',
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User has been logged out successfully',
        ], 200);
    }
    public function profile(Request $request)
    {
        $user = $request->user();
        $lawFirm = $user->lawFirm; // if relation exists
        return response()->json([
            'status' => true,
            'user' => $user,
            'law_firm' => $lawFirm,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|unique:law_firm,email,' . $user->lawFirm->id,
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'userType' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // ✅ Update law firm
        $lawFirm = $user->lawFirm;
        $lawFirm->update([
            'name' => $request->company_name,
            'userType' => $request->company_name,
            'email' => $request->company_email,
            'address' => $request->address,
            'phone' => $request->phone,
            'website' => $request->website,
        ]);

        // ✅ Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,
            'law_firm' => $lawFirm,
        ]);
    }
    public function update(Request $request, $id)
    {
        // ✅ Find the employee
        $employee = User::find($id);

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found',
            ], 404);
        }

        // ✅ Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // ✅ Update employee details
        $employee->name = $request->name;
        $employee->email = $request->email;

        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();

        return response()->json([
            'status' => true,
            'message' => 'Employee updated successfully',
            'data' => $employee,
        ]);
    }

    public function me()
    {
        // return response()->json(auth()->user());

        $user = auth()->user()->load('lawFirm'); // eager load the law firm

        return response()->json([
            'status' => true,
            'user' => $user,
            'law_firm' => $user->lawFirm,
        ]);

    }
}
