<?php

namespace App\Http\Controllers;

use App\Models\BudgetHolders;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\BudgetHoldersExport;
use App\Imports\BudgetHoldersImport;

class BudgetHoldersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedFilters = ['tin', 'name', 'region', 'district', 'address', 'phone', 'responsible'];
        $budgetHolders = BudgetHolders::query();

        foreach($allowedFilters as $allowedFilter){
            if($request->has($allowedFilter)){
                $budgetHolders->where($allowedFilter, 'ILIKE', '%' . $request->input($allowedFilter) . '%');
            }
        }


        $sort = $request->input('sort', 'name');
        $sort = in_array($sort, $allowedFilters) ? $sort : 'name';

        $direction = $request->input('direction', 'asc');
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'asc';


        return response()->json([
            'message' => 'Успешно',
            'data' => $budgetHolders->orderBy($sort, $direction)->paginate(50),
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
            'tin' => 'required|string|unique:budget_holders|min:9|max:9',
            'name' => 'required|string',
            'region' => 'required|string',
            'district' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'responsible' => 'required|string',
        ]);

        $user = $request->user();

        $budgetHolder = new BudgetHolders();
        $budgetHolder->tin = $request->tin;
        $budgetHolder->name = $request->name;
        $budgetHolder->region = $request->region;
        $budgetHolder->district = $request->district;
        $budgetHolder->address = $request->address;
        $budgetHolder->phone = $request->phone;
        $budgetHolder->responsible = $request->responsible;
        $budgetHolder->created_by = $user->id;
        $budgetHolder->updated_by = $user->id;
        $budgetHolder->save();


        return response()->json([
            'message' => 'Успешно',
            'data' => $budgetHolder,
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
           $budgetHolder = BudgetHolders::where('id', $id)->firstOrFail();

           return response()->json([
              'message' => 'Успешно',
              'data' => $budgetHolder,
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
                'tin' => 'string|unique:budget_holders|min:9|max:9',
                'name' => 'string',
                'region' => 'string',
                'district' => 'string',
                'address' => 'string',
                'phone' => 'string',
                'responsible' => 'string',
            ]);

            $user = $request->user();

            $budgetHolder = BudgetHolders::where('id', $id)->firstOrFail();

            if(!empty($request->tin)){
                $budgetHolder->tin = $request->tin;
            }

            if(!empty($request->name)){
                $budgetHolder->name = $request->name;
            }

            if(!empty($request->region)){
                $budgetHolder->region = $request->region;
            }
            if(!empty($request->district)){
                $budgetHolder->district = $request->district;
            }
            if(!empty($request->address)){
                $budgetHolder->address = $request->address;
            }

            if(!empty($request->phone)){
                $budgetHolder->phone = $request->phone;
            }

            if(!empty($request->responsible)){
                $budgetHolder->responsible = $request->responsible;
            }

            $budgetHolder->updated_by = $user->id;
            $budgetHolder->save();


            return response()->json([
                'message' => 'Успешно',
                'data' => $budgetHolder,
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
            $budgetHolder = BudgetHolders::where('id', $id)->firstOrFail();

            $budgetHolder->delete();

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
            Excel::import(new BudgetHoldersImport($request->user()->id), $request->file('file'));

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


        (new BudgetHoldersExport)->store('exports/budget-holders.xlsx');
        $fileUrl = asset('storage/exports/budget-holders.xlsx');


        return response()->json([
           'message' => 'Задача поставлена в очередь',
           'data' => $fileUrl,
           'timestamp' => time(),
           'success' => true,
        ], 200);
//         return Excel::download(new SwiftExport, 'swifts.xlsx');
    }
}
