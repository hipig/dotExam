<div x-data="dialogContainer()"
    x-show="show"
    @start-exam.window="startExam($event.detail.show, $event.detail.totalitiesUrl, $event.detail.storeUrl)"
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
        练习筛选
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
      <div class="w-full space-y-5">
        <form :action="storeUrl" method="post" x-ref="store-record-form" hidden>
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
    <div class="py-4 px-5 lg:px-6 w-full text-right space-x-6">
      <button x-on:click="start('exercise')" type="button" class="inline-flex py-2 px-8 text-base rounded text-white bg-gradient-to-r from-indigo-400 to-indigo-500 focus:outline-none">练习模式</button>
      <button x-on:click="start('test')" type="button" class="inline-flex py-2 px-8 text-base rounded text-white bg-gradient-to-r from-yellow-400 to-yellow-500 focus:outline-none">考试模式</button>
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
