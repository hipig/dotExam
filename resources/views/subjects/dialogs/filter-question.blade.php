<div x-data="dialogContainer()" @start-exam.window="startExam($event.detail.show, $event.detail.id)" x-show="show" class="fixed z-10 inset-0 overflow-y-auto">
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
        <h3 class="text-lg leading-6 font-medium text-gray-900">练习筛选</h3>
      </div>
      <div class="bg-white px-8">
        <div class="w-full space-y-5">
          <div class="flex flex-col">
            <h3 class="text-gray-400 mb-2">类型</h3>
            <div class="flex flex-wrap -mx-3">
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-indigo-500 text-white">全部</div>
              </div><div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">未做</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">已做</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">错题</div>
              </div>
            </div>
          </div>
          <div class="flex flex-col">
            <h3 class="text-gray-400 mb-2">题型</h3>
            <div class="flex flex-wrap -mx-3">
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-indigo-500 text-white">全部（0）</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">单选题（0）</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">多选题（0）</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">判断题（0）</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">填空题（0）</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">问答题（0）</div>
              </div>
            </div>
          </div>
          <div class="flex flex-col">
            <h3 class="text-gray-400 mb-2">数量</h3>
            <div class="flex flex-wrap -mx-3">
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-indigo-500 text-white">5</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">10</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">20</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">30</div>
              </div>
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">50</div>
              </div>
              <div class="w-1/6 px-3"><div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100">100</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="py-6 px-8">
        <div class="flex items-center justify-end">
          <button type="button" class="inline-flex py-2 px-8 text-base rounded text-white bg-gradient-to-r from-indigo-400 to-indigo-500 focus:outline-none">练习模式</button>
          <button type="button" class="ml-6 inline-flex py-2 px-8 text-base rounded text-white bg-gradient-to-r from-yellow-400 to-yellow-500 focus:outline-none">考试模式</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function dialogContainer() {
    return {
      show: false,
      id: null,

      startExam(show, id) {
        this.show = !!show
        this.id = id
      },
    }
  }
</script>
