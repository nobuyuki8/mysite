<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
{
    /**
     * このリクエストを実行する権限があるか
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            // ★ タイトルを追加
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'content' => [
                'required',
                'string',
                'max:500',
            ],

            // 画像は任意、2MB以下
            'image' => [
                'nullable',
                'image',
                'max:2048',
            ],

            // タグ入力
            'tags' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * エラーメッセージ
     */
    public function messages(): array
    {
        return [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',

            'content.required' => '投稿内容は必須です。',
            'content.max' => '投稿内容は500文字以内で入力してください。',

            'image.image' => '画像ファイルを選択してください。',
            'image.max' => '画像サイズは2MB以内にしてください。',

            'tags.max' => 'タグは255文字以内で入力してください。',
        ];
    }

    /**
     * バリデーション前に値を整形
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('tags')) {
            $clean = preg_replace('/\s+/u', ' ', $this->tags ?? '');

            $this->merge([
                'tags' => trim($clean),
            ]);
        }
    }
}
