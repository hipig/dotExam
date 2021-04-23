<div x-data="dialogContainer()" @start-exam.window="startExam($event.detail.show, $event.detail.totalitiesUrl, $event.detail.storeUrl)" x-show="show" class="fixed z-10 inset-0 overflow-y-auto">
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
          <form :action="storeUrl" method="post" x-ref="store-record-form">
            @csrf
            <input type="hidden" name="range" x-model="range">
            <input type="hidden" name="type" x-model="type">
            <input type="hidden" name="size" x-model="size">
            <input type="hidden" name="mode" x-model="mode">
          </form>
          <div class="flex flex-col">
            <h3 class="text-gray-400 mb-2">类型</h3>
            <div class="flex flex-wrap -mx-3">
              @foreach(\App\Models\Paper::$filterTypeMap as $key => $item)
                <div class="w-1/6 px-3">
                  <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer" :class="[range === '{{ $key }}' ? 'bg-indigo-500 text-white' : 'bg-gray-100']" x-on:click="switchRange('{{ $key }}')">{{ $item }}</div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="flex flex-col">
            <h3 class="text-gray-400 mb-2">题型</h3>
            <div class="flex flex-wrap -mx-3">
              <div class="w-1/6 px-3">
                <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer" :class="[type === 0 ? 'bg-indigo-500 text-white' : 'bg-gray-100']" x-on:click="switchType(0)">全部（<span x-text="totalities[0] || 0"></span>）</div>
              </div>
              @foreach(\App\Models\Question::$typeMap as $key => $item)
                <div class="w-1/6 px-3">
                  <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer" :class="[type === {{ $key }} ? 'bg-indigo-500 text-white' : 'bg-gray-100']" x-on:click="switchType({{ $key }})">{{ $item }}（<span x-text="totalities[{{ $key }}] || 0"></span>）</div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="flex flex-col">
            <h3 class="text-gray-400 mb-2">数量</h3>
            <div class="flex flex-wrap -mx-3">
              @foreach(\App\Models\Paper::$filterSizeMap as $item)
                <div class="w-1/6 px-3">
                  <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer" :class="[size === {{ $item }} ? 'bg-indigo-500 text-white' : 'bg-gray-100']" x-on:click="switchSize({{ $item }})">{{ $item }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="py-6 px-8">
        <div class="flex items-center justify-end">
          <button x-on:click="start('exercise')" type="button" class="inline-flex py-2 px-8 text-base rounded text-white bg-gradient-to-r from-indigo-400 to-indigo-500 focus:outline-none">练习模式</button>
          <button x-on:click="start('test')" type="button" class="ml-6 inline-flex py-2 px-8 text-base rounded text-white bg-gradient-to-r from-yellow-400 to-yellow-500 focus:outline-none">考试模式</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function dialogContainer() {
    return {
      show: false,
      storeUrl: null,
      totalitiesUrl: null,
      range: 'all',
      type: 0,
      size: 5,
      mode: '',
      totalities: {},

      startExam(show, totalitiesUrl, storeUrl) {
        this.show = !!show
        this.totalitiesUrl = totalitiesUrl
        this.storeUrl = storeUrl
        this.switchRange('all')
      },
      switchRange(range) {
        this.range = range

        axios.get(this.totalitiesUrl, {range: range})
          .then(res => {
            this.totalities = res.data
          })
      },
      switchType(type) {
        this.type = type
      },
      switchSize(size) {
        this.size = size
      },
      start(mode) {
        this.mode = mode
        this.$nextTick(_ => {
          this.$refs['store-record-form'].submit()
        })
      }
    }
  }
</script>
