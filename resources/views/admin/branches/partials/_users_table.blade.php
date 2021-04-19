<div class="mb-5">
    <div class="card border-light">
        <div class="tableFixHead">
            <table class="table card-table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th style="width:10px">#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th class="width-65">Create Date</th>
                    <th class="width-50">Status</th>
                    <th class="width-50">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if($users)
                    @forelse($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->first}}</td>
                            <td>{{$user->last}}</td>
                            <td>{{$user->username}}</td>
                            <td>
                                @include('admin.components.datatables._date', ['date' => $user->created_at])
                            </td>
                            <td>{{$user->statusName}}</td>
                            <td>
                                @php
                                    //if (auth()->user()->role == User::ROLE_SUPER) {
                                         $data = [
                                             'editAction' => route('admin.users.edit', [$user, 'type' => request('type')]),
                                             'deleteAction' => route('admin.users.destroy', $user),
                                         ];
                                     //}
                                @endphp
                                @include('admin.components.datatables._row-actions', $data)
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <h4>
                                    No items found!
                                </h4>
                            </td>
                        </tr>
                    @endforelse
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
