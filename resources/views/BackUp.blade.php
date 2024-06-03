
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $users = \App\Models\User::query()-> select('id','name','email') ->limit(50)->get();
        
    @endphp


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                  
@if (count($backups))
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>File</th>
                <th>Size</th>
                <th>Date</th>
                <th>Age</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($backups as $backup)
                <tr>
                    <td>{{ $backup['file_name'] }}</td>
                    <td>{{ $backup['file_size'] }}</td>
                    <td>
                        {{ date('d/M/Y, g:ia', strtotime($backup['last_modified'])) }}
                    </td>
                    <td>
                        {{ $backup['last_modified'] }}
                    </td>
                    <td class="text-right">
                        <a class="btn btn-primary" href="{{ url('backup/download/'.$backup['file_name']) }}">
                            <i class="fas fa-cloud-download"></i> Download</a>
                        <a class="btn btn-xs btn-danger" data-button-type="delete" href="{{ url('backup/delete/'.$backup['file_name']) }}">
                            <i class="fal fa-trash"></i>
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-center py-5">
        <h1 class="text-muted">No existen backups</h1>
    </div>
@endif



                </div>
            </div>
        </div>
    </div>
</x-app-layout>
