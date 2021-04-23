@extends('layouts.app')
@section('title', '注册')

@section('content')
  <div class="mt-24">
    <div class="w-full md:max-w-lg md:mx-auto">
      <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="py-8 px-4 md:px-10 flex flex-col">
          <form action="{{ route('register') }}" method="post" class="space-y-5">
            <div hidden>
              @csrf
            </div>
            <div class="flex flex-col">
              <label class="block mb-1 text-gray-700 font-semibold" for="name">用户名</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full rounded-md leading-snug focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" placeholder="请输入用户名">
              @error('name')
                <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
              @enderror
            </div>
            <div class="flex flex-col">
              <label class="block mb-1 text-gray-700 font-semibold" for="email">邮箱地址</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full rounded-md leading-snug focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" placeholder="请输入邮箱地址">
              @error('email')
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
            <div class="flex flex-col">
              <label class="block mb-1 text-gray-700 font-semibold" for="password_confirmation">确认密码</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-md leading-snug focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" placeholder="请输入确认密码">
            </div>
            <button type="submit" class="inline-flex items-center justify-center py-2 px-5 rounded-md shadow-sm leading-snug border border-transparent text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">注册</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
