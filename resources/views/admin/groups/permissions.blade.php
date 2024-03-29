@extends('admin.layout')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Groupe {{ $group->name}} | Permissions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item">Groupes</li>
                        <li class="breadcrumb-item">{{ $group->name }}</li>
                        <li class="breadcrumb-item active">Permissions</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- <div class="card-header">
                        <h3 class="card-title">Groupe {{ $group->name}} | Permissions</h3>
                    </div> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <group-permissions :group_id="{{ $group->id }}"></group-permissions>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@push('custom_scripts')
    <!-- <script>
        function  edit(id) {
            window.location = 'user/'+id+'/edit';
        }
    </script> -->
@endpush
