<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Util\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:user',
                'username' => 'required|string|min:3|max:20',
                'birthday' => 'required|date',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, "error" => $validator->errors()], 422);
            }

            User::create([
                "email" => $request->email,
                "username" => $request->username,
                "birthday" => $request->birthday,
                "password" => bcrypt($request->password)
            ]);

            return response()->json([
                'success' => true,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = Auth::attempt($credentials)) {
                return response()->json([
                    "success" => false,
                    'error' => 'Invalid email or password'
                ], 401);
            }

            return $this->respondWithToken($token, $credentials['email']);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function myProfile(string $idUser)
    {
        try {
            $user = User::where('idUser', $idUser)->first();

            if (!$user) {
                return response()->json([
                    "success" => false,
                    "error" => "Data Not Found"
                ], 404);
            }
            return response()->json([
                "success" => true,
                "data" => $user
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editProfile(Request $request, string $iduser)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|min:3|max:20',
                'birthday' => 'required|date',
                'photoProfile' => 'image|mimes:jpg,jpeg,png|max:3000',
                'description' => 'max:280'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, "error" => $validator->errors()], 422);
            }

            $path = null;

            if ($request->hasFile('photoProfile')) {
                $image = $request->file('photoProfile');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('photo_profile', $filename, 'public');
                $path = url('storage/' . $path);

                $profileImage = User::where('idUser', $iduser)->select("photoProfile")->get();
                $oldFile = basename($profileImage[0]["photoProfile"]);
                Storage::disk('public')->delete('photo_profile/' . $oldFile);
            }

            $user = User::where("idUser", $iduser)->update([
                "username" => $request->input('username'),
                "birthday" => $request->input('birthday'),
                "description" => $request->input('description'),
                "photoProfile" => $path
            ]);

            if ($user < 1) {
                return response()->json(["success" => false, "error" => "ID User Not Found"], 404);
            }
            return response()->json(["success" => true, "data" => $user]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editPhotoProfile(Request $request, string $iduser)
    {
        try {
            $validator = Validator::make($request->all(), [
                'photoProfile' => 'image|mimes:jpg,jpeg,png|max:3000',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, "error" => $validator->errors()], 422);
            }

            $path = null;

            if ($request->hasFile('photoProfile')) {
                $image = $request->file('photoProfile');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('photo_profile', $filename, 'public');
                $path = url('storage/' . $path);

                $profileImage = User::where('idUser', $iduser)->select("photoProfile")->get();
                $oldFile = basename($profileImage[0]["photoProfile"]);
                Storage::disk('public')->delete('photo_profile/' . $oldFile);
            }

            $user = User::where("idUser", $iduser)->update([
                "photoProfile" => $path
            ]);

            if ($user < 1) {
                return response()->json(["success" => false, "error" => "ID User Not Found"], 404);
            }

            return response()->json(["success" => true, "data" => $user]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function editDescription(Request $request, string $iduser)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'max:280',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, "error" => $validator->errors()], 422);
            }

            $user = User::where("idUser", $iduser)->update([
                "description" => $request->description
            ]);

            if ($user < 1) {
                return response()->json(["success" => false, "error" => "ID User Not Found"], 404);
            }

            return response()->json(["success" => true, "data" => $user]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function respondWithToken($token, $email)
    {
        $user = User::where('email', $email)->select("idUser", 'email', 'username', 'birthday', 'photoProfile')->first();
        return response()->json([
            "success" => true,
            "credentials" => [
                'access_token' => $token,
                'token_type' => 'bearer'
            ],
            "data" => $user,
        ], 200);
    }
}
