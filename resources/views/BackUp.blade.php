
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $users = \App\Models\User::query()-> select('id','name','email') ->limit(50)->get();
        
    @endphp


    <div class="py-12 bg-slate-900 h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                        <a href="{{ route('Runbackup') }}">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                BACKUP +
                            </button>
                        </a>
@if (count($backups))

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
            <th scope="col" class="p-4">

                </th>
                <th scope="col" class="px-6 py-3">
                    File
                </th>
                <th scope="col" class="px-6 py-3">
                    Size
                </th>
                <th scope="col" class="px-6 py-3">
                    Date
                </th>

                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($backups as $backup)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td>{{ $backup['file_name'] }}</td>
                    <td>{{ $backup['file_size'] }}</td>
                    <td>
                        {{ date('d/M/Y, g:ia', strtotime($backup['last_modified'])) }}
                    </td>
                    <td>
                        {{ $backup['last_modified'] }}
                    </td>
                    <td class="px-6 py-4">
                        <a class="font-medium text-blue-600 dark:text-gray-500 hover:underline" href="{{ url('backup/download/'.$backup['file_name']) }}">
                            <i class="fas fa-cloud-download"></i> Download</a>
                        <a class="font-medium text-blue-600 dark:text-gray-500 hover:underline" data-button-type="delete" href="{{ url('backup/backup_delete/'.$backup['file_name']) }}">
                            <i class="fal fa-trash"></i>
                            Delete
                        </a>
                        <a class="font-medium text-blue-600 dark:text-gray-500 hover:underline" href="{{ route('Restorebackup') }}">
                                Restore
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
