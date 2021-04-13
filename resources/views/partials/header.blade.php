<header class="w-full px-8 text-gray-700 bg-white shadow-sm">
  <div class="container flex flex-col flex-wrap items-center justify-between py-4 mx-auto md:flex-row max-w-6xl">
    <div class="relative flex flex-col md:flex-row">
      <a href="#_" class="flex items-center mb-5 font-medium text-gray-900 lg:w-auto lg:items-center lg:justify-center md:mb-0">
        <span class="text-2xl font-semibold leading-none">{{ config('app.name') }}</span>
      </a>
      <nav class="flex flex-wrap items-center mb-5 text-base md:mb-0 md:ml-10 space-x-8">
        <a href="{{ route('home') }}" class="text-gray-900 font-semibold">首页</a>
        <a href="{{ route('subjects.index') }}" class="font-medium hover:text-gray-900">题库</a>
        <a href="#" class="font-medium hover:text-gray-900">资讯</a>
        <a href="#" class="font-medium hover:text-gray-900">关于我们</a>
      </nav>
    </div>

    <div class="inline-flex items-center ml-5 space-x-1 lg:justify-end">
      <a href="#" class="inline-flex items-center justify-center px-5 py-1 text-base font-medium text-gray-600 whitespace-no-wrap transition duration-150 ease-in-out hover:text-gray-900">
        登录
      </a>
      <a href="#" class="inline-flex items-center justify-center px-5 py-1 text-base font-medium text-white whitespace-no-wrap bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600" data-rounded="rounded-md" data-primary="indigo-600" data-primary-reset="{}">
        注册
      </a>
    </div>
  </div>
</header>
