<?php

namespace App\Exports;

use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaggedUsersExport implements FromQuery, WithHeadings
{
    use Exportable;

    public $taxonomyId;

    public function __construct($taxonomyId)
    {
        $this->taxonomyId = $taxonomyId;

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {

        return DB::table('users')->select('users.id','users.first','users.last','users.phone_number','users.email')->join('taggables as t','t.taggable_id','=','users.id')
                   ->join('taxonomies as ta','ta.id','=','t.taxonomy_id')
                   ->where('ta.id',$this->taxonomyId)->orderBy('users.id');
    }


    public function headings(): array
    {
        return ['ID','First Name','Last Name','Phone Number','Email'];
    }
}
