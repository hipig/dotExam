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
              <div class="ml-5" x-data="{ open: false }" x-cloak>
                <button x-on:click="open = !open" type="button" class="border border-indigo-200 text-indigo-500 rounded-full px-2 py-0.5 flex items-center text-xs focus:outline-none">
                  <span class="mr-1">切换考试</span>
                  <x-heroicon-o-chevron-down class="w-4 h-4"></x-heroicon-o-chevron-down>
                </button>
                @include('subjects.choose')
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
        <div class="mt-5">
          <div class="flex space-x-5">
            <div class="w-48">
              <div class="bg-white shadow rounded-lg py-5 px-2">
                <div class="flex flex-col space-y-5">
                  <div class="pl-3 leading-tight truncate cursor-pointer border-l-4 text-indigo-500 border-indigo-500">Soluta expedita alias tempore totam et eius.</div>
                </div>
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <div class="bg-white shadow rounded-lg">
                <div class="flex flex-col">
                  <div class="flex items-center py-2 text-gray-400">
                    <div class="w-2/3 px-5"><span class="ml-8">名称</span></div>
                    <div class="w-1/3 px-5">进度</div>
                  </div>
                  <div class="flex flex-col">
                    @foreach($papers as $paper)
                      <div class="flex items-center py-4 border-t border-gray-100">
                        <div class="w-2/3 flex items-center px-5">
                          <div class="mr-2">
                            @if($paper->children->isNotEmpty())
                              <x-heroicon-o-plus-circle class="w-6 h-6 text-indigo-500 cursor-pointer"></x-heroicon-o-plus-circle>
                            @else
                              <x-heroicon-o-minus-circle class="w-6 h-6 text-gray-400"></x-heroicon-o-minus-circle>
                            @endif
                          </div>
                          <div class="text-base truncate">{{ $paper->title }}</div>
                        </div>
                        <div class="w-1/3 flex items-center justify-between px-5">
                          <div class="text-gray-400"><span class="text-indigo-500">0</span>/{{ $paper->total_count }}</div>
                          <button type="button" class="px-3 py-1 text-sm flex items-center justify-center border-2 border-indigo-500 text-indigo-500 bg-indigo-50 rounded focus:outline-none">马上练习</button>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
