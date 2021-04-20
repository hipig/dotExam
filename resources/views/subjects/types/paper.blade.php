<div class="flex flex-wrap -mx-3">
  @foreach($papers as $paper)
    <div class="w-1/2 px-3 mb-5">
      <div class="bg-white shadow rounded-lg p-5">
        <div class="mb-8 flex justify-between">
          <div class="text-base text-gray-900 pr-5 truncate">{{ $paper->title }}</div>
          <div class="text-gray-400"><span class="text-indigo-500">{{ $paper->record_count ?? 0 }}</span>/{{ $paper->total_count }}</div>
        </div>
        <div class="flex items-center justify-between">
          <div class="text-gray-400">
            <span class="mr-5">总分： {{ $paper->total_score }}分</span>
            <span>时间：{{ $paper->time_limit }}分</span>
          </div>
          <button type="button" class="px-3 h-8 flex items-center justify-center border-2 border-yellow-500 text-yellow-500 bg-yellow-50 rounded focus:outline-none">开始考试</button>
        </div>
      </div>
    </div>
  @endforeach
</div>
