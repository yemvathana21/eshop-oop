<p class="text-center text-gray-500 mb-6">Log in to start your session</p>

<form action="<?= BASE_URL ?>admin/login" method="post">
    <div class="mb-4">
        <input type="email" name="email" class="w-full border border-gray-300 p-2 focus:outline-none focus:border-blue-400 placeholder-gray-400" placeholder="Email address" required autofocus>
    </div>
    <div class="mb-6">
        <input type="password" name="password" class="w-full border border-gray-100 p-2 focus:outline-none focus:border-blue-400 placeholder-gray-400" placeholder="Password" required>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="bg-[#333] hover:bg-[#444] text-white px-8 py-2 transition-colors">Log In</button>
    </div>
</form>
