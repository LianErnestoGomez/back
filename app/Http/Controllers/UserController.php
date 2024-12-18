<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = User::all();
            if ($user->isEmpty()){
                return response()->json([
                    "success" => false,
                    "message" => "This model is empty"
                ], Response::HTTP_BAD_REQUEST);
            } else{
                return UserResource::collection($user);
            }    
        } catch (\Throwable $th) {
            throw $th;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }     
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                "name"  => "string|required|min:3|max:255",
                "email" => "string|required|email",
                "role"  => "required|in:manager,revisor,comprador"
            ]);

            if ($validator->fails()){
                return response()->json([
                    'error' => 'Validation error',
                    'messages' => $validator->errors(),
                ], 422);
            } else {
                $user = new User();
                $user->name     = $request->get('name');
                $user->email    = $request->get('email');
                $user->role     = $request->get('role');
                $user->save();

                return response()->json([
                    "success" => true,
                    "message" => "User saved successfully"
                ], Response::HTTP_CREATED);
            }
        } catch (\Throwable $th) {
            throw $th;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }     
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::all();
            if ($user->isEmpty()){
                return response()->json([
                    "success" => false,
                    "message" => "This model is empty"
                ], Response::HTTP_BAD_REQUEST);
            } else{
                return UserResource::collection($user);
            }    
        } catch (\Throwable $th) {
            throw $th;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = User::findorfail($request->user);
            $validator = Validator::make($request->all(),[
                "name"  => "string|required|min:3|max:255",
                "email" => "string|required|email",
                "role"  => "required|in:manager,revisor,comprador"
            ]);

            if ($validator->fails()){
                return response()->json([
                    'error' => 'Validation error',
                    'messages' => $validator->errors(),
                ], 422);
            } else {
                $user->name     = $request->get('name');
                $user->email    = $request->get('email');
                $user->role     = $request->get('role');
                $user->save();

                return response()->json([
                    "success" => true,
                    "message" => "User updated successfully"
                ], Response::HTTP_OK);
            }  
        } catch (\Throwable $th) {
            throw $th;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $user = User::findorfail($request->user);
            $user->delete();

            return response()->json([
                "success" => true,
                "message" => "User deleted successfully"
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }     
    }
    /**
     * Filter by role
     */
    public function Filter($role){
        try {
            $filtro = User::Where('role',$role)->get();

            if($filtro->isEmpty()){
                return response()->json([
                    "success" => false,
                    "message" => "This role not exists"
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                return UserResource::collection($filtro);
            }
        } catch (\Throwable $th) {
            throw $th;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }     
    }
    /**
     * Filter Statistics for role
     */
    public function Statistics($role){
        try {
            $filtro = User::Where('role',$role)->count();            
                return response()->json([
                    "success" => false,
                    "mount" => $filtro,
                    "message" => "The amount that exists is: ". $filtro
                ], Response::HTTP_OK);

        } catch (\Throwable $th) {
            throw $th;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }     
    }
    public function StatisticsAll(){
        try {
            $roles = User::select('role')
                ->distinct()
                ->pluck('role');
            
            $statistics = [];
            foreach($roles as $role) {
                $statistics[$role] = User::where('role', $role)->count();
            }
            
            return response()->json([
                "success" => true,
                "statistics" => $statistics
            ], Response::HTTP_OK);
            
        }catch (\Throwable $th) {
            throw $th;
        }   
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRoles(){
        try {
            $roles = User::select('role')
                ->distinct()
                ->pluck('role');    

                return response()->json([
                    "success" => true,
                    "roles" => $roles
                ], Response::HTTP_OK);
            }catch (\Throwable $th) {
                throw $th;
            }   
            catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }   
}
