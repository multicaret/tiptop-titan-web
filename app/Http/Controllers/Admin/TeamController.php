<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TokanTeam;
use DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:team.permissions.index', ['only' => ['index', 'store']]);
//        $this->middleware('permission:team.permissions.create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:team.permissions.edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:team.permissions.destroy', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return View
     */
    public function index(Request $request)
    {
        $columns = [
            [
                'data' => 'id',
                'name' => 'id',
                'title' => trans('strings.id'),
                'width' => '1',
            ],
            [
                'data' => 'name',
                'name' => 'name',
                'title' => trans('strings.name'),
                'width' => '40',
            ],
            [
                'data' => 'status',
                'name' => 'status',
                'title' => 'Status',
                'width' => '10',
            ],
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => trans('strings.create_date'),
                'width' => '10',
            ],
        ];

        return view('admin.teams.index', compact('columns'));
    }

    public function create(Request $request)
    {
        $data['team'] = new TokanTeam();

        return view('admin.teams.form', $data);
    }

    public function store(Request $request)
    {
//        $request->validate($this->validationRules());

        $team = new TokanTeam();
        $this->saveLogic($request, $team);

        return redirect()
            ->route('admin.teams.index')
            ->with('message', [
                'type' => 'Success',
                'text' => __('strings.successfully_created'),
            ]);
    }

    public function edit(TokanTeam $team, Request $request)
    {
        $data['team'] = $team;

        return view('admin.teams.form', $data);
    }

    public function update(Request $request, TokanTeam $team)
    {
        $this->saveLogic($request, $team, true);

        return redirect()
            ->route('admin.teams.index')
            ->with('message', [
                'type' => 'Success',
                'text' => 'Edited successfully',
            ]);
    }

    public function destroy(TokanTeam $team)
    {
        $team->delete();

        return back()->with('message', [
            'type' => 'Success',
            'text' => 'Successfully Deleted',
        ]);
    }

    private function saveLogic($request, TokanTeam $team, bool $isUpdating = false)
    {
        DB::beginTransaction();
        if ( ! $isUpdating) {
            $team->creator_id = auth()->id();
        }

        $team->editor_id = auth()->id();
        $team->name = $request->input('name');
        $team->description = $request->input('description');
        $team->status = $request->input('status');
        $team->save();

        DB::commit();
    }

}