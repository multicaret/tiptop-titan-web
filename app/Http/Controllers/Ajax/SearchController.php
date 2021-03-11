<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SearchController extends AjaxController
{
    protected $searchQuery, $searchTerms;

    public function __construct()
    {
        $this->searchQuery = trim(request('q'));
        $this->searchTerms = implode('|', preg_split('/\s* \s*/', $this->searchQuery));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (request()->wantsJson()) {
            if (empty($this->searchQuery)) {
                return;
            }

            return response()->json($this->lookForEverything());
        }

        $users = $this->lookForUsers('*')->limit(16)->get();


        return view('search', compact('users'));
    }


    /**
     * Display a listing of the Users.
     *
     * @return Response
     */
    public function users()
    {
        if (empty($this->searchQuery)) {
            return;
        }

        $users = $this->lookForUsers()
                      ->limit(10)
                      ->get()
                      ->each(function ($user) {
                          $user->avatar = $user->avatar;
                          $user->name = $user->name;
                      });

        return response()->json($users);
    }

    /**
     * Display a listing of the taxonomies.
     *
     * @return void
     */
    public function taxonomies()
    {
        if (empty($this->searchQuery)) {
            return;
        }

        $taxonomies = $this->lookForTaxonomies()->limit(10)->get();

        return response()->json($taxonomies);
    }


    /**
     * Get all users when match the name or username
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function lookForUsers()
    {
        $users = User::where(function ($query) {
            $query->whereRaw('users.first REGEXP "'.$this->searchTerms.'"')
                  ->orWhereRaw('users.last REGEXP "'.$this->searchTerms.'"');
        })
                     ->orderBy(DB::raw(
                         "CASE
                            WHEN users.first LIKE '%{$this->searchQuery}%' THEN 1
                            ELSE 2
                         END"
                     ));

        if (request()->has('type')) {
            $type = User::class.'::ROLE_'.strtoupper(str_replace('-', '_', request('type')));
            if (defined($type)) {
                $users->role(constant($type));
            }
        }

        return $users;
    }


    /**
     * Get all taxonomies when match.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function lookForTaxonomies()
    {
        $taxonomies = Taxonomy::whereHas('translations', function ($query) {
            $query->whereRaw('title REGEXP "'.$this->searchTerms.'"')
                  ->orderBy(DB::raw(
                      "CASE
                        WHEN title LIKE '%{$this->searchQuery}%' THEN 1
                        ELSE 2
                      END"
                  ));
        });

        if (request()->has('type')) {
            $type = Taxonomy::class.'::TYPE_'.strtoupper(str_replace('-', '_', request('type')));
            if (defined($type)) {
                $taxonomies->where('type', constant($type));
            }
        }

        return $taxonomies;
    }
}
