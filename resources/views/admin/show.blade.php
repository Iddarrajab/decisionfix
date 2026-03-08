<x-app-layout tittle="users">

    <x-slot name="heading">
        {{$user->name}}
    </x-slot>

    <x-slot name="body">
        <div class="">{{$user->email}}</div>
        <div>Registrasi at {{ $user->created_at->diffForHumans()}}</div>
        <form action="{{route ('users.destroy', $user)}}" method="POST" class="mt-6">
            @method('DELETE')
            @csrf
            <x-button>
                delete
            </x-button>
        </form>

    </x-slot>
</x-app-layout>