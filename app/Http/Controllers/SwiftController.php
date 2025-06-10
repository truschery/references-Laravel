<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Swift;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

use App\Exports\SwiftExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SwiftImport;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Jobs\NotifyUserOfCompletedExport;


class SwiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedFilters = ['swift_code', 'bank_name', 'country', 'city', 'address'];
        $swiftQuery = Swift::query();

        foreach($allowedFilters as $allowedFilter){
            if($request->has($allowedFilter)){
                $swiftQuery->where($allowedFilter, 'ILIKE', '%' . $request->input($allowedFilter) . '%');
            }
        }


        $sort = $request->input('sort', 'swift_code');
        $sort = in_array($sort, $allowedFilters) ? $sort : 'swift_code';

        $direction = $request->input('direction', 'asc');
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'asc';


        return response()->json([
                    'message' => 'Успешно',
                    'data' => $swiftQuery->orderBy($sort, $direction)->paginate(50),
                    'timestamp' => time(),
                    'success' => true,
                ], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'swift_code' => 'required|string|unique:swifts',
            'bank_name' => 'required|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
        ]);

        $user = $request->user();

        $swift = new Swift();
        $swift->swift_code = $request->swift_code;
        $swift->bank_name = $request->bank_name;
        $swift->country = $request->country;
        $swift->city = $request->city;
        $swift->address = $request->address;
        $swift->created_by = $user->id;
        $swift->updated_by = $user->id;
        $swift->save();


        return response()->json([
            'message' => 'Успешно',
            'data' => $swift,
            'timestamp' => time(),
            'success' => true,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       try {
            $swift = Swift::where('id', $id)->firstOrFail();

            return response()->json([
               'message' => 'Успешно',
               'data' => $swift,
               'timestamp' => time(),
               'success' => true,
            ], 200);
       }catch(ModelNotFoundException $error){
            return response()->json([
                  'message' => 'Запись не найдена',
                  'data' => null,
                  'timestamp' => time(),
                  'success' => false,
              ], 404);
       }catch(QueryException $error){
            return response()->json([
                 'message' => $error->getMessage(),
                 'data' => null,
                 'timestamp' => time(),
                 'success' => false,
             ], 404);
       }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                'swift_code' => 'string|unique:swifts',
                'bank_name' => 'string',
                'country' => 'string',
                'city' => 'string',
                'address' => 'string',
            ]);

            $user = $request->user();



            $swift = Swift::where('id', $id)->firstOrFail();

            if(!empty($request->swift_code)){
                $swift->swift_code = $request->swift_code;
            }

            if(!empty($request->bank_name)){
                $swift->bank_name = $request->bank_name;
            }

            if(!empty($request->country)){
                $swift->country = $request->country;
            }
            if(!empty($request->city)){
                $swift->city = $request->city;
            }
            if(!empty($request->address)){
                $swift->address = $request->address;
            }

            $swift->updated_by = $user->id;
            $swift->save();


            return response()->json([
                'message' => 'Успешно',
                'data' => $swift,
                'timestamp' => time(),
                'success' => true,
            ], 200);
        }catch(ModelNotFoundException $error){
             return response()->json([
                   'message' => 'Запись не найдена',
                   'data' => null,
                   'timestamp' => time(),
                   'success' => false,
               ], 404);
        }catch(QueryException $error){
             return response()->json([
                  'message' => $error->getMessage(),
                  'data' => null,
                  'timestamp' => time(),
                  'success' => false,
              ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $swift = Swift::where('id', $id)->firstOrFail();

            $swift->delete();

            return response()->json([
               'message' => 'Успешно',
               'data' => null,
               'timestamp' => time(),
               'success' => true,
            ], 200);
       }catch(ModelNotFoundException $error){
            return response()->json([
                  'message' => 'Запись не найдена',
                  'data' => null,
                  'timestamp' => time(),
                  'success' => false,
              ], 404);
       }catch(QueryException $error){
            return response()->json([
                 'message' => $error->getMessage(),
                 'data' => null,
                 'timestamp' => time(),
                 'success' => false,
             ], 404);
       }
    }



    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try{
            Excel::import(new SwiftImport($request->user()->id), $request->file('file'));

//             (new SwiftImport)->queue($request->file('file'));

            return response()->json([
               'message' => 'Задача поставлена в очередь',
               'data' => null,
               'timestamp' => time(),
               'success' => true,
            ], 200);
        }catch(ValidationException $error){
               return response()->json([
                 'message' => $error->getMessage(),
                 'data' => null,
                 'timestamp' => time(),
                 'success' => false,
             ], 422);
        }
    }

    public function export(Request $request)
    {


        NotifyUserOfCompletedExport::dispatch();
        $fileUrl = asset('storage/exports/swifts.xlsx');


        return response()->json([
           'message' => 'Задача поставлена в очередь',
           'data' => $fileUrl,
           'timestamp' => time(),
           'success' => true,
        ], 200);
//         return Excel::download(new SwiftExport, 'swifts.xlsx');
    }
}
