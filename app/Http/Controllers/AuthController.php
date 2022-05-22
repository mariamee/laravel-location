<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
// to buid our Authontification system we are using sanctum
class AuthController extends Controller
{
    public function register(Request $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'ville' => $request->ville,
            'addresse' => $request->addresse,
            'cin' => $request->cin,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password)
        ]);
        /*
                to assign a role to the user we have 3 values
                        1 for Admins
                        2 for Clients
                        3 for owners or 'Partenaire'
                to create a user with a role, you have to send your request in the following format
                        {
                            "name" : "first name last name",
                            "email": "example@mail.com",
                            "password": "password",
                            "ville" : "city",
                            "photo": "url",
                            "addresse" : "string",
                            "cin": "string",
                            "telephone": "+212xxxxxxxx",
                            "role": 1 
                            // role can be 1 or 2 or 3.
                        }
             */
        $user->assignRole($request->role);

        return response()->json([
            'message' => 'user created successfully',
            'user' => $user,
            'role' => $user->getRole()
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Invalid credentials!'
            ], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24); // the token will be valid for 1 day
        // I choose to store the token in a cookie because is much more safe than sand it to the front-end in the response json! 
        return response()->json([
            'Token' =>  $token,
            'message' => 'login success',
            'user' => $user,
            'role' => $user->getRole()
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }

    public function annonceUser($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->ville = $request->ville;
        $user->addresse = $request->addresse;
        $user->cin = $request->cin;
        $user->telephone = $request->telephone;
        // $user->password = Hash::make($request->password);
        $user->update();
        return response()->json([
            'message' => 'user updated successfully',
            'user' => $user,
            'role' => $user->getRole()
        ]);
    }

    public function addProfilePicture(Request $request)
    {
        $path = "";

        if ($request->hasFile('image')) {
            $destination_path = '/public/images/profile';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($destination_path, $image_name);
        }
        $user = User::findOrFail(Auth::user()->id);
        $user->photo = $path;
        $user->update();
        return response()->json([
            'message' => 'user updated successfully',
            'user' => $user,
            'role' => $user->getRole()
        ]);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response()->json([
            'message' => 'logout succeeded'
        ])->withCookie($cookie);
    }
}
