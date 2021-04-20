@extends('layouts.app')
@section('title', "{$parentSubject->title} - {$paperTypeMap[$paperType]}")

@section('content')
  <div class="py-5 px-4">
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col">
        <div class="flex items-center text-sm">
          <a href="{{ route('home') }}">首页</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span>{{ $parentSubject->parent->title }}</span>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span class="text-gray-400">{{ $parentSubject->title }}</span>
        </div>
        <div class="mt-2 shadow rounded-lg w-full bg-white overflow-hidden w-full block relative px-5">
          <div class="my-5 flex justify-between items-center">
            <div class="flex items-center">
              <div class="flex items-center">
                <x-heroicon-o-academic-cap class="w-8 h-8 text-indigo-500"></x-heroicon-o-academic-cap>
                <h3 class="text-xl ml-1">{{ $parentSubject->title }}</h3>
              </div>
              <div class="ml-5" x-data>
                <button x-on:click="$dispatch('choose-subject', true)" type="button" class="border border-indigo-200 text-indigo-500 rounded-full px-2 py-0.5 flex items-center text-xs focus:outline-none">
                  <span class="mr-1">切换考试</span>
                  <x-heroicon-o-chevron-down class="w-4 h-4"></x-heroicon-o-chevron-down>
                </button>
              </div>
            </div>
            <div class="flex space-x-10">
              <a href="#" class="flex items-center text-indigo-500">
                <x-heroicon-s-tag class="w-5 h-5"></x-heroicon-s-tag>
                <span class="ml-1">题目收藏</span>
              </a>
              <a href="#" class="flex items-center text-indigo-500">
                <x-heroicon-s-collection class="w-5 h-5"></x-heroicon-s-collection>
                <span class="ml-1">错题练习</span>
              </a>
            </div>
          </div>
          <div class="flex flex-col space-y-5">
            <div class="flex space-x-5">
              <div class="flex items-center text-gray-400 h-8">{{ \App\Models\Subject::$traitMap[\App\Models\Subject::TRAIT_SPECIAL] }}</div>
              <div class="flex-1 flex flex-wrap -mb-5">
                @foreach(($parentSubject->children_groups[\App\Models\Subject::TRAIT_SPECIAL] ?? []) as $item)
                  <a href="{{ route('subjects.show', ['parentSubject' => $parentSubject, 'subject' => $item, 'paperType' => $paperType]) }}" class="flex items-center h-8 px-5 mr-2 mb-5 rounded-full cursor-pointer {{ $item->id === $subject->id ? 'text-white bg-indigo-500' : '' }}">{{ $item->title }}</a>
                @endforeach
              </div>
            </div>
            <div class="flex space-x-5">
              <div class="flex items-center text-gray-400 h-8">{{ \App\Models\Subject::$traitMap[\App\Models\Subject::TRAIT_COMMON] }}</div>
              <div class="flex-1 flex flex-wrap -mb-5">
                @foreach(($parentSubject->children_groups[\App\Models\Subject::TRAIT_COMMON] ?? []) as $item)
                  <a href="{{ route('subjects.show', ['parentSubject' => $parentSubject, 'subject' => $item, 'paperType' => $paperType]) }}" class="flex items-center h-8 px-5 mr-2 mb-5 rounded-full cursor-pointer {{ $item->id === $subject->id ? 'text-white bg-indigo-500' : '' }}">{{ $item->title }}</a>
                @endforeach
              </div>
            </div>
          </div>
          <div class="flex justify-center">
            <div class="flex space-x-20">
              @foreach($paperTypeMap as $type => $title)
                <a href="{{ route('subjects.show', ['parentSubject' => $parentSubject, 'subject' => $subject, 'paperType' => $type]) }}" class="flex items-center h-16 text-lg cursor-pointer border-b-2 {{ $paperType === $type ? 'text-indigo-500 border-indigo-500 tab-active' : 'border-transparent' }}">{{ $title }}</a>
              @endforeach
            </div>
          </div>
        </div>
        <div class="my-5">
          @switch($paperType)
            @case(\App\Models\Paper::TYPE_CHAPTER)
              @include('subjects.types.chapter')
            @break
          @endswitch
        </div>
      </div>
    </div>
  </div>
  @include('subjects.dialogs.choose-subject')
  @include('subjects.dialogs.filter-question')
@endsection
