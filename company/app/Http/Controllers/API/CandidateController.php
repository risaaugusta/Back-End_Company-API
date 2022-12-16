<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
// use App\Http\Controllers\API\AuthController as AuthController;
use App\Models\Candidate;
use Validator;
use App\Http\Resources\Candidate as CandidateResource;
   
class CandidateController extends ResponseFormatter
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidates = Candidate::all();
    
        // return $this->sendResponse(CandidateResource::collection($candidates), 'Candidate retrieved successfully.');
      
        return ResponseFormatter::res([
            'code' => 200,
            'status' => 'success',
            'data' => CandidateResource::collection($candidates),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'full_name' => 'required',
            'date_of_born' => 'required',
            'province' => 'required',
            'city' => 'required',
            'education' => 'required' 
        ]);
   
        if($validator->fails()){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ],'Validation error', 500);     
        }
   
        $candidate = Candidate::create($input);
   
        // return $this->sendResponse(new CandidateResource($candidate), 'Candidate created successfully.');
        return ResponseFormatter::res([
            'code' => 200,
            'status' => 'success',
            'message' => 'Candidate created successfully.',
        ]);
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidate = Candidate::find($id);
  
        if (is_null($candidate)) {
            return $this->sendError('Candidate not found.');
        }
   
        // return $this->sendResponse(new CandidateResource($candidate), 'Candidate retrieved successfully.');
        return ResponseFormatter::res(
            [
                'id' => $candidate->id,
                'full_name' => $candidate->full_name,
                'date_of_born' => $candidate->date_of_born,
                'province' => 'province',
                'city' => 'city',
                'education' => 'education' 
            // 'code' => 200,
            // 'status' => 'success',
            // 'message' => 'Candidate created successfully.',
        ],new CandidateResource($candidate),);
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'full_name' => 'required',
            'date_of_born' => 'required',
            'province' => 'required',
            'city' => 'required',
            'education' => 'required' 
        ]);
   
        if($validator->fails()){
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ],'Validation error', 500);         
        }
   
        $candidate->full_name = $input['full_name'];
        $candidate->date_of_born = $input['date_of_born'];
        $candidate->province = $input['province'];
        $candidate->city = $input['city'];
        $candidate->education = $input['education'];
        $candidate->save();
   
        // return $this->sendResponse(new CandidateResource($candidate), 'Candidate updated successfully.');
        return ResponseFormatter::res([
            'code' => 200,
            'status' => 'success',
            'message' => 'Candidate updated successfully.',
        ]);
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        $candidate->delete();
   
        // return $this->sendResponse([], 'Candidate deleted successfully.');
        return ResponseFormatter::res([
            'code' => 200,
            'status' => 'success',
            'message' => 'Candidate deleted successfully.',
        ]);
    }
}