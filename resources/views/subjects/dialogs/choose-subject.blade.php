<div x-data="{show: false}"
    x-show="show"
    @choose-subject.window="show = !!$event.detail"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="transform opacity-0"
    x-transition:enter-end="transform opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="transform opacity-100"
    x-transition:leave-end="transform opacity-0"
    class="z-10 fixed inset-0 overflow-y-auto overflow-x-hidden bg-gray-500 bg-opacity-75 p-4 lg:p-8"
    x-cloak>
  <div class="flex flex-col rounded-lg shadow bg-white overflow-hidden w-full max-w-4xl mx-auto"
      x-show="show"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="transform opacity-0 scale-125"
      x-transition:enter-end="transform opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="transform opacity-100 scale-100"
      x-transition:leave-end="transform opacity-0 scale-125"
      x-on:click.away="show = false">
    <div class="py-4 px-5 lg:px-6 w-full bg-gray-50 flex justify-between items-center">
      <h3 class="font-medium">
        切换考试
      </h3>
      <div class="-my-4">
        <button
          type="button"
          class="inline-flex justify-center items-center space-x-2 border font-semibold focus:outline-none px-2 py-1 leading-5 text-sm rounded border-transparent text-gray-600 hover:text-gray-400 focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:text-gray-600"
          x-on:click="show = false"
        >
          <x-heroicon-s-x class="w-4 h-4 -mx-1"></x-heroicon-s-x>
        </button>
      </div>
    </div>
    <div class="p-5 lg:p-6 flex-grow w-full">
      <div class="flex flex-col space-y-5">
        @foreach($treeSubjects as $treeSubject)
          <div class="flex flex-col">
            <h3 class="text-gray-400 mb-2">{{ $treeSubject->title }}</h3>
            <div class="flex flex-wrap -mx-3">
              @foreach($treeSubject->_children as $childrenSubject)
                <div class="w-1/4 px-3">
                  <a href="{{ route('subjects.show', $childrenSubject) }}" class="flex items-center justify-center py-2 mb-3 rounded text-base {{ $parentSubject->id === $childrenSubject->id ? 'text-white bg-indigo-500' : 'bg-gray-100' }}">{{ $childrenSubject->title }}</a>
                </div>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

