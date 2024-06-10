<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('audit') }}
        </h2>
    </x-slot>

    @php
        $users = \App\Models\AuditTrail::select('id','Description','created_at','updated_at') ->paginate(15);
        // $users = \App\Models\User::select('id','name','email')->paginate(15);
    @endphp

   

    <div class="py-12 bg-slate-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                  

<div class="relative overflow-x-auto shadow-md sm:rounded-lg" x-data='{ user: [] }'>
    <div class="pb-4 bg-white dark:bg-gray-900">
        <label for="table-search" class="sr-only">Search</label>

    </div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">

                </th>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Description
                </th>
                <th scope="col" class="px-6 py-3">
                    Created_at
                </th>

                <th scope="col" class="px-6 py-3">
                    Updated_at
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="w-4 p-4">
                    <div class="flex items-center">

                    </div>
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $user->id }}
                </th>
                <td class="px-6 py-4">
                    {{ $user->description }}
                </td>
                <td class="px-6 py-4">
                    {{ $user->created_at }}
                </td>
                <td class="px-6 py-4">
                    {{ $user->updated_at }}
                </td>

                <!-- <td class="px-6 py-4">
                    <a @click="user = {{ $user }}" data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    <a href="{{ route('dashboard.delete', ['id=' .$user->id]) }}" class="font-medium text-blue-600 dark:text-gray-500 hover:underline">Delete</a>
                </td> -->
            </tr>
            @endforeach
            
            
        </tbody>
        
    </table>
    {{ $users->links() }}
<x-update-modal />

</div>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
