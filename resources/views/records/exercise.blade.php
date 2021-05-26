@extends('layouts.app')
@section('title', $record->paper->title)

@section('content')
  <div class="py-5 px-4"
       x-data="recordContainer()"
       @init-answer-list.window="initAnswerList($event.detail)"
       @update-answer.window="updateAnswer($event.detail)"
       @update-answer-card.window="updateAnswerCard()"
       x-cloak>
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col space-y-2 pb-20">
        <div class="flex items-center text-sm">
          <a href="{{ route('home') }}">首页</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span>{{ $record->subject->parent->title }}</span>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <a href="{{ route('subjects.show', ['parentSubject' => $record->subject->parent, 'subject' => $record->subject]) }}">{{ $record->subject->title }}</a>
          <x-heroicon-o-chevron-right class="w-4 h-4"></x-heroicon-o-chevron-right>
          <span class="text-gray-400">练习模式</span>
        </div>
        <div class="flex flex-wrap -mx-3">
          <div class="w-2/3 px-3 space-y-5">
            <div class="bg-white shadow rounded-lg">
              <div class="flex flex-col p-5">
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
            </div>
            @foreach($paperItems as $item)
              <div class="bg-white shadow rounded-lg px-10 py-5" x-show="activeIndex == {{ $loop->index }}">
                <div class="space-y-5" x-data="itemContainer()" x-init="() => { initRecord($dispatch, {{ $loop->index }}, {{ $item->id }}, {{ $item->record ?? null }}) }" x-cloak>
                  <div class="flex items-center justify-between">
                    <div class="flex items-start">
                      <div class="text-gray-400 text-2xl font-semibold">{{ ($loop->iteration < 10 ? '0' : '') . $loop->iteration }}</div>
                      <div class="text-indigo-500 text-lg ml-3">[{{ \App\Models\Question::$typeMap[$item->question_type] }}]</div>
                    </div>
                  </div>
                  <div class="text-gray-900 text-lg">{{$item->question->id}} {{ $item->question->title }}</div>
                  @switch($item->question_type)
                    @case(\App\Models\Question::SINGLE_SELECT)
                    @case(\App\Models\Question::JUDGE_SELECT)
                      <div class="flex flex-col space-y-2.5">
                        @foreach($item->question->option as $key => $label)
                          <label class="inline-flex items-center" :class="[isAnswered || showAnswer ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']">
                            <input type="radio"
                                   value="{{ $key }}"
                                   x-model="selfAnswer"
                                   class="w-5 h-5 border-2 border-gray-300"
                                   :class="[isAnswered ? (isRight ? 'text-green-500 focus:shadow-outline-green' : 'text-red-500 focus:shadow-outline-red') : 'text-indigo-500 focus:ring-indigo-500']"
                                   :disabled="isAnswered || showAnswer"
                                   x-on:change="submit('{{ route('paperRecords.items.store', ['record' => $record, 'paperItem' => $item]) }}')">
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
                                 :disabled="isAnswered || showAnswer">
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
                                   :disabled="isAnswered || showAnswer">
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
                                  :disabled="isAnswered || showAnswer"></textarea>
                      </label>
                      <div class="mt-2 text-gray-400">此类型的题目暂不支持判断对错，你可以点击下方查看答案解析</div>
                    @break
                  @endswitch
                  @switch($item->question_type)
                    @case(\App\Models\Question::MULTI_SELECT)
                    @case(\App\Models\Question::FILL_BLANK)
                    @case(\App\Models\Question::SHORT_ANSWER)
                      <button type="button" class="inline-flex items-center justify-center px-5 py-1.5 leading-snug border border-indigo-500 text-indigo-500 rounded-md shadow-sm bg-transparent focus:outline-none" :class="[isAnswered || showAnswer ? 'opacity-50 cursor-not-allowed' : '']" :disabled="isAnswered || showAnswer" @click="submit('{{ route('paperRecords.items.store', ['record' => $record, 'paperItem' => $item]) }}')">确认</button>
                    @break
                  @endswitch
                  <div class="space-y-2">
                    <button type="button" class="inline-flex items-center cursor-pointer py-1 focus:outline-none" x-on:click="toggleAnswerPanel">
                      <x-heroicon-o-eye class="w-6 h-6 text-gray-400 mr-1" x-show="!showAnswer"></x-heroicon-o-eye>
                      <x-heroicon-o-eye-off class="w-6 h-6 text-gray-400 mr-1" x-show="showAnswer"></x-heroicon-o-eye-off>
                      <span class="text-gray-900 leading-none" x-text="showAnswer ? '隐藏答案' : '查看答案'"></span>
                    </button>
                    <div x-show="showAnswer" class="flex flex-col">
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
                              <span class="flex-1 text-base font-semibold leading-tight" x-text="selfAnswerText"></span>
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
          <div class="w-1/3 px-3 space-y-5">
            <div class="bg-white shadow rounded-lg divide-y divide-gray-100">
              <div class="px-5 py-3 text-base text-gray-900 font-semibold">答题卡</div>
              <div class="px-5 py-4 h-36 overflow-y-auto">
                <div class="flex flex-wrap -mx-1 -mb-2">
                  @foreach($record->paper_items as $item)
                    <div class="w-6 h-6 mx-1 mb-2 leading-none flex items-center justify-center border text-xs rounded-sm cursor-pointer"
                      :class="[answerList[{{ $loop->index }}].answer.length > 0 ? (answerList[{{ $loop->index }}].is_right ? 'text-white bg-green-500 border-green-500 hover:border-green-500' : 'text-white bg-red-500 border-red-500 hover:border-red-500') : (activeIndex == {{ $loop->index }} ? 'text-gray-500 border-indigo-500 hover:border-indigo-500' : 'text-gray-500 hover:border-indigo-500')]"
                      x-on:click="activeIndex = {{ $loop->index }}">{{ $loop->iteration }}</div>
                  @endforeach
                </div>
              </div>
              <div class="px-5 py-3 flex justify-around items-center">
                <div class="flex items-center">
                  <div class="bg-green-500 w-4 h-4 mr-1"></div>
                  <div class="leading-none">正确 <span class="text-gray-900" x-text="rightCount"></span></div>
                </div>
                <div class="flex items-center">
                  <div class="bg-red-500 w-4 h-4 mr-1"></div>
                  <div class="leading-none">错误  <span class="text-gray-900" x-text="errorCount"></span></div>
                </div>
                <div class="flex items-center">
                  <div class="leading-none">正确率 <span class="text-green-500" x-text="rightRate"></span></div>
                </div>
              </div>
            </div>
            <div class="bg-white shadow rounded-lg">
              <div class="flex justify-center py-3 px-5 -mx-2">
                <div class="w-1/2 px-2 flex justify-center">
                  <a href="{{ route('subjects.show', ['parentSubject' => $record->subject->parent, 'subject' => $record->subject, 'paperType' => $record->type]) }}" class="w-full h-8 flex items-center justify-center border border-indigo-500 text-indigo-500 rounded focus:outline-none">返回章节练习</a>
                </div>
              </div>
            </div>
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
        answerList: [],
        rightCount: 0,
        errorCount: 0,
        rightRate: '0%',

        initAnswerList(detail) {
          this.answerList[detail.index] = {
            paper_item_id: detail.paper_item_id,
            answer: detail.answer,
            is_right: detail.is_right
          }
        },
        updateAnswer(detail) {
          this.answerList.push(Object.assign({}, this.answerList[this.activeIndex], detail))
        },
        updateAnswerCard() {
          this.rightCount = this.answerList.filter(item => {
            return item.is_right
          }).length

          this.errorCount = this.answerList.filter(item => {
            return item.is_right === false
          }).length

          let len = this.answerList.length
          this.rightRate = (len > 0 ? Math.round((this.rightCount / len) * 100) : 0) + '%'
        },

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
        selfAnswer: [],
        selfAnswerText: null,
        dispatcher: null,

        initRecord(dispatcher, index, paperItemId, record) {
          this.dispatcher = dispatcher
          this.dispatcher('init-answer-list', {
            index: index,
            paper_item_id: paperItemId,
            answer: (record && record.answer) || '',
            is_right: record ? record.is_correct == 1 : null,
          })
          this.dispatcher('update-answer-card')
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
        toggleAnswerPanel() {
          this.showAnswer = !this.showAnswer
        },
        submit(url) {
          this.isAnswered = true
          this.showAnswer = true

          this.$nextTick(_ => {
            axios.post(url, {answer: this.selfAnswer})
              .then(res => {
                this.isRight = res.data.is_correct == 1
                this.selfAnswerText = res.data.answer_text

                this.dispatcher('update-answer', {
                  answer: res.dataanswer,
                  is_right: res.data.is_correct == 1
                })
                this.dispatcher('update-answer-card')
              })
          })
        }
      }
    }
  </script>
@endsection

@section('footer')
@endsection
