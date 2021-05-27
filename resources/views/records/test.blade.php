@extends('layouts.app')
@section('title', $record->paper->title)

@section('content')
  <div class="py-5 px-4"
       x-data="recordContainer()"
       x-init="() => { init($watch) }"
       x-on:scroll.window="scroll"
       @init-answer-list.window="initAnswerList($event.detail)"
       @update-answer.window="updateAnswer($event.detail)"
       @auto-next.window="autoNextItem()"
       x-cloak>
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col space-y-2 pb-10">
        <div class="flex items-center text-sm space-x-0.5">
          <a href="{{ route('home') }}">首页</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span>{{ $record->subject->parent->title }}</span>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <a href="{{ route('subjects.show', ['parentSubject' => $record->subject->parent, 'subject' => $record->subject]) }}">{{ $record->subject->title }}</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span class="text-gray-400">考试模式</span>
        </div>
        <div class="flex flex-wrap -mx-3">
          <div class="w-2/3 px-3 space-y-5">
            <div class="bg-white shadow rounded-lg">
              <div class="flex flex-col p-5">
                <div class="flex items-center space-x-3">
                  <div class="text-2xl text-gray-900 leading-none truncate">{{ $record->paper->title }}</div>
                  <div class="flex-1">
                    <div class="flex justify-center text-base text-indigo-500 border border-indigo-500 rounded-sm w-20">{{ $paperType }}</div>
                  </div>
                </div>
              </div>
            </div>
            @foreach($paperItems as $item)
              <div class="bg-white shadow rounded-lg px-10 py-5 question-item">
                <div class="space-y-5" x-data="itemContainer()" x-init="() => { initRecord($dispatch, {{ $item->id }}, {{ $item->record ?? null }}) }" x-cloak>
                  <div class="flex items-center justify-between">
                    <div class="flex items-start">
                      <div class="text-gray-400 text-2xl font-semibold">{{ ($loop->iteration < 10 ? '0' : '') . $loop->iteration }}</div>
                      <div class="text-indigo-500 text-lg ml-3">[{{ \App\Models\Question::$typeMap[$item->question_type] }}]</div>
                    </div>
                    <div class="flex items-center space-x-5">
                      <button type="button" class="inline-flex items-center space-x-1 focus:outline-none">
                        <x-heroicon-o-clipboard-list class="w-6 h-6 opacity-50"></x-heroicon-o-clipboard-list>
                        <span>纠错</span>
                      </button>
                      <button type="button" class="inline-flex items-center space-x-1 focus:outline-none">
                        <x-heroicon-o-pencil-alt class="w-6 h-6 opacity-50"></x-heroicon-o-pencil-alt>
                        <span>写笔记</span>
                      </button>
                      <button type="button" class="inline-flex items-center space-x-1 focus:outline-none">
                        <x-heroicon-o-star class="w-6 h-6 opacity-50"></x-heroicon-o-star>
                        <span>收藏</span>
                      </button>
                    </div>
                  </div>
                  <div class="text-gray-900 text-lg">{{ $item->question->title }}</div>
                  @switch($item->question_type)
                    @case(\App\Models\Question::SINGLE_SELECT)
                    @case(\App\Models\Question::JUDGE_SELECT)
                      <div class="flex flex-col space-y-2.5">
                        @foreach($item->question->option as $key => $label)
                          <label class="inline-flex items-center" :class="[isAnswered || showAnswer ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']">
                            <input type="radio"
                                   name="option-radio-{{ $item->id }}"
                                   value="{{ $key }}"
                                   x-model="selfAnswer"
                                   class="w-5 h-5 border-2 border-gray-300"
                                   :class="[isAnswered ? (isRight ? 'text-green-500 focus:shadow-outline-green' : 'text-red-500 focus:shadow-outline-red') : 'text-indigo-500 focus:ring-indigo-500']"
                                   :disabled="isAnswered || showAnswer"
                                   x-on:change="submit({{ $loop->parent->index }})">
                            <span class="ml-3">{{ $label }}</span>
                          </label>
                        @endforeach
                      </div>
                    @break
                    @case(\App\Models\Question::MULTI_SELECT)
                      <div class="flex flex-col space-y-2.5">
                        @foreach($item->question->option as $key => $label)
                          <label class="inline-flex items-center" :class="[isAnswered || showAnswer ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']">
                            <input type="checkbox"
                                 name="answer"
                                 value="{{ $key }}"
                                 x-model="selfAnswer"
                                 class="w-5 h-5 rounded border-2 border-gray-300"
                                 :class="[isAnswered ? (isRight ? 'text-green-500 focus:shadow-outline-green' : 'text-red-500 focus:shadow-outline-red') : 'text-indigo-500 focus:ring-indigo-500']"
                                 :disabled="isAnswered || showAnswer"
                                 x-on:change="submit({{ $loop->parent->index }})">
                            <span class="ml-3">{{ $label }}</span>
                          </label>
                        @endforeach
                      </div>
                    @break
                    @case(\App\Models\Question::FILL_BLANK)
                      <div class="flex flex-col space-y-3">
                        @foreach($item->question->answer as $key => $answer)
                          <label class="flex items-center" :class="[isAnswered || showAnswer ? 'opacity-50 cursor-not-allowed' : '']">
                            <input type="text"
                                   :value="selfAnswer[{{ $key }}] || ''"
                                   class="w-full rounded-md leading-snug"
                                   :class="[isAnswered ? (isRight ? 'focus:ring-green-500 focus:border-green-500 border-green-500 bg-green-100' : 'focus:ring-red-500 focus:border-red-500 border-red-500 bg-red-100') : 'focus:ring-indigo-500 focus:border-indigo-500 border-gray-300']"
                                   :placeholder="isAnswered ? '未填写' : '请输入答案'"
                                   x-on:input="fillAnswer($event.target.value, {{ $key }})"
                                   :disabled="isAnswered || showAnswer"
                                   x-on:change="submit({{ $loop->parent->index }})">
                          </label>
                        @endforeach
                      </div>
                    @break
                    @case(\App\Models\Question::SHORT_ANSWER)
                      <label class="flex items-center" :class="[isAnswered || showAnswer ? 'opacity-50 cursor-not-allowed' : '']">
                        <textarea x-model="selfAnswer"
                                  class="h-24 w-full rounded-md leading-snug resize-none focus:ring-indigo-500 focus:border-indigo-500 border-gray-300"
                                  :class="[isAnswered || showAnswer ? 'opacity-50 cursor-not-allowed' : '']"
                                  :placeholder="isAnswered ? '未填写' : '请输入答案'"
                                  :disabled="isAnswered || showAnswer"
                                  x-on:change="submit({{ $loop->index }})"></textarea>
                      </label>
                      <div class="mt-2 text-gray-400">主观题仅提供作答，默认得分，不计入错题集，建议收藏。</div>
                    @break
                  @endswitch
                </div>
              </div>
            @endforeach
          </div>
          <div class="w-1/3 px-3 relative">
            <div class="sticky top-0 space-y-5">
              <div class="bg-white shadow rounded-lg divide-y divide-gray-100">
                <div class="px-5 py-3 text-base text-gray-900 font-semibold">答题卡</div>
                <div class="px-5 py-4 h-36 overflow-y-auto">
                  <div class="flex flex-wrap -mx-1 -mb-2">
                    @foreach($record->paper_items as $item)
                      <div class="w-6 h-6 mx-1 mb-2 leading-none flex items-center justify-center border text-xs rounded-sm cursor-pointer"
                           :class="[answerList[{{ $loop->index }}].answer.length > 0 ? 'text-white bg-gray-400 border-gray-400' : (activeIndex == {{ $loop->index }} ? 'text-gray-500 border-indigo-500 hover:border-indigo-500' : 'text-gray-500 hover:border-indigo-500')]"
                           x-on:click="toIndex({{ $loop->index }})">{{ $loop->iteration }}</div>
                    @endforeach
                  </div>
                </div>
                <div class="px-5 py-3 flex justify-around items-center">
                  <div class="flex items-center">
                    <div class="bg-gray-400 border border-gray-400 rounded-sm w-4 h-4 mr-1"></div>
                    <div class="leading-none">已做 <span class="text-gray-900" x-text="doneCount"></span></div>
                  </div>
                  <div class="flex items-center">
                    <div class="bg-white border border-gray-200 rounded-sm w-4 h-4 mr-1"></div>
                    <div class="leading-none">未做 <span class="text-gray-900" x-text="undoneCount"></span></div>
                  </div>
                </div>
              </div>
              <div class="bg-white shadow rounded-lg divide-y divide-gray-100">
                <div class="flex leading-none divide-x divide-gray-100">
                  <div class="w-2/3 flex items-center">
                    <div class="w-full flex items-center py-2 px-4 space-x-1">
                      <x-heroicon-o-clock class="w-6 h-6 opacity-50"></x-heroicon-o-clock>
                      <span x-text="countdownText"></span>
                    </div>
                  </div>
                  <div class="w-1/3 flex items-center py-2 px-4">
                    <div class="cursor-pointer flex items-center space-x-1" x-on:click="isPause = true">
                      <x-heroicon-o-pause class="w-6 h-6 opacity-50"></x-heroicon-o-pause>
                      <span class="text-gray-900">暂停</span>
                    </div>
                  </div>
                </div>
                <div class="flex justify-center py-3 px-5 -mx-2">
                  <div class="w-1/2 px-2 flex justify-center">
                    <button type="button" class="w-full h-8 flex items-center justify-center border border-indigo-500 bg-indigo-500 text-white rounded focus:outline-none" x-on:click="isSubmit = true">交卷</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      x-show="isPause"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="transform opacity-0"
      x-transition:enter-end="transform opacity-100"
      x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="transform opacity-100"
      x-transition:leave-end="transform opacity-0"
      class="z-90 fixed inset-0 overflow-y-auto overflow-x-hidden bg-gray-500 bg-opacity-75 p-4 lg:p-8"
    >
      <div
        class="flex flex-col rounded shadow-sm bg-white overflow-hidden w-full max-w-md mx-auto"
        x-show="isPause"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-125"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-125"
      >
        <div class="p-5 lg:p-6 flex-grow w-full text-center space-y-1 cursor-pointer" x-on:click="isPause = false">
          <div class="flex items-center justify-center">
            <x-heroicon-s-play class="w-20 h-20 text-green-500"></x-heroicon-s-play>
          </div>
          <h4 class="text-gray-900 text-xl font-semibold mb-1">
            点击继续
          </h4>
        </div>
      </div>
    </div>
    <div
      x-show="isSubmit"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="transform opacity-0"
      x-transition:enter-end="transform opacity-100"
      x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="transform opacity-100"
      x-transition:leave-end="transform opacity-0"
      class="z-90 fixed inset-0 overflow-y-auto overflow-x-hidden bg-gray-500 bg-opacity-75 p-4 lg:p-8"
    >
      <div
        class="flex flex-col rounded shadow-sm bg-white overflow-hidden w-full max-w-md mx-auto"
        x-show="isSubmit"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-125"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-125"
      >
        <div class="p-5 lg:p-6 flex-grow w-full flex space-x-5">
          <div class="w-16 h-16 flex-none flex items-center justify-center rounded-full bg-orange-100">
            <svg class="hi-solid hi-shield-exclamation inline-block w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
          </div>
          <div>
            <h4 class="text-gray-800 text-xl font-semibold mb-1">
              结束作答
            </h4>
            <p class="text-gray-600">
              <template x-if="undoneCount > 0">
                <span>
                  你还有 <span class="text-orange-500 font-semibold" x-text="undoneCount"></span> 道题未作答
                </span>
              </template>
              <template x-if="undoneCount == 0">
                <span>
                  你已全部作答完毕
                </span>
              </template>
              <span>，是否确认交卷？</span>
            </p>
          </div>
        </div>
        <div class="py-3 px-5 lg:px-6 w-full bg-gray-50 text-right space-x-1">
          <button
            type="button"
            class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-5 text-sm rounded border-transparent text-indigo-600 hover:text-indigo-400 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 active:text-indigo-600"
            x-on:click="isSubmit = false"
          >
            继续做题
          </button>
          <button
            type="button"
            class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-3 py-2 leading-5 text-sm rounded border-indigo-700 bg-indigo-700 text-white hover:text-white hover:bg-indigo-800 hover:border-indigo-800 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 active:bg-indigo-700"
            x-on:click="isSubmit = false"
          >
            我要交卷
          </button>
        </div>
      </div>
    </div>
  </div>
  <script>
    function recordContainer() {
      return {
        activeIndex: 0,
        sliders: [],
        clickFlag: true,
        answerList: [],
        doneCount: 0,
        undoneCount: 0,
        isPause: false,
        isSubmit: false,
        doneTime: 0,
        countdownText: '00:00:00',

        init(watcher) {
          this.sliders = [...document.querySelectorAll('.question-item')]
          watcher('answerList', value => {
            this.countAnswer(value)
          })
          this.intervalEvent()
          watcher('doneTime', value => {
            let arr = [
              Math.floor(value / 3600),
              Math.floor(value % 3600 / 60),
              Math.floor(value % 60)
            ]
            arr = arr.map(v => {
              return v >= 10 ? v : "0" + v
            })

            this.countdownText = arr.join(':')
          })
        },
        intervalEvent() {
          setInterval(_ => {
            if (!this.isPause) this.doneTime++
          }, 1000)
        },
        scroll() {
          let scrollTop = document.documentElement.scrollTop || document.body.scrollTop
          let sliders = [...document.querySelectorAll('.question-item')]

          if (this.clickFlag) {
            for (let i = 0; i < sliders.length; i++) {
              const startH= sliders[i].offsetTop
              const endH = startH + sliders[i].offsetHeight
              const isInView = scrollTop > (startH-1) && scrollTop <= endH
              if (isInView) return this.activeIndex = i
            }
          }
        },
        toIndex(index) {
          this.clickFlag = false
          this.activeIndex = index
          this.sliders[index].scrollIntoView({ behavior: "smooth" })
          setTimeout(() => {
            this.clickFlag = true
          }, 300)
        },

        initAnswerList(detail) {
          this.answerList = this.answerList.concat(detail)
          this.countAnswer(this.answerList)
        },
        updateAnswer(detail) {
          this.answerList[detail.index] = detail
        },
        countAnswer(answerList) {
          this.doneCount = answerList.filter(item => {
            return item.is_answered
          }).length

          let len = answerList.length
          this.undoneCount = len - this.doneCount
        },
      }
    }

    function itemContainer() {
      return {
        showAnswer: false,
        isAnswered: false,
        isRight: null,
        selfAnswer: [],
        selfAnswerText: null,
        dispatcher: null,

        initRecord(dispatcher, paperItemId, record) {
          this.dispatcher = dispatcher
          dispatcher('init-answer-list', {
            paper_item_id: paperItemId,
            answer: (record && record.answer) || '',
            is_answered: false
          })
          if(record) {
            this.isAnswered = true
            this.showAnswer = true
            this.isRight = record.is_correct == 1
            this.selfAnswer = record.answer
            this.selfAnswerText = record.answer_text
          }
        },
        fillAnswer(value, i) {
          this.selfAnswer[i] = value
        },
        submit(index) {
          this.dispatcher('update-answer', {
            index: index,
            answer: this.selfAnswer,
            is_answered: true
          })
        }
      }
    }
  </script>
@endsection

@section('footer')
@endsection
