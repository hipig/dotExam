<div class="w-full space-y-5" x-data="filterContainer()" x-ref="filter-wrapper">
  <form action="#" method="post">
    <input type="hidden" name="paper_id" value="">
  </form>
  <div class="flex flex-col">
    <h3 class="text-gray-400 mb-2">类型</h3>
    <div class="flex flex-wrap -mx-3">
      <div class="w-1/6 px-3">
        <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-indigo-500 text-white">全部</div>
      </div>
      @foreach(\App\Models\Paper::$filterTypeMap as $key => $item)
        <div class="w-1/6 px-3">
          <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100" x-on:click="switchRange({{ $key }})">{{ $item }}</div>
        </div>
      @endforeach
      <input type="hidden" name="range" x-model="range">
    </div>
  </div>
  <div class="flex flex-col">
    <h3 class="text-gray-400 mb-2">题型</h3>
    <div class="flex flex-wrap -mx-3">
      <div class="w-1/6 px-3">
        <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-indigo-500 text-white">全部（{{ $totalities[0] ?? 0 }}）</div>
      </div>
      @foreach(\App\Models\Question::$typeMap as $key => $item)
        <div class="w-1/6 px-3">
          <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100" x-on:click="switchType({{ $key }})">{{ $item }}（{{ $totalities[$key] ?? 0 }}）</div>
        </div>
      @endforeach
    </div>
  </div>
  <div class="flex flex-col">
    <h3 class="text-gray-400 mb-2">数量</h3>
    <div class="flex flex-wrap -mx-3">
      @foreach(\App\Models\Paper::$filterSizeMap as $item)
        <div class="w-1/6 px-3">
          <div class="flex items-center justify-center py-2 mb-3 rounded text-base cursor-pointer bg-gray-100" x-on:click="switchSize({{ $item }})">{{ $item }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<script>
  function filterContainer() {
    return {
      url: '{{ route() }}',
      range: '{{ $range }}',
      type: {{ $type }},
      size: {{ $size }},

      switchRange(range) {
        this.range = range
        axios.post(url, {range: range})
          .then(res => {
            this.$refs["filter-wrapper"].html = res
          })
      }
    }
  }
</script>
