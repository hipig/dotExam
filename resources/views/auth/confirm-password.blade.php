@extends('layouts.app')
@section('title', '确认密码')

@section('content')
  <div class="mt-24">
    <div class="w-full md:max-w-lg md:mx-auto">
      <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="py-8 px-4 md:px-10 flex flex-col">
          <div class="mb-4 px-3 py-2 rounded-md text-sm text-yellow-600 bg-yellow-50">
            请在下一步操作之前，确认您的密码！
          </div>
          <form action="{{ route('password.confirm') }}" method="post" class="space-y-5">
            <div hidden>
              @csrf
            </div>
            <div class="flex flex-col">
              <label class="block mb-1 text-gray-700 font-semibold" for="password">密码</label>
              <input type="password" id="password" name="password" class="w-full rounded-md leading-snug focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" placeholder="请输入密码">
              @error('password')
              <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
              @enderror
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center py-2 px-5 rounded-md shadow-sm leading-snug border border-transparent text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">登录</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
