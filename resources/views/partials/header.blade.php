<header class="w-full px-8 text-gray-700 bg-white shadow-sm">
  <div class="container flex flex-col flex-wrap items-center justify-between py-4 mx-auto md:flex-row max-w-6xl">
    <div class="relative flex flex-col md:flex-row">
      <a href="{{ route('home') }}" class="flex items-center mb-5 font-medium text-gray-900 lg:w-auto lg:items-center lg:justify-center md:mb-0">
        <span class="text-2xl font-semibold leading-none">{{ config('app.name') }}</span>
      </a>
      <nav class="flex flex-wrap items-center mb-5 text-base md:mb-0 md:ml-10 space-x-8">
        <a href="{{ route('home') }}" class="{{ if_route_pattern('home') ? 'text-gray-900 font-semibold' : 'font-medium hover:text-gray-900' }}">首页</a>
        <a href="{{ route('subjects.index') }}" class="{{ if_route_pattern(['subjects.*', 'papers.*', 'paperRecords.*']) ? 'text-gray-900 font-semibold' : 'font-medium hover:text-gray-900' }}">题库</a>
        <a href="#" class="font-medium hover:text-gray-900">资讯</a>
        <a href="#" class="font-medium hover:text-gray-900">关于我们</a>
      </nav>
    </div>

    <div class="inline-flex items-center ml-5 space-x-1 lg:justify-end">
      @guest
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-1 text-base font-medium text-gray-600 whitespace-no-wrap transition duration-150 ease-in-out hover:text-gray-900">
          登录
        </a>
        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-1 text-base font-medium text-white whitespace-no-wrap bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600" data-rounded="rounded-md" data-primary="indigo-600" data-primary-reset="{}">
          注册
        </a>
      @else
        <div x-data="{ open: false }" class="relative" x-cloak>
          <button x-on:click="open = !open" type="button" class="rounded-full shadow-sm font-semibold focus:outline-none focus:ring focus:ring-indigo-500 focus:ring-opacity-25">
            <img src="{{ \Auth::user()->avatar }}" alt="{{ \Auth::user()->name }}" class="w-8 h-8 object-cover rounded-full">
          </button>
          <div
            x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="transform opacity-0 scale-75"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-75"
            @click.away="open = false"
            class="absolute right-0 origin-top-right mt-2 z-1"
          >
            <div class="w-48 shadow-lg overflow-hidden rounded-lg py-0.5 bg-white ring-1 ring-gray-400 ring-opacity-5 divide-y divide-gray-100">
              <div class="py-1">
                <a href="#" class="flex items-center truncate space-x-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 block px-4 py-1.5">
                  <x-heroicon-s-eye class="w-5 h-5 opacity-50"></x-heroicon-s-eye>
                  <span>学习记录</span>
                </a>
                <a href="#" class="flex items-center truncate space-x-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 block px-4 py-1.5">
                  <x-heroicon-s-pencil-alt class="w-5 h-5 opacity-50"></x-heroicon-s-pencil-alt>
                  <span>修改资料</span>
                </a>
              </div>
              <div class="py-1">
                <a href="javascript:;" class="flex items-center truncate space-x-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 block px-4 py-1.5" x-on:click.prevent="$refs['logout-form'].submit()">
                  <x-heroicon-s-lock-closed class="w-5 h-5 opacity-50"></x-heroicon-s-lock-closed>
                  <span>退出</span>
                  <form action="{{ route('logout') }}" method="post" x-ref="logout-form">
                    @csrf
                  </form>
                </a>
              </div>
            </div>
          </div>
        </div>
      @endguest
    </div>
  </div>
</header>
