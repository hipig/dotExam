@extends('layouts.app')
@section('title', '验证邮箱')

@section('content')
  <div class="mt-24">
    <div class="w-full md:max-w-lg md:mx-auto">
      <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="py-8 px-4 md:px-10 flex flex-col">
          <div class="mb-4 px-3 py-2 rounded-md text-sm text-yellow-600 bg-yellow-50">
            邮箱地址尚未验证？验证邮件已发送至您的邮箱，请您点击邮箱内的链接进行验证。如果您没有收到电子邮件，请点击再次发送
          </div>
          @if (session('status') == 'verification-link-sent')
            <div class="mb-4 px-3 py-2 rounded-md text-sm text-green-600 bg-green-50">
              新的验证邮件已发送至您的邮箱！
            </div>
          @endif
          <div class="flex items-center space-x-5">
            <form action="{{ route('verification.send') }}" method="post">
              @csrf
              <button type="submit" class="inline-flex items-center justify-center py-2 px-5 rounded-md shadow-sm leading-snug border border-transparent text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">发送邮件</button>
            </form>
            <form action="{{ route('logout') }}" method="post">
              @csrf
              <button type="submit" class="inline-flex items-center justify-center py-2 px-5 rounded-md shadow-sm leading-snug border border-transparent text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">退出登录</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
