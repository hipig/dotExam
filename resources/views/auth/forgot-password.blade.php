@extends('layouts.app')
@section('title', '忘记密码')

@section('content')
  <div class="mt-24">
    <div class="w-full md:max-w-lg md:mx-auto">
      <div class="bg-white rounded-md shadow-sm overflow-hidden">
        <div class="py-8 px-4 md:px-10 flex flex-col">
          <div class="mb-4 px-3 py-2 rounded-md text-sm text-yellow-600 bg-yellow-50">
            请输入您注册时所填写的邮箱地址，我们将给您发送一封带有密码重置链接的邮件，请注意查收！
          </div>
          <form action="{{ route('password.email') }}" method="post" class="space-y-5">
            <div hidden>
              @csrf
            </div>
            <div class="flex flex-col">
              <label class="block mb-1 text-gray-700 font-semibold" for="email">邮箱地址</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full rounded-md leading-snug focus:ring-indigo-500 focus:border-indigo-500 border-gray-300" placeholder="请输入邮箱地址">
              @error('email')
              <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
              @enderror
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center py-2 px-5 rounded-md shadow-sm leading-snug border border-transparent text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">发送重置密码邮件</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
