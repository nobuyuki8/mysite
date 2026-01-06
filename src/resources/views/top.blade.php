<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-4 text-center">

        <h1 class="text-3xl font-bold text-gray-800 mb-4">
            東京家庭菜園 🌱
        </h1>

        <p class="text-gray-600 mb-8">
            東京で家庭菜園を楽しむ人たちが、<br>
            野菜・苗・知識を共有できるコミュニティです。
        </p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('posts.index') }}"
               class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700">
                投稿を見る
            </a>

            <a href="{{ route('exchanges.index') }}"
               class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                物々交換を見る
            </a>
        </div>

    </div>
</x-app-layout>


