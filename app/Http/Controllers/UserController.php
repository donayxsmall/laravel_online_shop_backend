<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\DataTables\UserDataTable;
use App\Helpers\ResponseHelper;
use DataTables;

class UserController extends Controller
{

    public function getUser(Request $request){
        $model = User::query();

        return DataTables::of($model)
        ->addIndexColumn()
        ->addColumn('intro', 'Hi {{$name}}!')
        ->addColumn('action', function($row) {
            $actionBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'"  class="edit btn btn-success btn-sm m-1">Edit</a><a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm m-1">Delete</a>';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->setRowAttr([
            'intro' => 'red',
        ])
        ->toJson();
    }

    public function indexOld(UserDataTable $dataTable){
        // echo "sdas";
        return $dataTable->render('pages.users.index-datatablesNew');
    }

    // public function index(Request $request,Builder $builder){

    // }

    public function index(Request $request){


        if($request->ajax()){

            $data = User::query();
            return DataTables::of($data)
                ->addIndexColumn()
                // ->addColumn('created_at', function($row) {
                //     return $row->created_at != null ? date('Y-m-d H:i:s', strtotime($row->created_at)) : "";
                // })
                // ->addColumn('updated_at', function($row) {
                //     return $row->updated_at != null ? date('Y-m-d H:i:s', strtotime($row->updated_at)) : "";
                // })
                ->addColumn('action', function($row) {
                    $actionBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-info btn-icon btn-sm m-1"><i class="fas fa-edit"></i> Edit</a> <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-icon btn-sm m-1"><i class="fas fa-times"></i> Delete</a>'
                    ;
                    return $actionBtn;
                })
                // ->rawColumns(['action'])
                ->toJson();
        }

        // return view('pages.users.index', compact('users'));
        return view('pages.users.index-datatables');
    }


    public function create(){
        return view('pages.users.create');
    }

    public function store(StoreUserRequest $request){
        try {
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            User::create($data);

            // $request->session()->flash('success', 'Task was successful!');
            // return redirect()->route('user.index');
            return  redirect()->route('user.index')->with('success', 'User succesfully created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('pages.users.edit',compact('user'));
    }

    public function update(UpdateUserRequest $request , User $user){
        try {
            $data = $request->all();

            //check if password is not empty
            if($request->input('password')){
                $data['password'] = Hash::make($request->input('password'));
            }else{
                //if password is empty, then use the old password
                $data['password'] = $user->password;
            }
            $user->update($data);

            // $request->session()->flash('success', 'Task was successful!');

            // return redirect()->route('user.index');

            // $successMessage = Session::pull('User successfully updated');
            return redirect()->route('user.index')->with('success', 'User successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function destroy(User $user){
        try {
            $user->delete();
            // return redirect()->route('user.index')->with('success', 'User successfully deleted');
            return ResponseHelper::message('success', 'Success Delete User');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Failed to delete user. Please try again.');
            return ResponseHelper::message('error', 'Failed to delete user. Please try again.');
        }
    }
}
