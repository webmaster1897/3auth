<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserName;

class UserController extends Controller
{
    // Show the page (form + table)
    public function create()
    {
        return view('User.userpage');
    }
public function upload(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048'
    ]);

    $imageName = time() . '.' . $request->image->extension();

    $request->image->move(public_path('uploads'), $imageName);

    return response()->json([
        'status' => true,
        'message' => 'Image uploaded successfully.',
        'image' => 'uploads/'.$imageName
    ]);
}
    /**
     * Shared validation rules for store() and update().
     * $id is null on create, and the user's own id on update
     * (so the unique email rule ignores that user's current row).
     */
    private function validateUser(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:user_names,email,' . $id,
            'phone'      => 'required|numeric|digits:11',

            // password is required on create, optional on update
            // (leave blank in the form to keep the current password)
            'password'   => $id ? 'nullable|min:8' : 'required|min:8',

            'hobbies'    => 'required|array|min:1',
        ], [
            'first_name.required' => 'First name is required',
            'last_name.required'  => 'Last name is required',

            'email.required' => 'Email is required',
            'email.email'    => 'Enter a valid email',
            'email.unique'   => 'Email already exists',

            'phone.required' => 'Phone number is required',
            'phone.digits'   => 'Phone number must be 11 digits',

            'password.required' => 'Password is required',
            'password.min'      => 'Password must be at least 8 characters',

            'hobbies.required' => 'Select at least one hobby',
        ]);
    }

    // GET /users - list all users for the table
    public function index()
    {
        $users = UserName::latest()->get();

        return response()->json([
            'status' => true,
            'data'   => $users,
        ]);
    }

    // GET /users/{id} - single user, used to populate the form for editing
    public function show($id)
    {
        $user = UserName::find($id);

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $user,
        ]);
    }
public function store1(Request $request)
{
    dd($request->all());
}

    // POST /users - create
    public function store(Request $request)
    {
        $validator = $this->validateUser($request);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = UserName::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'password'   => $request->password,
            'hobbies'    => implode(',', $request->hobbies),
            'image'      => 'uploads/default.png',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'User saved successfully',
            'data'    => $user,
        ]);
    }

    // PUT /users/{id} - update
    public function update(Request $request, $id)
    {
        $user = UserName::find($id);

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        $validator = $this->validateUser($request, $id);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'hobbies'    => implode(',', $request->hobbies),
         //   'image' => $request->image,
          'image'      => 'uploads/default.png',
        ];

        // only touch the password if the user actually typed one
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'User updated successfully',
            'data'    => $user,
        ]);
    }

    // DELETE /users/{id} - delete
    public function destroy($id)
    {
        $user = UserName::find($id);

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User deleted successfully',
        ]);
    }
}






