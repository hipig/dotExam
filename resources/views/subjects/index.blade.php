@extends('layouts.app')
@section('title', '题库')

@section('content')
  <div class="py-5 px-4">
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col mt-5">
        @foreach($treeSubjects as $subject)
          <div class="mb-4">
            <h3 class="text-2xl text-gray-700 mb-3">{{ $subject->title }}</h3>
            <div class="flex flex-wrap -mx-3">
              @foreach($subject->_children as $childrenSubject)
                <div class="w-1/6 px-3">
                  <div class="shadow rounded-lg w-full bg-white overflow-hidden block mb-6 relative transform duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <a href="{{ route('subjects.show', $childrenSubject) }}" class="flex flex-col items-center p-5">
                      <x-heroicon-o-academic-cap class="w-8 h-8 text-indigo-500"></x-heroicon-o-academic-cap>
                      <span class="mt-2 truncate">{{ $childrenSubject->title }}</span>
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection
