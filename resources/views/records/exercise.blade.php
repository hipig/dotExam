@extends('layouts.app')
@section('title', $record->paper->title)

@section('content')
  <div class="py-5 px-4" x-data="recordContainer()" x-cloak>
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col">
        <div class="flex items-center text-sm">
          <a href="{{ route('home') }}">首页</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span>{{ $record->subject->parent->title }}</span>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <a href="{{ route('subjects.show', ['parentSubject' => $record->subject->parent, 'subject' => $record->subject]) }}">{{ $record->subject->title }}</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span class="text-gray-400">练习模式</span>
        </div>
        <div class="mt-5 flex flex-wrap -mx-3">
          <div class="w-2/3 px-3 space-y-5">
            <div class="bg-white shadow rounded-lg p-5">
              <div class="flex items-center">
                <div class="text-2xl text-gray-900 leading-none truncate">{{ $record->paper->title }}</div>
                <div class="flex-1 ml-3">
                  <div class="flex justify-center text-base text-indigo-500 border border-indigo-500 rounded-sm w-20">{{ $paperType }}</div>
                </div>
              </div>
              <div class="text-base mt-4">
                <label class="inline-flex items-center">
                  <input type="checkbox" class="w-5 h-5 rounded text-indigo-500 border-gray-300 focus:ring-indigo-500">
                  <span class="ml-2">做对自动下一题</span>
                </label>
              </div>
            </div>
            @foreach($record->paper_items as $item)
              <div class="bg-white shadow rounded-lg px-10 py-5" x-show="activeIndex == {{ $loop->index }}">
                <div class="space-y-5" x-data="itemContainer()" x-cloak>
                  <div class="flex items-center justify-between">
                    <div class="flex items-start">
                      <div class="text-gray-400 text-2xl font-semibold">{{ ($loop->iteration < 10 ? '0' : '') . $loop->iteration }}</div>
                      <div class="text-indigo-500 text-lg ml-3">[{{ \App\Models\Question::$typeMap[$item->question_type] }}]</div>
                    </div>
                  </div>
                  <div class="text-gray-900 text-lg">{{$item->question->id}} {{ $item->question->title }}</div>
                  @switch($item->question_type)
                    @case(\App\Models\Question::SINGLE_SELECT)
                    <div class="flex flex-col space-y-2">
                      @foreach($item->question->option as $key => $label)
                        <label class="inline-flex items-center" :class="[isAnswered ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']">
                          <input type="radio" name="answer" value="{{ $key }}" x-model="selfAnswer" class="w-5 h-5 border-2 border-gray-400" :class="[isAnswered ? (isRight ? 'text-green-500 focus:shadow-outline-green' : 'text-red-500 focus:shadow-outline-red') : 'text-indigo-500 focus:ring-indigo-500']" :disabled="isAnswered" x-on:change="submit">
                          <span class="ml-3">{{ $label }}</span>
                        </label>
                      @endforeach
                    </div>
                    @break
                  @endswitch
                  <div class="flex flex-col space-y-2">
                    <button type="button" class="flex items-center cursor-pointer py-1 focus:outline-none" x-on:click="showAnswer = !showAnswer">
                      <x-heroicon-o-eye class="w-6 h-6 text-gray-400 mr-1" x-show="!showAnswer"></x-heroicon-o-eye>
                      <x-heroicon-o-eye-off class="w-6 h-6 text-gray-400 mr-1" x-show="showAnswer"></x-heroicon-o-eye-off>
                      <span class="text-gray-900 leading-none" x-text="showAnswer ? '隐藏答案' : '查看答案'"></span>
                    </button>
                    <div x-show="showAnswer">
                      <template x-if="{{ $item->question_type }} != {{ \App\Models\Question::SHORT_ANSWER }}">
                        <div class="mb-5 py-2 px-5 bg-gray-100 flex leading-tight rounded {{ $item->question_type ? 'flex-col' : 'flex-wrap items-center' }}">
                          <div class="mr-10 py-1" :class="isRight ? 'font-semibold text-green-500' : 'font-semibold text-red-500'" x-show="isAnswered" x-text="isRight ? '回答正确': '回答错误'"></div>
                          <div class="mr-10 py-1 flex {{ $item->question_type === \App\Models\Question::FILL_BLANK ? 'items-baseline' : 'items-center' }}">
                            <span class="text-gray-500">正确答案：</span>
                            <span class="flex-1 text-green-500 text-base font-semibold leading-tight">{{ $item->question->answer_text }}</span>
                          </div>
                          <template x-if="isAnswered && !isRight">
                            <div class="mr-10 py-1 flex {{ $item->question_type === \App\Models\Question::FILL_BLANK ? 'items-baseline' : 'items-center' }}">
                              <span class="text-gray-500">你的答案：</span>
                              <span class="flex-1 text-base font-semibold leading-tight" x-text="selfAnswer"></span>
                            </div>
                          </template>
                        </div>
                      </template>
                      <div class="px-5">
                        <div class="flex flex-wrap items-baseline">
                          <div class="text-gray-400">解析：</div>
                          <div class="flex-1 text-base">
                            {{ $item->question->parse }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="fixed bottom-0 left-0 right-0 px-4 z-30">
      <div class="max-w-6xl mx-auto">
        <div class="flex">
          <div class="w-2/3">
            <div class="flex justify-between py-3 px-10 bg-white rounded-lg shadow-lg mb-5">
              <button type="button" class="inline-flex items-center px-8 py-2 text-white rounded focus:outline-none" :class="[activeIndex === 0 ? 'bg-gray-400 opacity-50 cursor-not-allowed' : 'bg-indigo-500']" x-on:click="prevItem">上一题</button>
              <button type="button" class="inline-flex items-center px-8 py-2 text-white rounded focus:outline-none" :class="[activeIndex === lastIndex ? 'bg-gray-400 opacity-50 cursor-not-allowed' : 'bg-indigo-500']" x-on:click="nextItem">下一题</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function recordContainer() {
      return {
        activeIndex: 0,
        lastIndex: {{ $record->paper_items->count() - 1 }},

        prevItem() {
          if (this.activeIndex > 0)  this.activeIndex --
        },
        nextItem() {
          if (this.activeIndex < this.lastIndex)  this.activeIndex ++
        }
      }
    }

    function itemContainer() {
      return {
        showAnswer: false,
        isAnswered: false,
        isRight: false,
        selfAnswer: null,
        selfAnswerText: null,

        submit() {
          this.isAnswered = true
          this.showAnswer = true
        }
      }
    }
  </script>
@endsection

@section('footer')
@endsection
