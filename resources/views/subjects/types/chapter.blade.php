<div class="flex space-x-5 chapter-wrapper" x-data="slidesContainer()" x-init="() => { init() }" x-on:scroll.window="scroll" x-cloak>
  <div class="w-48 relative">
    <div class="bg-white shadow rounded-lg max-h-screen py-5 px-2 sticky top-0">
      <div class="flex flex-col space-y-5">
        @foreach($papers as $paper)
          <div class="pl-3 leading-tight truncate cursor-pointer border-l-4" x-bind:class="[activeIndex == {{ $loop->index }} ? 'border-indigo-500 text-indigo-500' : 'border-transparent']" x-on:click="toIndex({{ $loop->index }})">{{ $paper->title }}</div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="flex-1 min-w-0">
    <div class="bg-white shadow rounded-lg">
      <div class="flex flex-col">
        <div class="flex items-center py-2 text-gray-400">
          <div class="w-2/3 px-5"><span class="ml-8">名称</span></div>
          <div class="w-1/3 px-5">进度</div>
        </div>
        <div class="flex flex-col">
          @foreach($papers as $paper)
            <div class="flex flex-col chapter-item" x-data="{show: false}" x-cloak>
              <div class="flex items-center py-4 border-t border-gray-100">
                <div class="w-2/3">
                  <div class="flex items-center px-5">
                    @if($paper->children->isNotEmpty())
                      <div x-on:click="show = !show">
                        <x-heroicon-o-minus-circle class="w-6 h-6 text-indigo-500 cursor-pointer" x-show="show"></x-heroicon-o-minus-circle>
                        <x-heroicon-o-plus-circle class="w-6 h-6 text-indigo-500 cursor-pointer" x-show="!show"></x-heroicon-o-plus-circle>
                      </div>
                    @else
                      <x-heroicon-o-minus-circle class="w-6 h-6 text-gray-400"></x-heroicon-o-minus-circle>
                    @endif
                    <div class="pl-2 text-base flex-1 truncate">{{ $paper->title }}</div>
                  </div>
                </div>
                <div class="w-1/3">
                  <div class="flex items-center justify-between px-5">
                    <div class="text-gray-400"><span class="text-indigo-500">0</span>/{{ $paper->total_count }}</div>
                    <button type="button" x-data x-on:click="$dispatch('start-exam', {show: true, storeUrl: '#', totalitiesUrl: '{{ route('papers.getTotalities', $paper) }}'})" class="px-3 py-1 text-sm flex items-center justify-center border-2 border-indigo-500 text-indigo-500 bg-indigo-50 rounded focus:outline-none">马上练习</button>
                  </div>
                </div>
              </div>
              <div class="flex flex-col" x-show="show">
                @foreach($paper->children as $childrenPaper)
                  <div class="flex items-center py-4 border-t border-gray-100">
                    <div class="w-2/3">
                      <div class="flex items-center px-5 overflow-hidden">
                        <div class="pl-8 w-full flex items-center">
                          <span class="block w-2 h-2 rounded-full border-indigo-500 border-2"></span>
                          <span class="pl-4 text-base flex-1 truncate">{{ $childrenPaper->title }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="w-1/3">
                      <div class="flex items-center justify-between px-5">
                        <div class="text-gray-400"><span class="text-indigo-500">0</span>/{{ $childrenPaper->total_count }}</div>
                        <button type="button" x-data x-on:click="$dispatch('start-exam', {show: true, storeUrl: '#', totalitiesUrl: '{{ route('papers.getTotalities', $childrenPaper) }}'})" class="px-3 py-1 text-sm flex items-center justify-center border-2 border-indigo-500 text-indigo-500 bg-indigo-50 rounded focus:outline-none">马上练习</button>
                      </div>
                    </div>
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

@section('js')
  <script>
    function slidesContainer() {
      return {
        activeIndex: 0,
        sliders: [],
        clickFlag: true,

        init() {
          this.sliders = [...document.querySelectorAll('.chapter-item')]
        },
        scroll() {
          let scrollTop = document.documentElement.scrollTop || document.body.scrollTop
          let sliders = [...document.querySelectorAll('.chapter-item')]

          if (this.clickFlag) {
            for (let i = 0; i < sliders.length; i++) {
              const startH= sliders[i].offsetTop
              const endH = startH + sliders[i].offsetHeight
              const isInView = scrollTop > startH && scrollTop <= endH
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
        }
      }
    }
  </script>
@endsection
