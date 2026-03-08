<x-app-layout tittle="users">

    <x-slot name="heading">
        {{$gejala->gejala}}
    </x-slot>

    <x-slot name="body">
        <div class="">{{$gejala->gejala}}</div>
        <div>Registrasi at {{ $gejala->created_at->diffForHumans()}}</div>
        <form action="{{route ('gejala.destroy', $gejala)}}" method="POST" class="mt-6">
            @method('DELETE')
            @csrf
            <x-button>
                delete
            </x-button>
        </form>

    </x-slot>
</x-app-layout>