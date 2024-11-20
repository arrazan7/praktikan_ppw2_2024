<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class GreetController extends Controller
{
    /**
    * @OA\Get(
    *   path="/api/greet",
    *   tags={"greeting"},
    *   summary="Returns a Sample API response",
    *   description="A sample greeting to test out the API",
    *   operationId="greet",
    *   @OA\Parameter(
    *       name="firstname",
    *       description="nama depan",
    *       required=true,
    *       in="query",
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="lastname",
    *       description="nama belakang",
    *       required=true,
    *       in="query",
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="successful operation",
    *       @OA\JsonContent(
    *           example={
    *               "success": true,
    *               "message": "Berhasil memproses masukan user",
    *               "data": {
    *                   "output": "Hello John Doe",
    *                   "firstname": "John",
    *                   "lastname": "Doe"
    *               }
    *           }
    *       ),
    *   )
    * )
    */
    public function greet(Request $request)
    {
        $userData = $request->only([
            'firstname',
            'lastname',
        ]);

        if (empty($userData['firstname']) && empty($userData['lastname'])) {
            return new \Exception('Missing data', 404);
        }

        return response()->json([
            'message' => 'Berhasil memproses masukan user',
            'success' => true,
            'data' => [
                'output' => 'Hello ' . $userData['firstname'] . ' ' . $userData['lastname'],
                'firstname' => $userData['firstname'],
                'lastname' => $userData['lastname'],
            ],
        ], 200);
    }
}
