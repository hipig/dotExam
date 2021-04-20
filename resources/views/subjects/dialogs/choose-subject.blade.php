<div x-data="{show: false}" @choose-subject.window="show = !!$event.detail" x-show="show" class="fixed z-10 inset-0 overflow-y-auto">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div x-show="open"
      x-transition:enter="ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="fixed inset-0 transition-opacity"
      aria-hidden="true">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
    <div x-show="show"
      x-transition:enter="ease-out duration-300"
      x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
      x-transition:leave="ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
      x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle max-w-4xl w-full relative"
      role="dialog"
      aria-modal="true"
      aria-labelledby="modal-header"
      style="min-height: 16rem"
      x-on:click.away="show = false">
      <div class="absolute top-0 right-0 mt-4 mr-4 cursor-pointer" x-on:click="show = false">
        <x-heroicon-o-x class="w-6 h-6 text-gray-600 hover:text-gray-700"></x-heroicon-o-x>
      </div>
      <div class="px-8 pt-8 pb-4 flex items-center justify-center sm:justify-between">
          <h3 class="text-lg leading-6 font-medium text-gray-900">切换考试</h3>
      </div>
      <div class="bg-white px-8 pb-8">
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
</div>
