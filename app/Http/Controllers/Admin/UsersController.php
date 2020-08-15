<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Admin\RolesController;

class UsersController extends Controller
{
    const STUDENT_ROLES = 3;
    const USER_ROLES_TEACHER = 4;

    public function index()
    {
        abort_unless(\Gate::allows('user_access'), 403);

        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function create(User $user)
    {
        abort_unless(\Gate::allows('user_create'), 403);
        $currentUserRoles = RolesController::getLoggedInUserRolesIdAndName();
        $getRolesIds = RolesController::getAllRolesDependency();
        $roles = Role::whereIn('id', $getRolesIds[$currentUserRoles->roleId])->get()->pluck('title', 'id');
        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        abort_unless(\Gate::allows('user_create'), 403);
        $inputData = $request->all();
        $inputData['created_by'] = auth()->user()->id;
        $user = User::create($inputData);
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_unless(\Gate::allows('user_edit'), 403);

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        abort_unless(\Gate::allows('user_edit'), 403);

        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_unless(\Gate::allows('user_show'), 403);

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_unless(\Gate::allows('user_delete'), 403);

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function activate(Request $request, $userId) {
        $user = User::where('id', $userId)->first();
        if($user->user_status == 0) {    
            User::where('id', $userId)->update(['user_status' => 1]);
            Alert::success('User Activated Successfully', '');
        } else {
            User::where('id', $userId)->update(['user_status' => 0]);
            Alert::success('User De-Activated Successfully', '');
        }
        return redirect()->route('admin.users.index');
    }
}
