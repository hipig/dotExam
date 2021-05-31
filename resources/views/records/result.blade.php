@extends('layouts.app')
@section('title', $record->paper->title . ' 考试概况')

@section('content')
  <div class="py-5 px-4">
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col space-y-2 pb-10">
        <div class="flex items-center text-sm space-x-0.5">
          <a href="{{ route('home') }}">首页</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span>{{ $record->subject->parent->title }}</span>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <a href="{{ route('subjects.show', ['parentSubject' => $record->subject->parent, 'subject' => $record->subject]) }}">{{ $record->subject->title }}</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span class="text-gray-400">考试结果</span>
        </div>
        <div class="flex flex-wrap -mx-3">
          <div class="w-2/3 px-3 space-y-5">
            <div class="bg-white shadow rounded-lg">
              <div class="flex flex-col p-5 space-y-3">
                <div class="flex items-center space-x-3">
                  <div class="text-2xl text-gray-900 leading-none truncate">{{ $paper->title }}</div>
                  <div class="flex-1">
                    <div class="flex justify-center text-base text-indigo-500 border border-indigo-500 rounded-sm w-20">{{ $paperType }}</div>
                  </div>
                </div>
                <div class="flex flex-warp items-center text-gray-400 space-x-10">
                  <span>本卷共 {{ $paper->total_count }} {{ $paper->has_section ? '大' : '小' }}题</span>
                  <span>总分：{{ $paper->total_score }} 分</span>
                  <span>时间：{{ $paper->time_limit }} 分钟</span>
                </div>
              </div>
            </div>
            <div class="bg-white shadow rounded-lg">
              <div class="h-40 flex flex-col items-center justify-center rounded-tl-lg rounded-tr-lg bg-gradient-to-r from-indigo-400 via-indigo-500 to-indigo-400 text-white mb-3">
                <div class="mb-3"><span class="text-5xl">{{ $record->score }}</span> 分</div>
                <div class="mb-1 text-xs">总分：<span class="text-sm">{{ $paper->total_score }} 分</span></div>
                <div class="text-xs">{{ $record->done_time / 60 }} 分钟</div>
              </div>
              <div class="flex flex-col items-center justify-center">
                <table class="border-b border-gray-100">
                  <thead>
                    <tr>
                      <th class="w-24 text-left text-gray-400 font-normal py-3">题型</th>
                      <th class="w-24 text-gray-400 font-normal py-3">数量</th>
                      <th class="w-24 text-gray-400 font-normal py-3">正确</th>
                      <th class="w-24 text-gray-400 font-normal py-3">错误</th>
                      <th class="w-24 text-gray-400 font-normal py-3">得分</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($result as $type => $item)
                      <tr>
                        <td class="text-gray-900 font-semibold py-2">{{ \App\Models\Question::$typeMap[$type] }}</td>
                        <td class="text-center text-base text-gray-900 font-semibold py-2">{{ $item['total'] }}</td>
                        <td class="text-center text-base text-green-500 font-semibold py-2">{{ $item['right'] }}</td>
                        <td class="text-center text-base text-red-500 font-semibold py-2">{{ $item['error'] }}</td>
                        <td class="text-center py-2">{{ $item['score'] }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="text-gray-400 text-xs py-2">主观题无法判断对错，按正确计分</div>
                <div class="pt-8 pb-12">
                  <button type="button" class="inline-flex justify-center py-3 w-64 leading-tight rounded text-white bg-gradient-to-r from-indigo-400 to-indigo-500 focus:outline-none">查看解析</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
@endsection
