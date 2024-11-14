<?php

namespace App\Http\Controllers;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tasks;
class ToDoControllers extends Controller
{

    protected $redirectTo = '/login';

    public function __construct(){
        $this->middleware('auth');
    }

    public function Insert(Request $request){
        // dd($request->all());
        // dd(Auth::id());

        $user_id = Auth::id();

        $validatedData = $request->validate([
            'Tittle' => 'required|max:255',
            'Description' => 'required',
            'Status' => 'required',
            'Priority' => 'required',
        ]);
    
        $tasks = new Tasks;
        $tasks->title = $validatedData['Tittle'];
        $tasks->description = $validatedData['Description'];
        $tasks->user_id=$user_id;
        $tasks->status=$validatedData['Status'];
        $tasks->created_at=now();
        $tasks->priority=$validatedData['Priority'];
        $tasks->save();
    
        return response()->json([
            'message'=>"Created Succesfully!"
        ]);
        //return redirect()->route('allTasks')->with('success', 'Post saved successfully!');
    }

   public function updateTask(Request $request){
    $id = $request->id;

    $task = Tasks::find($id);

    if($task){

        $validatedData = $request->validate([
            'Tittle' => 'required|max:255',
            'Description' => 'required',
            
        ]);


        $task->update([
            'title'=>$validatedData['Tittle'],
            'Description'=>$validatedData['Description'],
            'status'=>$request->Status,
            'priority'=>$request->Priority,
            'edited_at'=>now()

        ]);

       
        return response()->json([
            'message'=>"Updated Succesfully!",
            'task'=>$task,
            'request'=>$request
        ]);
    }
    else{
        return response()->json([
            'message'=>"Task Not Found!"
        ]);
    }
   }


    public function getAllTasks(){
        $user_id = Auth::id();

       $allTasks= Tasks::where('user_id',$user_id)->get();

       return view('home', compact('allTasks'));
    }

    public function getDataById(Request $request){
        $id = $request->id;

        $result = Tasks::findOrFail($id);

        return response()->json(['message'=>"Success",'result'=>$result]);
    }

    public function deleteTask(Request $request){
        $id = $request->id;


        $task = Tasks::find($id);
        if ($task) {
            $task->delete();
            
            return response()->json([
            'message'=>'Deleted Succesfully!'
        ]);
        }
        else{
             return response()->json([
                'message'=>'Fail! Task not found!'
            ]);
        }

       
    }


    public function filterTasks(Request $request){
        $priority = $request->priority;
        $user_id = Auth::id();


       $filterTasks= Tasks::where([['priority',$priority],['user_id',$user_id]])->get();

       return response()->json($filterTasks);
    }
}
