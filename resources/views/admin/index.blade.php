<x-app-layout tittle="admin">

    <x-slot name="heading">
        admin
    </x-slot>

    <x-slot name="body">
        <div class="sm:flex sm:items-center">
            <x-section-title>
                <x-slot name="title">admin</x-slot>
                <x-slot name="description"> tabel admin yang sudah registrasi</x-slot>
            </x-section-title>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <x-button class="" as="a" href="{{route('admin.create')}}">
                    Add admin
                </x-button>
            </div>

        </div>
        <div class="mt-8 flow-root">
            <x-table class="min-w-full divide-y divide-gray-300">
                <x-table.thead>
                    <tr>
                        <x-table.th>Name</x-table.th>
                        <x-table.th>email</x-table.th>
                        <x-table.th>created_at</x-table.th>
                      
                    </tr>
                </x-table.thead>
                <x-table.tbody>
                    @foreach ($admin as $admin)
                    <tr>
                        <x-table.td> {{$admin->name}}</x-table.td>
                        <x-table.td>{{$admin->email}}</x-table.td>
                        <x-table.td>{{$admin->created_at}}</x-table.td>
                       
        </div>
        @endforeach

        <!-- More people... -->
        </x-table.tbody>
        </x-table>
        </div>
    </x-slot>
</x-app-layout>