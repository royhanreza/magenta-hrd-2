<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Firebase\JWT\JWT;

class LoginApiController extends Controller
{
    public function loginEmployee(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = Employee::with(['activeCareer' => function ($query) {
            $query->with(['designation', 'department', 'jobTitle']);
        }, 'npwp', 'bpjs'])->where('email', $username)->orWhere('username', $username)->first();

        if (is_null($user)) {
            return response()->json([
                'message' => 'incorrect username or password',
                'error' => true,
                'code' => 401,
            ], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'incorrect username or password',
                'error' => true,
                'code' => 401,
            ], 401);
        }

        // if ($user->is_active_account !== "1") {
        //     return response()->json([
        //         'message' => 'this account is inactive',
        //         'error' => true,
        //         'code' => 403,
        //     ], 403);
        // }

        // if ($user->has_mobile_access !== "1") {
        //     return response()->json([
        //         'message' => 'this user doesnt have access to the app',
        //         'error' => true,
        //         'code' => 403,
        //     ], 403);
        // }

        try {
            $user->fcm_registration_token = $request->fcm_registration_token;
            $user->save();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to save token',
                'error' => true,
                'code' => 200,
                'data' => $user,
            ]);
        }

        return response()->json([
            'message' => 'OK',
            'error' => false,
            'code' => 200,
            'data' => $user,
        ]);

        // if($user) {
        //     if( Hash::check($password, $user->password) ) {
        //         if( $user->is_active_account == 1 ) {
        //             if( $user->has_mobile_access == 1 ) {
        //                 return response()->json([
        //                     'message' => 'OK',
        //                     'error' => false,
        //                     'code' => 200,
        //                     'data' => $user,
        //                 ]);
        //             } else {
        //                 return response()->json([
        //                     'message' => 'this user doesnt have access to the app',
        //                     'error' => true,
        //                     'code' => 403,
        //                 ], 403);
        //             }

        //         } else {
        //             return response()->json([
        //                 'message' => 'this account is inactive',
        //                 'error' => true,
        //                 'code' => 403,
        //             ], 403);
        //         }
        //     } else {
        //         return response()->json([
        //             'message' => 'incorrect username or password',
        //             'error' => true,
        //             'code' => 401,
        //         ], 401);
        //     }

        // } else {
        //     return response()->json([
        //         'message' => 'incorrect username or password',
        //         'error' => true,
        //         'code' => 401,
        //     ], 401);
        // }
    }

    public function loginAdmin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = Employee::with(['activeCareer' => function ($query) {
            $query->with(['designation', 'department', 'jobTitle']);
        }, 'npwp', 'bpjs'])->where('email', $username)->orWhere('username', $username)->first();

        if (is_null($user)) {
            return response()->json([
                'message' => 'incorrect username or password',
                'error' => true,
                'code' => 401,
            ], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'incorrect username or password',
                'error' => true,
                'code' => 401,
            ], 401);
        }

        // if ($user->is_active_account !== 1) {
        //     return response()->json([
        //         'message' => 'this account is inactive',
        //         'error' => true,
        //         'code' => 403,
        //     ], 403);
        // }

        // if ($user->has_mobile_access !== "1") {
        //     return response()->json([
        //         'message' => 'this user doesnt have access to the app',
        //         'error' => true,
        //         'code' => 403,
        //     ], 403);
        // }

        if ($user->mobile_access_type !== "admin") {
            return response()->json([
                'message' => 'this user doesnt have access to the admin app',
                'error' => true,
                'code' => 403,
            ], 403);
        }

        try {
            $user->fcm_registration_token = $request->fcm_registration_token;
            $user->save();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to save token',
                'error' => true,
                'code' => 200,
                'data' => $user,
            ]);
        }

        return response()->json([
            'message' => 'OK',
            'error' => false,
            'code' => 200,
            'data' => $user,
        ]);
    }

    public function logoutEmployee(Request $request)
    {
        $employee = Employee::find($request->employee_id);

        if ($employee == null) {
            return response()->json([
                'message' => 'Employee not found',
                'error' => true,
                'code' => 200,
            ]);
        }

        try {
            $employee->fcm_registration_token = null;
            $employee->save();

            return response()->json([
                'message' => 'Nullify token success',
                'error' => true,
                'code' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to nullify token',
                'error' => true,
                'code' => 200,
                'errors' => $e,
            ]);
        }
    }

    public function logoutAdmin(Request $request)
    {
        $employee = Employee::find($request->employee_id);

        if ($employee == null) {
            return response()->json([
                'message' => 'Employee not found',
                'error' => true,
                'code' => 200,
            ]);
        }

        try {
            $employee->fcm_registration_token = null;
            $employee->save();
            return response()->json([
                'message' => 'Nullify token success',
                'error' => true,
                'code' => 200,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to nullify token',
                'error' => true,
                'code' => 200,
                'errors' => $e,
            ]);
        }
    }

    public function loginDashboardEmployee(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = Employee::with(['careers' => function ($query) {
            $query->where('is_active', 1)->with(['jobTitle', 'designation', 'department'])->first();
        }])->where('email', $username)->orWhere('username', $username)->first();

        if (is_null($user)) {
            return response()->json([
                'message' => 'incorrect username or password',
                'error' => true,
                'code' => 401,
            ], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'incorrect username or password',
                'error' => true,
                'code' => 401,
            ], 401);
        }

        // if ($user->is_active_account !== "1") {
        //     return response()->json([
        //         'message' => 'this account is inactive',
        //         'error' => true,
        //         'code' => 403,
        //     ], 403);
        // }

        // if ($user->has_mobile_access !== 1) {
        //     return response()->json([
        //         'message' => 'this user doesnt have access to the app',
        //         'error' => true,
        //         'code' => 403,
        //     ], 403);
        // }

        // try {
        //     $user->fcm_registration_token = $request->fcm_registration_token;
        //     $user->save();
        // } catch (Exception $e) {
        //     return response()->json([
        //         'message' => 'Failed to save token',
        //         'error' => true,
        //         'code' => 200,
        //         'data' => $user,
        //     ]);
        // }

        return response()->json([
            'message' => 'OK',
            'error' => false,
            'code' => 200,
            'data' => $user,
        ]);
    }
}
