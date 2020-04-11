<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySchoolRequest;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Http\Requests\AssignPrincipalRequest;
use App\School;
use App\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SchoolsController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('school_access'), 403);
        $schools = \DB::table('schools')
            ->leftJoin('users', 'users.id', '=', 'schools.user_id')
            ->get(['schools.id as schoolId', 'users.id as userId', 'users.name as userName', 'schools.school_name', 'schools.school_phone']);
        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        abort_unless(\Gate::allows('school_create'), 403);

        return view('admin.schools.create');
    }

    public function store(StoreSchoolRequest $request)
    {
        abort_unless(\Gate::allows('school_create'), 403);

        $school = School::create($request->all());

        return redirect()->route('admin.schools.index');
    }

    public function edit(School $school)
    {
        abort_unless(\Gate::allows('school_edit'), 403);

        return view('admin.schools.edit', compact('school'));
    }

    public function update(UpdateSchoolRequest $request, School $school)
    {
        abort_unless(\Gate::allows('school_edit'), 403);

        $school->update($request->all());

        return redirect()->route('admin.schools.index');
    }

    public function show(School $school)
    {
        abort_unless(\Gate::allows('school_show'), 403);

        return view('admin.schools.show', compact('school'));
    }

    public function destroy(School $school)
    {
        abort_unless(\Gate::allows('school_delete'), 403);

        $school->delete();

        return back();
    }

    public function massDestroy(MassDestroySchoolRequest $request)
    {
        School::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function assign(User $user, $schoolId) 
    {
        $allPrincipals = User::whereHas('roles', function($q){$q->whereIn('id', [env('USER_ROLES_PRINCIPAL', '2')]);})->get();
        return view('admin.schools.assign', compact('allPrincipals', 'schoolId'));
    }

    public function assignTeacher(AssignPrincipalRequest $request) {
        if($request->user_id == 0) {
            Alert::error('Please select Principal first!', '');
            return redirect()->route('admin.schools.assignTeachers', $request->schoolId);
        } else if(School::where('user_id', '=', $request->user_id)->exists()) {
            Alert::error('Selected principal is already assigned!', '');
            return redirect()->route('admin.schools.assignTeachers', $request->schoolId);
        }
        School::where('id', $request->schoolId)->update(['user_id' => $request->user_id]);
        Alert::success('Principal assigned successfully', '');
        return redirect()->route('admin.schools.index');
    }
}
