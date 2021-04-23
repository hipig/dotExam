@extends('layouts.app')
@section('title', '登录')

@section('content')
  <div class="mt-24">
    <div class="w-full md:max-w-lg md:mx-auto">
      <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="py-8 px-4 md:px-10 flex flex-col">
          <form action="{{ route('login') }}" method="post" class="space-y-5">
            <div hidden>
              @csrf
            </div>
            <div class="flex flex-col">
              <label class="block mb-1 text-gray-700 font-semibold" for="username">用户名</label>
              <input type="text" id="username" name="username" value="{{ old('username') }}" class="w-full rounded-md leading-snug focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" placeholder="请输入用户名/邮箱地址">
              @error('username')
                <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
              @enderror
            </div>
            <div class="flex flex-col">
              <label class="block mb-1 text-gray-700 font-semibold" for="password">密码</label>
              <input type="password" id="password" name="password" class="w-full rounded-md leading-snug focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" placeholder="请输入密码">
              @error('password')
                <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
              @enderror
            </div>
            <div class="flex items-center justify-between">
              <label class="inline-flex items-center text-truncate" for="remember">
                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 rounded text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('remember') ? 'checked' : '' }}>
                <span class="ml-2 select-none text-gray-700">记住我</span>
              </label>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm inline-flex text-indigo-500">忘记密码？</a>
              @endif
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center py-2 px-5 rounded-md shadow-sm leading-snug border border-transparent text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">登录</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
