@extends('dashboards.admins.layouts.admin-dash-layout')
@section('title','Settings')

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="form">
                <form action="{{ route('user.search') }}" method="get">
                    @csrf
                    <div class="form-group">
                        <div class="">
                            <input type="search" class="form-control mb-3" name="searchword" value="{{ $search_word ?? '' }}" placeholder="search with email or name now">
                            <div class="">
                                <button type="submit" class="btn btn-primary mb-3">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered text-center" style="width:100%">
                    <thead class="font-weight-bold">
                    <tr>
                        <th>#</th>
                        <th>name</th>
                        <th>email</th>
                        <th>status</th>
                        <th>action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $key => $user)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>   {{ $user->name }}

                            </td>
                            <td>
                                {{ $user->email }}
                            </td>


                            <td>
                                <span class="badge bg-{{ $user->is_active == '1' ? 'success' : 'warning text-dark' }}">{{ $user->is_active == 1 ? 'true' : 'false'  }}</span>
                            </td>

                            <td>
                                <a class="btn btn-{{ $user->is_active == 0 ? 'warning' : 'success' }}" href="{{ url('admin/users/mange-block/'.$user->id) }}" onclick="return  confirm('are you sure?');" ><span>{{ $user->is_active == 'active' ? 'De active' : 'active' }}</span></a>
                                <a class="btn btn-danger" href="{{ url('admin/users/delete/'.$user->id) }}" onclick="return  confirm('are you sure?');" ><span>Delete</span></a>



                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

@endsection

@section("script")
    <script src="{{ asset('dashboard') }}/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('dashboard') }}/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>

@endsection
