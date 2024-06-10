<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
@php

$query = request()->get('query'); // Get search query from URL parameter
if ($query) {
  $users = \App\Models\User::where('name', 'like', "%$query%")
    ->orWhere('id', '=', "$query")

    ->select('id', 'name', 'email')
    ->paginate(15);
} else {
  $users = \App\Models\User::select('id', 'name', 'email')
    ->paginate(15);
}
@endphp

<div class="py-12 bg-slate-900">
  <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
      <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg" x-data='{ user: [] }'>
          <div class="pb-4 bg-white dark:bg-gray-900">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
              <form action="/dashboard" method="GET">
                <input type="text" name="query" value="{{ $query ?? '' }}" placeholder="Search username or UID"> <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
              </form>
            </div>
          </div>
          <table class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th scope="col" class="p-4"></th>
                <th scope="col" class="px-6 py-3">ID</th>
                <th scope="col" class="px-6 py-3">Username</th>
                <th scope="col" class="px-6 py-3">Email</th>
                <th scope="col" class="px-6 py-3">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="w-4 p-4"></td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $user->id }}</th>
                <td class="px-6 py-4">{{ $user->name }}</td>
                <td class="px-6 py-4">{{ $user->email }}</td>
                <td class="px-6 py-4">
                  <a @click="user = {{ $user }}" data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                  Edit</a>
                  <a @click="user = {{ $user }}" data-modal-target="warningModal" data-modal-toggle="warningModal" class="font-medium text-blue-600 dark:text-gray-500 hover:underline">
                  Delete</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <x-update-modal />
          <x-delete-modal />
          {{ $users->links() }} </div>
      </div>
    </div>
  </div>
</div>

    
</x-app-layout>
